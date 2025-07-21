<?php

namespace App\Http\Livewire\Accounting;

use Livewire\Component;
use App\Models\User;
use App\Models\Order;
use App\Models\PaymentCollection;
use App\Models\Country;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Interfaces\AccountingRepositoryInterface;

class AddPaymentReceipt extends Component
{
    protected $accountingRepository;
    public $searchResults = [];
    public $errorClass = [];
    public $errorMessage = [];
    public $activePayementMode = 'cash';
    public $staffs =[];
    public $my_designation;
    public $payment_voucher_no;
    public $payment_id;
    public $new_customer = false;
    public $payment_collection_id = "";
    public $readonly = "readonly";
    public $customer,$customer_id, $customer_name, $staff_id, $amount, $voucher_no, $payment_date,$next_payment_date,$credit_date,$deposit_date,$payment_mode, $chq_utr_no, $bank_name, $receipt_for = "Customer",$cheque_photo;
    public $mobileLengthPhone,$countries,$selectedCountryPhone,$phone,$customer_email,$customer_company,$customer_address;
    public function boot(AccountingRepositoryInterface $accountingRepository)
    {
        $this->accountingRepository = $accountingRepository;
    }
    public function mount($payment_voucher_no = ""){
          $user = Auth::guard('admin')->user();
        $this->my_designation = $user->designation;
        $payment_collection = PaymentCollection::with('customer', 'user')->where('voucher_no',$payment_voucher_no)->first();

        if(!empty($payment_voucher_no)){
            if(!$payment_collection){
                abort(404);
                return false;
            }
        }

        $this->payment_voucher_no = $payment_voucher_no;
        $this->voucher_no = 'PAYRECEIPT'.time();
         if($user->designation == 1){
            $this->staffs = User::where('user_type', 0)->whereIn('designation', [2,12])->select('name', 'id','designation')->orderBy('name', 'ASC')->get();
        }else{
            $this->staffs = collect([$user]); // Only themselves
            $this->staff_id = $user->id;
        }
        if($payment_collection){
            $this->payment_collection_id = $payment_collection->id;
            $this->customer = $payment_collection->customer->name;
            $this->customer_id = $payment_collection->customer_id;
            $this->staff_id = $payment_collection->user_id;
            $this->amount = $payment_collection->collection_amount;
            $this->voucher_no = $payment_collection->voucher_no;
            $this->payment_date = $payment_collection->cheque_date;
            $this->payment_mode = $payment_collection->payment_type;
            $this->chq_utr_no = $payment_collection->cheque_number;
            $this->bank_name = $payment_collection->bank_name;
            $this->payment_id = $payment_collection->payment_id;
            $this->activePayementMode = $payment_collection->payment_type;
        }
        if(empty($payment_voucher_no)){
            $this->readonly = "";
        }

        $this->countries = Country::where('status',1)->get();
    }
    public function rules()
    {
       $rules = [];
    if ($this->payment_mode === 'cheque') {
        $rules['credit_date'] = 'required'; // Optional: adjust validation as needed
        $rules['deposit_date'] = 'required'; // Optional: adjust validation as needed
        $rules['cheque_photo'] = 'required|image|max:5120';
    }

    return $rules;
    }
    public function GetCountryDetails($mobileLength, $field){
        switch($field){
            case 'phone':
                $this->mobileLengthPhone  = $mobileLength;
                break;
        }
    }

    public function changeNewCustomer(){
        // dd($this->new_customer); // This will show true or false
    }

    public function submitForm()
    {
        // dd($this->all());

        $this->reset(['errorMessage']);

        $this->errorMessage = array();
                         $this->validate();

        // Validate customer
        if($this->new_customer){
            if (empty($this->customer_name)) {
                $this->errorMessage['customer_name'] = 'Please enter customer name.';
            }
             if (empty($this->phone)) {
                $this->errorMessage['phone'] = 'Please enter mobile number.';
            } elseif (!ctype_digit($this->phone)) {
                $this->errorMessage['phone'] = 'Phone number must contain only digits.';
            } elseif (strlen($this->phone) != $this->mobileLengthPhone) {
                $this->errorMessage['phone'] = 'Phone number must be exactly ' . $this->mobileLengthPhone . ' digits.';
            }

        }else{
            if (empty($this->customer_id)) {
                $this->errorMessage['customer_id'] = 'Please select a customer.';
            }
        }


        // Validate collected by
        if (empty($this->staff_id)) {
           $this->errorMessage['staff_id'] = 'Please select a staff member.';
        }

        // Validate amount
        if (empty($this->amount) || !is_numeric($this->amount)) {
           $this->errorMessage['amount'] = 'Please enter a valid amount.';
        }

        // Validate voucher no
        if (empty($this->voucher_no)) {
           $this->errorMessage['voucher_no'] = 'Please enter a voucher number.';
        }

        // Validate payment date
        if (empty($this->payment_date) || !$this->is_valid_date($this->payment_date)) {
           $this->errorMessage['payment_date'] = 'Please select a valid payment date.';
        }

        // Validate payment mode
        if (empty($this->payment_mode)) {
           $this->errorMessage['payment_mode'] = 'Please select a payment mode.';
        }

        // Validate cheque no / UTR no
        if ($this->payment_mode != 'cash' && empty($this->chq_utr_no)) {
           $this->errorMessage['chq_utr_no'] = 'Please enter a cheque no / UTR no.';
        }

        // Validate bank name
        if ($this->payment_mode != 'cash' && empty($this->bank_name)) {
           $this->errorMessage['bank_name'] = 'Please enter a bank name.';
        }
        if(count($this->errorMessage)>0){
            return $this->errorMessage;
        }else{
            try {
                DB::beginTransaction();
                if($this->new_customer){
                    $user = new User;
                    $user->name = ucwords($this->customer_name);
                    $user->country_code_phone = $this->selectedCountryPhone;
                    $user->phone = $this->phone;
                    $user->email = $this->customer_email ?? null;
                    $user->company_name = $this->customer_company ?? null;
                    $user->location = $this->customer_address ?? null;
                    $user->user_type = 1; // Assuming 1 is for customers
                    $user->save();
                    $this->customer_id = $user->id;
                }

                //code...
                $this->accountingRepository->StorePaymentReceipt($this->all());
                session()->flash('success', 'Payment receipt added successfully.');
                DB::commit();
                return redirect()->route('admin.accounting.payment_collection');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', $e->getMessage());
            }
        }

    }
    public function ResetForm(){
        $this->reset(['customer','customer_id','staff_id', 'amount', 'voucher_no', 'payment_date', 'payment_mode', 'chq_utr_no', 'bank_name']);
        $this->voucher_no = 'PAYRECEIPT'.time();
    }

    public function FindCustomer($term)
    {
        $this->searchResults = Helper::GetCustomerDetails($term);
    }
      // Function to validate date
       public function is_valid_date($date) {
            $timestamp = strtotime($date);
            if ($timestamp !== false) {
                return true;
            }
            return false;
        }

      public function selectCustomer($customer_id){

            $customer = User::find($customer_id);
            if($customer){
                $this->customer = $customer->name.'('.$customer->phone.')';
                $this->customer_id = $customer->id;
            }else{
                $this->reset(['customer','customer_id',]);
            }
            $this->searchResults = [];

      }
    public function ChangePaymentMode($value){
        $this->activePayementMode = $value;
    }
    public function render()
    {
        $this->staff_id = Auth::guard('admin')->user()->id;
        $this->payment_date=date('Y-m-d');
        return view('livewire.accounting.add-payment-receipt');
    }
}
