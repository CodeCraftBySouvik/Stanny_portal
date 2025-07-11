<?php

namespace App\Http\Livewire\Order;
use App\Models\Order;
use \App\Models\Product;
use \App\Models\Invoice;
use \App\Models\StockFabric;
use \App\Models\StockProduct;
use \App\Models\OrderStockEntry;
use \App\Models\ChangeLog;
use \App\Models\Delivery;
use \App\Models\OrderItem;
use Livewire\Component;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductionOrderDetails extends Component
{
    public $showModal = false;
    public $selectedItem = [];
    public $orderItems = [];
    public $rows = [];
    public $orderId;
    public $latestOrders = [];
    public $order;
    public $available_meter;
    public $selectedDeliveryItem = [];
    public $actualUsage = [];
    // public $deliveryType = 'full';
    public $showExtraStockPrompt;
    

    public function mount($id){
        $this->orderId = $id;
        $this->order = Order::with('items')->findOrFail($this->orderId);
        // dd($this->order);
        $invoicePayment = Invoice::where('order_id', $this->order->id)->orderBy('id','desc')->first(); 
        if($invoicePayment){
            $this->order->total_amount = $invoicePayment->net_price;
            $this->order->paid_amount = $invoicePayment->net_price - $invoicePayment->required_payment_amount;
            $this->order->remaining_amount = $invoicePayment->required_payment_amount;
        }

        
         // Fetch the latest 5 orders for the user (customer)
         $this->latestOrders = Order::where('customer_id',$this->order->customer_id)
                                     ->latest()
                                     ->where('id', '!=', $this->order->id)
                                     ->take(5)
                                     ->get();
    }



        public function updateStock($index, $inputName)
    {
        try {
            DB::beginTransaction();

            $item = $this->orderItems[$index];
            $orderItemId = $item['id'];
            $enteredQuantity = $this->rows[$inputName] ?? 0;

            // ✅ Validate input
            $validator = Validator::make(
                [$inputName => $enteredQuantity],
                [
                    $inputName => [
                        'required',
                        'numeric',
                        'min:1',
                    ],
                ],
                [
                    $inputName . '.required' => 'Quantity is required.',
                    $inputName . '.numeric'  => 'Quantity must be a number.',
                    $inputName . '.min'      => 'Quantity must be at least 1.',
                ]
            );

            if ($validator->fails()) {
                $this->rows['is_valid_' . $inputName] = false;
                $this->addError($inputName, $validator->errors()->first($inputName));
                DB::rollBack();
                return;
            }

            // ✅ Get stock
            $stock = null;
            $availableStock = 0;
            $fabricId = null;
            $productId = null;

            if ($item['collection_id'] == 1) {
                $fabricId = $item['fabrics']->id;
                $stock = StockFabric::where('fabric_id', $fabricId)->first();
                $availableStock = $stock?->qty_in_meter ?? 0;

            } elseif ($item['collection_id'] == 2) {
                $productId = $item['product']->id;
                $stock = StockProduct::where('product_id', $productId)->first();
                $availableStock = $stock?->qty_in_pieces ?? 0;
            }

            // ✅ Get or create existing stock entry
            $stockEntry = OrderStockEntry::where('order_item_id', $orderItemId)->first();
            $previousQuantity = $stockEntry?->quantity ?? 0;

            // ✅ Ensure new quantity doesn't exceed total available
            $maxAllowed = $availableStock + $previousQuantity;

            if ($enteredQuantity > $maxAllowed) {
                $this->addError($inputName, "Quantity must be less than or equal to {$maxAllowed}.");
                DB::rollBack();
                return;
            }

            // ✅ Adjust stock and save/update entry
            $difference = $enteredQuantity - $previousQuantity;

            OrderStockEntry::updateOrCreate(
                ['order_item_id' => $orderItemId],
                [
                    'order_id'   => $this->orderId,
                    'fabric_id'  => $fabricId,
                    'product_id' => $productId,
                    'quantity'   => $enteredQuantity,
                    'unit'       => $item['stock_entry_data']['type'],
                    'created_by' => auth()->guard('admin')->user()->id,
                ]
            );

            // ✅ Update stock
            if ($stock) {
                if ($item['collection_id'] == 1) {
                    $stock->decrement('qty_in_meter', $difference);
                } elseif ($item['collection_id'] == 2) {
                    $stock->decrement('qty_in_pieces', $difference);
                }
            }

            // ✅ Log changes
            ChangeLog::create([
                'done_by' => auth()->guard('admin')->user()->id,
                'purpose' => 'stock_entry_update',
                'data_details' => json_encode([
                    'order_item_id' => $orderItemId,
                    'entered_quantity' => $enteredQuantity,
                ]),
            ]);

            DB::commit();

            // ✅ Frontend and state reset
            $this->rows['is_done_' . $inputName] = true;
            $this->resetPage($inputName);
            $this->loadOrderItems();
            $this->openStockModal($index); // reopen modal with updated data
            return redirect()->route('production.order.details', $this->orderId);

        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }







    public function revertBackStock($index,$inputName){
         try {
           DB::beginTransaction();
           $item = $this->orderItems[$index];
            $orderItemId = $item['id'];
            $enteredQuantity = $this->rows[$inputName] ?? 0;

            // Find the latest stock entry for this order item
            $stockEntry = OrderStockEntry::where('order_item_id', $orderItemId)
                            ->latest()->first();
            if ($stockEntry) {
            // Revert the stock
            if ($item['collection_id'] == 1) {
                $stock = StockFabric::where('fabric_id', $stockEntry->fabric_id)->first();
                $stock->update(['qty_in_meter' => $stock->qty_in_meter + $stockEntry->quantity]);
            } elseif ($item['collection_id'] == 2) {
                $stock = StockProduct::where('product_id', $stockEntry->product_id)->first();
                $stock->update(['qty_in_pieces' => $stock->qty_in_pieces + $stockEntry->quantity]);
            }

            $stockEntry->delete();

           
        }
           DB::commit(); 
            // Reset and reload
            $this->resetPage($inputName);
            $this->loadOrderItems();
            $this->openStockModal($index);
            return redirect()->route('production.order.details',$this->orderId);

         }catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function loadOrderItems(){
        $this->orderItems = $this->order->items
             ->filter(function($item){
               return $item->status === 'Process' &&
                      $item->tl_status === 'Approved' &&
                      $item->admin_status === 'Approved';
            })
            ->map(function ($item) {
            $product = Product::find($item->product_id);
            $stockData = Helper::getStockEntryData(
                $item->collection,
                $item->fabrics,
                $item->product_id,
                $this->orderId,
                $item->id
            );
           $hasStockEntry = OrderStockEntry::where('order_item_id', $item->id)->exists();
             // Fetch logs for this item
           $logs = Changelog::whereJsonContains('data_details->order_item_id', $item->id)
                ->whereIn('purpose', ['stock_entry_update', 'extra_stock_entry','delivery_proceed'])
                ->get();

            $deliveryCount = 0;
            $logTooltip = $logs->map(function ($log) use($item, &$deliveryCount) {
                $details = json_decode($log->data_details, true);
                if ($log->purpose === 'stock_entry_update') {
                    return 'Entered Quantity: ' . ($details['entered_quantity'] ?? '-');
                } elseif ($log->purpose === 'extra_stock_entry') {
                    return 'Extra Quantity: ' . ($details['extra_quantity'] ?? '-');
                } elseif ( $log->purpose === 'delivery_proceed' &&
                            $item->collection == 2 &&
                            isset($details['delivered_quantity']))
                {
                     $deliveryCount++;
                     $ordinal = match($deliveryCount){
                        1 => '1st',
                        2 => '2nd',
                        3 => '3rd',
                        default => $deliveryCount . 'th',
                     };
                    // return '{$ordinal} Delivered Quantity : '.($details['delivered_quantity']);
                      return "{$ordinal} Delivered Quantity : " . $details['delivered_quantity'];
                }
                return null;
            })->filter()->implode(' | ');

            $stock = null;
            $totalStock = 0;
            $used = 0;
            $deliveredQty = 0;
            $remainingQty = 0;

            if($item->collection == 1){
                //Fabric
                $stock = StockFabric::where('fabric_id',$item->fabrics)->first();
                $totalStock = $stock ? $stock->qty_in_meter : 0;
                $used = OrderStockEntry::where('order_item_id', $item->id)->sum('quantity');
                $isDelivered = Delivery::where('order_item_id', $item->id)
                                        ->where('fabric_id', $item->fabrics)
                                        ->exists();
            }elseif ($item->collection == 2) {
                // Product
                $stock = StockProduct::where('product_id', $item->product_id)->first();
                $totalStock = $stock ? $stock->qty_in_pieces : 0;
                $used = OrderStockEntry::where('order_item_id', $item->id)->sum('quantity');
                $isDelivered = Delivery::where('order_item_id', $item->id)
                                            ->where('product_id', $item->product_id)
                                            ->exists();

                 // Calculate delivered and remaining quantity for products
                $deliveredQty = Delivery::where('order_item_id', $item->id)->sum('delivered_quantity');
                $remainingQty = $item->quantity - $deliveredQty;
           }

            $initialStock = $totalStock + $used; // initial = current + used
            $totalUsed = $initialStock - $totalStock; // amount used so far

            return [
                'id' => $item->id,
                'product_name' => $item->product_name ?? $product->name,
                'collection_id' => $item->collection,
                'collection_title' => $item->collectionType ?  $item->collectionType->title : "",
                'fabrics' => $item->fabric,
                'product' => $item->product,
                'measurements' => $item->measurements,
                'catalogue' => $item->catalogue_id ? $item->catalogue:"",
                'catalogue_id' => $item->catalogue_id,
                'cat_page_number' => $item->cat_page_number,
                'price' => $item->piece_price,
                'quantity' => $item->quantity,
                'product_image' => $product ? $product->product_image : null,
                'stock_entry_data' => $stockData,
                'has_stock_entry'  => $hasStockEntry,
                'total_used' => $totalUsed,
                'initial_stock' => $initialStock,
                'is_delivered' => $isDelivered,
                'delivered_quantity' => $deliveredQty,
                'remaining_to_deliver' => $remainingQty,
                'logs' => $logTooltip,
                'remarks' => $item->remarks,
                'catlogue_images' => $item->catlogue_image,
                'voice_remarks' => $item->voice_remark,
            ];
        });
        
    }

    public function resetPage($inputName){
         // Clear the input field
        $this->rows[$inputName] = '';
         // Reset validation for this input
        unset($this->rows['is_valid_'.$inputName]);
    }   

    public function openStockModal($index){
        $item =  $this->orderItems[$index];

        $fabricId = $item['collection_id'] == 1 ? ($item['fabrics']->id ?? null) : null;
        $productId = $item['collection_id'] == 2 ? ($item['product']->id ?? null) : null;

         $totalUsed = OrderStockEntry::query()
                    ->where('order_item_id', $item['id'])
                    ->when($fabricId, fn($q) => $q->where('fabric_id', $fabricId))
                    ->when($productId, fn($q) => $q->where('product_id', $productId))
                    ->sum('quantity');

        $inputName = 'row_' . $index . '_' . $item['stock_entry_data']['input_name'];
         $this->selectedItem = [
            'item_id' => $item['id'],
            'index' => $index,
            'collection_title' => $item['collection_title'],
            'collection_id' => $item['collection_id'],
            'product_name' => $item['product']['name'] ?? '',
            'fabric_title' => $item['fabrics']['title'] ?? '',
            'available_label' => $item['stock_entry_data']['available_label'],
            'available_value' => $item['stock_entry_data']['available_value'],
            'updated_label' => $item['stock_entry_data']['updated_label'],
            'input_name' => $inputName,
            'has_stock_entry' => $item['has_stock_entry'],
            'total_used' => $totalUsed, 
      ]; 
        $this->rows[$inputName] = $totalUsed;
        $this->dispatch('open-stock-modal');
    }

   

        public function openDeliveryModal($index)
    {
        $item = $this->orderItems[$index];

        $plannedUsage = 0;
        $unit = '';
        $fabricId = null;
        $productId = null;

        $stockProduct = 0;
        if ($item['collection_id'] == 1) {
            $fabricId = $item['fabrics']->id ?? null;
            $plannedUsage = OrderStockEntry::query()
                ->where('order_item_id', $item['id'])
                ->when($fabricId, fn($q) => $q->where('fabric_id', $fabricId))
                ->sum('quantity');  
             $unit = 'meters';
            if (!isset($this->actualUsage[$item['id']])) {
                $this->actualUsage[$item['id']] = $plannedUsage;
            }
        } elseif ($item['collection_id'] == 2) {
            $productId = $item['product']->id ?? null;
            $stockProduct = StockProduct::where('product_id',$productId)->sum('qty_in_pieces');
            $plannedUsage = $item['quantity'];
            $unit = 'pieces';

            // ✅ Calculate already delivered and remaining
            $alreadyDelivered = Delivery::where('order_item_id', $item['id'])->sum('delivered_quantity');
            $remainingToDeliver = $plannedUsage - $alreadyDelivered;
            // For collection_id == 2, prefill actualUsage:
            $this->actualUsage[$item['id']] = $remainingToDeliver;
        }

        $this->selectedDeliveryItem = [
            'item_id' => $item['id'],
            'index' => $index,
            'collection_id' => $item['collection_id'],
            'collection_title' => $item['collection_title'],
            'product_name' => $item['product']['name'] ?? '',
            'fabric_title' => $item['fabrics']['title'] ?? '',
            'product_id'   => $productId,
            'fabric_id'    => $fabricId,
            'planned_usage' => $plannedUsage,
            'stock_product' => $stockProduct,
            'unit' => $unit,

            'ordered_quantity' => $plannedUsage,
            'delivered_quantity' => $alreadyDelivered ?? 0,
            'remaining_to_deliver' => $remainingToDeliver ?? 0,
        ];
        // dd($this->selectedDeliveryItem);

        $this->dispatch('open-delivery-modal');
    }

   


        public function checkActualUsage()
    {
        if ($this->selectedDeliveryItem['collection_id'] == 1) {
            $planned = $this->selectedDeliveryItem['planned_usage'] ?? 0;
            $itemId = $this->selectedDeliveryItem['item_id'] ?? null;
            $actual = floatval($this->actualUsage[$itemId] ?? 0);

            $this->showExtraStockPrompt = $actual > $planned;
        } else {
            $this->showExtraStockPrompt = false;
        }
    }



    public function updatedActualUsage()
    {
        $planned = $this->selectedDeliveryItem['planned_usage'] ?? 0;
        $this->showExtraStockPrompt = $this->actualUsage > $planned;
    }


    public function addExtraStock()
{
    $index = $this->selectedDeliveryItem['index'] ?? null;

    if ($index !== null) {
        $item = $this->orderItems->get($index);

        $itemId = $item['id'];
        $collectionId = $item['collection_id'];
        $fabricId = $collectionId == 1 ? ($item['fabrics']->id ?? null) : null;
        $productId = $collectionId == 2 ? ($item['product']->id ?? null) : null;

        $actualQty = $this->actualUsage[$itemId] ?? 0;
        $currentUsage = $this->selectedDeliveryItem['planned_usage'] ?? 0;

        if ($actualQty <= $currentUsage) {
            return;
        }

        $availableStock = $item['stock_entry_data']['available_value'] ?? 0;
        $extraQty = $actualQty - $currentUsage;

        if ($extraQty > $availableStock) {
            session()->flash('stock_error', 'Entered extra quantity exceeds available stock.');
            return;
        }

        DB::beginTransaction();

        try {
            // ✅ Update or create stock entry
            $stockEntry = OrderStockEntry::where('order_item_id', $itemId)->first();

            if ($stockEntry) {
                $stockEntry->update(['quantity' => $actualQty]); // overwrite with new total usage
            } else {
                OrderStockEntry::create([
                    'order_id' => $this->orderId,
                    'order_item_id' => $itemId,
                    'fabric_id' => $fabricId,
                    'product_id' => $productId,
                    'quantity' => $actualQty,
                    'unit' => $item['stock_entry_data']['type'],
                    'created_by' => auth()->guard('admin')->user()->id,
                ]);
            }

            // ✅ Update physical stock
            if ($collectionId == 1 && $fabricId) {
                StockFabric::where('fabric_id', $fabricId)->decrement('qty_in_meter', $extraQty);
            } elseif ($collectionId == 2 && $productId) {
                StockProduct::where('product_id', $productId)->decrement('qty_in_pieces', $extraQty);
            }

            // ✅ Log the change (optional)
            ChangeLog::create([
                'done_by' => auth()->guard('admin')->user()->id,
                'purpose' => 'extra_stock_entry',
                'data_details' => json_encode([
                    'order_item_id' => $itemId,
                    'extra_quantity' => $extraQty,
                ]),
            ]);

            DB::commit();

            // ✅ Update frontend state
            $item['stock_entry_data']['available_value'] -= $extraQty;
            $item['stock_entry_data']['updated_label'] = $actualQty;
            $this->orderItems->put($index, $item);

            $this->selectedDeliveryItem['planned_usage'] = $actualQty;
            $this->actualUsage[$itemId] = $actualQty;
            $this->showExtraStockPrompt = false;

            session()->forget('stock_error');
            $this->dispatch('stock-updated'); // optional for UI refresh

        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
            session()->flash('stock_error', 'Something went wrong. Please try again.');
        }
    }
}




    public function processDelivery()
    {
        $item = $this->selectedDeliveryItem;
        $itemId = $item['item_id'];

        $this->validate([
            'actualUsage.' . $itemId => 'required|numeric|min:1',
        ], [
            'actualUsage.*.required' => 'Please enter the actual usage.',
            'actualUsage.*.numeric'  => 'The actual usage must be a number.',
            'actualUsage.*.min'      => 'The actual usage must be at least 1.',
        ]);

        $actual = floatval($this->actualUsage[$itemId]);
        $plannedUsage = floatval($item['planned_usage'] ?? 0);
        $availableStock = floatval($item['stock_product'] ?? 0);

        // ✅ Additional check for collection_id == 2
        if ($item['collection_id'] == 2) {
            $actual = floatval($this->actualUsage[$itemId]);
            $orderedQty = floatval($item['ordered_quantity'] ?? 0);
            $alreadyDelivered = floatval($item['delivered_quantity'] ?? 0);
            $remainingToDeliver = $orderedQty -  $alreadyDelivered;

            if ($actual > $remainingToDeliver) {
                session()->flash('stock_error', 'You are trying to deliver more ('. $actual .') than remaining quantity ('. $remainingToDeliver .').');
                return;
            }

            // Check if user tries to over-deliver
            if ($actual > $remainingToDeliver) {
                session()->flash('stock_error', 'You are trying to deliver more ('. $actual .') than the remaining quantity ('. $remainingToDeliver .').');
                return;
            }

             $availableStock = floatval($item['stock_product'] ?? 0);
            if ($availableStock < $actual) {
                session()->flash('stock_error', 'Available stock ('. $availableStock .') is less than entered quantity ('. $actual .').');
                return;
            }
        }

        DB::beginTransaction();
        try {
            $orderItemModel = OrderItem::find($itemId);
            // 1️⃣ Create the delivery record
            Delivery::create([
                'order_id' => $this->orderId,
                'order_item_id' => $itemId,
                'product_id' => $item['collection_id'] == 2 ? ($item['product_id'] ?? null) : null,
                'fabric_id' => $item['collection_id'] == 1 ? ($item['fabric_id'] ?? null) : null,
                'fabric_quantity' => $item['collection_id'] == 1 ? $orderItemModel->quantity : null,
                'delivered_quantity' => $actual,
                'unit' => $item['unit'],
                'delivered_by' => auth()->guard('admin')->user()->id,
                'delivered_at' => now(),
            ]);

            // 2️⃣ For fabric: consume multiple stock entries
            if ($item['collection_id'] == 1) {
                $remaining = $actual;

                $stockEntries = OrderStockEntry::where('order_item_id', $itemId)
                    ->where('fabric_id', $item['fabric_id'])
                    ->orderBy('created_at')
                    ->get();

                foreach ($stockEntries as $entry) {
                    if ($remaining <= 0) break;

                    if ($entry->quantity <= $remaining) {
                        $remaining -= $entry->quantity;
                        // $entry->delete();
                    } else {
                        $entry->update(['quantity' => $entry->quantity - $remaining]);
                        $remaining = 0;
                        break;
                    }
                }



                // Return leftover fabric back to stock
                // $leftoverEntries = OrderStockEntry::where('order_item_id', $itemId)
                //     ->where('fabric_id', $item['fabric_id'])
                //     ->get();

                // $leftoverQuantity = $leftoverEntries->sum('quantity');

                // if ($leftoverQuantity > 0) {
                //     $fabricStock = StockFabric::where('fabric_id', $item['fabric_id'])->first();
                //     if ($fabricStock) {
                //         $fabricStock->increment('qty_in_meter', $leftoverQuantity);
                //     }
                  
                // }
            }

            // 3️⃣ For product: just reduce main stock
            if ($item['collection_id'] == 2) {
                $productStock = StockProduct::where('product_id', $item['product_id'])->first();
                if ($productStock) {
                    $productStock->decrement('qty_in_pieces', $actual);
                }
            }

            // 4️⃣ Insert to changelog
            Changelog::create([
                    'done_by' => auth()->guard('admin')->user()->id,
                    'purpose' => 'delivery_proceed',
                    'data_details' => json_encode([
                    'order_id' => $this->orderId,
                    'order_item_id' => $itemId,
                    'collection_id' => $item['collection_id'],
                    'delivered_quantity' => $actual,
                    'unit' => $item['unit'],
                    'timestamp' => now(),
                ]),
            ]);

            // 5️⃣ Update overall order status
            // $order = Order::find($this->orderId);
            // $totalItems = $order->items()->count();
            // $deliveredItems = $order->items()->whereHas('deliveries')->count();

            // if ($deliveredItems == $totalItems) {
            //     $order->update(['status' => 'Fully Delivered']);
            // } elseif ($deliveredItems > 0) {
            //     $order->update(['status' => 'Partial Delivered']);
            // }

            // 5️⃣ Update overall order status with quantity checks for products
            $order = Order::find($this->orderId);
            $items = $order->items;

            $allDelivered = true;
            $anyDelivered = false;

            foreach ($items as $item) {
                if ($item->collection == 2) {
                    // Check if full quantity is delivered
                    $deliveredQty = Delivery::where('order_item_id', $item->id)->sum('delivered_quantity');
                    
                    if ($deliveredQty >= $item->quantity) {
                        $anyDelivered = true;
                    } else {
                        $allDelivered = false;
                        if ($deliveredQty > 0) {
                            $anyDelivered = true;
                        }
                    }
                } else {
                    // For fabrics (collection_id = 1), keep your current logic
                    $hasDelivery = Delivery::where('order_item_id', $item->id)
                                        ->where('fabric_id', $item->fabrics)
                                        ->exists();

                    if ($hasDelivery) {
                        $anyDelivered = true;
                    } else {
                        $allDelivered = false;
                    }
                }
            }

            // Final status decision
            if ($allDelivered) {
                $order->update(['status' => 'Fully Delivered By Production']);
            } elseif ($anyDelivered) {
                $order->update(['status' => 'Partial Delivered By Production']);
            }


            DB::commit();

            unset($this->actualUsage[$itemId]);
            $this->loadOrderItems();
            $this->dispatch('close-delivery-modal');

            return redirect()->route('production.order.details', $this->orderId);
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }




    public function render()
    {
         // Fetch the order and its related items
        //  $order = Order::with('items')->findOrFail($this->orderId);
         
         // Fetch product details for each order item
         $this->loadOrderItems();
        return view('livewire.order.production-order-details',[
            //  'order' => $this->order,
            'orderItems' => $this->orderItems,
            'latestOrders'=>$this->latestOrders,
        ]);
    }
}
