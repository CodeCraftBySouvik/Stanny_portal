<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Helpers\Helper;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;
use App\Models\Delivery;
use App\Models\Invoice;

use Illuminate\Support\Facades\Auth;
// use Barryvdh\DomPDF\Facade as PDF;
// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf;



class OrderIndex extends Component
{
    use WithPagination;

    public $customer_id;
    public $created_by, $search,$status,$start_date,$end_date;
    public $invoiceId;
    public $orderId;
    public $totalPrice;
    public $auth;

    public $tab = 'all';
    // protected $listeners = ['cancelOrder'];
    protected $listeners = ['cancelOrder','markReceivedConfirmed','deliveredToCustomer','deliveredToCustomerPartial'];

    protected $paginationTheme = 'bootstrap'; // Optional: For Bootstrap styling

    public function confirmSalesMarkAsReceived($id){
        $this->dispatch('showSalesMarkAsReceived',['orderId' => $id]);
    }

    //  public function markSalesReceivedConfirmed($orderId)
    // {
    //     $order = Order::find($orderId);
    //     if ($order && $order->status == 'Approved') {
    //         $order->status = 'Received at Sales';
    //         $order->save();

    //         // Optional: add status log or notification
    //         session()->flash('message', 'Order marked as Received.');
    //     } else {
    //         session()->flash('error', 'Order not eligible for receiving.');
    //     }
    // }

    public function changeTab($status){
        $this->tab = $status;
        $this->resetPage();
    }
    public function resetForm(){
        $this->reset(['search', 'start_date','end_date','created_by','status']);
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
    public function setStatus($status){
        $this->status = $status;
    }

    public function export()
    {
        return Excel::download(new OrdersExport(
            $this->customer_id,
            $this->created_by,
            $this->start_date,
            $this->end_date,
            $this->search
        ), 'orders.csv');
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

        ->when($this->created_by, fn($query) => $query->where('created_by', $this->created_by)) // Filter by creator
        ->when($this->start_date, fn($query) => $query->whereDate('created_at', '>=', $this->start_date)) // Start date filter
        ->when($this->end_date, fn($query) => $query->whereDate('created_at', '<=', $this->end_date)) // End date filter
        // ->when(!$auth->is_super_admin, fn($query) => $query->where('created_by', $auth->id)) // Restrict non-admins
        ->when($this->status, fn($query) => $query->where('status', $this->status)) // Filter by creator

        ->when(!$auth->is_super_admin, function ($query) use ($auth) {
            $query->where(function ($subQuery) use ($auth) {
                $subQuery->where('created_by', $auth->id)
                        ->orWhere('team_lead_id', $auth->id);
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(20);
        return view('livewire.order.order-index', [
            'placed_by' => $placed_by,
            'orders' => $orders,
            'usersWithOrders' => $this->usersWithOrders,
        ]);
    }


    public function downloadOrderInvoice($orderId)
    {
        $invoice = Invoice::with(['order', 'customer.billingAddressLatest', 'user', 'packing'])
                    ->where('order_id', $orderId)
                    ->firstOrFail();
        // dd($invoice);
        // Generate PDF
        $pdf = PDF::loadView('invoice.order_pdf', compact('invoice'));

        // Download the PDF
         return response($pdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="invoice_' . $invoice->invoice_no . '.pdf"');
    }
    public function downloadOrderBill($orderId)
    {
        $invoice = Invoice::with(['order', 'customer', 'user', 'packing'])
                    ->where('order_id', $orderId)
                    ->firstOrFail();
        // dd($invoice);
        // Generate PDF
        $pdf = PDF::loadView('invoice.bill_pdf', compact('invoice'));

        // Download the PDF
        return response($pdf->output(), 200)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="bill_' . $invoice->order->order_number . '.pdf"');
    }



    public function confirmCancelOrder($id = null)
    {
        if (!$id) {
            throw new \Exception("Order ID is missing in confirmCancelOrder.");
        }

        $this->dispatch('confirmCancel', orderId: $id);
    }

    public function cancelOrder($orderId = null)
    {
        \Log::info("cancelOrder method triggered with Order ID: " . ($orderId ?? 'NULL'));

        if (!$orderId) {
            throw new \Exception("Order ID is required but received null.");
        }

        // Perform order cancellation logic here
         Order::where('id', $orderId)->update(['status' => 'Cancelled']);

        session()->flash('message', 'Order has been cancelled successfully.');
    }
    public function markReceivedConfirmed($orderId = null)
    {
        \Log::info("Mark As Received After Production method triggered with Order ID: " . ($orderId ?? 'NULL'));

        if (!$orderId) {
            throw new \Exception("Order ID is required but received null.");
        }

        // Perform order cancellation logic here
         Order::where('id', $orderId)->update(['status' => 'Received by Sales Team']);

        session()->flash('message', 'Order has been Received successfully.');
    }
    public function deliveredToCustomer($orderId = null)
    {
        \Log::info("Mark As Received After Production method triggered with Order ID: " . ($orderId ?? 'NULL'));

        if (!$orderId) {
            throw new \Exception("Order ID is required but received null.");
        }

        // Perform order cancellation logic here
         Order::where('id', $orderId)->update(['status' => 'Delivered to Customer']);
         Delivery::where('order_id', $orderId)->update(['status' => 'Delivered to Customer']);

        session()->flash('message', 'Order has been Delivered successfully.');
    }


}
