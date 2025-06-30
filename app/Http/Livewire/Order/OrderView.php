<?php

namespace App\Http\Livewire\Order;
use App\Models\Order;
use App\Models\OrderItem;

use \App\Models\Product;
use \App\Models\Invoice;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;

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

            $product = Product::find($item->product_id);
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
                // 'deliveries' => !empty($item->deliveries)?
                //     $item->deliveries:"",
                'deliveries' => !empty($item->deliveries)
                    ? $item->deliveries->map(function ($delivery) use ($item) {
                        return [
                            'id' => $delivery->id,
                            'delivered_at' => $delivery->delivered_at,
                            'status' => $delivery->status,
                            'remarks' => $delivery->remarks,
                            'fabric_quantity' => $delivery->fabric_quantity,
                            'delivered_quantity' => $delivery->delivered_quantity,
                            'user' => $delivery->user ? ['name' => $delivery->user->name] : ['name' => 'N/A'],
                            'collection_id' => $item->collection, // inject here for later use
                        ];
                    })
                    : [],
                'quantity' => $item->quantity,
                'remarks' => $item->remarks,
                'catlogue_image' => $item->catlogue_image,
                'voice_remark' => $item->voice_remark,

                'product_image' => $product ? $product->product_image : null,
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
    //\Log::info("Mark As Customer Delivered Method method triggered with Order ID: " . ($this->Id ?? 'NULL'));

        if (!$this->Id) {
            throw new \Exception("Order ID is required but received null.");
        }
        $totalQuantity = OrderItem::where('order_id', $this->orderId)->sum('quantity');

        // // Perform order cancellation logic here
        Delivery::where('id', $this->Id)
        ->update( ['status' =>$this->status,'remarks'=>$this->remarks,'customer_delivered_by'=>auth()->guard('admin')->user()->id]);

        $totalDelevery= Delivery::where('order_id', $this->orderId)->where('status','Delivered to Customer')->sum('delivered_quantity');

        if($totalQuantity==$totalDelevery)
        {
           Order::where('id', operator: $this->orderId)->update(['status' => 'Delivered to Customer']);
        }
        else{
            if($this->status=='Delivered')
            {
                Order::where('id', operator: $this->orderId)->update(['status' => 'Partial Delivered to Customer']);

            }

        }

    session()->flash('success', 'Order delivery updated successfully!');

    // Close modal (optional)
    $this->dispatch('close-delivery-modal');


        //return redirect(url()->previous())->with('success', 'Order has been Delivered to Customer successfully!');
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
}
