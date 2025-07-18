<?php

namespace App\Http\Livewire\PurchaseOrder;

use Livewire\Component;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrderIndex extends Component
{
    public $purchaseOrders = '';
    public $search = '';
    protected $paginationTheme = 'bootstrap'; // Optional: For Bootstrap styling
    public function mount(){
        
    }
      public function updatingSearch()
    {
        $this->resetPage(); 
    }
    public function FindCustomer($keywords){
        $this->search = $keywords;
    }

    public function resetForm(){
        $this->reset(['search']);
    }
    // $purchaseOrder = PurchaseOrder::with('supplier', 'orderproducts')->findOrFail($purchase_order_id);
    // $pdf = Pdf::loadView('livewire.purchase-order.generate-pdf', compact('purchaseOrder'));
    // return $pdf->download('purchase_order_' . $purchase_order_id . '.pdf');
    public function downloadPdf($purchase_order_id)
    {
        // $invoice = Invoice::with(['order', 'customer', 'user', 'packing'])
        //             ->where('order_id', $orderId)
        //             ->firstOrFail();
        $purchaseOrder = PurchaseOrder::with('supplier', 'orderproducts')->findOrFail($purchase_order_id);
        // Generate PDF
        $pdf =  Pdf::loadView('livewire.purchase-order.generate-pdf', compact('purchaseOrder'));
        $pdf->setPaper('A4', 'portrait');
        // Download the PDF
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'purchase_order_' . $purchase_order_id . '.pdf');
        // return $pdf->download('purchase_order_' . $purchase_order_id . '.pdf');
    }
    public function render()
    {
        $query = PurchaseOrder::with(['orderproducts.product', 'orderproducts.fabric', 'orderproducts.collection'])
        ->when(!empty($this->search), function ($query) {
            $query->where('unique_id', 'like', '%' . $this->search . '%')
                ->orWhereHas('supplier', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('mobile', 'like', '%' . $this->search . '%');
                });
        })
        ->orderBy('id','DESC')
        ->paginate(20);

        return view('livewire.purchase-order.purchase-order-index', [
            'data' => $query,
        ]);
    }
    
}
