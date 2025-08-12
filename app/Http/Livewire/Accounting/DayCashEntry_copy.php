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
    public $showCashInput = false;
    public $showDigitalInput = false;
    public $cashCollectedAmount;
    public $digitalCollectedAmount;

    public function mount()
    {
        $this->staffs = User::where('user_type', 0)->whereIn('designation', [2,12])->select('name', 'id','designation')->orderBy('name', 'ASC')->get();

    }
    public function toggleCashCheckbox()
    {
        $this->showCashInput = !$this->showCashInput;
    }
    public function toggleDigitalCheckbox()
    {
        $this->showDigitalInput = !$this->showDigitalInput;
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
        // $this->totalDigital = $collections->where('payment_type', 'digital_payment')->sum('collection_amount');
         $this->totalDigital = $collections
        ->where('payment_type', 'digital_payment')
        ->sum(function ($item) {
            return $item->collection_amount + $item->withdrawal_charge;
        });
        
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

//     public function submit()
//     {
//     $this->validate();

//     // Require at least one payment type
//     if (!$this->payment_cash && !$this->payment_digital) {
//         $this->addError('payment_cash', 'Please select at least one payment type.');
//         return;
//     }
    
//     if ($this->entry_type === 'collect') {
//         if ($this->collectedAmount > $this->totalCash) {
//             $this->addError('collectedAmount', 'Collected amount exceeds available cash.');
//             return;
//         }
//     }

//     try {
//         \DB::beginTransaction();

//         // Save Day Cash Entry
//         DayCashEntryModel::create([
//             'staff_id'     => $this->staff_id,
//             'type'         => $this->entry_type,
//             'payment_date' => now()->toDateString(),
//             'amount'       => $this->collectedAmount,
//         ]);

//         if ($this->entry_type === 'collect') {
//             $remaining = $this->collectedAmount;

//             $collections = PaymentCollection::where('user_id', $this->staff_id)
//                 ->where('is_approve', 1)
//                 ->where('is_settled', 0)
//                 ->orderBy('id')
//                 ->get();

//             foreach ($collections as $collection) {
//                 if (in_array($collection->payment_type, ['cheque', 'neft'])) {
//                     $collection->update([
//                         'collection_amount' => 0,
//                         'is_settled' => 1,
//                     ]);
//                     continue;
//                 }

//                 if ($collection->payment_type === 'cash' && $remaining > 0) {
//                     if ($remaining >= $collection->collection_amount) {
//                         $remaining -= $collection->collection_amount;
//                         $collection->update([
//                             'collection_amount' => 0,
//                             'is_settled' => 1,
//                         ]);
//                     } else {
//                         $collection->update([
//                             'collection_amount' => $collection->collection_amount - $remaining,
//                             'is_settled' => 0,
//                         ]);
//                         $remaining = 0;
//                     }
//                 }
//             }
//         }

//         //  Handle 'given' entries
//        if ($this->entry_type === 'given') {
//     // 1. Add to wallet balance (NOT expense)
//     Payment::create([
//         'payment_for' => 'debit',
//         'stuff_id' => $this->staff_id,
//         'amount' => $this->collectedAmount,
//         'payment_in' => 'cash', // or any default
//         'voucher_no' => 'EXPENSE'.time(),
//         'payment_date'=> now(),
//     ]);

//     // 2. Only wallet increase, not added to expenses
//     Journal::create([
//         'payment_id' => null, // if needed
//         'is_debit' => 1, // means this is money given to staff (but not expensed)
//         'transaction_amount' => $this->collectedAmount,
//         'created_at' => now(),
//     ]);

//    }


//         \DB::commit();

//         $this->reset([
//             'collectedAmount', 'totalWallet', 'staff_id', 'entry_type',
//             'totalCash', 'totalNEFT', 'totalCheque', 'totalDigital'
//         ]);

//         session()->flash('success', 'Day cash entry submitted successfully!');
//     } catch (\Exception $e) {
//         \DB::rollBack();
//         \Log::error('DayCashEntry Submit Error: ' . $e->getMessage());
//         session()->flash('error', 'Something went wrong. Please try again.');
//     }
// }


    //   public function submit()
    // {
    //     // Validation
    //     $this->validate([
    //         'staff_id' => 'required|exists:users,id',
    //         'totalWallet' => 'required',
    //         'entry_type' => 'required',
    //         'collectedAmount' => 'required|numeric|min:1',
    //     ]);

    //     // Require at least one payment type
    //     if (!$this->payment_cash && !$this->payment_digital) {
    //         $this->addError('payment_cash', 'Please select at least one payment type.');
    //         return;
    //     }

    //     // Type-specific checks
    //     if ($this->entry_type === 'collect') {
    //         if ($this->payment_cash && !$this->payment_digital && $this->collectedAmount > $this->totalCash) {
    //             $this->addError('collectedAmount', 'Collected amount exceeds available cash.');
    //             return;
    //         }

    //         if ($this->payment_digital && !$this->payment_cash && $this->collectedAmount > $this->totalDigital) {
    //             $this->addError('collectedAmount', 'Collected amount exceeds available digital payment.');
    //             return;
    //         }

    //         if ($this->payment_cash && $this->payment_digital) {
    //             $totalAvailable = $this->totalCash + $this->totalDigital;
    //             if ($this->collectedAmount > $totalAvailable) {
    //                 $this->addError('collectedAmount', 'Collected amount exceeds available cash + digital payment.');
    //                 return;
    //             }
    //         }
    //     }


    //     try {
    //         \DB::beginTransaction();
    //         // dd($this->all());
    //         // Save entry
    //         DayCashEntryModel::create([
    //             'staff_id'     => $this->staff_id,
    //             'type'         => $this->entry_type,
    //             'payment_date' => now()->toDateString(),
    //             'amount'       => $this->collectedAmount,
    //             'payment_cash' => $this->payment_cash,
    //             'payment_digital' => $this->payment_digital,
    //         ]);

    //         if ($this->entry_type === 'collect') {
    //             $remaining = $this->collectedAmount;

    //             $collections = PaymentCollection::where('user_id', $this->staff_id)
    //                 ->where('is_approve', 1)
    //                 ->where('is_settled', 0)
    //                 ->orderBy('id')
    //                 ->get();

    //             foreach ($collections as $collection) {
    //                 // Skip if not matching the selected payment types
    //                 if (
    //                     ($collection->payment_type === 'cash' && !$this->payment_cash) ||
    //                     ($collection->payment_type === 'digital_payment' && !$this->payment_digital)
    //                 ) {
    //                     continue;
    //                 }

    //                 // Settlement logic
    //                 if (in_array($collection->payment_type, ['cheque', 'neft'])) {
    //                     $collection->update([
    //                         'collection_amount' => 0,
    //                         'is_settled' => 1,
    //                     ]);
    //                     continue;
    //                 }

    //                 if ($remaining > 0) {
    //                     if ($remaining >= $collection->collection_amount) {
    //                         $remaining -= $collection->collection_amount;
    //                         $collection->update([
    //                             'collection_amount' => 0,
    //                             'is_settled' => 1,
    //                         ]);
    //                     } else {
    //                         $collection->update([
    //                             'collection_amount' => $collection->collection_amount - $remaining,
    //                             'is_settled' => 0,
    //                         ]);
    //                         $remaining = 0;
    //                     }
    //                 }
    //             }
    //         }

    //         if ($this->entry_type === 'given') {
    //             Payment::create([
    //                 'payment_for' => 'debit',
    //                 'stuff_id' => $this->staff_id,
    //                 'amount' => $this->collectedAmount,
    //                 'payment_in' => $this->payment_cash ? 'cash' : 'digital_payment',
    //                 'voucher_no' => 'EXPENSE'.time(),
    //                 'payment_date'=> now(),
    //             ]);

    //             Journal::create([
    //                 'payment_id' => null,
    //                 'is_debit' => 1,
    //                 'transaction_amount' => $this->collectedAmount,
    //                 'created_at' => now(),
    //             ]);
    //         }

    //         \DB::commit();

    //         $this->reset([
    //             'collectedAmount', 'totalWallet', 'staff_id', 'entry_type',
    //             'totalCash', 'totalNEFT', 'totalCheque', 'totalDigital',
    //             'payment_cash', 'payment_digital'
    //         ]);

    //         session()->flash('success', 'Day cash entry submitted successfully!');
    //     } catch (\Exception $e) {
    //         \DB::rollBack();
    //         \Log::error('DayCashEntry Submit Error: ' . $e->getMessage());
    //         session()->flash('error', 'Something went wrong. Please try again.');
    //     }
    // }

    public function submit()
    {
        // Validation
        $this->validate([
            'staff_id' => 'required|exists:users,id',
            'totalWallet' => 'required',
            'entry_type' => 'required',
            'cashCollectedAmount' => 'nullable|numeric|min:0',
            'digitalCollectedAmount' => 'nullable|numeric|min:0',
        ]);

        // Ensure at least one payment type is selected
        if (!$this->payment_cash && !$this->payment_digital) {
            $this->addError('payment_cash', 'Please select at least one payment type.');
            return;
        }

        // Total collected
        $totalCollected = ($this->cashCollectedAmount ?? 0) + ($this->digitalCollectedAmount ?? 0);

        if ($totalCollected <= 0) {
            $this->addError('cashCollectedAmount', 'Please enter a valid amount for at least one payment type.');
            return;
        }

        // Type-specific checks
        if ($this->entry_type === 'collect') {
            if ($this->cashCollectedAmount > $this->totalCash) {
                $this->addError('cashCollectedAmount', 'Cash amount exceeds available cash.');
                return;
            }
            if ($this->digitalCollectedAmount > $this->totalDigital) {
                $this->addError('digitalCollectedAmount', 'Digital amount exceeds available digital payments.');
                return;
            }
        }

        try {
            \DB::beginTransaction();

            // Save Day Cash Entry
            DayCashEntryModel::create([
                'staff_id'        => $this->staff_id,
                'type'            => $this->entry_type,
                'payment_date'    => now()->toDateString(),
                'amount'          => $totalCollected,
                'payment_cash'    => $this->cashCollectedAmount ?? 0,
                'payment_digital' => $this->digitalCollectedAmount ?? 0,
            ]);

            if ($this->entry_type === 'collect') {
                // Settlement for cash
                $remainingCash = $this->cashCollectedAmount ?? 0;
                if ($remainingCash > 0) {
                    $cashCollections = PaymentCollection::where('user_id', $this->staff_id)
                        ->where('is_approve', 1)
                        ->where('is_settled', 0)
                        ->where('payment_type', 'cash')
                        ->orderBy('id')
                        ->get();

                    foreach ($cashCollections as $collection) {
                        if ($remainingCash <= 0) break;

                        if ($remainingCash >= $collection->collection_amount) {
                            $remainingCash -= $collection->collection_amount;
                            $collection->update([
                                'collection_amount' => 0,
                                'is_settled' => 1,
                            ]);
                        } else {
                            $collection->update([
                                'collection_amount' => $collection->collection_amount - $remainingCash,
                                'is_settled' => 0,
                            ]);
                            $remainingCash = 0;
                        }
                    }
                }

                // Settlement for digital
                $remainingDigital = $this->digitalCollectedAmount ?? 0;
                if ($remainingDigital > 0) {
                    $digitalCollections = PaymentCollection::where('user_id', $this->staff_id)
                        ->where('is_approve', 1)
                        ->where('is_settled', 0)
                        ->where('payment_type', 'digital_payment')
                        ->orderBy('id')
                        ->get();

                    foreach ($digitalCollections as $collection) {
                        if ($remainingDigital <= 0) break;

                        if ($remainingDigital >= $collection->collection_amount) {
                            $remainingDigital -= $collection->collection_amount;
                            $collection->update([
                                'collection_amount' => 0,
                                'is_settled' => 1,
                            ]);
                        } else {
                            $collection->update([
                                'collection_amount' => $collection->collection_amount - $remainingDigital,
                                'is_settled' => 0,
                            ]);
                            $remainingDigital = 0;
                        }
                    }
                }
            }

            // Given type (debit to staff)
            if ($this->entry_type === 'given') {
                if ($this->cashCollectedAmount > 0) {
                    Payment::create([
                        'payment_for' => 'debit',
                        'stuff_id' => $this->staff_id,
                        'amount' => $this->cashCollectedAmount,
                        'payment_in' => 'cash',
                        'voucher_no' => 'EXPENSE' . time(),
                        'payment_date'=> now(),
                    ]);
                }
                if ($this->digitalCollectedAmount > 0) {
                    Payment::create([
                        'payment_for' => 'debit',
                        'stuff_id' => $this->staff_id,
                        'amount' => $this->digitalCollectedAmount,
                        'payment_in' => 'digital_payment',
                        'voucher_no' => 'EXPENSE' . time(),
                        'payment_date'=> now(),
                    ]);
                }

                Journal::create([
                    'payment_id' => null,
                    'is_debit' => 1,
                    'transaction_amount' => $totalCollected,
                    'created_at' => now(),
                ]);
            }

            \DB::commit();

            $this->reset([
                'cashCollectedAmount', 'digitalCollectedAmount', 'totalWallet', 'staff_id', 'entry_type',
                'totalCash', 'totalNEFT', 'totalCheque', 'totalDigital',
                'payment_cash', 'payment_digital'
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
