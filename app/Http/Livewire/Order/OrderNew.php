<?php

namespace App\Http\Livewire\Order;

use App\Repositories\OrderRepository;
use Livewire\Component;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Collection;
use App\Models\Fabric;
use App\Models\CollectionType;
use App\Models\Measurement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ledger;
use App\Models\Catalogue;
use App\Models\SalesmanBilling;
use App\Models\OrderMeasurement;
use App\Models\Payment;
use App\Models\Country;
use App\Models\BusinessType;
use App\Models\UserWhatsapp;
use App\Models\Page;
use App\Models\CataloguePageItem;
use App\Models\OrderItemCatalogueImage;
use App\Models\OrderItemVoiceMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class OrderNew extends Component
{
    use WithFileUploads;
    public $skip_order_reason;
    public $searchTerm = '';
    public $prefix;
    public $searchResults = [];
    public $errorClass = [];
    public $existing_measurements = [];
    public $catalogue_page_item = [];
    public $collections = [];
    public $errorMessage = [];
    public $activeTab = 1;
    public $FetchProduct = 1;

    public $customers = null;
    public $orders = null;
    public $name, $company_name,$employee_rank, $email,$customer_image, $dob, $customer_id, $whatsapp_no, $phone ,$alternative_phone_number_1,$alternative_phone_number_2;
    public $billing_address,$billing_landmark,$billing_city,$billing_state,$billing_country,$billing_pin;

    public $is_billing_shipping_same;

    public $shipping_address,$shipping_landmark,$shipping_city,$shipping_state,$shipping_country,$shipping_pin;

    //  product
    public $categories,$subCategories = [], $products = [], $measurements = [];
    public $selectedCategory = null, $selectedSubCategory = null,$searchproduct, $product_id =null,$collection;
    public $paid_amount = 0;
    public $billing_amount = 0;
    public $remaining_amount = 0;
    public $payment_mode = null;
    public $order_number;
    public $bill_id;
    public $bill_book = [];

    // For Catalogue
    public $selectedCatalogue = [];
    public $cataloguePages = [];
    public $catalogues = [];
    public $maxPages = [];

    // For ordered by
    public $salesmen;
    public $salesman;

    // for checking salesman billing exists or not
    public $salesmanBill;
    public $selectedFabric = null;

    public $mobileLength;
    public $country_code;
    public $country_id;
    public $Business_type;
    public $selectedBusinessType = "TEXTILES";
    public $pageItems = [];

    public $phone_code,$selectedCountryWhatsapp,$alt_phone_code_1,$alt_phone_code_2;
    public $isWhatsappPhone, $isWhatsappAlt1, $isWhatsappAlt2;
    public $mobileLengthPhone,$mobileLengthWhatsapp,$mobileLengthAlt1,$mobileLengthAlt2;
    public $items = [];
    public $imageUploads = [];
    public $newUploads = [];
    public $voiceUploads = [];
    public $air_mail,$logedin_user;
    public $customerType = 'new';
    public $extra_measurement = [];
    
    public function onCustomerTypeChange($value){
        $this->customerType = $value;
        if($value == 'new'){
            $this->searchResults = [];
            $this->searchTerm = '';
        }else{
            $this->searchResults = [];
        }
    }
    public function mount()
    {
        $this->logedin_user = auth()->guard('admin')->user();
        $user_id = request()->query('user_id');

        if ($user_id) {
            $customer = User::with(['billingAddress', 'shippingAddress'])
                ->where([
                    ['id', $user_id],
                    ['user_type', 1],
                    ['status', 1]
                ])
                ->first();

            if ($customer) {
                $this->customer_id = $customer->id;
                $this->prefix = $customer->prefix;
                $this->name = $customer->name;
                $this->searchTerm = $customer->prefix . ' ' . $customer->name;
                $this->company_name = $customer->company_name;
                $this->employee_rank = $customer->employee_rank;
                $this->email = $customer->email;
                $this->dob = $customer->dob;
                $this->phone = $customer->phone;
                // $this->whatsapp_no = $customer->whatsapp_no;

                $this->phone_code = $customer->country_code_phone;
                // $this->selectedCountryWhatsapp = $customer->country_code_whatsapp;
                $this->alt_phone_code_1 = $customer->country_code_alt_1;
                $this->alt_phone_code_2 = $customer->country_code_alt_2;

                $this->phone = $customer->phone;
                $this->alternative_phone_number_1 = $customer->alternative_phone_number_1;
                $this->alternative_phone_number_2 = $customer->alternative_phone_number_2;

                $this->mobileLengthPhone = Country::where('country_code',$this->phone_code)->value('mobile_length') ?? '';
                $this->mobileLengthWhatsapp = Country::where('country_code',$this->selectedCountryWhatsapp)->value('mobile_length') ?? '';
                $this->mobileLengthAlt1 = Country::where('country_code',$this->alt_phone_code_1)->value('mobile_length') ?? '';
                $this->mobileLengthAlt2 = Country::where('country_code',$this->alt_phone_code_2)->value('mobile_length') ?? '';

                $this->isWhatsappPhone = UserWhatsapp::where('user_id',$customer->id)->where('whatsapp_number',$this->phone)->exists();
                $this->isWhatsappAlt1 = UserWhatsapp::where('user_id',$customer->id)->where('whatsapp_number',$this->alternative_phone_number_1)->exists();
                $this->isWhatsappAlt2 = UserWhatsapp::where('user_id',$customer->id)->where('whatsapp_number',$this->alternative_phone_number_2)->exists();

                // Assign Billing Address (if exists)
                if ($billing = $customer->billingAddress) {
                    $this->billing_address = $billing->address;
                    $this->billing_landmark = $billing->landmark;
                    $this->billing_city = $billing->city;
                    $this->billing_state = $billing->state;
                    $this->billing_country = $billing->country;
                    $this->billing_pin = $billing->zip_code;
                }

                // Fetch latest order
                $this->orders = Order::with(['customer:id,prefix,name'])
                    ->where('customer_id', $customer->id)
                    ->latest()
                    ->take(1)
                    ->get();
            }
        }

        // Load common dropdowns
        $this->customers = User::where([
            ['user_type', 1],
            ['status', 1]
        ])->orderBy('name')->get();

        $this->categories = Category::where('status', 1)->orderBy('title')->get();
        $this->collections = Collection::whereIn('id', [1, 2])->orderBy('title')->get();
        $this->salesmen = User::where([
            ['user_type', 0],
            ['designation', 2]
        ])->get();

        // Auto-select the logged-in Salesman
        $this->salesman = auth()->guard('admin')->user()->id ?? null;

        // Auto-fetch bill book number for the salesman
        if ($this->salesman) {
            $this->changeSalesman($this->salesman);
        }

        // Fetch Salesman Billing if exists
        if (auth()->guard('admin')->check()) {
            $this->salesmanBill = SalesmanBilling::where('salesman_id', auth()->guard('admin')->user()->id)->first();
        }

        // Add initial order item
        $this->addItem();

        foreach ($this->items as $index => $item) {
            if (isset($item['measurements'])) {
                foreach ($item['measurements'] as $measurement) {
                    foreach ($this->existing_measurements as $existing) {
                        if (trim($existing['short_code']) === trim($measurement['short_code'])) {
                            $this->items[$index]['get_measurements'][$measurement['id']]['value'] = $existing['value'];
                        }
                    }
                }
            }
        }
        $this->Business_type = BusinessType::all();
        $this->selectedBusinessType = BusinessType::where('title','TEXTILES')->value('id');
    }

    public function skipOrder(){
        // dd('hi');
      $this->validate([
            'order_number' => 'required|string|not_in:000|unique:orders,order_number',
            'skip_order_reason' => 'required'
        ]);

         DB::beginTransaction();
          try {
             $order = new Order();
            $order->order_number = $this->order_number;
            $order->status = 'Cancelled';
            $order->skip_order_reason = $this->skip_order_reason;
            $order->created_by = auth()->guard('admin')->id();
            $order->save();

             // Update the bill book usage
            $billBook = SalesmanBilling::find($this->bill_id);
            if ($billBook) {
                $billBook->increment('no_of_used');
            }

            DB::commit();
            $this->reset(['skip_order_reason']); // clear modal field
            $this->dispatch('hide-skip-modal'); 
            return redirect()->route('admin.order.index');
            session()->flash('success', 'Order skipped successfully.');
          }catch(\Exception $e){
            dd($e->getMessage());
             DB::rollBack();
            session()->flash('error', 'Error skipping order: ' . $e->getMessage());
          }
    }


    // Skip Order Bill open modal
    public function skipOrderBill(){
        $this->dispatch('open-skip-modal');
    }

   public function updatedItems($value, $key)
    {
        [$index, $field] = explode('.', $key);

        if ($field === 'collection' && $this->items[$index]['collection'] == 1) {
            $this->items[$index]['quantity'] = 1;
        }
    }



    public function GetCountryDetails($mobileLength, $field){
        switch($field){
            case 'phone':
                $this->mobileLengthPhone  = $mobileLength;
                break;

            case 'whatsapp':
                $this->mobileLengthWhatsapp = $mobileLength;
                break;

            case 'alt_phone_1':
                $this->mobileLengthAlt1 = $mobileLength;
                break;

            case 'alt_phone_2':
                $this->mobileLengthAlt2 = $mobileLength;
                break;
        }
    }




    public function searchFabrics($index)
    {

        // Perform the fabric search
        $productId = $this->items[$index]['product_id'] ?? null;
        $searchTerm = $this->items[$index]['searchTerm'] ?? '';

        if (!empty($searchTerm) && !is_null($productId)) {
            $this->items[$index]['searchResults'] = Fabric::join('product_fabrics', 'fabrics.id', '=', 'product_fabrics.fabric_id')
                ->leftJoin('stock_fabrics', 'fabrics.id', '=', 'stock_fabrics.fabric_id')
                ->where('product_fabrics.product_id', $productId)
                ->where('fabrics.status', 1)
                ->where('fabrics.title', 'LIKE', "%{$searchTerm}%")
                ->select('fabrics.id', 'fabrics.title', \DB::raw('COALESCE(SUM(stock_fabrics.qty_in_meter),0) as available_stock'))
                 ->groupBy('fabrics.id', 'fabrics.title')
                ->limit(10)
                ->get();
        } else {
            $this->items[$index]['searchResults'] = [];
        }


    }



    public function selectFabric($fabricId, $index)
    {
        // Get the selected fabric details
        $fabric = Fabric::find($fabricId);

        if (!$fabric) {
            return;
        }

        // Set the exact selected fabric name
        $this->items[$index]['searchTerm'] = $fabric->title;
        $this->items[$index]['selected_fabric'] = $fabric->id;

        // Clear search results to hide the dropdown after selection
        $this->items[$index]['searchResults'] = [];
    }

    // Define rules for validation
    public function rules()
    {
        $auth = Auth::guard('admin')->user();
        $rules = [
            'items' => 'required|min:1',
            'items.*.collection' => 'required|string',
            'items.*.category' => 'required|string',
            'items.*.searchproduct' => 'required|string',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.selectedCatalogue' => 'required_if:items.*.collection,1',
            'items.*.page_number' => 'required_if:items.*.collection,1',
            'items.*.price' => 'required|numeric|min:1',
            'items.*.fitting' => 'required_if:items.*.collection,1',
            'items.*.expected_delivery_date' => 'required',
            'items.*.item_status' => 'required',
            'items.*.searchTerm' => 'required_if:items.*.collection,1',
            'order_number' => 'required|string|not_in:000|unique:orders,order_number',
            'air_mail' => 'nullable|numeric',
            'imageUploads.*.*'  => 'nullable|image|mimes:jpg,jpeg,png,webp', 
            'voiceUploads.*.*' => 'nullable|mimes:mp3,wav,ogg,m4a,wma,webm,mpga', 
        ];

        //  Add dynamic rules based on extra measurement per index
        foreach ($this->items as $index => $item) {
            $extra = $this->extra_measurement[$index] ?? null;
            
            if ($extra === 'mens_jacket_suit') {
                $rules["items.$index.vents"] = 'required';
                $rules["items.$index.shoulder_type"] = 'required';
            }

            if ($extra === 'ladies_jacket_suit') {
                $rules["items.$index.vents_required"] = 'required';
                $rules["items.$index.vents_count"]    = 'required_if:items.'.$index.'.vents_required,Yes|nullable|integer|min:1';
                $rules["items.$index.shoulder_type"] = 'required';

            }

            if ($extra === 'trouser') {
                $rules["items.$index.fold_cuff_required"]   = 'required';
                $rules["items.$index.fold_cuff_size"]       = 'required_if:items.'.$index.'.fold_cuff_required,Yes|nullable|numeric|min:1';
                $rules["items.$index.pleats_required"]      = 'required';
                $rules["items.$index.pleats_count"]         = 'required_if:items.'.$index.'.pleats_required,Yes|nullable|integer|min:1';
                $rules["items.$index.back_pocket_required"] = 'required';
                $rules["items.$index.back_pocket_count"]    = 'required_if:items.'.$index.'.back_pocket_required,Yes|nullable|integer|min:1';
                $rules["items.$index.adjustable_belt"]      = 'required';
                $rules["items.$index.suspender_button"]     = 'required';
                $rules["items.$index.trouser_position"]     = 'required';
            }
            if ($extra === 'shirt') {
                $rules["items.$index.sleeves"] = 'required';
                $rules["items.$index.collar"]  = 'required';
                $rules["items.$index.pocket"]  = 'required';
                $rules["items.$index.cuffs"]   = 'required';
                $rules["items.$index.collar_style"] = 'required_if:items.'.$index.'.collar,Other';
                $rules["items.$index.cuff_style"]   = 'required_if:items.'.$index.'.cuffs,Other';
            }
            if ($extra === 'ladies_jacket_suit' || $extra === 'shirt' || $extra === 'mens_jacket_suit') {
                $rules["items.$index.client_name_required"] = 'required';
                $rules["items.$index.client_name_place"] = 'required_if:items.'.$index.'.client_name_required,Yes';
            }
        }

        if (in_array($auth->designation, [1, 4])) {
            $rules['items.*.priority'] = 'required';
        }


        return $rules;
    }


    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    public function validateSingle($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    public function messages(){
        return [
            'items.required' => 'Please add at least one item to the order.',
             'items.*.category.required' => 'Please select a category for the item.',
             'items.*.searchproduct.required' => 'Please select a product for the item.',
             'items.*.quantity' => 'Please select a quantity for the item.',
             'items.*.selectedCatalogue.required_if' => 'Please select a catalogue for the item.',
             'items.*.page_number.required_if' => 'Please select a page for the item.',
             'items.*.price.required'  => 'Please enter a price for the item.',
             'items.*.fitting.required_if'  => 'Please select a fittings for the item.',
             'items.*.priority.required'  => 'Please select a priority for the item.',
             'items.*.item_status.required'  => 'Please select a status for the item.',
             'items.*.expected_delivery_date.required'  => 'Please select expected delivery date for the item.',
             'items.*.collection.required' =>  'Please enter a collection for the item.',
             'items.*.searchTerm.required_if' =>  'Please enter a Fabric for the item.',
             'order_number.required' => 'Order number is required.',
             'order_number.not_in' => 'Order number "000" is not allowed.',
             'order_number.unique' => 'Order number already exists, please try again.',
             'items.*.get_measurements.*.value.required' => 'Each measurement value is required.',

             //  Extra measurement messages
            'items.*.shoulder_type.required'          => 'Please select shoulder type',
            'items.*.vents.required'                  => 'Please select vents option for mens suit/jacket.',
            'items.*.vents_required.required'         => 'Please specify if vents are required for ladies suit/jacket.',
            'items.*.vents_count.required_if'         => 'Please specify how many vents for ladies suit/jacket.',
            'items.*.fold_cuff_required.required'     => 'Please specify if fold cuffs are required for the trouser.',
            'items.*.fold_cuff_size.required_if'      => 'Please enter the cuff size if fold cuffs are required.',
            'items.*.pleats_required.required'        => 'Please specify if pleats are required for the trouser.',
            'items.*.pleats_count.required_if'        => 'Please specify how many pleats for the trouser.',
            'items.*.back_pocket_required.required'   => 'Please specify if back pockets are required for the trouser.',
            'items.*.back_pocket_count.required_if'   => 'Please specify how many back pockets for the trouser.',
            'items.*.adjustable_belt.required'        => 'Please specify if an adjustable belt is required.',
            'items.*.suspender_button.required'       => 'Please specify if suspender buttons are required.',
            'items.*.trouser_position.required'       => 'Please select the trocopyMeasurementsosition.',

            'items.*.sleeves.required'      => 'Please select sleeves (L/S or H/S).',
            'items.*.collar.required'       => 'Please select a collar option.',
            'items.*.collar_style.required_if' => 'Please specify the collar style.',
            'items.*.pocket.required'       => 'Please select pocket option.',
            'items.*.cuffs.required'        => 'Please select cuff option.',
            'items.*.cuff_style.required_if'=> 'Please specify the cuff style.',
            'items.*.client_name_required.required' => 'Please specify if client name is required on the item.',
            'items.*.client_name_place.required_if' => 'Please specify where the client name should be placed on the item.',
        ];
    }



    public function FindCustomer($term)
    {
        // $this->searchTerm = $term;
        $this->reset('searchResults');

        if (!empty($term)) {
            // Fetch users based on search term
            $users = User::where('user_type', 1)
                ->where('status', 1)
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', '%' . $term . '%')
                        ->orWhere('phone', 'like', '%' . $term . '%')
                        // ->orWhere('whatsapp_no', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $term . '%');
                })
                ->take(20)
                ->get();

            // Fetch orders based on search term
            $orders = Order::where('order_number', 'like', '%' . $term . '%')
                ->orWhereHas('customer', function ($query) use ($term) {
                    $query->where('name', 'like', '%' . $term . '%');
                })
                ->latest()
                ->take(1)
                ->get();

            $this->orders = $orders;
              if ($orders->count()) {
            $customerFromOrder = $orders->first()->customer;
            if ($customerFromOrder) {
                $users->prepend($customerFromOrder); // Just for listing
            }
            session()->flash('orders-found', 'Orders found for this customer.');
        } else {
            session()->flash('no-orders-found', 'No orders found for this customer.');
        }

            // Remove duplicate users by `id`
            $this->searchResults = $users->unique('id')->values();
        } else {
            // Reset results when the search term is empty
            $this->reset([
                'searchResults','orders','prefix','phone_code','selectedCountryWhatsapp','alt_phone_code_1','alt_phone_code_2','isWhatsappPhone', 'isWhatsappAlt1', 'isWhatsappAlt2'
            ]);
        }
    }



    public function addItem()
    {
        $this->items[] = [
            'collection' => '',
            'category' => '',
            'sub_category' => '',
            'searchproduct' => '',
            'selected_fabric' => null,
            'measurements' => [],
            'products' => [],
            'product_id' => null,
            'price' => '',
            'selectedCatalogue' => '',
            'page_number' => '',
            'page_item' => '',
            'searchTerm' => '', // Ensure search field is empty
            'fitting' => null
        ];
    }



    // updateSalesman
    public function changeSalesman($value){
        $this->bill_book = Helper::generateInvoiceBill($value);
        $this->order_number = $this->bill_book['number'];
        $this->bill_id = $this->bill_book['bill_id'] ?? null;
    }






    public function removeItem($index)
    {
        unset($this->items[$index]);
        unset($this->extra_measurement[$index]);
        $this->items = array_values($this->items);
        $this->updateBillingAmount();  // Update billing amount after checking price
    }



    public function GetCategory($value,$index)
    {
        // Reset products, and product_id for the selected item
        $this->items[$index]['product_id'] = null;
        $this->items[$index]['measurements'] = [];
        $this->items[$index]['fabrics'] = [];
        $this->items[$index]['selectedCatalogue'] = null; // Reset catalogue
        // $this->items[$index]['selectedPage'] = null;
            // Fetch categories and products based on the selected collection
            $this->items[$index]['categories'] = Category::orderBy('title', 'ASC')->where('collection_id', $value)->where('status',1)->get()->toArray();
          
            // If collection_id = 2, auto-select "ACCESSORIES"
            if($value == 2){
                $category = Category::where([
                        ['collection_id','=',2],
                        ['title','=','ACCESSORIES'],
                        ['status', '=', 1]
                ])->first();
                if ($category) {
                    $this->items[$index]['category'] = (string)$category->id;
                }                       
            }
            if ($value == 1) {
                $catalogues = Catalogue::where('status',1)->get();
                $this->catalogues[$index] = $catalogues->pluck('catalogueTitle.title', 'id');
                
                // Fetch max page numbers per catalogue
                $this->maxPages[$index] = [];
                foreach ($catalogues as $catalogue) {
                    $this->maxPages[$index][$catalogue->id] = $catalogue->page_number;
                }
            } else {
                $this->catalogues[$index] = [];
                $this->maxPages[$index] = [];
            }
    }

    public function SelectedCatalogue($catalogueId, $index)
    {
        $this->items[$index]['page_number'] = null; // Reset page number
        $this->maxPages[$index] = []; // Reset max page number

        // Fetch max page number from database
        $maxPage = Catalogue::where('id', $catalogueId)->value('page_number');
        if ($maxPage) {
            $this->maxPages[$index][$catalogueId] = $maxPage;
        }
    }

    public function validatePageNumber($value, $index)
    {


        if (!isset($this->items[$index]['page_number']) || !isset($this->items[$index]['selectedCatalogue'])) {
            return;
        }

        $pageNumber = (int) $this->items[$index]['page_number'];
        $selectedCatalogue = $this->items[$index]['selectedCatalogue'];  //this is actually catalogue title id
        // dd($pageNumber,$selectedCatalogue);
        // Get all catalogues under the selected catalogue title
         $catalogueIds = Catalogue::where('id', $selectedCatalogue)->pluck('id');
         // Fetch the page ID first
            $page = Page::where('catalogue_id', $catalogueIds)
            ->where('page_number', $pageNumber)
            ->first();
         // Fetch catalog items from `catalogue_page_item` table
         if ($page) {
            $pageItems = CataloguePageItem::join('pages', 'catalogue_page_items.page_id', '=', 'pages.id')
                ->whereIn('catalogue_page_items.catalogue_id', $catalogueIds)
                ->where('pages.page_number', $pageNumber)
                ->select('catalogue_page_items.id', 'catalogue_page_items.catalog_item', 'pages.page_number')
                ->get();

            // Store fetched items in a property for dropdown use
            if(count($pageItems)>0){
                $this->catalogue_page_item[$index]=  $value;
                  // 🔑 Reset selection if current value is not in the new list
                $validItems = $pageItems->pluck('catalog_item')->toArray() ?? [];
                if (!in_array($this->items[$index]['page_item'] ?? null, $validItems)) {
                    $this->items[$index]['page_item'] = null;
                }
            }else{
                $this->catalogue_page_item[$index] = "";
            }
            $this->pageItems[$index] = $pageItems;
            // dd($this->pageItems[$index]);
        } else {
            $this->pageItems[$index] = [];
        }

        // Ensure we get the correct max page for the selected catalogue
        $maxPage = $this->maxPages[$index][$selectedCatalogue] ?? null;

        if ($maxPage === null) {
            return; // No catalogue selected, or no max page found
        }

        if ($pageNumber < 1 || $pageNumber > $maxPage) {
            $this->addError("items.$index.page_number", "Page number must be between 1 to $maxPage.");
        } else {
            $this->resetErrorBag("items.$index.page_number");
        }


    }



    // public function validateMeasurement($itemIndex, $measurementId)
    // {
    //     $measurement = $this->items[$itemIndex]['get_measurements'][$measurementId] ?? null;

    //     if ($measurement) {
    //         $value = trim($measurement['value'] ?? '');

    //         if ($value === '') {
    //             $this->addError("items.$itemIndex.get_measurements.$measurementId.value", 'Measurement value is required.');
    //         } elseif (!is_numeric($value) || floatval($value) < 1) {
    //             $this->addError("items.$itemIndex.get_measurements.$measurementId.value", 'Measurement must be a number greater than 0.');
    //         } else {
    //             $this->resetErrorBag("items.$itemIndex.get_measurements.$measurementId.value");
    //         }

    //         // Check if all required measurements for the product are present
    //         $productId = $this->items[$itemIndex]['product_id'] ?? null;

    //         if ($productId) {
    //             $expectedMeasurementIds = Measurement::where('product_id', $productId)->pluck('id')->toArray();

    //             $enteredMeasurementIds = array_keys(array_filter($this->items[$itemIndex]['get_measurements'] ?? [], function ($m) {
    //                 return isset($m['value']) && is_numeric($m['value']) && floatval($m['value']) > 0;
    //             }));

    //             $missing = array_diff($expectedMeasurementIds, $enteredMeasurementIds);

    //             if (!empty($missing)) {
    //                 session()->flash("measurements_error.$itemIndex", '🚨 Oops! All measurement data should be mandatory.');
    //             } else {
    //                 session()->forget("measurements_error.$itemIndex");
    //             }
    //         }
    //     }
    // }

    





    public function CategoryWiseProduct($categoryId, $index)
    {
        // Reset products for the selected item
        $this->items[$index]['products'] = [];
        $this->items[$index]['product_id'] = null;

        if ($categoryId) {
            // Fetch products based on the selected category and collection
            $this->items[$index]['products'] = Product::where('category_id', $categoryId)
                ->where('collection_id', $this->items[$index]['collection']) // Ensure the selected collection is considered
                ->where('status', 1)
                ->get();
        }
    }



    public function FindProduct($term, $index)
    {
        $collection = $this->items[$index]['collection'];
        $category = $this->items[$index]['category'];

        if (empty($collection)) {
            session()->flash('errorProduct.' . $index, '🚨 Please select a collection before searching for a product.');
            return;
        }

        if (empty($category)) {
            session()->flash('errorProduct.' . $index, '🚨 Please select a category before searching for a product.');
            return;
        }

        // Clear previous products in the current index
        $this->items[$index]['products'] = [];

        if (!empty($term)) {
            // Search for products within the specified collection and matching the term
            $this->items[$index]['products'] = Product::where('collection_id', $collection)
                ->where('category_id', $category)
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', '%' . $term . '%')
                          ->orWhere('product_code', 'like', '%' . $term . '%');
                })
                ->where('status', 1)
                ->get();
        }

    }
     
    public function updateTotalAmount()
    {
        $total = 0;

        foreach ($this->items as $item) {
            $quantity = isset($item['quantity']) ? floatval($item['quantity']) : 0;
            $price = isset($item['price']) ? floatval($item['price']) : 0;

            $total += $quantity * $price;
        }

        $this->billing_amount = round($total, 2);
    }


    
    public function checkproductPrice($value, $index)
    {
        $selectedFabricId = $this->items[$index]['selected_fabric'] ?? null;
        // dd($selectedFabricId);
        if ($selectedFabricId) {
            $fabricData = Fabric::find($selectedFabricId);
            if ($fabricData && floatval($value) < floatval($fabricData->threshold_price)) {
                // Show an error message for threshold price violation
                session()->flash('errorPrice.' . $index,
                    "🚨 The price for fabric '{$fabricData->title}' cannot be less than its threshold price of {$fabricData->threshold_price}.");
                return;
            }
        }

        // Sanitize and validate input value
        $formattedValue = preg_replace('/[^0-9.]/', '', $value);
        if (is_numeric($formattedValue)) {
            // If valid, format to two decimal places and update
            $this->items[$index]['price'] =$formattedValue;
            session()->forget('errorPrice.' . $index);
        } else {
            // Reset price and show error for invalid input
            $this->items[$index]['price'] = '';
            session()->flash('errorPrice.' . $index, '🚨 Please enter a valid price.');
        }

        $this->updateBillingAmount(); // Update billing after validation
    }

    // public function updateBillingAmount()
    // {
    //     // Recalculate the total billing amount
    //     $itemTotal = array_sum(array_column($this->items, 'price'));
    //     $airMail = floatval($this->air_mail);
    //     $this->billing_amount = $airMail > 0 ? ($itemTotal + $airMail) : $itemTotal;
    //     $this->paid_amount = $this->billing_amount;
    //     $this->GetRemainingAmount($this->paid_amount);
    //     return;
    // }

    public function updateBillingAmount()
    {
        $total = 0;

        foreach ($this->items as $item) {
            $quantity = isset($item['quantity']) ? floatval($item['quantity']) : 0;
            $price = isset($item['price']) ? floatval($item['price']) : 0;

            $total += $quantity * $price;
        }

        $airMail = floatval($this->air_mail ?? 0);
        $this->billing_amount = $total + $airMail;
        $this->paid_amount = $this->billing_amount;

        $this->GetRemainingAmount($this->paid_amount);
    }

    public function GetRemainingAmount($paid_amount)
    {
       // Remove leading zeros if present in the paid amount

        // Ensure the values are numeric before performing subtraction
        $billingAmount = floatval($this->billing_amount);
        $paidAmount = floatval($paid_amount);
        $paidAmount = $paidAmount;
        if ($billingAmount > 0) {
            if(empty($paid_amount)){
                $this->paid_amount = 0;
                $this->remaining_amount = $billingAmount;
                return;
            }
            $this->paid_amount = $paidAmount;
            $this->remaining_amount = $billingAmount - $this->paid_amount;

            // Check if the remaining amount is negative
            if ($this->remaining_amount < 0) {
                $this->remaining_amount = 0;
                $this->paid_amount = $this->billing_amount;
                session()->flash('errorAmount', '🚨 The paid amount exceeds the billing amount.');
            }
        } else {
            $this->paid_amount = 0;

            session()->flash('errorAmount', '🚨 Please add item amount first.');
        }
    }

    public function selectProduct($index, $name, $id)
    {
        // Set product details
        $this->items[$index]['searchproduct'] = $name;
        $this->items[$index]['product_id'] = $id;
        $this->items[$index]['products'] = [];

        $this->extra_measurement[$index] = Helper::ExtraRequiredMeasurement(trim($name));

        // Get the measurements available for the selected product
        $this->items[$index]['measurements'] = Measurement::where('product_id', $id)
                                                        ->where('status', 1)
                                                        ->orderBy('position', 'ASC')
                                                        ->get()
                                                        ->toArray();

        // Get previous measurements if user ordered this product before
        $this->populatePreviousOrderMeasurements($index, $id);
        // dd($this->populatePreviousOrderMeasurements($index, $id));
        // Clear measurement error message if it was previously set
        session()->forget('measurements_error.' . $index);

        // If no measurements exist, show an error message
        if (empty($this->items[$index]['measurements'])) {
            session()->flash('measurements_error.' . $index, '🚨 Oops! Measurement data not added for this product.');
        }
    }



    public function populatePreviousOrderMeasurements($index, $productId)
    {
        $previousOrderItem = OrderItem::where('product_id', $productId)
                                    ->whereHas('order', function ($query) {
                                        $query->where('customer_id', $this->customer_id); // Ensure the same customer
                                    })
                                    ->latest()
                                    ->first(); // Get the most recent order for the product

        if ($previousOrderItem) {
            // Get the measurements related to this previous order's product
            $previousMeasurements = OrderMeasurement::where('order_item_id', $previousOrderItem->id)->get();

            foreach ($previousMeasurements as $previousMeasurement) {
                // Query the Measurement model using the 'measurement_name' field from OrderMeasurement
                $measurement = Measurement::where('title', $previousMeasurement->measurement_name)->first();

                if ($measurement) {
                    // Auto-populate measurement values
                    $this->existing_measurements[] = [
                        // 'short_code' => trim($previousMeasurement->measurement_title_prefix),
                        // 'value' => trim($previousMeasurement->measurement_value)
                        'short_code' => $previousMeasurement->measurement_title_prefix,
                        'value' => $previousMeasurement->measurement_value
                    ];
                }
            }

            // Ensure values are appended into `items[$index]['get_measurements']`
            foreach ($this->items[$index]['measurements'] as &$measurement) {
                foreach ($this->existing_measurements as $existing) {
                    if ($existing['short_code'] == $measurement['short_code']) {
                        // Ensure `get_measurements` array exists
                        if (!isset($this->items[$index]['get_measurements'])) {
                            $this->items[$index]['get_measurements'] = [];
                        }
                        $this->items[$index]['get_measurements'][$measurement['id']]['value'] = $existing['value'];
                    }
                }
            }
        } else {
            // If no previous measurements exist, set empty values
            $this->items[$index]['existing_measurements'] = [];
        }
    }
    public function copyMeasurements($index){
        if ($index > 0) {
            if (!empty($this->items[$index]['copy_previous_measurements'])) {
                if (!empty($this->items[$index - 1]['get_measurements'])) {
                    $this->items[$index]['get_measurements'] = $this->items[$index - 1]['get_measurements'];
                }
            } else {
                $this->items[$index]['get_measurements'] = [];
            }
        }
    }

    public function updatedNewUploads($value, $index)
    {
        if (!isset($this->imageUploads[$index])) {
            $this->imageUploads[$index] = [];
        }

        // Merge new uploads
        $this->imageUploads[$index] = array_merge($this->imageUploads[$index], $value);

        // Clear temporary uploads so input can be used again
        $this->newUploads[$index] = [];
    }

    public function removeUploadedImage($index, $imageIndex){
        unset($this->imageUploads[$index][$imageIndex]);
        $this->imageUploads[$index] = array_values($this->imageUploads[$index]);
    }

    public function removeUploadedVoice($index, $voiceIndex)
    {
        if (isset($this->voiceUploads[$index][$voiceIndex])) {
            unset($this->voiceUploads[$index][$voiceIndex]);
            $this->voiceUploads[$index] = array_values($this->voiceUploads[$index]);
        }
    }


    public function save(OrderRepository $orderRepo)
    {
        // dd($this->all());
        $this->validate();
        DB::beginTransaction(); // Begin transaction
        try{
            // Calculate the total amount
            $total_product_amount = array_sum(array_column($this->items, 'price'));
            // Correct total based on price * quantity for each item
            $total_amount = collect($this->items)->reduce(function ($carry, $item) {
                return $carry + ((float) $item['price'] * (int) $item['quantity']);
            }, 0);

            $airMail = floatval($this->air_mail);
            $total_amount += $airMail;
          
            $this->remaining_amount = $total_amount - $this->paid_amount;

            // Retrieve user details
            $user = User::find($this->customer_id);
             // If customer does not exist, create a new user
            if (!$user) {
                $user = User::create([
                    'prefix' => $this->prefix,
                    'name' => $this->name,
                    'business_type' => $this->selectedBusinessType,
                    'company_name' => $this->company_name,
                    'employee_rank' => $this->employee_rank,
                    'email' => $this->email,
                    'dob' => $this->dob,
                    'country_id' => $this->country_id,
                    'country_code_phone' => $this->phone_code,
                    'phone' => $this->phone,
                   
                    'country_code_alt_1'  => $this->alt_phone_code_1,
                    'alternative_phone_number_1' => $this->alternative_phone_number_1,
                    'country_code_alt_2'  => $this->alt_phone_code_2,
                    'alternative_phone_number_2' => $this->alternative_phone_number_2,
                    'user_type' => 1, // Customer
                    'created_by' => auth()->guard('admin')->user()->id, // Customer
                ]);
             }
                // Store Billing Address for the new user
             $billingAddress = $user->address()->where('address_type', 1)->first();
             if (!$billingAddress) {
                 $user->address()->create([
                     'address_type' => 1, // Billing address
                     'state' => $this->billing_state,
                     'city' => $this->billing_city,
                     'address' => $this->billing_address,
                     'landmark' => $this->billing_landmark,
                     'country' => $this->billing_country,
                     'zip_code' => $this->billing_pin,
                 ]);
             }



            if ($user) {

                $user->update([
                    'prefix' => $this->prefix,
                    'name' => $this->name,
                    'business_type' => $this->selectedBusinessType,
                    'company_name' => $this->company_name,
                    'employee_rank' => $this->employee_rank,
                    'email' => $this->email,
                    'dob' => $this->dob,
                    'country_id' => $this->country_id,
                    'country_code_phone' => $this->phone_code,
                    'phone' => $this->phone,
                    
                    'country_code_alt_1'  => $this->alt_phone_code_1,
                    'alternative_phone_number_1' => $this->alternative_phone_number_1,
                    'country_code_alt_2'  => $this->alt_phone_code_2,
                    'alternative_phone_number_2' => $this->alternative_phone_number_2,
                    'user_type' => 1, // Customer
                ]);
                // Retrieve existing billing address
                $existingBillingAddress = $user->address()->where('address_type', 1)->first();
                // dd($existingBillingAddress);
                // Check and update billing address if needed
                $billingAddressUpdated = false;
                if ($existingBillingAddress) {
                    if (
                        $existingBillingAddress->state !== $this->billing_state ||
                        $existingBillingAddress->city !== $this->billing_city ||
                        $existingBillingAddress->address !== $this->billing_address
                    ) {
                        $existingBillingAddress->update([
                            'state' => $this->billing_state,
                            'city' => $this->billing_city,
                            'address' => $this->billing_address,
                            'landmark' => $this->billing_landmark,
                            'country' => $this->billing_country,
                            'zip_code' => $this->billing_pin,
                        ]);
                        $billingAddressUpdated = true;
                    }
                } else {
                    // Create new billing address if none exists
                    $user->address()->create([
                        'address_type' => 1, // Billing address
                        'state' => $this->billing_state,
                        'city' => $this->billing_city,
                        'address' => $this->billing_address,
                        'landmark' => $this->billing_landmark,
                        'country' => $this->billing_country,
                        'zip_code' => $this->billing_pin,
                    ]);
                    $billingAddressUpdated = true;
                }


            }


            if (!empty($this->order_number)) {
                $order_number = $this->order_number;
            } else {
                $invoiceData = Helper::generateInvoiceBill();
                $order_number =  $invoiceData['number'];
            }

            $customer_image = $this->customer_image ? Helper::handleFileUpload($this->customer_image,"client_image") : null;

            // Create the order
            $order = new Order();
            $order->order_number = $order_number;
            $order->customer_id = $user->id;
            $order->prefix = $this->prefix;
            $order->customer_name = $this->name;
            $order->customer_email = $this->email;
            $order->customer_image = $customer_image;
            $order->billing_address = $this->billing_address . ', ' . $this->billing_landmark . ', ' . $this->billing_city . ', ' . $this->billing_state . ', ' . $this->billing_country . ' - ' . $this->billing_pin;


            $order->total_product_amount = $total_product_amount;
            $order->air_mail = $airMail;
            $order->total_amount = $total_amount;
            $order->last_payment_date = date('Y-m-d H:i:s');
            $order->created_by = (int) $this->salesman; // Explicitly cast to integer
            // for team-lead id
            $loggedInAdmin = auth()->guard('admin')->user();
            $order->team_lead_id = $loggedInAdmin->parent_id ?? null;
            // if ($loggedInAdmin->designation == 4) { // TL
            // // Count pending items: Hold items OR Process items not TL approved
            // $pendingItemsCount = $order->items()
            //     ->where(function ($q) {
            //         $q->where('status', 'Hold')
            //         ->orWhere(function ($q2) {
            //             $q2->where('status', 'Process')
            //                 ->where(function ($q3) {
            //                     $q3->whereNull('tl_status')
            //                         ->orWhere('tl_status', '!=', 'Approved');
            //                 });
            //         });
            //     })
            //     ->count();

            // $status = $pendingItemsCount == 0 ? 'Fully Approved By TL' : 'Partial Approved By TL';
            // $order->status = $status; 
            // }else {
            //     $order->status = 'Approval Pending'; // default if not TL or Admin
            // }
           

            // dd($order);
            $order->save();
            
            $update_bill_book = SalesmanBilling::where('id',$this->bill_id)->first();
            if($update_bill_book){
                $update_bill_book->no_of_used = $update_bill_book->no_of_used +1;
                $update_bill_book->save();
            }

            // Save order items and measurements
            foreach ($this->items as $k => $item) {
                if($item['collection']==1 && empty($item['page_item'])){
                    $page = Page::where('catalogue_id', $item['selectedCatalogue'])->where('page_number',$item['page_number'])->first();
                    if($page){
                        $exist_pages = CataloguePageItem::where('page_id', $page->id)->get();
                        if(count($exist_pages)>0 && empty($item['page_item'])){
                            $this->addError("items.$k.page_item", "Please select a page item for this page.");
                            return false;
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
                // Only save page_item if valid 
                $validItems = $this->pageItems[$k] ?? collect();
                $allowedPageItems = $validItems->pluck('catalog_item')->toArray();
             if (in_array($item['page_item'] ?? null, $allowedPageItems)) {
                $orderItem->cat_page_item = $item['page_item'] ;
             }else{
                $orderItem->cat_page_item = null;
             }
                $orderItem->product_id = $item['product_id'];
                $orderItem->collection = $collection_data ? $collection_data->id : "";
                $orderItem->category = $category_data ? $category_data->id : "";
                
                $orderItem->product_name = $item['searchproduct'];
                $orderItem->remarks  = $item['remarks'] ?? null;
                $orderItem->status  = $item['item_status'] ?? null;
                $orderItem->piece_price = $item['price'];
                // $orderItem->quantity = $item['quantity'];
                $orderItem->quantity = ($item['collection'] == 1) ? 1 : $item['quantity'];
                $orderItem->fittings  = ($item['collection'] == 1) ? $item['fitting'] : null;

                $orderItem->priority_level = in_array($loggedInAdmin->designation, [1, 4]) 
                    ? ($item['priority'] ?? null) 
                    : null;
                $orderItem->expected_delivery_date  = $item['expected_delivery_date'];
                $itemPrice = floatval($item['price']);
                $orderItem->total_price = $itemPrice * $orderItem->quantity;
                $orderItem->fabrics = $fabric_data ? $fabric_data->id : "";
               

                if ($orderItem->status === 'Process') {
                    if (in_array($loggedInAdmin->designation,[1,12])) {
                        // Admin is creating the order
                        $orderItem->tl_status = 'Approved';
                        $orderItem->admin_status = 'Approved';
                        $orderItem->assigned_team = 'production';
                    } elseif ($loggedInAdmin->designation == 4) {
                        // Team Lead is creating the order
                        $orderItem->tl_status = 'Approved';
                        $orderItem->admin_status = 'Pending';
                    } else {
                        // For others
                        $orderItem->tl_status = 'Pending';
                        $orderItem->admin_status = 'Pending';
                    }
                } else {
                    // If status is not Process
                    $orderItem->tl_status = 'Pending';
                    $orderItem->admin_status = 'Pending';
                }

                // Extra Fields
                if ($item['collection'] == 1) {
                    $extra = $this->extra_measurement[$k] ?? null;

                    if ($extra == 'mens_jacket_suit') {
                        $orderItem->vents = $item['vents'] ?? null;
                        $orderItem->shoulder_type = $item['shoulder_type'] ?? null;
                    } elseif ($extra == 'ladies_jacket_suit') {
                        $orderItem->shoulder_type = $item['shoulder_type'] ?? null;
                        $orderItem->vents_required = $item['vents_required'] ?? null;
                        if ($orderItem->vents_required) {
                            $orderItem->vents_count = $item['vents_count'] ?? null;
                        }
                    } elseif ($extra == 'trouser') {
                        $orderItem->fold_cuff_required   = $item['fold_cuff_required'] ?? null;
                        if ($orderItem->fold_cuff_required=="Yes") {
                            $orderItem->fold_cuff_size  = $item['fold_cuff_size'] ?? null;
                        }else{
                            $orderItem->fold_cuff_size  = null;
                        }
                        $orderItem->pleats_required      = $item['pleats_required'] ?? null;
                        if ($orderItem->pleats_required=="Yes") {
                            $orderItem->pleats_count = $item['pleats_count'] ?? null;
                        }else{
                            $orderItem->pleats_count = null;
                        }
                        $orderItem->back_pocket_required = $item['back_pocket_required'] ?? null;
                        if ($orderItem->back_pocket_required=="Yes") {
                            $orderItem->back_pocket_count = $item['back_pocket_count'] ?? null;
                        }else{
                            $orderItem->back_pocket_count = null;
                        }
                        $orderItem->adjustable_belt      = $item['adjustable_belt'] ?? null;
                        $orderItem->suspender_button     = $item['suspender_button'] ?? null;
                        $orderItem->trouser_position     = $item['trouser_position'] ?? null;
                    }elseif ($extra == 'shirt') {
                        $orderItem->sleeves       = $item['sleeves'] ?? null;          // L/S or H/S
                        $orderItem->collar        = $item['collar'] ?? null;           // Normal or Other
                        $orderItem->collar_style  = $item['collar_style'] ?? null;     // If "Other"
                        $orderItem->pocket        = $item['pocket'] ?? null;           // With / Without
                        $orderItem->cuffs         = $item['cuffs'] ?? null;            // Regular / French / Other
                        $orderItem->cuff_style    = $item['cuff_style'] ?? null;       // If "Other"
                    }
                    if ($extra === 'ladies_jacket_suit' || $extra === 'shirt' || $extra === 'mens_jacket_suit') {
                        $orderItem->client_name_required = $item['client_name_required'] ?? null;
                        if ($orderItem->client_name_required=="Yes") {
                            $orderItem->client_name_place = $item['client_name_place'] ?? null;
                        }else{
                            $orderItem->client_name_place = null;
                        }
                    }
                }
                $orderItem->save();
                // When Tl Logged in and create the order 
                    if ($loggedInAdmin->designation == 4) {
                        $totalItems = count($this->items);
                        $approvedItems = $order->items()
                        ->where('status', 'Process')
                        ->where('tl_status', 'Approved')
                         ->count();

                    if ($approvedItems == $totalItems) {
                        $order->status = 'Fully Approved By TL';
                    } elseif ($approvedItems > 0) {
                        $order->status = 'Partial Approved By TL';
                    } else {
                        $order->status = 'Approval Pending';
                    }
                     $order->save();
                }
                 // upload multiple catalogue images
                if(!empty($this->imageUploads[$k])){
                    foreach ($this->imageUploads[$k] as $images) {
                        $path = $images->store('uploads/order_item_catalogue_images', 'public');
                        OrderItemCatalogueImage::create([
                            'order_item_id' => $orderItem->id,
                            'image_path' => $path,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }

                if(!empty($this->voiceUploads[$k])){
                    foreach ($this->voiceUploads[$k] as $voice) {
                        $audioPath = $voice->store('uploads/order_item_voice_messages', 'public');
                        OrderItemVoiceMessage::create([
                            'order_item_id' => $orderItem->id,
                            'voices_path' => $audioPath,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }



                if (isset($item['get_measurements']) && count($item['get_measurements']) > 0) {
                    $get_all_measurment_field = [];
                    $get_all_field_measurment_id = [];

                    foreach ($item['get_measurements'] as $mindex => $measurement) {
                        if (!isset($measurement['value'])) {
                            session()->flash('measurements_error.' . $k, '🚨 Measurement value is missing.');
                            return;
                        }

                        $value = trim($measurement['value']);

                        // if ($value === '' || !is_numeric($value) || floatval($value) < 1) {
                        //     $measurement_data = Measurement::find($mindex);
                        //     $title = $measurement_data->title ?? 'Unknown';
                        //     session()->flash('measurements_error.' . $k, '🚨 Oops! Measurement "' . $title . '" must be numeric and greater than 0.');
                        //     return;
                        // }

                        // Now store only validated value
                        $measurement_data = Measurement::find($mindex);
                        if (!$measurement_data) continue;

                        $orderMeasurement = new OrderMeasurement();
                        $orderMeasurement->order_item_id = $orderItem->id;
                        $orderMeasurement->measurement_name = $measurement_data->title;
                        $orderMeasurement->measurement_title_prefix = $measurement_data->short_code;
                        $orderMeasurement->measurement_value = $value; // Use validated value
                        $orderMeasurement->save();
                    }


                    $missing_measurements = array_diff($get_all_measurment_field, $get_all_field_measurment_id);

                    if (!empty($missing_measurements)) {
                        session()->flash('measurements_error.' . $k, '🚨 Oops! All measurement data should be mandatory.');
                        return;
                    }

                }
            }

            // Store WhatsApp details if the flags are set
                if ($this->isWhatsappPhone) {
                    $existingRecord = UserWhatsapp::where('whatsapp_number', $this->phone)
                                                    ->where('user_id', '!=', $user->id)
                                                    ->exists();
                    if (!$existingRecord) {
                        UserWhatsapp::updateOrCreate(
                            ['user_id' => $user->id,'whatsapp_number' => $this->phone],
                            [ 'country_code' => $this->phone_code, 'created_at' => now(),'updated_at' => now()]
                        );
                    }
                }


                if ($this->isWhatsappAlt1) {
                    $existingRecord = UserWhatsapp::where('whatsapp_number', $this->alternative_phone_number_1)
                                                    ->where('user_id', '!=', $user->id)
                                                    ->exists();
                    if(!$existingRecord){
                        UserWhatsapp::updateOrCreate([
                             'user_id' => $user->id,
                             'whatsapp_number' => $this->alternative_phone_number_1,
                            ],
                            [
                            'country_code' => $this->alt_phone_code_1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }


                if ($this->isWhatsappAlt2) {
                    $existingRecord = UserWhatsapp::where('whatsapp_number', $this->alternative_phone_number_2)
                                                    ->where('user_id', '!=', $user->id)
                                                    ->exists();

                    if(!$existingRecord){
                        UserWhatsapp::updateOrCreate([
                            'user_id' => $user->id,
                            'whatsapp_number' => $this->alternative_phone_number_2],
                            ['country_code' => $this->alt_phone_code_2,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Auto Approve for Admin And Store Person
                 $staff = User::find($this->salesman);
                if ($staff && (in_array($staff->designation,[1,12]))) {
                    $orderRepo->approveOrder($order->id, $staff->id);
                }


            DB::commit();


            session()->flash('success', 'Order has been generated successfully.');
            return redirect()->route('admin.order.index');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            //\Log::error('Error saving order: ' . $e->getMessage());
            session()->flash('error', '🚨 Something went wrong. The operation has been rolled back.');
        }
    }


    public function resetForm()
    {
        // Reset all the form properties
        $this->reset([
            'name',
            'company_name',
            'employee_rank',
            'email',
            'dob',
            'phone',

        ]);
    }
    public function updateMobileLengths()
    {
        $this->mobileLengthPhone = Country::where('country_code', $this->phone_code)->value('mobile_length') ?? '';
        $this->mobileLengthAlt1 = Country::where('country_code', $this->alt_phone_code_1)->value('mobile_length') ?? '';
        $this->mobileLengthAlt2 = Country::where('country_code', $this->alt_phone_code_2)->value('mobile_length') ?? '';
    }
    public function CountryCodeSet($selector, $Code, $number = null)
    {
        $mobile_length = Country::where('country_code', $Code)->value('mobile_length') ?? '';

        // Dispatch for maxlength
        $this->dispatch('update_input_max_length', [
            'id' => $selector,
            'mobile_length' => $mobile_length
        ]);

        // Dispatch for setting code + number
        $this->dispatch('update_input_code_number', [
            'id' => $selector,
            'dialCode' => $Code,
            'number' => $number
        ]);
        $this->mobileLengthPhone = $mobile_length;
    }

    public function selectCustomer($customerId)
    {
        $this->resetForm(); // Reset form to default values

        $customer = User::find($customerId);
        if ($customer) {
            // Populate customer details
            $this->customer_id = $customer->id;
            $this->prefix = $customer->prefix;
            $this->name = $customer->name;
            $this->company_name = $customer->company_name;
            $this->employee_rank = $customer->employee_rank;
            $this->email = $customer->email;
            $this->dob = $customer->dob;
            $this->phone = $customer->phone;
            $this->phone_code = $customer->country_code_phone;
            $this->alt_phone_code_1 = $customer->country_code_alt_1;
            $this->alternative_phone_number_1 = $customer->alternative_phone_number_1;
            $this->alt_phone_code_2 = $customer->country_code_alt_2;
            $this->alternative_phone_number_2 = $customer->alternative_phone_number_2;

            if($this->phone_code){
                $this->CountryCodeSet('#mobile', $this->phone_code, $this->phone);
            }
            if($this->alt_phone_code_1){
                $this->CountryCodeSet('#alt_phone_1', $this->alt_phone_code_1,$this->alternative_phone_number_1);
            }
            if($this->alt_phone_code_2){
                $this->CountryCodeSet('#alt_phone_2', $this->alt_phone_code_2,$this->alternative_phone_number_2);
            }
            
            $this->updateMobileLengths();
            // Fetch billing address (address_type = 1)
            $billingAddress = $customer->address()->where('address_type', 1)->first();
            $this->populateAddress('billing', $billingAddress);

            // Fetch shipping address (address_type = 2)
            $shippingAddress = $customer->address()->where('address_type', 2)->first();
            $this->populateAddress('shipping', $shippingAddress);
        }

        // Clear search results after selection
        $this->searchResults = [];
        $this->searchTerm = '';
    }



    public function TabChange($value)
    {
        // dd($this->all());
        // dd($this->errorClass, $this->errorMessage);

        // Initialize or reset error classes and messages
        $this->errorClass = [];
        $this->errorMessage = [];
        if ($value== 1) {
            $this->activeTab = $value;
        }
        if ($value > 1) {

            // Validate Business type
            if(empty($this->selectedBusinessType)){
                $this->errorClass['selectedBusinessType'] = 'border-danger';
                $this->errorMessage['selectedBusinessType'] = 'Please select your business type';

            }else{
                $this->errorClass['selectedBusinessType'] = null;
                $this->errorMessage['selectedBusinessType'] = null;
            }



            // validate Salesman
            if(empty($this->salesman)){
                $this->errorClass['salesman'] = 'border-danger';
                $this->errorMessage['salesman'] = 'Please select a salesman first';
            }else{
                $this->errorClass['salesman']  = null;
                $this->errorMessage['salesman']  = null;
            }

            // validate order number
            if(($this->order_number == 000)){
                $this->errorClass['order_number'] = 'border-danger';
                $this->errorMessage['order_number'] = 'Please Choose a another salesman';
            }else{
                $this->errorClass['order_number']  = null;
                $this->errorMessage['order_number']  = null;
            }

            // Validate prefix
            if (empty($this->prefix)) {
                $this->errorClass['prefix'] = 'border-danger';
                $this->errorMessage['prefix'] = 'Please choose a prefix';
            } else {
                $this->errorClass['prefix'] = null;
                $this->errorMessage['prefix'] = null;
            }

            //validate name
            if (empty($this->name)) {
                $this->errorClass['name'] = 'border-danger';
                $this->errorMessage['name'] = 'Please enter customer name';
            } else {
                $this->errorClass['name'] = null;
                $this->errorMessage['name'] = null;
            }

            if ($this->email) {
                if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                    $this->errorMessage['email'] = 'Please enter a valid email address.';
                    $this->errorClass['email'] = 'is-invalid';
                }  else {
                    $this->errorClass['email'] = null;
                    $this->errorMessage['email'] = null;
                }
            }


            // validate customer image
            if (empty($this->customer_image)) {
                $this->errorClass['customer_image'] = 'border-danger';
                $this->errorMessage['customer_image'] = 'Please choose customer image';
            } else {
                $this->errorClass['customer_image'] = null;
                $this->errorMessage['customer_image'] = null;
            }


           // Validate Phone Number
            if (empty($this->phone)) {
                $this->errorClass['phone'] = 'border-danger';
                $this->errorMessage['phone'] = 'Please enter customer phone number';
            } elseif (!preg_match('/^\d{'. $this->mobileLengthPhone .'}$/', $this->phone)) {
                $this->errorClass['phone'] = 'border-danger';
                $this->errorMessage['phone'] = "Phone number must be exactly ".$this->mobileLengthPhone." digits";
            } else {
                $this->errorClass['phone'] = null;
                $this->errorMessage['phone'] = null;
            }



            // Validate Alternative Phone Number 1
            if (!empty($this->alternative_phone_number_1)) {
                if (!preg_match('/^\d{'. $this->mobileLengthAlt1 .'}$/', $this->alternative_phone_number_1)) {
                    $this->errorClass['alternative_phone_number_1'] = 'border-danger';
                    $this->errorMessage['alternative_phone_number_1'] = 'Alternative number 1 must be exactly ' . $this->mobileLengthAlt1 . ' digits';
                } else {
                    $this->errorClass['alternative_phone_number_1'] = null;
                    $this->errorMessage['alternative_phone_number_1'] = null;
                }
            }

            // Validate Alternative Phone Number 2
            if (!empty($this->alternative_phone_number_2)) {
                if (!preg_match('/^\d{'. $this->mobileLengthAlt2 .'}$/', $this->alternative_phone_number_2)) {
                    $this->errorClass['alternative_phone_number_2'] = 'border-danger';
                    $this->errorMessage['alternative_phone_number_2'] = 'Alternative number 2 must be exactly ' . $this->mobileLengthAlt2 . ' digits';
                } else {
                    $this->errorClass['alternative_phone_number_2'] = null;
                    $this->errorMessage['alternative_phone_number_2'] = null;
                }
            }


            // Validate Billing Information
            if (empty($this->billing_address)) {
                $this->errorClass['billing_address'] = 'border-danger';
                $this->errorMessage['billing_address'] = 'Please enter address';
            } else {
                $this->errorClass['billing_address'] = null;
                $this->errorMessage['billing_address'] = null;
            }

            if (empty($this->billing_city)) {
                $this->errorClass['billing_city'] = 'border-danger';
                $this->errorMessage['billing_city'] = 'Please enter city';
            } else {
                $this->errorClass['billing_city'] = null;
                $this->errorMessage['billing_city'] = null;
            }



            if (empty($this->billing_country)) {
                $this->errorClass['billing_country'] = 'border-danger';
                $this->errorMessage['billing_country'] = 'Please enter country';
            } else {
                $this->errorClass['billing_country'] = null;
                $this->errorMessage['billing_country'] = null;
            }



            // Check if both errorClass and errorMessage arrays are empty

            $errorClassNull = empty(array_filter($this->errorClass, function($val) {
                return !is_null($val);
            }));
            // If all values are null, set activeTab to the value passed
            if ($errorClassNull) {
                $this->activeTab = $value;
            }
             // Return the error classes and messages
            return [$this->errorClass, $this->errorMessage];
        }

    }
    private function populateAddress($type, $address)
    {
        // dd($address);
        if ($address) {
            $this->{$type . '_address'} = $address->address;
            $this->{$type . '_landmark'} = $address->landmark;
            $this->{$type . '_city'} = $address->city;
            $this->{$type . '_state'} = $address->state;
            $this->{$type . '_country'} = $address->country;
            $this->{$type . '_pin'} = $address->zip_code;
        } else {
            $this->{$type . '_address'} = null;
            $this->{$type . '_landmark'} = null;
            $this->{$type . '_city'} = null;
            $this->{$type . '_state'} = null;
            $this->{$type . '_country'} = null;
            $this->{$type . '_pin'} = null;
        }
    }


    public function render()
    {
        $this->dispatch('error_message');
        return view('livewire.order.order-new', [
            'categories' => $this->categories,
        ]);
    }

}
