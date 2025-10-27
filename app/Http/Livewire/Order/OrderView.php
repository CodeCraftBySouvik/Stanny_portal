<?php

namespace App\Http\Livewire\Order;
use App\Models\Order;
use App\Models\OrderItem;

use \App\Models\Product;
use \App\Models\Invoice;
use App\Models\Delivery;
use App\Models\Measurement;
use App\Models\InvoicePayment;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Carbon\Carbon;

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
            'items.catalogue',
            'items.deliveries' => fn($q) => $q->with('user:id,name'),
            'items.voice_remark','items.catlogue_image'
         ])->findOrFail($this->orderId);
         $orderItems = $order->items->map(function ($item) use($order) {
            $product = Product::find($item->product_id);
            $delivery = $item->deliveries->first();
             // Decide extra measurement type
             $extra = \App\Helpers\Helper::ExtraRequiredMeasurement($item->product_name);
              //  Build item-specific measurements
            $measurements = Measurement::where('product_id', $item->product_id)
                ->orderBy('position','ASC')
                ->get()
                ->map(function ($measurement) use ($item) {
                    $selected = $item->measurements->firstWhere('measurement_name', $measurement->title);
                    return [
                        'measurement_name'          => $measurement->title,
                        'measurement_title_prefix'  => $measurement->short_code,
                        'measurement_value'         => $selected ? $selected->measurement_value : '',
                    ];
                });
            return [
                'product_name' => $item->product_name ?? $product->name,
                'measurements' => $measurements,
                'collection_id' => $item->collection,
                'collection_title' => $item->collectionType ?  $item->collectionType->title : "",
                'fabrics' => $item->fabric,
                'catalogue' => optional(optional($item->catalogue)->catalogueTitle)->title ?? "",
                'catalogue_id' => $item->catalogue_id,
                'cat_page_number' => $item->cat_page_number,
                'cat_page_item' => $item->cat_page_item,
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
                'extra_type'           => $extra,
                'shoulder_type'        => $item->shoulder_type,
                'vents'                => $item->vents,
                'vents_required'       => $item->vents_required,
                'vents_count'          => $item->vents_count,
                'fold_cuff_required'   => $item->fold_cuff_required,
                'fold_cuff_size'       => $item->fold_cuff_size,
                'pleats_required'      => $item->pleats_required,
                'pleats_count'         => $item->pleats_count,
                'back_pocket_required' => $item->back_pocket_required,
                'back_pocket_count'    => $item->back_pocket_count,
                'adjustable_belt'      => $item->adjustable_belt,
                'suspender_button'     => $item->suspender_button,
                'trouser_position'     => $item->trouser_position,   
                'client_name_required'     => $item->client_name_required,   
                'client_name_place'     => $item->client_name_place,   
            ];
        });

        return view('livewire.order.order-view',[
            'order' => $order,
            'orderItems' => $orderItems,
            'latestOrders'=>$this->latestOrders,
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
        // ======================
        //  ITEM CALCULATIONS
        // ======================
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

            // ======================
            //  PAYMENT TABLE LOGIC
            // ======================
            $totalAmount = $order->total_amount;
            $deliveredAmount = 0;
            $paymentRows = [];

            // Fetch payments related to this order’s invoices
           $payments = InvoicePayment::whereHas('invoice', function($q) use ($order) {
                $q->where('order_id', $order->id);
            })->orderBy('created_at', 'asc')->get();

            $totalAmount = $order->total_amount;
            $totalPaid = 0;
            $paymentRows = [];

            foreach ($payments as $p) {
                $totalPaid += $p->paid_amount;
                $remaining = max(0, $totalAmount - $totalPaid); // TOTAL.REST

                // if fully paid, actual rest should also be 0
                $actualRest = ($remaining == 0) ? 0 : $p->rest_amount;

                $paymentRows[] = [
                    'date' => Carbon::parse($p->created_at)->format('d.m.Y'),
                    'pay' => number_format($p->paid_amount, 2),
                    'total_rest' => number_format($remaining, 2),
                    'act_rest' => number_format($actualRest, 2),
                    'signature' => '',
                ];
            }


             // ======================
            //  PDF DATA
            // ======================
            
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
            'paymentRows' => $paymentRows,
        ];
        $pdf = Pdf::loadView('invoice.product_delivery', $data)->setPaper('A4');
        // return $pdf->download('product_delivery.pdf');
         return response($pdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="product_delivery.pdf"');
    }
}
