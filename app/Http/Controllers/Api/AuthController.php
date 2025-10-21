<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Otp;
use App\Models\Order;
use App\Models\UserLogin;
use App\Models\Ledger;
use App\Models\Country;
use App\Models\BusinessType;
use App\Models\PaymentCollection;
use App\Models\UserWhatsapp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class AuthController extends Controller
{
    public function CountryList(){
        $data = Country::select('id', 'title', 'country_code', 'mobile_length')->orderBy('title', 'ASC')->where('status', 1)->get();
        return response()->json([
            'status' => true,
            'message' => 'Country list retrieved successfully',
            'countries' => $data,
        ], 200);
    }
    public function CountryDetailsByID($id){
        $data = Country::select('title', 'country_code', 'mobile_length')->find($id);

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Country not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Country data retrieved successfully',
            'country' => $data,
        ], 200);
    }

    // User Login
    public function checkDevice(Request $request){
        $validator = Validator::make($request->all(), [
            'device_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $userLogin = UserLogin::where('device_id', $request->device_id)->first();

        if ($userLogin) {
            return response()->json([
                'message' => 'Device found, use MPIN to login',
                'data'=>$userLogin,
                'show_mpin' => true
            ], 200);
        }

        return response()->json([
            'message' => 'Device not registered, login with OTP first',
            'show_mpin' => false
        ], 200);
    }

    public function userLogin(Request $request){
       // dd('hi');
        $validator = Validator::make($request->all(),[
            'country_code' => 'required',
            'mobile' => [
            'required',
            'numeric',
            function ($attribute, $value, $fail) {
                $exists = User::where('phone', $value)
                            ->whereIn('user_type', [1,0])
                            ->exists();

                if (! $exists) {
                    $fail('The selected mobile number is invalid or does not belong to a valid user.');
                }
            },
        ],
            'device_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(), // Returns only the first error message
            ], 422);
        }

        // Check if the user already exists in user_logins
        $userLogin = UserLogin::where('country_code', $request->country_code)
         ->where('mobile', $request->mobile)
         ->first();
         $user = User::where('country_code_phone', $request->country_code)
         ->where('phone', $request->mobile)
         ->first();

        if ($userLogin && $userLogin->is_verified) {
            return response()->json([
                'message' => 'User already verified, use MPIN to login',
                'show_mpin' => true
            ], 200);
        }

        // Generate and store OTP
        // $otp = rand(1000, 9999);
        $otp = 1234;
        UserLogin::updateOrCreate(
            ['user_id'=>$user->id,'country_code' => $request->country_code, 'mobile' => $request->mobile],
            ['otp' => $otp, 'device_id' => $request->device_id]
        );

        // Send OTP (Replace with SMS API)
        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp // Remove in production
        ], 200);
    }

    public function verifyOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'mobile' => 'required|exists:users,phone',
            'otp' => 'required|digits:4',
            'device_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        
        $userLogin = UserLogin::where('country_code', $request->country_code)
            ->where('mobile', $request->mobile)
            ->where('otp', $request->otp)
            ->first();

        if (!$userLogin) {
            return response()->json([
                'status'=>false,
                'message' => 'Invalid OTP'
            ], 401);
        }

        $userLogin->is_verified = true;
        $userLogin->otp = null;
        $userLogin->device_id = $request->device_id;
        $userLogin->save();

        return response()->json([
            'status'=>true,
            'message' => 'OTP verified successfully. Please set MPIN.',
        ], 200);
    }

    /**
     * Step 4: Set MPIN
     */
    public function setMpin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:user_logins,mobile',
            'mpin' => 'required|digits:4',
            'device_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $userLogin = UserLogin::where('mobile', $request->mobile)->first();
        if (!$userLogin) {
            return response()->json([
                'status'=>false,
                'message' => 'User not found'
            ], 404);
        }

        $userLogin->mpin = Hash::make($request->mpin);
        $userLogin->save();

            // return response()->json([
            //     'status'=>true,
            //     'message' => 'MPIN set successfully',
            // ], 200);

        if (!$userLogin || !Hash::check($request->mpin, $userLogin->mpin)) {
            return response()->json([
                'status'=>false,
                'message' => 'Invalid MPIN or Device ID'
            ], 401);
        }

        $userLogin->device_id = $request->device_id;
        $userLogin->save();
        // Generate API token
        $user = $userLogin->user; // Assuming `user_id` is linked to `users` table
        $user->tokens()->delete();
        $token = $user->createToken('Login API')->plainTextToken;
        $data=[
            'id' => $user->id,
            'firstname' => $user->name,
            'surname' => $user->surname ?? '', // Avoid errors if surname is null
            'designation' => optional($user->designationDetails)->name ?? 'N/A', // Check if relation exists
            'email' => $user->email,
            'mobile' => $user->phone,
            'country_code' => $user->country_code_phone,
        ];
        return response()->json([
            'status'=>true,
            'message' => 'MPIN set with login successful',
            'token' => $token,
            'user' => $data
        ], 200);
    }

     /**
     * Step 5: Login with MPIN and Device ID
     */
    public function mpinLogin(Request $request){
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:user_logins,mobile',
            'mpin' => 'required|digits:4',
            'device_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $userLogin = UserLogin::where('mobile', $request->mobile)
            ->first();

        if (!$userLogin || !Hash::check($request->mpin, $userLogin->mpin)) {
            return response()->json([
                'status'=>false,
                'message' => 'Invalid MPIN or Device ID'
            ], 401);
        }

        $userLogin->device_id = $request->device_id;
        $userLogin->save();
        // Generate API token
        $user = $userLogin->user; // Assuming `user_id` is linked to `users` table
        $user->tokens()->delete();
        $token = $user->createToken('Login API')->plainTextToken;
        $data=[
            'id' => $user->id,
            'firstname' => $user->name,
            'surname' => $user->surname ?? '', // Avoid errors if surname is null
            'designation' => optional($user->designationDetails)->name ?? 'N/A', // Check if relation exists
            'email' => $user->email,
            'mobile' => $user->phone,
            'country_code' => $user->country_code_phone,
        ];
        return response()->json([
            'status'=>true,
            'message' => 'MPIN login successful',
            'token' => $token,
            'user' => $data
        ], 200);
    }

    public function logout(Request $request){
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|exists:user_logins,mobile',
            'device_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $userLogin = UserLogin::where('mobile', $request->mobile)
            ->where('device_id', $request->device_id)
            ->first();
        if (!$userLogin) {
            return response()->json(['message' => 'User not found or already logged out'], 404);
        }

        // Remove device ID to require OTP on next login
        $userLogin->device_id = null;
        // $userLogin->mpin = null; // Optional: Remove MPIN if required
        $userLogin->save();

        // Delete API tokens if the user is authenticated
        if (Auth::check()) {
            Auth::user()->tokens()->delete(); // Logs out by deleting all tokens
        }
        return response()->json([
            'message' => 'Logout successful. Next login will require OTP.'
        ], 200);
    }

    /**
     * Step 1: Send OTP for Forgot MPIN
     */
    public function forgotMpin(Request $request){

        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'mobile' => 'required|exists:user_logins,mobile',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Generate a 4-digit OTP
        // $otp = rand(1000, 9999);
        $otp = 1234;

        // Update OTP in the database
        $userLogin = UserLogin::where('mobile', $request->mobile)
        ->where('country_code', $request->country_code)
        ->first();
        if ($userLogin) {
            $userLogin->otp = $otp;
            $userLogin->save();

            // TODO: Send OTP via SMS (Integrate SMS API here)

            return response()->json([
                'status' => true,
                'message' => 'OTP sent to your mobile.'
            ], 200);
        }

        return response()->json([
            'status' => false, 
            'message' => 'User not found.'
        ], 404);
    }

     /**
     * Step 2: Verify OTP for Forgot MPIN
     */
    public function verifyOtpMpin(Request $request){
        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'mobile' => 'required|exists:user_logins,mobile',
            'otp' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $userLogin = UserLogin::where('mobile', $request->mobile)
            ->where('country_code', $request->country_code)
            ->where('otp', $request->otp)
            ->first();

        if (!$userLogin) {
            return response()->json([
                'status' => false, 
                'message' => 'Invalid OTP.'
            ], 400);
        }

        // OTP verified successfully, clear OTP and allow reset MPIN
        $userLogin->otp = null;
        $userLogin->save();

        return response()->json([
            'status' => true, 
            'message' => 'OTP verified. You can now reset MPIN.'
        ], 200);
    }

     /**
     * Step 3: Reset MPIN After OTP Verification
     */
    public function resetMpin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required',
            'mobile' => 'required|exists:user_logins,mobile',
            'new_mpin' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false, 
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $userLogin = UserLogin::where('mobile', $request->mobile)
            ->where('country_code', $request->country_code)
            ->first();

        if (!$userLogin) {
            return response()->json(['status' => false, 'message' => 'User not found.'], 404);
        }

        // Hash the MPIN before saving
        $userLogin->mpin = Hash::make($request->new_mpin);
        $userLogin->save();

        return response()->json(['status' => true, 'message' => 'MPIN reset successfully.'], 200);
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
    public function profile(){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }

        return response()->json([
            'status' => true,
            'message' => 'User profile retrieved successfully',
            'user' => [
                'id' => $user->id,
                'firstname' => $user->name,
                'surname' => $user->surname ?? '', // Avoid errors if surname is null
                'designation' => optional($user->designationDetails)->name ?? 'N/A', // Check if relation exists
                'email' => $user->email,
                'mobile' => $user->phone,
                'country_code' => $user->country_code_phone,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            ]
        ], 200);
    }

    public function dashboard(){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }
        // Get total sales Amount for this user from order table
        $totalSales = Order::where('created_by', $user->id)
        ->whereDate('created_at', Carbon::today()) // Filters only today's orders
        ->sum('total_amount');

        // Get Total Collections for this user from payment_collections table
        $totalCollections = PaymentCollection::where('created_at', Carbon::today())->where('user_id', $user->id)
        ->sum('collection_amount');

        // Get All business type

        $totalBusinesstype = BusinessType::select('id', 'title', 'image')->orderBy('title', 'ASC')->get();

        return response()->json([
            'status' => true,
            'message' => 'Dashboard data retrieved successfully',
            'data' => [
                'total_sales' => $totalSales,
                'total_collections' => $totalCollections,
                'total_business_type' => $totalBusinesstype
            ]
        ], 200);

    }

    public function customer_list(){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }

        // Get All customer created by this user

        $customers = User::with('billingAddress')->where('created_by', $user->id)->orderBy('id','DESC')->get();
        // dd($customers);
        return response()->json([
            'status' => true,
            'message' => 'Customer list retrieved successfully',
            'customers' => $customers
        ], 200);
    }
    public function customer_details($id){
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user; // Return the response if the user is not authenticated
        }

        $details = User::with('billingAddress')->find($id);
      
        if (!$details) {
            return response()->json([
                'status' => false,
                'message' => 'Customer details not found',
            ], 404);
        }
        $latest_order = Order::select('id', 'order_number', 'total_amount','created_at')
        ->where('customer_id', $id)
        ->where('created_by', $user->id)
        ->with(['items' => function ($query) {
            $query->select('order_id', 'product_name'); // Fetch only relevant columns
        }])
        ->withCount('items') // Get the count of related items
        ->latest('id')
        ->get();
        $orders = [];
      
        if(count($latest_order)>0){
            foreach($latest_order as $key => $item){
                $orders[$key]['id'] =$item->id; 
                $orders[$key]['order_number'] =$item->order_number; 
                $orders[$key]['total_amount'] =$item->total_amount;
                $extra_item = count($item->items)==1?"":" +(".(count($item->items)-1)." Item)";
                $orders[$key]['products'] =count($item->items)==1?$item->items[0]->product_name.$extra_item:"N/A"; 
                $orders[$key]['order_date'] = date('d-m-y', strtotime($item->created_at)); 
            }
        }
       
        $ledgerCredit=Ledger::where('customer_id',$id)->where('is_credit',1)->sum('transaction_amount');
        $ledgerDebit=Ledger::where('customer_id',$id)->where('is_debit',1)->sum('transaction_amount');
        
        $data = [];
        $data['details']=$details;
        $data['latest_orders']=$orders;
        $data['wallet']=$ledgerCredit;
        $data['collectionAmount']=$ledgerDebit;
        return response()->json([
            'status' => true,
            'message' => 'Customer data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function customer_filter(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }

        $filter = trim($request->keyword);

        // Base query
        $query = User::with('billingAddress')
            ->where('user_type', 1)
            ->where('status', 1)
            ->where(function ($query) use ($filter) {
                $query->where('name', 'like', "%{$filter}%")
                    ->orWhere('prefix', 'like', "%{$filter}%")
                    ->orWhere('phone', 'like', "%{$filter}%")
                    ->orWhere('whatsapp_no', 'like', "%{$filter}%")
                    ->orWhere('email', 'like', "%{$filter}%")
                    ->orWhereRaw("CONCAT(prefix, ' ', name) LIKE ?", ["%{$filter}%"])
                    ->orWhereRaw("CONCAT(country_code_phone, ' ', phone) LIKE ?", ["%{$filter}%"])
                    ->orWhereRaw("CONCAT(country_code_phone, '', phone) LIKE ?", ["%{$filter}%"]);
            });

        // Apply restriction if user is not super admin
        if (!$user->is_super_admin) {
            $query->where('created_by', $user->id);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        // Optional: also find customer by order number or customer name in orders
        // $order = Order::with('customer')
        //     ->where('order_number', 'like', "%{$filter}%")
        //     ->orWhereHas('customer', function ($query) use ($filter) {
        //         $query->where('name', 'like', "%{$filter}%");
        //     })
        //     ->when(!$user->is_super_admin, fn($q) => $q->where('created_by', $user->id))
        //     ->latest()
        //     ->first();

        // // Prepend matched customer if found in order
        // if ($order && $order->customer && !$users->contains('id', $order->customer->id)) {
        //     $users->prepend($order->customer);
        // }

        return response()->json([
            'status' => true,
            'message' => 'Data fetched successfully!',
            'data' => $users,
        ], 200);
    }

    public function customer_store(Request $request){
        $authUser = $this->getAuthenticatedUser();
         // ✅ Fetch country-specific mobile lengths dynamically
        $mobileLengthPhone = Country::where('country_code', $request->phone_code)->value('mobile_length');
        $mobileLengthAlt1  = Country::where('country_code', $request->alternative_phone_code_1)->value('mobile_length');
        $mobileLengthAlt2  = Country::where('country_code', $request->alternative_phone_code_2)->value('mobile_length');
        
        $rules = [
            'prefix' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            // 👇 Dynamic mobile length regex based on country
            'phone' => [
                'required',
                'regex:/^\d{' . $mobileLengthPhone . '}$/',
                
            ],

            'alternative_phone_code_1' => 'nullable|string|max:10',
            'alternative_phone_number_1' => [
                'nullable',
                'regex:/^\d{' . $mobileLengthAlt1 . '}$/',
            ],

            'alternative_phone_code_2' => 'nullable|string|max:10',
            'alternative_phone_number_2' => [
                'nullable',
                'regex:/^\d{' . $mobileLengthAlt2 . '}$/',
            ],
            
            'dob' => 'nullable|date',
            'company_name' => 'nullable|string|max:255',
            'employee_rank' => 'nullable|string|max:255',
           
            'billing_address' => 'required|string',
            'billing_landmark' => 'nullable|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_country' => 'required|string|max:255',
            'billing_pin' => 'nullable|string|max:10',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

          $messages = [
            'phone.required' => 'The primary mobile number is required.',
            'phone.regex' => "The mobile number must be exactly {$mobileLengthPhone} digits as per selected country.",
            'phone.unique' => 'This mobile number is already registered with another customer.',

            'alternative_phone_number_1.regex' => "Alternative phone number 1 must be exactly {$mobileLengthAlt1} digits.",
            'alternative_phone_number_2.regex' => "Alternative phone number 2 must be exactly {$mobileLengthAlt2} digits.",

            'email.unique' => 'This email address is already in use.',
            'profile_image.mimes' => 'Profile image must be a file of type: jpeg, png, jpg.',
            'gst_certificate_image.mimes' => 'GST certificate must be a file of type: jpg, png, or pdf.',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules,$messages);

        // Return error response if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        
        DB::beginTransaction();

        try {
            
            $profileImagePath = $request->hasFile('profile_image')
                ? 'storage/' . $request->file('profile_image')->store('profile_images', 'public')
                : null;

          
            // Create the user
            $user = User::create([
                'prefix' => $request->prefix,
                'name' => $request->name,
                'customer_badge' => $request->badge_type,
                'profile_image' => $profileImagePath,
                'company_name' => $request->company_name,
                'employee_rank' => $request->employee_rank,
                'email' => $request->email,
                'dob' => $request->dob,
                'country_code_phone' => $request->phone_code,
                'phone' => $request->phone,
                'country_code_whatsapp' => $request->whatsapp_code,
                // 'whatsapp_no' => $request->whatsapp_no,
                'gst_number' => $request->gst_number,
                'credit_limit' => $request->credit_limit === '' ? 0 : $request->credit_limit,
                'credit_days' => $request->credit_days === '' ? 0 : $request->credit_days,
                'gst_certificate_image' => $request->hasFile('gst_certificate_image') ? $this->uploadGSTCertificate($request) : null,
                'country_id' => $request->country_id,
                'country_code_alt_1' => $request->country_code_alternative_1,
                'alternative_phone_number_1' => $request->alternative_phone_number_1,
                'country_code_alt_2' => $request->country_code_alternative_2,
                'alternative_phone_number_2'  => $request->alternative_phone_number_2,
                'created_by' => $authUser->id,
                // 'verified_video' => $verifiedVideoPath,
            ]);

            if($request->isWhatsappPhone){
                $existingRecord = UserWhatsapp::where('whatsapp_number', $authUser->phone)
                                                    ->where('user_id', '!=', $user->id)
                                                    ->exists();
                if(!$existingRecord){
                    UserWhatsapp::updateOrCreate(
                        ['user_id' => $user->id,
                        'whatsapp_number' => $request->phone,
                        ],
                        ['country_code' => $request->phone_code,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if ($request->isWhatsappAlt1) {
                $existingRecord = UserWhatsapp::where('whatsapp_number', $request->alternative_phone_number_1)
                                                ->where('user_id', '!=', $user->id)
                                                ->exists();
                if(!$existingRecord){
                UserWhatsapp::updateOrCreate([
                    'user_id' => $user->id,
                    'whatsapp_number' => $request->alternative_phone_number_1
                    ],
                    ['country_code' => $request->country_code_alternative_1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
              }
            }
    
            if ($request->isWhatsappAlt2) {
                $existingRecord = UserWhatsapp::where('whatsapp_number', $request->alternative_phone_number_1)
                                                ->where('user_id', '!=', $user->id)
                                                ->exists();
                if(!$existingRecord){
                UserWhatsapp::updateOrCreate([
                    'user_id' => $user->id,
                    'whatsapp_number' => $request->alternative_phone_number_2,
                    ],
                    ['country_code' => $request->country_code_alternative_2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
              }
            }
            // Save billing address
            UserAddress::create([
                'user_id' => $user->id,
                'address_type' => 1, // Billing address
                'address' => $request->billing_address,
                'landmark' => $request->billing_landmark,
                'city' => $request->billing_city,
                'country' => $request->billing_country,
                'zip_code' => $request->billing_pin,
            ]);
            DB::commit();

            // Return success response
            return response()->json([
                'status' => true,
                'message' => 'Customer information saved successfully!',
                'user' => $user->load('userAddress'),
            ]);
        } catch (\Exception $e) {
            // Log error and return response
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function customer_update(Request $request, $id)
    {
        $authUser = $this->getAuthenticatedUser();
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        // ✅ Fetch country-specific mobile lengths dynamically
        $mobileLengthPhone = Country::where('country_code', $request->phone_code)->value('mobile_length');
        $mobileLengthAlt1  = Country::where('country_code', $request->alternative_phone_code_1)->value('mobile_length');
        $mobileLengthAlt2  = Country::where('country_code', $request->alternative_phone_code_2)->value('mobile_length');

        $rules = [
            'prefix' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone_code' => 'required|string|max:10',
            
            // 👇 Dynamic mobile length regex based on country
            'phone' => [
                'required',
                'regex:/^\d{' . $mobileLengthPhone . '}$/',
                Rule::unique('users', 'phone')
                    ->ignore($user->id)
                    ->whereNull('deleted_at'),
            ],

            'alternative_phone_code_1' => 'nullable|string|max:10',
            'alternative_phone_number_1' => [
                'nullable',
                'regex:/^\d{' . $mobileLengthAlt1 . '}$/',
            ],

            'alternative_phone_code_2' => 'nullable|string|max:10',
            'alternative_phone_number_2' => [
                'nullable',
                'regex:/^\d{' . $mobileLengthAlt2 . '}$/',
            ],
            'dob' => 'nullable|date',
            'company_name' => 'nullable|string|max:255',
            'employee_rank' => 'nullable|string|max:255',
            'billing_address' => 'required|string',
            'billing_landmark' => 'nullable|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_country' => 'required|string|max:255',
            'billing_pin' => 'nullable|string|max:10',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // 'verified_video' => 'nullable|file|mimes:mp4,avi,mkv|max:10240',
            'gst_certificate_image' => 'nullable|file|mimes:jpg,png,pdf|max:5120',
        ];

         $messages = [
            'phone.required' => 'The primary mobile number is required.',
            'phone.regex' => "The mobile number must be exactly {$mobileLengthPhone} digits as per selected country.",
            'phone.unique' => 'This mobile number is already registered with another customer.',

            'alternative_phone_number_1.regex' => "Alternative phone number 1 must be exactly {$mobileLengthAlt1} digits.",
            'alternative_phone_number_2.regex' => "Alternative phone number 2 must be exactly {$mobileLengthAlt2} digits.",

            'email.unique' => 'This email address is already in use.',
            'profile_image.mimes' => 'Profile image must be a file of type: jpeg, png, jpg.',
            'gst_certificate_image.mimes' => 'GST certificate must be a file of type: jpg, png, or pdf.',
        ];

        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        DB::beginTransaction();

         if ($request->hasFile('profile_image')) {
                // Delete the old profile image if exists
                if ($user->profile_image && Storage::exists($user->profile_image)) {
                    Storage::delete($user->profile_image);
                }
                $profileImagePath = 'storage/' . $request->file('profile_image')->store('profile_images', 'public');
            } else {
                $profileImagePath = $user->profile_image;
            }

            
        try {
            // Update base user fields
            $user->fill([
                'prefix' => $request->prefix,
                'name' => $request->name,
                'customer_badge' => $request->badge_type,
                'company_name' => $request->company_name,
                'employee_rank' => $request->employee_rank,
                'email' => $request->email,
                'dob' => $request->dob,
                'country_code_phone' => $request->phone_code,
                'phone' => $request->phone,
                'country_code_whatsapp' => $request->whatsapp_code,
                'gst_number' => $request->gst_number,
                'credit_limit' => $request->credit_limit ?: 0,
                'credit_days' => $request->credit_days ?: 0,
                'country_id' => $request->country_id,
                'country_code_alt_1' => $request->alternative_phone_code_1,
                'alternative_phone_number_1' => $request->alternative_phone_number_1,
                'country_code_alt_2' => $request->alternative_phone_code_2,
                'alternative_phone_number_2' => $request->alternative_phone_number_2,
                'updated_by' => $authUser->id,
            ]);

               // Handle Profile Image Upload
            if ($request->hasFile('profile_image')) {
                // Delete the old profile image if exists
                if ($user->profile_image && Storage::exists($user->profile_image)) {
                    Storage::delete($user->profile_image);
                }
                $profileImagePath = 'storage/' . $request->file('profile_image')->store('profile_images', 'public');
            } else {
                $profileImagePath = $user->profile_image;
            }

            if ($request->hasFile('profile_image')) {
                $user->profile_image = $profileImagePath;
            }
            
            if ($request->hasFile('gst_certificate_image')) {
                $user->gst_certificate_image = $this->uploadGSTCertificate($request);
            }

            $user->save();

            //  WhatsApp associations
            // Main number
            if ($request->boolean('isWhatsappPhone')) {
                $exists = UserWhatsapp::where('whatsapp_number', $request->phone)
                    ->where('user_id', '!=', $user->id)
                    ->exists();

                if (!$exists) {
                    UserWhatsapp::updateOrCreate(
                        ['user_id' => $user->id, 'whatsapp_number' => $request->phone],
                        ['country_code' => $request->phone_code]
                    );
                }
            } else {
                UserWhatsapp::where('user_id', $user->id)
                    ->where('whatsapp_number', $request->phone)
                    ->delete();
            }

            // Alt 1
            if ($request->boolean('isWhatsappAlt1')) {
                $exists = UserWhatsapp::where('whatsapp_number', $request->alternative_phone_number_1)
                    ->where('user_id', '!=', $user->id)
                    ->exists();

                if (!$exists) {
                    UserWhatsapp::updateOrCreate(
                        ['user_id' => $user->id, 'whatsapp_number' => $request->alternative_phone_number_1],
                        ['country_code' => $request->alternative_phone_code_1]
                    );
                }
            } else {
                UserWhatsapp::where('user_id', $user->id)
                    ->where('whatsapp_number', $request->alternative_phone_number_1)
                    ->delete();
            }

            // Alt 2
            if ($request->boolean('isWhatsappAlt2')) {
                $exists = UserWhatsapp::where('whatsapp_number', $request->alternative_phone_number_2)
                    ->where('user_id', '!=', $user->id)
                    ->exists();

                if (!$exists) {
                    UserWhatsapp::updateOrCreate(
                        ['user_id' => $user->id, 'whatsapp_number' => $request->alternative_phone_number_2],
                        ['country_code' => $request->alternative_phone_code_2]
                    );
                }
            } else {
                UserWhatsapp::where('user_id', $user->id)
                    ->where('whatsapp_number', $request->alternative_phone_number_2)
                    ->delete();
            }

            // Update Billing Address
            UserAddress::updateOrCreate(
                ['user_id' => $user->id, 'address_type' => 1],
                [
                    'address' => $request->billing_address,
                    'landmark' => $request->billing_landmark,
                    'city' => $request->billing_city,
                    'state' => $request->billing_state,
                    'country' => $request->billing_country,
                    'zip_code' => $request->billing_pin,
                ]
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Customer information updated successfully!',
                'data' => $user->load('userAddress'),
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

   
}
