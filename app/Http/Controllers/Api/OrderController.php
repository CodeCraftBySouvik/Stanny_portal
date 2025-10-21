<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// use App\Models\User;
// use App\Models\Order;
// use App\Models\OrderItem;
// use App\Models\Product;
// use App\Models\OrderMeasurement;
// use App\Models\Ledger;
// use App\Models\PaymentCollection;
// use App\Models\SalesmanBilling;
// use App\Models\Measurement;
use App\Helpers\Helper;
use App\Models\{
    User, Order, OrderItem, OrderMeasurement, UserWhatsapp, UserAddress,
    SalesmanBilling, Page, Collection, Category, SubCategory, Fabric,
    CataloguePageItem, OrderItemCatalogueImage, OrderItemVoiceMessage, Measurement,Ledger,PaymentCollection,TodoList
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\AccountingRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    protected $accountingRepository;

    public function __construct(AccountingRepositoryInterface $accountingRepository){
        $this->accountingRepository = $accountingRepository;
    }
    
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
    
    
    public function store(Request $request, OrderRepository $orderRepo)
    {
        DB::beginTransaction();
        try {
            $validated = $request->validate([
                'customer_id' => 'nullable|integer|exists:users,id',
                'customerType' => 'nullable|string|max:15',
                'order_number' => 'nullable|string|not_in:000|unique:orders,order_number',
                'prefix' => 'nullable|string|max:10',
                'name' => 'required|string|max:255',
                'employee_rank' => 'nullable|string|max:15',
                'phone' => 'required|string|max:15',
                'alt_phone_1' => 'nullable|string|max:15',
                'alt_phone_2' => 'nullable|string|max:15',
                'email' => 'nullable|email|max:255',
                'dob' => 'nullable|date',
                'selectedBusinessType' => 'nullable|integer',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'phone_code' => 'nullable|string|max:5',
                'alt_phone_code_1' => 'nullable|string|max:5',
                'alt_phone_code_2' => 'nullable|string|max:5',
                'pin' => 'nullable|string|max:10',
                'landmark' => 'nullable|string|max:255',
                'billing_address' => 'nullable|string|max:255',
                'billing_city' => 'nullable|string|max:255',
                'billing_state' => 'nullable|string|max:255',
                'billing_country' => 'nullable|string|max:255',
                'billing_pin' => 'nullable|string|max:10',
                'billing_landmark' => 'nullable|string|max:255',
                'customer_image' => 'nullable|file|image|max:2048',
                'isWhatsappPhone' => 'nullable|boolean',
                'isWhatsappAlt1' => 'nullable|boolean',
                'isWhatsappAlt2' => 'nullable|boolean',
                'team_lead_id' => 'nullable|integer|exists:users,id',
                'paid_amount' => 'nullable|numeric|min:0',
                'air_mail' => 'nullable|numeric|min:0',
                'salesman' => 'required|integer|exists:users,id',
                'bill_id' => 'nullable|integer|exists:salesman_billing_number,id',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.selected_fabric' => 'nullable|integer|exists:fabrics,id',
                'items.*.collection' => 'nullable|integer|exists:collections,id',
                'items.*.category' => 'nullable|integer|exists:categories,id',
                'items.*.sub_category' => 'nullable|integer|exists:categories,id',
                'items.*.page_number' => 'nullable|integer',
                'items.*.page_item' => 'nullable|integer',
                'items.*.item_status' => 'nullable|string',
                'items.*.expected_delivery_date' => 'nullable|date',
                'items.*.priority' => 'nullable|integer',
                'items.*.remarks' => 'nullable|string',
                'extra_measurement' => 'nullable|array',
                'extra_measurement.*' => 'nullable|string',
                'newUploads' => 'nullable|array',
                'voiceUploads' => 'nullable|array',
                'measurements' => 'nullable|array',
                'measurements.*.*.value' => 'nullable|string',
            ]);

            // $loggedInAdmin = auth()->user();
            $loggedInAdmin = Auth::guard('sanctum')->user();


            if (!$loggedInAdmin) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not logged in',
                ], 401);
            }

            // ------------------------------
            // User Create/Update
            // ------------------------------
            $user = User::find($validated['customer_id'] ?? null);
            if (!$user) {
                $user = User::create([
                    'prefix' => $validated['prefix'] ?? null,
                    'name' => $validated['name'],
                    'business_type' => $validated['selectedBusinessType'] ?? null,
                    'company_name' => $request->company_name ?? null,
                    'employee_rank' => $validated['employee_rank'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'dob' => $validated['dob'] ?? null,
                    'country_id' => $request->country_id ?? null,
                    'country_code_phone' => $validated['phone_code'] ?? '+91',
                    'phone' => $validated['phone'],
                    'country_code_alt_1' => $validated['alt_phone_code_1'] ?? null,
                    'alternative_phone_number_1' => $validated['alt_phone_1'] ?? null,
                    'country_code_alt_2' => $validated['alt_phone_code_2'] ?? null,
                    'alternative_phone_number_2' => $validated['alt_phone_2'] ?? null,
                    'user_type' => 1,
                    'created_by' => $loggedInAdmin->id,
                ]);
            } else {
                $user->update([
                    'prefix' => $validated['prefix'] ?? $user->prefix,
                    'name' => $validated['name'] ?? $user->name,
                    'business_type' => $validated['selectedBusinessType'] ?? $user->business_type,
                    'company_name' => $request->company_name ?? $user->company_name,
                    'employee_rank' => $validated['employee_rank'] ?? $user->employee_rank,
                    'email' => $validated['email'] ?? $user->email,
                    'dob' => $validated['dob'] ?? $user->dob,
                    'country_id' => $request->country_id ?? $user->country_id,
                    'country_code_phone' => $validated['phone_code'] ?? $user->country_code_phone,
                    'phone' => $validated['phone'],
                    'country_code_alt_1' => $validated['alt_phone_code_1'] ?? $user->country_code_alt_1,
                    'alternative_phone_number_1' => $validated['alt_phone_1'] ?? $user->alternative_phone_number_1,
                    'country_code_alt_2' => $validated['alt_phone_code_2'] ?? $user->country_code_alt_2,
                    'alternative_phone_number_2' => $validated['alt_phone_2'] ?? $user->alternative_phone_number_2,
                    'user_type' => 1,
                ]);
            }

            // ------------------------------
            // Billing Address
            // ------------------------------
            UserAddress::updateOrCreate(
                ['user_id' => $user->id, 'address_type' => 1],
                [
                    'address' => $validated['billing_address'] ?? $validated['landmark'] ?? '',
                    'city' => $validated['billing_city'] ?? $validated['city'] ?? '',
                    'state' => $validated['billing_state'] ?? $validated['state'] ?? '',
                    'country' => $validated['billing_country'] ?? $validated['country'] ?? '',
                    'zip_code' => $validated['billing_pin'] ?? $validated['pin'] ?? '',
                    'landmark' => $validated['billing_landmark'] ?? $validated['landmark'] ?? ''
                ]
            );

            // ------------------------------
            // WhatsApp Numbers
            // ------------------------------
            $whatsappMapping = [
                ['field'=>'phone', 'flag'=>'isWhatsappPhone', 'code'=>$validated['phone_code']??'+91'],
                ['field'=>'alt_phone_1', 'flag'=>'isWhatsappAlt1', 'code'=>$validated['alt_phone_code_1']??'+91'],
                ['field'=>'alt_phone_2', 'flag'=>'isWhatsappAlt2', 'code'=>$validated['alt_phone_code_2']??'+91'],
            ];
            foreach($whatsappMapping as $map){
                if(!empty($validated[$map['field']]) && !empty($validated[$map['flag']])){
                    $exists = UserWhatsapp::where('whatsapp_number',$validated[$map['field']])
                                        ->where('user_id','!=',$user->id)
                                        ->exists();
                    if(!$exists){
                        UserWhatsapp::updateOrCreate(
                            ['user_id'=>$user->id,'whatsapp_number'=>$validated[$map['field']]],
                            ['country_code'=>$map['code']]
                        );
                    }
                }
            }

            // ------------------------------
            // Customer Image
            // ------------------------------
            $customerImagePath = $request->hasFile('customer_image') 
                ? Helper::handleFileUpload($request->file('customer_image'), 'client_image') 
                : null;

            // ------------------------------
            // Order Amount
            // ------------------------------
            $totalProductAmount = collect($validated['items'])->sum(fn($i)=>$i['price']*$i['quantity']);
            $airMail = $validated['air_mail'] ?? 0;
            $totalAmount = $totalProductAmount + $airMail;
            $paidAmount = $validated['paid_amount'] ?? 0;
           
            $orderNumber = $validated['order_number'];

            // ------------------------------
            // Create Order
            // ------------------------------
            $order = Order::create([
                'order_number'=>$orderNumber,
                'customer_id'=>$user->id,
                'prefix'=>$validated['prefix'] ?? null,
                'customer_name'=>$validated['name'],
                'customer_email'=>$validated['email'] ?? null,
                'customer_image'=>$customerImagePath,
                'billing_address'=> trim(($validated['billing_address'] ?? $validated['landmark'] ?? '').', '.($validated['billing_city'] ?? $validated['city'] ?? '').', '.($validated['billing_state'] ?? $validated['state'] ?? ''), ', '),
                'total_product_amount'=>$totalProductAmount,
                'air_mail'=>$airMail,
                'total_amount'=>$totalAmount,
                'paid_amount'=>$paidAmount,
             
                'last_payment_date'=>now(),
                'created_by'=>$validated['salesman'],
                'team_lead_id'=>$validated['team_lead_id'] ?? $loggedInAdmin->parent_id ?? null,
            ]);

            // ------------------------------
            // Salesman Billing
            // ------------------------------
            if($request->bill_id){
                $bill = SalesmanBilling::find($request->bill_id);
                if($bill) $bill->increment('no_of_used');
            }

            // ------------------------------
            // Order Items + Measurements + Extra fields + Images/Voice
            // ------------------------------
            foreach ($validated['items'] as $k => $item) {

                if ($item['collection'] == 1 && empty($item['page_item'])) {
                    $page = Page::where('catalogue_id', $item['selectedCatalogue'])
                        ->where('page_number', $item['page_number'])
                        ->first();

                    if ($page) {
                        $exist_pages = CataloguePageItem::where('page_id', $page->id)->get();
                        if ($exist_pages->count() > 0 && empty($item['page_item'])) {
                            throw new \Exception("Please select a page item for page number {$item['page_number']}");
                        }
                    }
                }

                $collection_data = Collection::find($item['collection']);
                $category_data = Category::find($item['category']);
                $sub_category_data = SubCategory::find($item['sub_category']);
                $fabric_data = Fabric::find($item['selected_fabric']);

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->catalogue_id = $item['selectedCatalogue'] ?? null;
                $orderItem->cat_page_number = $item['page_number'] ?? null;

                $validItems = $validated['page_item'][$k] ?? [];
                $allowedPageItems = collect($validItems)->pluck('catalog_item')->toArray();

                $orderItem->cat_page_item = in_array($item['page_item'] ?? null, $allowedPageItems)
                    ? $item['page_item']
                    : null;

                $orderItem->product_id = $item['product_id'] ?? null;
                $orderItem->collection = $collection_data?->id ?? null;
                $orderItem->category = $category_data?->id ?? null;
                $orderItem->sub_category = $sub_category_data?->id ?? null;
                $orderItem->product_name = $item['searchproduct'] ?? null;
                $orderItem->remarks = $item['remarks'] ?? null;
                $orderItem->status = $item['item_status'] ?? null;
                $orderItem->piece_price = $item['price'] ?? 0;
                $orderItem->quantity = ($item['collection'] == 1) ? 1 : ($item['quantity'] ?? 1);
                $orderItem->fittings = ($item['collection'] == 1) ? ($item['fitting'] ?? null) : null;

                $orderItem->priority_level = in_array($loggedInAdmin->designation, [1, 4])
                    ? ($item['priority'] ?? null)
                    : null;

                $orderItem->expected_delivery_date = $item['expected_delivery_date'] ?? null;
                $orderItem->total_price = floatval($item['price']) * $orderItem->quantity;
                $orderItem->fabrics = $fabric_data?->id ?? null;

                if ($orderItem->status === 'Process') {
                    if (in_array($loggedInAdmin->designation, [1, 12])) {
                        $orderItem->tl_status = 'Approved';
                        $orderItem->admin_status = 'Approved';
                        $orderItem->assigned_team = 'production';
                    } elseif ($loggedInAdmin->designation == 4) {
                        $orderItem->tl_status = 'Approved';
                        $orderItem->admin_status = 'Pending';
                    } else {
                        $orderItem->tl_status = 'Pending';
                        $orderItem->admin_status = 'Pending';
                    }
                } else {
                    $orderItem->tl_status = 'Pending';
                    $orderItem->admin_status = 'Pending';
                }

                if ($item['collection'] == 1) {
                    $extra = $validated['extra_measurement'][$k] ?? null;

                    if ($extra === 'mens_jacket_suit') {
                        $orderItem->vents = $item['vents'] ?? null;
                        $orderItem->shoulder_type = $item['shoulder_type'] ?? null;
                    } elseif ($extra === 'ladies_jacket_suit') {
                        $orderItem->shoulder_type = $item['shoulder_type'] ?? null;
                        $orderItem->vents_required = $item['vents_required'] ?? null;
                        if ($orderItem->vents_required) {
                            $orderItem->vents_count = $item['vents_count'] ?? null;
                        }
                    } elseif ($extra === 'trouser') {
                        $orderItem->fold_cuff_required = $item['fold_cuff_required'] ?? null;
                        $orderItem->fold_cuff_size = ($orderItem->fold_cuff_required === 'Yes')
                            ? ($item['fold_cuff_size'] ?? null) : null;

                        $orderItem->pleats_required = $item['pleats_required'] ?? null;
                        $orderItem->pleats_count = ($orderItem->pleats_required === 'Yes')
                            ? ($item['pleats_count'] ?? null) : null;

                        $orderItem->back_pocket_required = $item['back_pocket_required'] ?? null;
                        $orderItem->back_pocket_count = ($orderItem->back_pocket_required === 'Yes')
                            ? ($item['back_pocket_count'] ?? null) : null;

                        $orderItem->adjustable_belt = $item['adjustable_belt'] ?? null;
                        $orderItem->suspender_button = $item['suspender_button'] ?? null;
                        $orderItem->trouser_position = $item['trouser_position'] ?? null;
                    } elseif ($extra === 'shirt') {
                        $orderItem->sleeves = $item['sleeves'] ?? null;
                        $orderItem->collar = $item['collar'] ?? null;
                        $orderItem->collar_style = $item['collar_style'] ?? null;
                        $orderItem->pocket = $item['pocket'] ?? null;
                        $orderItem->cuffs = $item['cuffs'] ?? null;
                        $orderItem->cuff_style = $item['cuff_style'] ?? null;
                    }

                    if (in_array($extra, ['ladies_jacket_suit', 'shirt', 'mens_jacket_suit'])) {
                        $orderItem->client_name_required = $item['client_name_required'] ?? null;
                        $orderItem->client_name_place = ($orderItem->client_name_required === 'Yes')
                            ? ($item['client_name_place'] ?? null)
                            : null;
                    }
                }

                $orderItem->save();

                if ($loggedInAdmin->designation == 4) {
                    $totalItems = count($validated['items']);
                    $approvedItems = $order->items()
                        ->where('status', 'Process')
                        ->where('tl_status', 'Approved')
                        ->count();

                    $order->status = match (true) {
                        $approvedItems == $totalItems => 'Fully Approved By TL',
                        $approvedItems > 0 => 'Partial Approved By TL',
                        default => 'Approval Pending',
                    };

                    $order->save();
                }

                if (!empty($validated['newUploads'][$k])) {
                    foreach ($validated['newUploads'][$k] as $image) {
                        $path = $image->store('uploads/order_item_catalogue_images', 'public');
                        OrderItemCatalogueImage::create([
                            'order_item_id' => $orderItem->id,
                            'image_path' => $path,
                        ]);
                    }
                }

                if (!empty($validated['voiceUploads'][$k])) {
                    foreach ($validated['voiceUploads'][$k] as $voice) {
                        $audioPath = $voice->store('uploads/order_item_voice_messages', 'public');
                        OrderItemVoiceMessage::create([
                            'order_item_id' => $orderItem->id,
                            'voices_path' => $audioPath,
                        ]);
                    }
                }

                if (!empty($item['get_measurements'])) {
                    foreach ($item['get_measurements'] as $mindex => $measurement) {
                        if (!isset($measurement['value'])) continue;

                        $value = trim($measurement['value']);
                        $measurement_data = Measurement::find($mindex);

                        if ($measurement_data) {
                            OrderMeasurement::create([
                                'order_item_id' => $orderItem->id,
                                'measurement_name' => $measurement_data->title,
                                'measurement_title_prefix' => $measurement_data->short_code,
                                'measurement_value' => $value,
                            ]);
                        }
                    }
                }
            }

           

            $staff = isset($validated['salesman']) ? User::find($validated['salesman']) : null;

            if ($staff && in_array($staff->designation, [1, 12])) {
                $orderRepo->approveOrder($order->id, $staff->id);
            }


            DB::commit();

            return response()->json([
                'status'=>true,
                'message'=>'Order created/updated successfully',
                'data'=>$order
            ],201);

        } catch(\Exception $e){
            DB::rollBack();
            \Log::error('Order creation/update failed: '.$e->getMessage());
            return response()->json([
                'status'=>false,
                'message'=>'Order creation/update failed',
                'error'=>$e->getMessage()
            ],500);
        }
    }
    
    
    
    
    //customer order list
    public function customer_order_list(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        $filter = $request->filter;
        $customer_id = $request->customer_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $status = $request->status;

        $auth = $user; 

        $ordersQuery = Order::query();

   
        if (!empty($customer_id)) {
            $ordersQuery->where('customer_id', $customer_id);
        }

        if (!empty($filter)) {
            $ordersQuery->where(function ($query) use ($filter) {
                $query->where('order_number', 'like', "%{$filter}%")
                    ->orWhereHas('customer', function ($q2) use ($filter) {
                        $q2->where(function ($sub) use ($filter) {
                            $sub->where('name', 'like', "%{$filter}%")
                                ->orWhere('email', 'like', "%{$filter}%")
                                ->orWhere('phone', 'like', "%{$filter}%")
                                ->orWhere('whatsapp_no', 'like', "%{$filter}%");
                        });
                    });
            });
        }

        if (!empty($start_date) && !empty($end_date)) {
            $ordersQuery->whereBetween('created_at', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ]);
        }

        if (!empty($status)) {
            $ordersQuery->where('status', $status);
        }

        if (!$auth->is_super_admin) {
            $ordersQuery->where(function ($subQuery) use ($auth) {
                $subQuery->where('created_by', $auth->id)
                        ->orWhere('team_lead_id', $auth->id);
            });
        }

        $orders = $ordersQuery
            ->with([
                'items.product:id,name',
                'items.collectionType:id,title',
                'items.categoryInfo:id,title'
            ])
            ->orderBy('created_at', 'desc')
            ->get();    


        $data = $orders->map(function ($item) {
            $orderTime = Carbon::parse($item->created_at);

            if ($orderTime->isToday()) {
                $formattedOrderTime = "Today " . $orderTime->format('h:i A');
            } elseif ($orderTime->isYesterday()) {
                $formattedOrderTime = "Yesterday " . $orderTime->format('h:i A');
            } else {
                $formattedOrderTime = $orderTime->format('d M y h:i A');
            }

            return [
                'order_id' => $item->id,
                'customer_name' => trim(($item->prefix ?? '') . ' ' . ($item->customer_name ?? '')),
                'order_number' => $item->order_number,
                'order_amount' => $item->total_amount,
                'order_time' => $formattedOrderTime,
                'order_item' => $item->items->map(function ($orderItem) use ($item) {
                    return [
                        'id' => $orderItem->id,
                        'order_number' => $item->order_number,
                        'product_name' => $orderItem->product->name ?? null,
                        'collection_name' => optional($orderItem->collectionType)->title,
                        'category_name' => optional($orderItem->categoryInfo)->title,
                        'quantity' => $orderItem->quantity,
                        'piece_price' => $orderItem->piece_price,
                        'total_price' => $orderItem->total_price,
                        'priority_level' => $orderItem->priority_level,
                        'expected_delivery_date' => $orderItem->expected_delivery_date,
                        'status' => $orderItem->status,
                        'tl_status' => $orderItem->tl_status,
                        'admin_status' => $orderItem->admin_status,
                        'created_at' => $orderItem->created_at,
                        'updated_at' => $orderItem->updated_at,
                    ];
                }),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Order information fetched successfully!',
            'orders' => $data,
        ]);
    }
    //ledger view
    public function ledgerView(Request $request)
    {

        // 1. Get and Validate Request Parameters
        $userType = $request->input('user_type'); 
        $userId = $request->input('user_id'); // Can be staff_id, customer_id, or supplier_id
        $fromDate = $request->input('from_date', date('Y-m-01'));
        $toDate = $request->input('to_date', date('Y-m-d'));
        $bankCash = $request->input('bank_cash');
        if (!$userType || !$userId) {
            return response()->json([
                'status' => 'error',
                'message' => 'The user_type and user_id fields are required.'
            ], 400);
        }

        // 2. Data Fetching Logic (Mirrors Livewire's LedgerUserData)

        $opening_bal_query = Ledger::query();
        $query = Ledger::query();
        $opening_bal_showable = 1;
        $day_opening_amount = 0;
        $errorMessage = [];

        // Apply Date Filters for Transaction Data
        $query->whereDate('entry_date', '>=', $fromDate);
        $query->whereDate('entry_date', '<=', $toDate);

        // Apply User Filters
        if ($userType === 'staff') {
            $user = User::where('user_type', 0)->find($userId);
            if (!$user) { $errorMessage['staff'] = 'Staff not found.'; }
            $query->where('staff_id', $userId);
            $opening_bal_query->where('staff_id', $userId);
        } elseif ($userType === 'customer') {
            $user = User::where('user_type', 1)->find($userId);
            if (!$user) { $errorMessage['customer'] = 'Customer not found.'; }
            $query->where('customer_id', $userId);
            $opening_bal_query->where('customer_id', $userId);
        } elseif ($userType === 'supplier') {
            $user = Supplier::find($userId);
            if (!$user) { $errorMessage['supplier'] = 'Supplier not found.'; }
            $query->where('supplier_id', $userId);
            $opening_bal_query->where('supplier_id', $userId);
        } else {
             return response()->json([
                'status' => 'error',
                'message' => 'Invalid user_type provided.'
            ], 400);
        }

        if (!empty($errorMessage)) {
             return response()->json([
                'status' => 'error',
                'message' => array_values($errorMessage)[0]
            ], 404);
        }
        
        // Logic for Opening Balance Date Range (Mirroring Livewire component)
        $opening_bal_date_end = date('Y-m-d', strtotime('-1 day', strtotime($fromDate)));

        if ($userType === 'customer') {
            $check_ob_exist_customer = Ledger::where('purpose', 'opening_balance')
                ->where('user_type', 'customer')
                ->where('customer_id', $userId)
                ->orderBy('id', 'asc')
                ->first();

            if (!empty($check_ob_exist_customer)) {
                if ($fromDate == $check_ob_exist_customer->entry_date) {
                    $opening_bal_showable = 0;
                    $opening_bal_query->whereDate('entry_date', $check_ob_exist_customer->entry_date);
                } else {
                    $opening_bal_date_start = $check_ob_exist_customer->entry_date;
                    // Filter between OB entry date and $fromDate - 1 day
                    $opening_bal_query->whereRaw(" entry_date BETWEEN '{$opening_bal_date_start}' AND '{$opening_bal_date_end}'");
                }
            } else {
                // Filter all entries before $fromDate
                $opening_bal_query->whereDate('entry_date', '<=', $opening_bal_date_end);
            }
        } else {
            // For Staff/Supplier, filter all entries before $fromDate
            $opening_bal_query->whereDate('entry_date', '<=', $opening_bal_date_end);
        }
        
        // Calculate Opening Balance Amount
        $opening_bal = $opening_bal_query->orderBy('entry_date', 'ASC')->orderBy('updated_at', 'ASC')->get();
        foreach ($opening_bal as $ob) {
            if (!empty($ob->is_credit)) {
                $day_opening_amount += $ob->transaction_amount;
            }
            if (!empty($ob->is_debit)) {
                $day_opening_amount -= $ob->transaction_amount;
            }
        }

        // Apply Bank/Cash Filter
        if (!empty($bankCash)) {
            $query->where('bank_cash', $bankCash);
        }

        $ledgerData = $query->orderBy('entry_date', 'ASC')->get();


        // 3. Structure the Response Data

        $net_value = $day_opening_amount;
        $transactions = [];

        // Add Opening Balance Row
        if ($opening_bal_showable == 1) {
             $getCrDrOB = Helper::getCrDr($day_opening_amount);
             $deb_ob_amount = ($getCrDrOB === 'Dr') ? Helper::replaceMinusSign($day_opening_amount) : 0;
             $cred_ob_amount = ($getCrDrOB === 'Cr') ? $day_opening_amount : 0;
            
            $transactions[] = [
                'date' => date('d-m-Y', strtotime($fromDate)),
                'purpose' => 'Opening Balance',
                'debit' => $deb_ob_amount > 0 ? number_format($deb_ob_amount, 2, '.', '') : null,
                'credit' => $cred_ob_amount > 0 ? number_format($cred_ob_amount, 2, '.', '') : null,
                'balance' => number_format(Helper::replaceMinusSign($net_value), 2, '.', ''),
                'balance_type' => Helper::getCrDr($net_value)
            ];
        }

        // Process Ledger Transactions
        foreach ($ledgerData as $item) {
            $debit_amount = null;
            $credit_amount = null;

            if (!empty($item->is_credit)) {
                $credit_amount = $item->transaction_amount;
                $net_value += $item->transaction_amount;
            }

            if (!empty($item->is_debit)) {
                $debit_amount = $item->transaction_amount;
                $net_value -= $item->transaction_amount;
            }
            
            $purpose_with_mode = ucwords(str_replace('_', ' ', $item->purpose)) . ' (' . ucwords($item->bank_cash) . ')';

            $transactions[] = [
                'id' => $item->id,
                'transaction_id' => $item->transaction_id,
                'date' => date('d-m-Y', strtotime($item->created_at)),
                'purpose' => $purpose_with_mode,
                'remarks' => $item->purpose_description, 
                'debit' => $debit_amount ? number_format((float) $debit_amount, 2, '.', '') : null,
                'credit' => $credit_amount ? number_format((float) $credit_amount, 2, '.', '') : null,
                'balance' => number_format(Helper::replaceMinusSign($net_value), 2, '.', ''),
                'balance_type' => Helper::getCrDr($net_value),
            ];
        }

        // 4. Final API Response

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $userId,
                'type' => $userType,
                'name' => $user->name ?? 'N/A'
            ],
            'filters' => [
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'bank_cash' => $bankCash
            ],
            'ledger_entries' => $transactions,
            'closing_balance' => [
                'amount' => number_format(Helper::replaceMinusSign($net_value), 2, '.', ''),
                'type' => Helper::getCrDr($net_value)
            ]
        ]);
    }

     /**
     * Store Payment Collection API
     */
    
        public function paymentReceiptSave(Request $request)
    {
        // Base rules
        $rules = [
            'customer_id'       => 'required|exists:users,id',
            'staff_id'          => 'required|exists:users,id',
            'collection_amount' => 'required|numeric|min:0.01',
            'payment_type'      => 'required|in:cash,cheque,digital_payment,neft',
            'voucher_no'        => 'nullable|string',
            'payment_date'      => 'required|date',
            'next_payment_date' => 'nullable|date',
            'deposit_date'      => 'nullable|date',
            'payment_collection_id' => 'nullable|integer|exists:payment_collections,id',
        ];

        // add conditional rules
        if ($request->payment_type === 'cheque') {
            $rules['cheque_number'] = 'required|string|max:255';
            // deposit_date may be required depending on business rules; include as needed
            $rules['deposit_date'] = 'required|date';
            $rules['cheque_file'] = 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120';
        } elseif ($request->payment_type === 'digital_payment') {
            $rules['transaction_no'] = 'required|string|max:255';
            $rules['withdrawal_charge'] = 'required|numeric|min:0';
        } elseif ($request->payment_type === 'neft') {
            $rules['cheque_number'] = 'required|string|max:255';
        }

        // If updating an existing collection, avoid voucher duplicate rule on same record
        if ($request->filled('payment_collection_id')) {
            $rules['voucher_no'] .= ',voucher_no,' . $request->payment_collection_id . ',id';
        } else {
            $rules['voucher_no'] .= '|unique:payment_collections,voucher_no';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();
            $customer = User::find($request->customer_id);
            if(!$customer){
                return response()->json([
                    'status' => false,
                    'message' => 'Customer not found in users table.'
                ],422);
            }
            // Prepare data mapping exactly as repository expects
            $data = [
                'customer_id'           => $customer->id,
                'staff_id'              => $request->staff_id,        
                'amount'                => $request->collection_amount,
                'payment_mode'          => $request->payment_type,    // repository expects 'payment_mode'
                'voucher_no'            => 'PAYRECEIPT'.time(),
                'payment_date'          => $request->payment_date,
                'receipt_for'           => $request->input('receipt_for', 'Customer'), // default
                'payment_collection_id' => $request->payment_collection_id ?? null,
                'credit_date'           => $request->credit_date ?? null, // optional
                'next_payment_date'     => $request->next_payment_date ?? null,
                'deposit_date'          => $request->deposit_date ?? null,
            ];
            // dd($data);

            // Payment-mode specific fields
            if ($request->payment_type === 'cheque') {
                $data['chq_utr_no'] = $request->cheque_number;
                $data['bank_name']  = $request->bank_name ?? null;
                // file upload
                if ($request->hasFile('cheque_file')) {
                    $ext = $request->file('cheque_file')->getClientOriginalExtension();
                    $filename = Str::random(10) . '.' . $ext;
                    $path = $request->file('cheque_file')->storeAs('uploads/cheque', $filename, 'public');
                    $data['cheque_photo'] = 'storage/' . $path;
                } elseif ($request->filled('cheque_photo')) {
                    // accept pre-uploaded path if provided by client
                    $data['cheque_photo'] = $request->cheque_photo;
                }
            } elseif ($request->payment_type === 'digital_payment') {
                $data['transaction_no'] = $request->transaction_no;
                $data['withdrawal_charge'] = $request->withdrawal_charge ?? 0;
                $data['bank_name'] = $request->bank_name ?? null; 
                $data['chq_utr_no'] = $request->cheque_number;
            }
            elseif ($request->payment_type === 'neft') {
                $data['bank_name'] = $request->bank_name ?? null; 
                $data['chq_utr_no'] = $request->cheque_number;
            } else { // cash
                // ensure bank fields are empty for cash (repository uses bank_name/chq_utr_no optionally)
                $data['bank_name'] = null;
                $data['chq_utr_no'] = null;
                $data['transaction_no'] = null;
                $data['withdrawal_charge'] = null;
            }

            // keep backward compatibility: repository sometimes uses 'payment_id' when updating — allow passing it
            if ($request->filled('payment_id')) {
                $data['payment_id'] = $request->payment_id;
            }

            // call repository (it will insert/update payment, ledger, journals & invoice payments)
            $this->accountingRepository->StorePaymentReceipt($data);

            // create todos if required (mirrors your Livewire logic)
            $admin_id = Auth::guard('admin')->check() ? Auth::guard('admin')->id() : Auth::id();
            if (!empty($data['next_payment_date'])) {
                TodoList::create([
                    'user_id' => $data['staff_id'],
                    'customer_id' => $data['customer_id'],
                    'created_by' => $admin_id,
                    'todo_type' => 'Payment',
                    'todo_date' => $data['next_payment_date'],
                    'remark' => 'Next Payment Schedule on ' . $data['next_payment_date'],
                ]);
            }
            if (!empty($data['deposit_date'])) {
                TodoList::create([
                    'user_id' => $data['staff_id'],
                    'customer_id' => $data['customer_id'],
                    'created_by' => $admin_id,
                    'todo_type' => 'Cheque Deposit',
                    'todo_date' => $data['deposit_date'],
                    'remark' => 'Deposit Date ' . $data['deposit_date'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Payment collection stored successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to store payment collection.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

   public function skipOrderBill(Request $request)
{
    $validated = $request->validate([
        'skip_order_reason' => 'required|string',
        'salesman_id' => 'required|exists:users,id',
    ]);

    DB::beginTransaction();
    try {
        // Generate order number and get bill ID automatically
        $billData = Helper::generateInvoiceBill($validated['salesman_id']);
        $orderNumber = $billData['number'];
        $billId = $billData['bill_id'];

        if ($orderNumber === '000' || !$billId) {
            return response()->json([
                'success' => false,
                'message' => 'No active bill book available for this salesman.'
            ], 400);
        }

        // Create cancelled order
        $order = new Order();
        $order->order_number = $orderNumber;
        $order->status = 'Cancelled';
        $order->skip_order_reason = $validated['skip_order_reason'];
        $order->created_by = $validated['salesman_id'];
        $order->save();

        // Increment used count
        $billBook = SalesmanBilling::find($billId);
        $billBook->increment('no_of_used');

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Order skipped successfully.',
            'data' => [
                'order_number' => $orderNumber,
                'bill_id' => $billId
            ]
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error skipping order: ' . $e->getMessage()
        ], 500);
    }
}


    
   
    
   
}
