<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PackingSlip;
use App\Models\Invoice;
use App\Models\Ledger;
use App\Models\InvoiceProduct;
use App\Models\PaymentCollection;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\AccountingRepositoryInterface;

class AddOrderSlip extends Component
{
    protected $accountingRepository;
    public $order;
    public $errorMessage = [];
    public $order_item = [];
    public $activePayementMode = 'cash';
    public $staffs =[];
    public $from_date;
    public $to_date;
    public $document_type = 'invoice';
    public $payment_collection_id = "";
    public $readonly = "readonly";
    public $customer,$customer_id, $staff_id,$staff_name, $total_amount, $actual_amount, $voucher_no, $payment_date, $payment_mode, $chq_utr_no, $bank_name, $receipt_for = "Customer",$amount;
    public $air_mail;

    public function boot(AccountingRepositoryInterface $accountingRepository)
    {
        $this->accountingRepository = $accountingRepository;
    }
    public function mount($id){

        $this->order = Order::with('items.measurements','items.fabric','customer','createdBy')->where('id', $id)->first();
        if($this->order){
            foreach($this->order->items as $key=>$order_item){
               $product =  $order_item->product ?? null;

               $this->order_item[$key] = [
                'id' => $order_item->id,
                'product_name' => $order_item->product_name ?? $product?->name,
                'collection_id' => $order_item->collection,
                'collection_title' => $order_item->collectionType?->title ?? '',
                'fabrics' => $order_item->fabric,
               'measurements' => $order_item->measurements->map(function ($m) {
                    return [
                        'measurement_name' => $m->name,
                        'measurement_title_prefix' => $m->title_prefix,
                        'measurement_value' => $m->value,
                    ];
                })->toArray(),
                'catalogue' => $order_item->catalogue_id ? $order_item->catalogue : '',
                'catalogue_id' => $order_item->catalogue_id,
                'cat_page_number' => $order_item->cat_page_number,
                'piece_price' => (int) $order_item->piece_price,
                'quantity' => $order_item->quantity,
                'remarks' => $order_item->remarks,
                'catlogue_image' => $order_item->catlogue_image,
                'voice_remark' => $order_item->voice_remark,
             ];
                // $this->order_item[$key]['id']= $order_item->id;
                // $this->order_item[$key]['piece_price']= (int)$order_item->piece_price;
                // $this->order_item[$key]['quantity']= $order_item->quantity;
                // $this->order_item[$key]['measurements']= $order_item->measurements->toArray();

            }
            $this->total_amount = $this->order->total_amount;
            $this->actual_amount = $this->order->total_amount;
            $this->air_mail = $this->order->air_mail;
            $this->customer = optional($this->order->customer)->name;
            $this->customer_id = optional($this->order->customer)->id;
            $this->staff_id = optional($this->order->createdBy)->id;
            $this->staff_name = optional($this->order->createdBy)->name;
            $this->payment_date = date('Y-m-d');
        }else{
            abort(404);
        }

        $this->voucher_no = 'PAYRECEIPT'.time();
        $this->staffs = User::where('user_type', 0)->where('designation', 2)->select('name', 'id')->orderBy('name', 'ASC')->get();
    }

    public function updateQuantity($value, $key,$price){
        if(!empty($value)){
            $this->order_item[$key]['quantity']= $value;
            $base_price = $price * $value;
            $this->order_item[$key]['price'] = $base_price;

            $subtotal = 0;
            foreach ($this->order_item as $item) {
                $subtotal += $item['price'];
            }

            // Add the air_mail from the Order, not items
           $this->actual_amount = $subtotal + $this->air_mail;
        }
    }

