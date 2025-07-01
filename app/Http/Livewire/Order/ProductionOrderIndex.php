<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Helpers\Helper;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Delivery;
use App\Models\OrderStockEntry;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;


class ProductionOrderIndex extends Component
{
     use WithPagination;
    
    public $customer_id;
    public $created_by, $search,$status,$start_date,$end_date; 
    public $invoiceId;
    public $orderId;
    public $totalPrice;
    public $auth;
    public $stockOrderId;
    public $showStockModal = false;
    
    public $tab = 'all';
    // protected $listeners = ['cancelOrder'];
    protected $listeners = ['cancelOrder'];
    

    protected $paginationTheme = 'bootstrap'; // Optional: For Bootstrap styling

    public function confirmMarkAsReceived($id){
        $this->dispatch('showMarkAsReceived',['orderId' => $id]);
    }

     public function markReceivedConfirmed($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status == 'Approved') {
            $order->status = 'Received at Production';
            $order->save();

            // Optional: add status log or notification
            session()->flash('message', 'Order marked as Received.');
        } else {
            session()->flash('error', 'Order not eligible for receiving.');
        }
    }

    
    public function openStockModal($orderNumber)
    {
        $this->stockOrderId = $orderNumber;
        $this->dispatch('showStockModal');
    }

    public function closeStockModal()
    {
        $this->reset(['stockOrderId']);
        $this->dispatch('hideStockModal');
    }


    public function changeTab($status){
        $this->tab = $status;
        $this->resetPage();
    }
    
    public function resetForm(){
        $this->reset(['search', 'start_date','end_date','created_by']);
    }

    public function updatingSearch()
    {
        $this->resetPage(); 
    }

     public function mount($customer_id = null)
    {
        $this->customer_id = $customer_id; // Store the customer_id if provided
    }
    public function FindCustomer($keywords){
        $this->search = $keywords;
    }
    public function AddStartDate($date){
        $this->start_date = $date;
    }
    public function AddEndDate($date){
        $this->end_date = $date;
    }
    public function CollectedBy($staff_id){
        $this->created_by = $staff_id;
    }

    public function downloadOrderPdf($orderid){
       $order = Order::with('items','customer')->findOrFail($orderid);
       $previousOrder = Order::where('id','<',$orderid)
                                ->orderBy('id','desc')
                                ->first();
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
                'quantity' => $item->quantity,
                'product_image' => $product ? $product->product_image : null,
                'remarks' => $item->remarks,
                'catlogue_image' => $item->catlogue_image,
                'voice_remark' => $item->voice_remark,
                'expected_delivery_date' => $item->expected_delivery_date,
            ];
        });

        $pdf = PDF::loadView('invoice.production_pdf', compact('orderItems','order','previousOrder'));
         return response($pdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="bill_' . $order->order_number . '.pdf"');
    }

    public function render()
    {
         $placed_by = User::where('user_type', 0)->get();
         $auth = Auth::guard('admin')->user();

        if($auth->is_super_admin){
            $wonOrders = order::get()->pluck('created_by')->toArray();
        }else{
            // Fetch orders
            $wonOrders = $auth->orders(); // Start the query
            // dd($wonOrders);
            // If the user is not a super admin, filter by `created_by`
            if (!$auth->is_super_admin) {
                $wonOrders->where('created_by', $auth->id);
            }
            // Execute the query
            $wonOrders = $wonOrders->get()->pluck('created_by')->toArray();
        }
        
       
        $this->usersWithOrders = $wonOrders;
        $orders = Order::query()
        ->whereIn('status',['Approved','Received at Production','Partial Delivered By Production','Fully Delivered By Production'])
        // ->where('status', '!=' , 'Cancelled') // Uncomment if needed
        ->when($this->customer_id, fn($query) => $query->where('customer_id', $this->customer_id)) // Filter by customer ID
        ->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('order_number', 'like', '%' . $this->search . '%')
                ->orWhereHas('customer', function ($q2) {
                    $q2->where(function ($subQuery) {
                        $subQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%')
                                ->orWhere('phone', 'like', '%' . $this->search . '%')
                                ->orWhere('whatsapp_no', 'like', '%' . $this->search . '%'); 
                    });
                });
            });
        })

        ->when($this->created_by, fn($query) => $query->where('created_by', $this->created_by))
        ->when($this->start_date, fn($query) => $query->whereDate('created_at', '>=', $this->start_date)) 
        ->when($this->end_date, fn($query) => $query->whereDate('created_at', '<=', $this->end_date))
        ->orderBy('created_at', 'desc')
        ->paginate(20);
        
        $orderId = $orders->pluck('id');
        $stockEntries = OrderStockEntry::whereIn('order_id', $orderId)
                    ->select('order_id')
                    ->distinct()
                    ->pluck('order_id')
                    ->toArray();
        
        $deliveredOrderIds = Delivery::whereIn('order_id', $orderId)
                            ->select('order_id')
                            ->distinct()
                            ->pluck('order_id')
                            ->toArray();

        return view('livewire.order.production-order-index',[
             'placed_by' => $placed_by,
             'orders' => $orders,
             'usersWithOrders' => $this->usersWithOrders,
             'has_order_entry' => $stockEntries, 
             'has_delivered'  => $deliveredOrderIds
        ]);
    }
}
