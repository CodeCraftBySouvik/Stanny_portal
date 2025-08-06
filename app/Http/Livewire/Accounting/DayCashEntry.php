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
use App\Models\DayCashEntry as DayCashEntryModel;
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

    public $totalCash = 0;
    public $totalNEFT = 0;
    public $totalCheque = 0;
    public $totalDigital = 0;
    public $collectedAmount;

    public $start_date;
    public $end_date;
    public $searchStaff = '';
    public $selectedStaffId = null,$label=null,$entry_type,$balannce;
    public $staffs = [];
    public $payment_date;
    public $staff_id;


    public function mount()
    {
    $this->staffs = User::where('user_type', 0)->whereIn('designation', [2,12])->select('name', 'id','designation')->orderBy('name', 'ASC')->get();

    }
   
    // public function fetchBalance($value)
    // {
    //     $this->staff_id=$value;
    //      // Fetch all payments collected by this user
    //     $collections = PaymentCollection::where('user_id', $value)
    //                     ->where('is_approve', 1)
    //                     ->get();

    //     // Calculate totals
    //     $total = $collections->sum('collection_amount');
    //     $cash = $collections->where('payment_type', 'cash')->sum('collection_amount');
    //     $neft = $collections->where('payment_type', 'neft')->sum('collection_amount');
    //     $cheque = $collections->where('payment_type', 'cheque')->sum('collection_amount');
    //     $digital = $collections->where('payment_type', 'digital_payment')->sum('collection_amount');

    //     // Set display value
    //     $this->totalWallet = $total . " (Cash={$cash}, NEFT={$neft}, Cheque={$cheque}, Digi Payment={$digital})";
    // }
        public function fetchBalance($value)
    {
        $this->staff_id = $value;

        $collections = PaymentCollection::where('user_id', $value)
                        ->where('is_approve', 1)
                        ->where('is_settled', 0)
                        ->get();

        $this->totalCash = $collections->where('payment_type', 'cash')->sum('collection_amount');
        $this->totalNEFT = $collections->where('payment_type', 'neft')->sum('collection_amount');
        $this->totalCheque = $collections->where('payment_type', 'cheque')->sum('collection_amount');
        $this->totalDigital = $collections->where('payment_type', 'digital_payment')->sum('collection_amount');

        $total = $this->totalCash + $this->totalNEFT + $this->totalCheque + $this->totalDigital;

        $this->totalWallet = "{$total} (Cash={$this->totalCash}, NEFT={$this->totalNEFT}, Cheque={$this->totalCheque}, Digi Payment={$this->totalDigital})";
    }

    public function setEntryType($value)
    {
        $this->entry_type=$value;
    }

    protected $rules = [
        'staff_id' => 'required|exists:users,id',
        'totalWallet' => 'required',
        'entry_type' => 'required',
         'collectedAmount' => 'required|numeric|min:1',
    ];

 public function submit()
{
    $this->validate();

    if ($this->entry_type === 'collect') {
        if ($this->collectedAmount > $this->totalCash) {
            $this->addError('collectedAmount', 'Collected amount exceeds available cash.');
            return;
        }
    }

    try {
        \DB::beginTransaction();

        // Save Day Cash Entry
        DayCashEntryModel::create([
            'staff_id'     => $this->staff_id,
            'type'         => $this->entry_type,
            'payment_date' => now()->toDateString(),
            'amount'       => $this->collectedAmount,
        ]);

        if ($this->entry_type === 'collect') {
            $remaining = $this->collectedAmount;

            $collections = PaymentCollection::where('user_id', $this->staff_id)
                ->where('is_approve', 1)
                ->where('is_settled', 0)
                ->orderBy('id')
                ->get();

            foreach ($collections as $collection) {
                if (in_array($collection->payment_type, ['digital_payment', 'cheque', 'neft'])) {
                    $collection->update([
                        'collection_amount' => 0,
                        'is_settled' => 1,
                    ]);
                    continue;
                }

                if ($collection->payment_type === 'cash' && $remaining > 0) {
                    if ($remaining >= $collection->collection_amount) {
                        $remaining -= $collection->collection_amount;
                        $collection->update([
                            'collection_amount' => 0,
                            'is_settled' => 1,
                        ]);
                    } else {
                        $collection->update([
                            'collection_amount' => $collection->collection_amount - $remaining,
                            'is_settled' => 0,
                        ]);
                        $remaining = 0;
                    }
                }
            }
        }

        //  Handle 'given' entries
       if ($this->entry_type === 'given') {
    // 1. Add to wallet balance (NOT expense)
    Payment::create([
        'payment_for' => 'debit',
        'stuff_id' => $this->staff_id,
        'amount' => $this->collectedAmount,
        'payment_in' => 'cash', // or any default
        'voucher_no' => 'EXPENSE'.time(),
        'payment_date'=> now(),
    ]);

    // 2. Only wallet increase, not added to expenses
    Journal::create([
        'payment_id' => null, // if needed
        'is_debit' => 1, // means this is money given to staff (but not expensed)
        'transaction_amount' => $this->collectedAmount,
        'created_at' => now(),
    ]);

   }


        \DB::commit();

        $this->reset([
            'collectedAmount', 'totalWallet', 'staff_id', 'entry_type',
            'totalCash', 'totalNEFT', 'totalCheque', 'totalDigital'
        ]);

        session()->flash('success', 'Day cash entry submitted successfully!');
    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('DayCashEntry Submit Error: ' . $e->getMessage());
        session()->flash('error', 'Something went wrong. Please try again.');
    }
}






     public function render()
    {
        return view('livewire.accounting.day-cash-entry');
    }
}