    public function submitForm(){
        // dd($this->all());
        $this->reset(['errorMessage']);
        $this->errorMessage = array();
        foreach ($this->order_item as $key => $item) {
            if (!isset($item['air_mail'])) {
                $item['air_mail'] = 0;
            }
            if (empty($item['quantity'])) {  // Ensure 'quantity' exists
                $this->errorMessage["order_item.$key.quantity"] = 'Please enter quantity.';
            }
        }
        // Validate customer
        if (empty($this->customer_id)) {
           $this->errorMessage['customer_id'] = 'Please select a customer.';
        }

        // Validate collected by
        if (empty($this->staff_id)) {
           $this->errorMessage['staff_id'] = 'Please select a staff member.';
        }


        if(count($this->errorMessage)>0){
            return $this->errorMessage;
        }else{
            try {
                DB::beginTransaction();
                $this->updateOrder();

                $this->updateOrderItems();

                $this->createPackingSlip();

                // $this->accountingRepository->StorePaymentReceipt($this->all());


                DB::commit();

                session()->flash('success', 'Order Approved successfully.');
                return redirect()->route('admin.order.index');
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', $e->getMessage());
            }
        }

    }
    public function updateOrder()
    {
        $this->validate([
            'total_amount' => 'required|numeric',
            'customer_id' => 'required|exists:users,id',
            'staff_id' => 'required|exists:users,id',
        ]);

        $order = Order::find($this->order->id);
        $userDesignationId = auth()->guard('admin')->user()->designation;
        if($userDesignationId==4)
        {
            $status="Approved By TL";
        }
        else{

            $status="Approved";
        }
        if ($order) {
            $order->update([
                'customer_id' => $this->customer_id,
                'created_by' => $this->staff_id,
                'status' => $status,
                'last_payment_date' => $this->payment_date,
            ]);
        }
    }
    public function updateOrderItems()
    {
            $subtotal = 0;
            foreach ($this->order_item as $item) {
                $piecePrice = (float)$item['piece_price'];
                $quantity = (int)$item['quantity'];
                $totalPrice = $piecePrice * $quantity;

                OrderItem::where('id', $item['id'])->update([
                    'total_price' => $totalPrice,
                    'quantity' => $quantity,
                    'piece_price' => $piecePrice,
                ]);

                $subtotal += $totalPrice;
            }

            // Get the Order's air_mail
            $order = Order::find($this->order->id);
            $air_mail = $order->air_mail ?? 0;
            $total_amount = $subtotal + $air_mail;

            // Update the Order's total_amount
            $order->update(['total_amount' => $total_amount]);
    }
    public function createPackingSlip()
    {
        $order = Order::find($this->order->id);

        if ($order) {
            // Calculate the remaining amount
            $packingSlip=PackingSlip::create([
                'order_id' => $this->order->id,
                'customer_id' => $this->customer_id,
                'slipno' => $this->order->order_number,
                // 'is_disbursed' => ($remaining_amount == 0) ? 1 : 0,
                'is_disbursed' => 0,
                'created_by' => $this->staff_id,
                'created_at' => now(),
                'disbursed_by' => $this->staff_id,
                // 'updated_by' => auth()->id(),
                // 'updated_at' => now(),
            ]);


            do {
                $lastInvoice = Invoice::orderBy('id', 'DESC')->first();
                $invoice_no = str_pad(optional($lastInvoice)->id + 1, 6, '0', STR_PAD_LEFT);
            } while (Invoice::where('invoice_no', $invoice_no)->exists()); // Ensure unique invoice_no


            $order->invoice_type = $this->document_type;
            $invoice = Invoice::create([
                'order_id' => $this->order->id,
                'customer_id' => $this->customer_id,
                'user_id' => $this->staff_id,
                'packingslip_id' => $packingSlip->id,
                'invoice_no' => $invoice_no,
                'net_price' => $order->total_amount,
                'required_payment_amount' =>$order->total_amount,
                'created_by' =>  $this->staff_id,
                'created_at' => now(),
                // 'updated_by' => auth()->id(),
                'updated_at' => now(),
            ]);

            // Fetch Products from Order Items
            $orderItems = $order->items;
             // Insert Invoice Products
             foreach ($orderItems as $key => $item) {
                InvoiceProduct::create([
                    'invoice_id' =>  $invoice->id,
                    'product_id' => $item->product_id,
                    'product_name'=> $item->product? $item->product->name : "",
                    'quantity' => $item->quantity,
                    'single_product_price'=> $item->piece_price,
                    'total_price' => $item->total_price + ($item->air_mail ?? 0),
                    'is_store_address_outstation' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
             }

            Ledger::insert([
                'user_type' => 'customer',
                'transaction_id' => $invoice_no,
                'customer_id' => $order->customer_id,
                'transaction_amount' => $order->total_amount,
                'bank_cash' => 'cash',
                'is_credit' => 0,
                'is_debit' => 1,
                'entry_date' => date('Y-m-d H:i:s'),
                'purpose' => 'invoice',
                'purpose_description' => 'invoice raised of sales order for customer',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }


    public function is_valid_date($date) {
        $timestamp = strtotime($date);
        if ($timestamp !== false) {
            return true;
        }
        return false;
    }
    public function ResetForm(){
        $this->reset(['customer','customer_id','staff_id', 'amount', 'voucher_no', 'payment_date', 'payment_mode', 'chq_utr_no', 'bank_name']);
        $this->voucher_no = 'PAYRECEIPT'.time();
    }
    public function ChangePaymentMode($value){
        $this->activePayementMode = $value;
    }
    public function render()
    {
        return view('livewire.order.add-order-slip');
    }
}
