<?php

namespace App\Http\Livewire\Order;
use App\Models\Order;
use App\Models\OrderItem;

use \App\Models\Product;
use \App\Models\Invoice;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class OrderView extends Component
{
    public $latestOrders = [];
    public $order;
    protected $listeners = ['deliveredToCustomerPartial','openDeliveryModal','markReceivedConfirmed'];
    public $Id, $orderId, $status, $remarks;
    protected $rules = [
        'status' => 'required',
        'remarks' => 'required|string|min:3',
    ];
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

    public function render()
    {
         // Fetch the order and its related items
         $order = Order::with([
            'items.deliveries' => fn($q) => $q->with('user:id,name'),
            'items.voice_remark','items.catlogue_image'
        ])->findOrFail($this->orderId);
        //echo "<pre>";print_r($order->toArray());exit;
         $orderItems = $order->items->map(function ($item) use($order) {
            // dd($item);
            $product = Product::find($item->product_id);
            $delivery = $item->deliveries->first();
             // Decide extra measurement type
             $extra = \App\Helpers\Helper::ExtraRequiredMeasurement($item->product_name);
            return [
                'product_name' => $item->product_name ?? $product->name,
                'collection_id' => $item->collection,
                'collection_title' => $item->collectionType ?  $item->collectionType->title : "",
                'fabrics' => $item->fabric,
                'measurements' => $item->measurements,
                'catalogue' => $item->catalogue_id?$item->catalogue:"",
                'catalogue_id' => $item->catalogue_id,
                'cat_page_number' => $item->cat_page_number,
                'price' => $item->piece_price,
               
                'deliveries' => $delivery ? [
                    'id' => $delivery->id,
                    'delivered_at' => $delivery->delivered_at,
                    'delivered_by' => $delivery->delivered_by,
                    'status' => $delivery->status,
                    'remarks' => $delivery->remarks,
                    'fabric_quantity' => $delivery->fabric_quantity,
                    'delivered_quantity' => $delivery->delivered_quantity,
                    'user' => $delivery->user ? ['name' => $delivery->user->name] : ['name' => 'N/A'],
                    'collection_id' => $item->collection,
                ] : null,
                'quantity' => $item->quantity,
                'remarks' => $item->remarks,
                'catlogue_images' => $item->catlogue_image,
                'voice_remarks' => $item->voice_remark,

                'product_image' => $product ? $product->product_image : null,
                'expected_delivery_date' => $item->expected_delivery_date,
                'fittings' => $item->fittings,
                'priority' => $item->priority_level,

                // Extra fields packed here
                'extra_type'     => $extra,
                'vents'          => $item->vents,
                'vents_required' => $item->vents_required,
                'vents_count'    => $item->vents_count,
                'fold_cuff_required'   => $item->fold_cuff_required,
                'fold_cuff_size'       => $item->fold_cuff_size,
                'pleats_required'      => $item->pleats_required,
                'pleats_count'         => $item->pleats_count,
                'back_pocket_required' => $item->back_pocket_required,
                'back_pocket_count'    => $item->back_pocket_count,
                'adjustable_belt'      => $item->adjustable_belt,
                'suspender_button'     => $item->suspender_button,
                'trouser_position'     => $item->trouser_position,   
            ];
        });

        return view('livewire.order.order-view',[
            'order' => $order,
            'orderItems' => $orderItems,
            'latestOrders'=>$this->latestOrders
        ]);
    }

      public function deliveredToCustomerPartial()
    {
        $this->validate();

        if (!$this->Id) {
            throw new \Exception("Order ID is required but received null.");
        }

        // Update the current delivery
        Delivery::where('id', $this->Id)->update([
            'status' => $this->status,
            'remarks' => $this->remarks,
            'customer_delivered_by' => auth()->guard('admin')->user()->id,
        ]);

        // Get all order items for this order
        $orderItems = OrderItem::where('order_id', $this->orderId)->get();
        $totalItems = $orderItems->count();

        // Count of items that have at least one 'Delivered' delivery record
        $deliveredCount = OrderItem::where('order_id', $this->orderId)
            ->whereHas('deliveries', function ($query) {
                $query->where('status', 'Delivered');
            })
            ->count();

        // Decide final order status
        if ($deliveredCount == $totalItems && $totalItems > 0) {
            $newStatus = 'Delivered to Customer';
        } elseif ($deliveredCount > 0) {
            $newStatus = 'Partial Delivered to Customer';
        } else {
            $newStatus = 'Pending';  // fallback
        }

        Order::where('id', $this->orderId)->update(['status' => $newStatus]);

        session()->flash('success', 'Order delivery updated successfully!');
        $this->dispatch('close-delivery-modal');
    }

   
    // public function deliveredToCustomerPartial()
    // {
    //     $this->validate();

    //     if (!$this->Id) {
    //         throw new \Exception("Order ID is required but received null.");
    //     }

    //     // Update the current delivery
    //     Delivery::where('id', $this->Id)->update([
    //         'status' => $this->status,
    //         'remarks' => $this->remarks,
    //         'customer_delivered_by' => auth()->guard('admin')->user()->id,
    //     ]);

    //     // Flags for collection-wise delivery
    //     $collection1Delivered = false;
    //     $collection2Delivered = false;

    //     // Get all order items
    //     $orderItems = OrderItem::where('order_id', $this->orderId)->get();

    //     foreach ($orderItems as $item) {
    //         $delivered = Delivery::where('order_item_id', $item->id)
    //             ->where('status', 'Delivered')
    //             ->exists();

    //         if ($delivered) {
    //             if ($item->collection == 1) {
    //                 $collection1Delivered = true;
    //             } elseif ($item->collection == 2) {
    //                 $collection2Delivered = true;
    //             }
    //         }
    //     }
        
    //     // Decide final order status
    //     if ($collection1Delivered && $collection2Delivered) {
    //         $newStatus = 'Delivered to Customer';
    //     } elseif ($collection1Delivered || $collection2Delivered) {
    //         $newStatus = 'Partial Delivered to Customer';
    //     } else {
    //         $newStatus = 'Pending'; // optional fallback
    //     }

    //     Order::where('id', $this->orderId)->update(['status' => $newStatus]);

    //     session()->flash('success', 'Order delivery updated successfully!');
    //     $this->dispatch('close-delivery-modal');
    // }

    // public function deliveredToCustomerPartial()
    // {
    //     $this->validate();

    //     if (!$this->Id) {
    //         throw new \Exception("Order ID is required but received null.");
    //     }

    //     // Update the current delivery
    //     Delivery::where('id', $this->Id)->update([
    //         'status' => $this->status,
    //         'remarks' => $this->remarks,
    //         'customer_delivered_by' => auth()->guard('admin')->user()->id,
    //     ]);

    //     // Get all order items
    //     $orderItems = OrderItem::where('order_id', $this->orderId)->get();

    //     $totalQuantity = 0;
    //     $totalDelivery = 0;

    //     foreach ($orderItems as $item) {
    //         $totalQuantity += $item->quantity;

    //         // For this item, get all Delivered deliveries
    //         // $deliveries = Delivery::where('order_item_id', $item->id)
    //         //     ->where('status', 'Delivered')
    //         //     ->get();

    //         $deliveries = Delivery::where('order_item_id', $item->id)
    //             ->where('status', 'Delivered')
    //             ->orderBy('id', 'asc') 
    //             ->first();

    //         foreach ($deliveries as $delivery) {
    //             if ($item->collection == 1) {
    //                 $totalDelivery += (int)$delivery->fabric_quantity;
    //             } else {
    //                 $totalDelivery += (int)$delivery->delivered_quantity;
    //             }
    //         }
    //     }

    //     dd($totalQuantity, $totalDelivery);

    //     // Update order status
    //     if ($totalQuantity == $totalDelivery) {
    //         Order::where('id', $this->orderId)->update(['status' => 'Delivered to Customer']);
    //     } elseif ($this->status == 'Delivered') {
    //         Order::where('id', $this->orderId)->update(['status' => 'Partial Delivered to Customer']);
    //     }

    //     session()->flash('success', 'Order delivery updated successfully!');
    //     $this->dispatch('close-delivery-modal');
    // }

    public function openDeliveryModal($Id=null,$orderId=null)
    {
        $this->Id = $Id;
        $this->orderId = $orderId;
    }
    public function markReceivedConfirmed($Id=null)
    {
        //\Log::info("Mark As Received By Sales Team Method method triggered with Order ID: " . ($orderId ?? 'NULL'));

        if (!$Id) {
            throw new \Exception("Order ID is required but received null.");
        }
        Delivery::where('id', $Id)
        ->update( ['status' =>'Received by Sales Team']);
        session()->flash('success', 'Delivery has been receive by sales team!');
        return redirect(url()->previous())->with('success', 'Order has been Delivered to Customer successfully!');


    }
    public function generatePdf($id)
    {
        $order = Order::with([
            'customer'=>fn($q) => $q->select('id','country_code_phone','phone','employee_rank'),
            'items.deliveries' => fn($q) => $q->with('user:id,name'),
            'items.voice_remark','items.catlogue_image'
        ])->findOrFail($id);
        $last_order = Order::where('customer_id', $order['customer_id'])
        ->where('id', '<', $id) // ensure it's before current order
        ->orderBy('id', 'desc') // get the most recent before current
        ->first();
        
       //die($last_order->order_number);exit;
        $item_sold=[];
        $item_delivered=[];
        $rest_items=[];
        $net_qty=0;
        foreach($order['items'] as $item)
        {
            $item_sold[]=$item['quantity'].' '.$item['product_name'];
            $net_qty+=$item['quantity'];
            $delivered_qty=0;
            foreach($item['deliveries'] as $delivered)
            {
                if($delivered['status']=='Delivered')
                {
                    // $delivered_qty+=1;
                     // Extract numeric part from 'delivered_quantity' like '2 pieces'
                    preg_match('/\d+/', $delivered['delivered_quantity'], $matches);
                    $qty = isset($matches[0]) ? (int)$matches[0] : 1;

                    $delivered_qty += $qty;
                }
            }
            $delivered_qty = min($delivered_qty, $item['quantity']);
            if($delivered_qty>0)
            {
                $item_delivered[] = $delivered_qty.' '.$item['product_name'];

            }
            $rest_qty=$item['quantity']- $delivered_qty;
            if($rest_qty>0)
            {
                $rest_items[] = $rest_qty.' '.$item['product_name'];

            }
        }
        $data = [
            'order_no' => $order['order_number'],
            'last_order_no' =>$last_order->order_number ?? 'N/A',
            'name' => $order['customer_name'],
            'rank' =>$order['customer']['employee_rank'],
            'address' =>$order['billing_address'],
            'telephone' => $order['customer']['country_code_phone'].$order['customer']['phone'],
            'amount' => number_format($order['total_amount'], 2, ',', ''),
            'item_sold' => implode("+",$item_sold),
            'rest_items' => implode("+",$rest_items),
            'status' => implode("+",$item_delivered),
            'net_qty' =>$net_qty,
        ];
        $pdf = Pdf::loadView('invoice.product_delivery', $data)->setPaper('A4');
        // return $pdf->download('product_delivery.pdf');
         return response($pdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="product_delivery.pdf"');
    }
}
