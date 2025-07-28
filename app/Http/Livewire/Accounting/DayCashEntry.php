<?php

namespace App\Http\Livewire\Accounting;
use Livewire\Component;
use App\Models\PaymentCollection;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Journal;
use App\Models\Ledger;
use App\Models\Payment;
use App\Models\PaymentRevoke;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;

class DayCashEntry extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $totalCollections = 0;
    public $totalExpenses = 0;
    public $totalWallet = 0;
    public $paymentCollections = [];
    public $paymentExpenses = [];
    public $totalcashCollections = 0;
    public $totalneftCollections = 0;
    public $totalchequeCollections = 0;
    public $totaldigitalCollections = 0;

    public $start_date;
    public $end_date;
    public $searchStaff = '';
    public $selectedStaffId = null,$amount,$label=null,$entry_type,$balannce;
    public $staffs = [];
    public function mount()
    {
    $this->staffs = User::where('user_type', 0)->whereIn('designation', [2,12])->select('name', 'id','designation')->orderBy('name', 'ASC')->get();

    }
    public function render()
    {
        return view('livewire.accounting.day-cash-entry');
    }
    public function fetchBalance($value)
    {
        $this->staff_id=$value;
    //     $collectionQuery = PaymentCollection::where('is_approve', 1)
    //         ->where('user_id', $this->staff_id)
    //         ->where(function ($query) {
    //     $query->where('payment_type', '!=', 'cheque')  // Include all except 'cheque'
    //           ->orWhere(function ($subQuery) {
    //               $subQuery->where('payment_type', 'cheque')
    //                        ->whereNotNull('credit_date'); // Only include 'cheque' if credit_date is not null
    //           });
    // });
    //     $this->totalCollections = $collectionQuery->sum('collection_amount');

    //     $collectionQuery = PaymentCollection::where('is_approve', 1)
    //         ->where('payment_type','cash')
    //         ->where('user_id', $this->staff_id);


    //     $this->totalcashCollections = $collectionQuery->sum('collection_amount');
    //      $collectionQuery = PaymentCollection::where('is_approve', 1)
    //         ->where('payment_type','neft')
    //         ->where('user_id', $this->staff_id);



    //     $this->totalneftCollections = $collectionQuery->sum('collection_amount');

    //     $collectionQuery = PaymentCollection::where('is_approve', 1)
    //         ->where('payment_type','digital_payment')
    //         ->where('user_id', $this->staff_id);



    //     $this->totaldigitalCollections = $collectionQuery->sum('collection_amount');

    //      $collectionQuery = PaymentCollection::where('is_approve', 1)
    //         ->where('payment_type','cheque')
    //         ->whereNotNull('credit_date')
    //         ->where('user_id', $this->staff_id);



    //     $this->totalchequeCollections = $collectionQuery->sum('collection_amount');
    //     $expenseQuery = Journal::where('is_debit', 1);
    //                 //   ->where('user_id', $this->staff_id);



    //     $this->totalExpenses = $expenseQuery->sum('transaction_amount');

    //     $pastCollections = PaymentCollection::where('is_approve', 1)
    //         ->whereDate('created_at', '<', $this->start_date)
    //         ->where('user_id', $this->staff_id)

    //         ->sum('collection_amount');

    //     // Opening Balance (Past Expenses)
    //     // $pastExpenses = Journal::where('is_debit', 1)
    //     //     ->whereDate('created_at', '<', $this->start_date)
    //     //     ->where('user_id', $this->staff_id)

    //     //     ->sum('transaction_amount');

    //     $openingBalance = $pastCollections ;
    //     $this->totalWallet = $openingBalance + ($this->totalCollections - $this->totalExpenses);



    }
    public function setEntryType($value)
    {
        $this->entry_type=$value;
    }
}
