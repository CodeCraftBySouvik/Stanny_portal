<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\OrderMeasurement;
use App\Models\Ledger;
use App\Models\PaymentCollection;
use App\Helpers\Helper;
use App\Models\SalesmanBilling;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    
    protected function getAuthenticatedUser()
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        return $user;
    }

    public function index(Request $request){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
       
        $filter = $request->filter;
        $start_date = !empty($request->start_date) ? $request->start_date . '' : null;
        $end_date = !empty($request->end_date) ? $request->end_date . '' : null;
        $ordersQuery=Order::where('created_by',$user->id);
        if (!empty($filter)) {
            
            $ordersQuery->where(function ($query) use ($filter) {
                $query->where('order_number', 'like', "%{$filter}%")
                ->orWhere('customer_name', 'like', "%{$filter}%");
            });
        }

        // Apply date filter (only if both start & end dates are provided)
        if (!empty($start_date) && !empty($end_date)) {
            $ordersQuery->whereBetween('created_at', [$start_date, $end_date]);
        }

        // Fetch the filtered orders
        $orders = $ordersQuery->orderBy('id', 'DESC')->get();
        if($orders){
            return response()->json([
                'status' => 'success',
                'message' => 'Order list fetch successfully.',
                'data' => $orders,
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'No data found!'
            ]);
        }
       

    }
    
    //detail
    
     public function detail(Request $request){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
       $data=Order::where('id',$request->id)->with('items','items.measurements')->get();

        if($data){
            return response()->json([
                'status' => 'success',
                'message' => 'Order detail fetch successfully.',
                'data' => $data,
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'No data found!'
            ]);
        }
       

    }
    
    
    public function createOrder(Request $request){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        //dd($request->all());
        // Validation rules
        $rules = [
            'items.*.collection' => 'required|string',
            'items.*.category' => 'required|string',
            'items.*.product_id' => 'required|integer',
            'items.*.price' => 'required|numeric|min:1',
            'paid_amount' => 'nullable|numeric|min:1',
            'payment_mode' => 'nullable|string',
            'items.*.measurements.*' => 'nullable|string',
            'business_type' => 'nullable|integer',
            'customer_id' => 'nullable|integer',
            'company_name' => 'nullable|string',
            'employee_rank' => 'nullable',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => ['required', 'numeric', 'digits_between:' . config('app.phone_min_length') . ',' . config('app.phone_max_length')],
            // 'phone' => ['required', 'regex:' . env('PHONE_REGEX')],
            // 'whatsapp_no' =>['required', 'regex:' . env('PHONE_REGEX')],
            'whatsapp_no' => ['nullable', 'numeric', 'digits_between:' . config('app.phone_min_length') . ',' . config('app.phone_max_length')],
      
            'dob' => 'required|date',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_state' => 'required|string',
            'billing_country' => 'required|string',
            'billing_pin' => 'nullable|string',
            'billing_landmark' => 'nullable|string',
            'is_billing_shipping_same' => 'nullable|boolean',
            'shipping_address' => 'nullable|string',
            'shipping_city' => 'nullable|string',
            'shipping_state' => 'nullable|string',
            'shipping_country' => 'nullable|string',
            'shipping_pin' => 'nullable|string',
            'shipping_landmark' => 'nullable|string',
        ];

        // If shipping address is different from billing, apply additional rules
        if ($request->input('is_billing_shipping_same') == 0) {
            $rules = array_merge($rules, [
                'shipping_address' => 'nullable|string',
                'shipping_landmark' => 'nullable|string|max:255',
                'shipping_city' => 'nullable|string|max:255',
                'shipping_state' => 'nullable|string|max:255',
                'shipping_country' => 'nullable|string|max:255',
                'shipping_pin' => 'nullable|string|max:10',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ]);
        }

        try {
            // Calculate total amount and validate paid amount
            $total_amount = array_sum(array_column($request->items, 'price'));
            // if ($request->paid_amount > $total_amount) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'The paid amount cannot exceed the total billing amount.',
            //     ]);
            // }
            //$remaining_amount = $total_amount - $request->paid_amount;

            // Retrieve or create user
            //$userDetail = User::find($user->id);
            if (!$user) {
                $user = User::create([
                    'prefix' => $request->prefix,
                    'name' => $request->name,
                    'company_name' => $request->company_name,
                    'employee_rank' => $request->employee_rank,
                     'business_type' => $request->business_type,
                    'email' => $request->email,
                    'dob' => $request->dob,
                    'phone' => $request->phone,
                    'is_phone_whatsapp' => $request->is_phone_whatsapp,
                    'country_code_alt_1' => $request->country_code_alt_1,
                    'alternative_phone_number_1' => $request->alternative_phone_number_1,
                    'is_alternate_no_whatsapp' => $request->is_alternate_no_whatsapp,
                    'country_code_alt_2' => $request->country_code_alt_2,
                    'alternative_phone_number_2' => $request->alternative_phone_number_2,
                     'is_alternate_no2_whatsapp' => $request->is_alternate_no2_whatsapp,
                    'whatsapp_no' => $request->whatsapp_no ??'',
                    'user_type' => 1, // Customer
                ]);
            }

            // Handle billing address update or create
            $user->address()->updateOrCreate(
                ['address_type' => 1], // Billing address
                [
                    'state' => $request->billing_state,
                    'city' => $request->billing_city,
                    'address' => $request->billing_address,
                    'landmark' => $request->billing_landmark,
                    'country' => $request->billing_country,
                    'zip_code' => $request->billing_pin,
                ]
            );

            // Handle shipping address update or create
            if (!$request->is_billing_shipping_same) {
                $user->address()->updateOrCreate(
                    ['address_type' => 2], // Shipping address
                    [
                        'state' => $request->billing_state,
                        'city' => $request->billing_city,
                        'address' => $request->billing_address,
                        'landmark' => $request->billing_landmark,
                        'country' => $request->billing_country,
                        'zip_code' => $request->billing_pin,
                    ]
                );
            } else {
                // Update shipping address to match billing if the same
                $user->address()->updateOrCreate(
                    ['address_type' => 2], // Shipping address
                    [
                        'state' => $request->billing_state,
                        'city' => $request->billing_city,
                        'address' => $request->billing_address,
                        'landmark' => $request->billing_landmark,
                        'country' => $request->billing_country,
                        'zip_code' => $request->billing_pin,
                    ]
                );
            }

            // Generate invoice number
            $bill_book = Helper::generateInvoiceBill($user->id);
            $order_number = $bill_book['number'];

            // Create order
            $order = Order::create([
                'order_number' => $order_number,
                'customer_id' => $request->customer_id,
                'created_by' => $user->id,
                'business_type' => $request->business_type,
                'customer_name' => $request->name,
                'customer_email' => $request->email,
                'billing_address' => $request->billing_address . ', ' . $request->billing_city . ', ' . $request->billing_state . ', ' . $request->billing_country . ' - ' . $request->billing_pin,
               // 'shipping_address' => $request->is_billing_shipping_same
                   // ? $request->billing_address . ', ' . $request->billing_city . ', ' . $request->billing_state . ', ' . $request->billing_country . ' - ' . $request->billing_pin
                    //: $request->shipping_address . ', ' . $request->shipping_city . ', ' . $request->shipping_state . ', ' . $request->shipping_country . ' - ' . $request->shipping_pin,
                'total_amount' => $total_amount,
                //'paid_amount' => $request->paid_amount,
               // 'remaining_amount' => $remaining_amount,
               // 'payment_mode' => $request->payment_mode,
                'verified_video' => $request->verified_video,
                'created_by' => (int) $user->id,
                'last_payment_date' => now(),
            ]);
            $update_bill_book = SalesmanBilling::where('id',$user->id)->first();
            if($update_bill_book){
                $update_bill_book->no_of_used = $update_bill_book->no_of_used +1;
                $update_bill_book->save();
            }
            // Create ledger entry
            // $ledger=Ledger::create([
            //     'order_id' => $order->id,
            //     'user_id' => $user->id,
            //     'transaction_date' => now(),
            //     'transaction_type' => 'Debit',
            //     'transaction_amount' => $total_amount,
            //     'payment_method' => $request->payment_mode,
            //   //  'paid_amount' => $request->paid_amount,
            //   'customer_id' => $request->customer_id,
            //     'remarks' => 'Initial payment for order #' . $order->order_number,
            // ]);

            // Process order items and measurements
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'catalogue_id' => $item['selectedCatalogue'] ?? null,
                    'cat_page_number' => $item['page_number'] ?? null,
                    'cat_page_item' => $item['page_item'] ?? null,
                    'product_id' => $item['product_id'],
                    'collection' => $item['collection'],
                    'category' => $item['category'],
                    'product_name' => $product->name,
                    'piece_price' => $item['price'],
                    'total_price' => $item['price'],
                    'quantity' =>1,
                     'fabrics' => $item['fabric_id'],
                ]);

                if (!empty($item['measurements'])) {
                    foreach ($item['measurements'] as $measurement_name => $measurement_value) {
                        OrderMeasurement::create([
                            'order_item_id' => $orderItem->id,
                            'measurement_name' => $measurement_name,
                            'measurement_value' => $measurement_value,
                        ]);
                    }
                }
            }

            // Return success response
            return response()->json([
                'status' => 'true',
                'message' => 'Order has been created successfully.',
                'user' => $user,
                'order_data' => $order,
                //'ledger' => $ledger,
                'order_item' => $orderItem,
            ]);
        } catch (\Exception $e) {
             dd($e->getMessage());
            Log::error('Error creating order: ' . $e->getMessage());
            return response()->json([
                'status' => 'false',
                'message' => 'An error occurred while creating the order.',
            ]);
        }
    }
    
    
    public function createVideo(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        $validator = Validator::make($request->all(),[
            'verified_video' => ['required']
        ]);

        if(!$validator->fails()){
             $verifiedVideoPath = $request->hasFile('verified_video')
                ? 'storage/' . $request->file('verified_video')->store('verified_videos', 'public')
                : null;
            
            
			return response()->json(['error' => false, 'resp' => 'Image added', 'data' => $verifiedVideoPath]);

        }else {
            return response()->json(['error' => true, 'resp' => $validator->errors()->first()]);
        }

    }
    
    //customer order list
     public function customer_order_list(Request $request){
         
        $filter = $request->filter;
        $start_date = !empty($request->start_date) ? $request->start_date . '' : null;
        $end_date = !empty($request->end_date) ? $request->end_date . '' : null;
        $user = $this->getAuthenticatedUser();
        // dd($filter);
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
         // Start Query
        $ordersQuery = Order::where('customer_id', $request->customer_id);

        // Apply keyword search filter (on order_number or customer name)
        if (!empty($filter)) {
            
            $ordersQuery->where(function ($query) use ($filter) {
                $query->where('order_number', 'like', "%{$filter}%")
                ->orWhere('customer_name', 'like', "%{$filter}%");
            });
        }

        // Apply date filter (only if both start & end dates are provided)
        if (!empty($start_date) && !empty($end_date)) {
            $ordersQuery->whereBetween('created_at', [$start_date, $end_date]);
        }

        // Fetch the filtered orders
        $orders = $ordersQuery->orderBy('id', 'DESC')->get();

        $data = [];
        if(count($orders)>0){
            foreach($orders as $key=>$item){
                // Convert order time to Carbon instance
                $orderTime = Carbon::parse($item->created_at);
                
                // Determine the formatted order time
                if ($orderTime->isToday()) {
                    $formattedOrderTime = "Today " . $orderTime->format('h:i A');
                } elseif ($orderTime->isYesterday()) {
                    $formattedOrderTime = "Yesterday " . $orderTime->format('h:i A');
                } else {
                    $formattedOrderTime = $orderTime->format('d M y h:i A'); // Example: "12 Jan 25 14:25"
                }
                $data[$key]['order_id'] = $item->id;
                $data[$key]['customer_name'] = $item->prefix.' '.$item->customer_name;
                $data[$key]['order_number'] = $item->order_number;
                $data[$key]['order_amount'] = $item->total_amount;
                $data[$key]['order_time'] = $formattedOrderTime;
                $data[$key]['order_item'] = $item->items;
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'order information fetch successfully!',
            'orders' => $data,
        ]);
    }
    //ledger view
    public function ledgerView(Request $request)
    {
        $filter = $request->filter;
        $start_date = !empty($request->start_date) ? $request->start_date . '' : null;
        $end_date = !empty($request->end_date) ? $request->end_date . '' : null;
        $user = $this->getAuthenticatedUser();
        // dd($filter);
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        
        
    }
    
    //payment receipt save
     public function paymentReceiptSave(Request $request)
    {
         
        $user = $this->getAuthenticatedUser();
        // dd($filter);
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        
        $ledger= new Ledger();
        $ledger->customer_id=$request->customer_id;
         $ledger->staff_id = $user->id;
         $ledger->is_debit = 1;
         $ledger->bank_cash = $request->payment_type;
         $ledger->transaction_amount = $request->collection_amount;
         $ledger->entry_date = date('Y-m-d');
          $ledger->purpose = 'payment_receipt';
           $ledger->purpose_description = $request->remarks;
           
         $ledger->save();
         
            $payment=new PaymentCollection();
            $payment->voucher_no = 'PAYRECEIPT'.time();
        //$staffs = User::where('user_type', 0)->where('designation', 2)->select('name', 'id')->orderBy('name', 'ASC')->get();
        
            
            
            $payment->customer_id = $request->customer_id;
            $payment->user_id = $user->id;
            $payment->collection_amount = $request->collection_amount;
           
            $payment->cheque_date = date('Y-m-d');
            $payment->payment_type = $request->payment_type;
            if($ledger){
                $payment->is_ledger_added = 1;
            }
             $payment->remarks = $request->remarks;
           $payment->save();
        
         return response()->json([
            'status' => true,
            'message' => 'payment collected successfully!',
            'orders' => $payment,
        ]);
        
    }
    
   
    
   
}
