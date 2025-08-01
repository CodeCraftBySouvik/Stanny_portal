<?php

namespace App\Http\Livewire\Accounting;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\Journal;

class IndexExpense extends Component
{
    use WithPagination;

    public $search = '';
    public $payment_date = '';
    public $paymentDate = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPaymentDate()
    {
        $this->resetPage();
    }
    public function AddPaymentDate($date){
        $this->payment_date = $date;
    }
    public function FindExpense($keywords){
        $this->search = $keywords;
    }
    public function resetForm(){
        $this->reset(['search','paymentDate']);
    }
    public function render()
    {
         $validPaymentIds = Journal::whereNotNull('payment_id')->pluck('payment_id');
        $expenses = Payment::where('payment_for', 'debit')
            ->whereIn('id', $validPaymentIds)
            ->when($this->search, function ($query) {
                $query->where('voucher_no', 'like', '%' . $this->search . '%')
                      ->orWhere('amount', 'like', '%' . $this->search . '%');
            })
            ->when($this->paymentDate, function ($query) {
                $query->whereDate('payment_date', $this->paymentDate);
            })
            ->orderBy('payment_date', 'desc')
            ->paginate(10);

        return view('livewire.accounting.index-expense', compact('expenses'));
    }
}
