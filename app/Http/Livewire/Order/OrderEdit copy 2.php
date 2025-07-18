<?php

namespace App\Http\Livewire\Order;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Catalogue;
use App\Models\Measurement;
use App\Models\OrderMeasurement;
use App\Models\Fabric;
use App\Models\Ledger;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\SalesmanBilling;
use App\Models\Country;
use App\Models\BusinessType;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\UserWhatsapp;

class OrderEdit extends Component
{
    public $searchTerm = '';
    public $searchResults = [];
    public $errorClass = [];
    // public $collectionsType = [];
    public $collections = [];
    public $errorMessage = [];
    public $activeTab = 1;
    public $items = [];
   
    public $FetchProduct = 1;
    public $maxPages = [];
    public $salesman;
    
    public $customers = null;
    public $orders;
    public $is_wa_same, $prefix, $name, $company_name,$employee_rank, $email, $dob, $customer_id, $whatsapp_no, $phone ,$alternative_phone_number_1, $alternative_phone_number_2,
    $selectedCountryPhone, $selectedCountryWhatsapp, $selectedCountryAlt1 , $selectedCountryAlt2 ,$mobileLengthPhone, $mobileLengthWhatsapp, $mobileLengthAlt1, $mobileLengthAlt2,
    $countries,
     $isWhatsappPhone, $isWhatsappAlt1 , $isWhatsappAlt2
    ;
    
    public $order_number, $billing_address,$billing_landmark,$billing_city,$billing_state,$billing_country,$billing_pin;

    public $is_billing_shipping_same;

    public $shipping_address,$shipping_landmark,$shipping_city,$shipping_state,$shipping_country,$shipping_pin;

    //  product 
    public $categories,$subCategories = [], $products = [], $measurements = [];
    public $selectedCategory = null, $selectedSubCategory = null,$searchproduct, $product_id =null,$collection;
    public $paid_amount = 0;
    public $billing_amount = 0;
    public $remaining_amount = 0;
    public $payment_mode = null;
    public $catalogues = [];

    // salesmanBill
    public $salesmanBill;
    public $mobileLength;
    public $selectedCountryId;
    public $filteredCountries;
    public $Business_type;
    public $selectedBusinessType;
    public $search;
    public $country_code;
    public $country_id;
    
    public function mount($id)
    {
        $this->orders = Order::with(['items.measurements'])->findOrFail($id); // Fetch the order by ID
    //    dd($this->orders->customer->id);
        if ($this->orders) {
            $this->order_number = $this->orders->order_number;
            $this->customer_id = $this->orders->customer_id;
            $this->name = $this->orders->customer_name;
            $this->email = $this->orders->customer_email;
            $this->dob = $this->orders->customer->dob;
            $this->billing_address = $this->orders->billing_address;
            $this->shipping_address = $this->orders->shipping_address;
            $this->is_billing_shipping_same = ($this->orders->billing_address == $this->orders->shipping_address);
            $this->phone = $this->orders->customer->phone;
            $this->alternative_phone_number_1 = $this->orders->customer->alternative_phone_number_1;
            $this->alternative_phone_number_2 = $this->orders->customer->alternative_phone_number_2;
            $this->countries = Country::where('status',1)->get();
            $this->selectedCountryPhone =  $this->orders->customer->country_code_phone;
            $this->selectedCountryWhatsapp =  $this->orders->customer->country_code_whatsapp;
            $this->selectedCountryAlt1 =  $this->orders->customer->country_code_alt_1;
            $this->selectedCountryAlt2 =  $this->orders->customer->country_code_alt_2;

            // Set mobile lengths based on selected countries
            $this->mobileLengthPhone = Country::where('country_code', $this->selectedCountryPhone)->value('mobile_length') ?? '';
            $this->mobileLengthWhatsapp = Country::where('country_code', $this->selectedCountryWhatsapp)->value('mobile_length') ?? '';
            $this->mobileLengthAlt1 = Country::where('country_code', $this->selectedCountryAlt1)->value('mobile_length') ?? '';
            $this->mobileLengthAlt2 = Country::where('country_code', $this->selectedCountryAlt2)->value('mobile_length') ?? '';
            
            $this->isWhatsappPhone = UserWhatsapp::where('user_id',$this->orders->customer->id)->where('whatsapp_number',$this->phone)->exists();
            $this->isWhatsappAlt1 = UserWhatsapp::where('user_id',$this->orders->customer->id)->where('whatsapp_number',$this->alternative_phone_number_1)->exists();
          

            $this->isWhatsappAlt2 = UserWhatsapp::where('user_id',$this->orders->customer->id)->where('whatsapp_number',$this->alternative_phone_number_2)->exists();
           
            $this->catalogues = Catalogue::with('catalogueTitle')->get()->toArray();
           
            $this->items = $this->orders->items->map(function ($item) {
               
                $selected_titles = OrderMeasurement::where('order_item_id', $item->id)->pluck('measurement_name')->toArray();
                $selected_values = OrderMeasurement::where('order_item_id', $item->id)->pluck('measurement_value')->toArray();
                $fabrics = Fabric::join('product_fabrics', 'product_fabrics.fabric_id', '=', 'fabrics.id')
                ->where('product_fabrics.product_id', $item->product_id)
                ->select('fabrics.id', 'fabrics.title')
                ->get();
        
                // Get the selected fabric object if exists
                // $selectedFabric = $fabrics->firstWhere('id', $item->fabrics);
                $selectedFabric = collect($fabrics)->firstWhere('id', $item->fabrics);

                // Map measurements with selected values
                $measurements = Measurement::where('product_id', $item->product_id)->orderBy('position','ASC')->get()
                    ->map(function ($measurement) use ($selected_titles, $selected_values) {
                        $index = array_search($measurement->title, $selected_titles); // Check if title exists in selected titles
                        return [
                            'id' => $measurement->id,
                            'title' => $measurement->title,
                            'short_code' => $measurement->short_code,
                            'value' => $index !== false ? $selected_values[$index] : '', // Assign value if title is in selected titles
                        ];
                });
                //  dd($item->catalogue_id);
                return [
                    'order_item_id' => $item->id, 
                    'product_id' => $item->product_id,
                    'searchproduct' => $item->product_name,
                    'price' => round($item->total_price),
                    'selected_collection' => $item->collection,
                    'collection' => Collection::orderBy('title', 'ASC')->whereIn('id',[1,2])->get(),
                    'selected_category' => $item->category,
                    'categories' =>Category::orderBy('title', 'ASC')->where('collection_id', $item->collection)->get(),
                    'searchTerm' => optional($selectedFabric)->title, // Set default search value
                    'searchResults' => [],

                    'selected_fabric' => $item->fabrics,
                    'fabrics' => $fabrics,
                    'searchTerm' => optional($selectedFabric)->title ?? '',

                    'searchResults' => [],
                    'selected_measurements_title' => $selected_titles,
                    'selected_measurements_value' => $selected_values,
                    'measurements' => $measurements,
                    'catalogues' => $item->collection == 1 ? $this->catalogues : [],
                    'selectedCatalogue' => $item->catalogue_id,
                    'page_number' => $item->cat_page_number,
                    'pageItems' => $item->cat_page_item,
                ];
            })->toArray();
        }
        // Split the address and assign to the properties
        $billingAddress = explode(',', $this->orders->billing_address);

        // Assuming the address is saved in the format: street, landmark, city, state, country - pin
        if (count($billingAddress) >= 5) {
            $this->billing_address = trim($billingAddress[0]); // Street Address
            $this->billing_landmark = trim($billingAddress[1]); // Landmark
            $this->billing_city = trim($billingAddress[2]); // City
            $this->billing_state = trim($billingAddress[3]); // State
            $this->billing_country = trim($billingAddress[4]); // Country and PIN code

            // Extract pin code from the country field (assuming it's at the end)
            $countryAndPin = explode('-', $this->billing_country);
            if (count($countryAndPin) > 1) {
                $this->billing_country = trim($countryAndPin[0]);
                $this->billing_pin = trim($countryAndPin[1]);
            }
        }

        // Split the address and assign to the properties
        $shippingAddress = explode(',', $this->orders->shipping_address);

        // Assuming the address is saved in the format: street, landmark, city, state, country - pin
        if (count($shippingAddress) >= 5) {
            $this->shipping_address = trim($shippingAddress[0]); // Street Address
            $this->shipping_landmark = trim($shippingAddress[1]); // Landmark
            $this->shipping_city = trim($shippingAddress[2]); // City
            $this->shipping_state = trim($shippingAddress[3]); // State
            $this->shipping_country = trim($shippingAddress[4]); // Country and PIN code

            // Extract pin code from the country field (assuming it's at the end)
            $countryAndPin = explode('-', $this->shipping_country);
            if (count($countryAndPin) > 1) {
                $this->shipping_country = trim($countryAndPin[0]);
                $this->shipping_pin = trim($countryAndPin[1]);
            }
        }
        $this->Business_type = BusinessType::all();
        $this->selectedCountryId = $this->orders->customer->country_id;
        $this->search = Country::where('id',$this->orders->customer->country_id)->pluck('title');
       
       
        $this->selectedBusinessType = $this->orders->customer->business_type;
        $this->customer_id = $this->orders->customer_id;
        $this->prefix = $this->orders->prefix;
        $this->name = $this->orders->customer_name;
        $this->company_name = $this->orders->customer->company_name;
        $this->employee_rank = $this->orders->customer->employee_rank;
        $this->email = $this->orders->customer_email;
        $this->dob = $this->orders->customer->dob;
        $this->whatsapp_no = $this->orders->customer->whatsapp_no;

        $this->customers = User::where('user_type', 1)->where('status', 1)->orderBy('name', 'ASC')->get();
        $this->categories = Category::where('status', 1)->orderBy('title', 'ASC')->get();
        $this->collections = Collection::orderBy('title', 'ASC')->whereIn('id',[1,2])->get();

        $this->paid_amount = $this->orders->paid_amount;
        $this->billing_amount =  $this->orders->total_amount;
        $this->remaining_amount =  $this->orders->remaining_amount;
        $this->payment_mode = $this->orders->payment_mode;
       
        $this->salesmanBill = SalesmanBilling::where('salesman_id',auth()->guard('admin')->user()->id)->first();


        foreach ($this->items as $index => $item) {
            $this->items[$index]['copy_previous_measurements'] = false; // Ensure checkbox is not selected
        }
    }
    public function GetCountryDetails($mobileLength, $field)
    {
        switch($field){
            case 'phone':
                $this->mobileLengthPhone = $mobileLength;
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

    public function addItem()
    {
        $this->items = array_values($this->items); 
        $this->items[] = [
           
            'selected_collection' => '',
            'selected_category' => '',
            'collection' =>  Collection::orderBy('title', 'ASC')->whereIn('id',[1,2])->get(),
            'categories' => [],
            'searchproduct' => '',
            'selected_fabric' => null,
            'measurements' => [],
            'products' => [],
            'product_id' => null,
            'price' => '', // Ensure price is initialized to an empty string, not null.
            'fabrics' => [],
            // 'selected_fabric' => '',
            'catalogues' => [],
            'selectedCatalogue' => '',
            'page_number' => '',
        ];
        // Ensure catalogues and max pages are initialized
   
    }

    public function FindCountry($term){
        $this->search = $term;
        if(!empty($this->search)){
            $this->filteredCountries = Country::where('title', 'LIKE', '%' . $this->search . '%')->get();
        }else{
            $this->filteredCountries = [];
        }
    }

    public function selectCountry($countryId){
        $country = Country::find($countryId);
        if($country){
            $this->selectedCountryId = $country->id;
            $this->search = $country->title;
            $this->country_code = $country->country_code;
            $this->mobileLength = $country->mobile_length;
        }

        $this->filteredCountries = [];
    }

    public function searchFabrics($index)
    {
        // Ensure product_id exists for the given index
        if (!isset($this->items[$index]['product_id'])) {
            return;
        }
    
        $productId = $this->items[$index]['product_id'];
    
        // Ensure searchTerm exists for this index
        $searchTerm = $this->items[$index]['searchTerm'] ?? '';
    
        if (!empty($searchTerm)) {
            $this->items[$index]['searchResults'] = Fabric::join('product_fabrics', 'fabrics.id', '=', 'product_fabrics.fabric_id')
                ->where('product_fabrics.product_id', $productId)
                ->where('fabrics.status', 1)
                ->where('fabrics.title', 'LIKE', "%{$searchTerm}%")
                ->select('fabrics.id', 'fabrics.title')
                ->distinct()
                ->limit(10)
                ->get();
                // dd($this->items[$index]['searchResults']);
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
    

    protected $rules = [
        'items.*.selected_collection' => 'required',
        'items.*.selected_category' => 'required',
        'items.*.searchproduct' => 'required',
       
        'items.*.price' => 'required|numeric|min:1',  // Ensuring that price is a valid number (and greater than or equal to 0).
       
        'items.*.measurements.*' => 'nullable',
        'items.*.selectedCatalogue' => 'required_if:items.*.selected_collection,1', 
        'items.*.page_number' => 'required_if:items.*.selected_collection,1'
    ];

    protected function messages(){
        return [
             'items.*.selected_category.required' => 'Please select a category for the item.',
             'items.*.searchproduct.required' => 'Please select a product for the item.',
             'items.*.selectedCatalogue.required_if' => 'Please select a catalogue for the item.',
             'items.*.page_number.required' => 'Please select a page for the item.',
             'items.*.price.required'  => 'Please enter a price for the item.',
             'items.*.selected_collection.required' =>  'Please enter a collection for the item.',
        ];
    }

    public function removeItem($index)
    {
        $itemId = $this->items[$index]['order_item_id'] ?? null; 
        if ($itemId) {
            // Actually remove from the DB (or set a 'deleted' flag)
            $orderItem = OrderItem::find($itemId);
            if ($orderItem) {
                $orderItem->delete();
            }
        }
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->updateBillingAmount();  // Update billing amount after checking price
    }

    public function updateBillingAmount()
    {
        // Recalculate the total billing amount
        $this->billing_amount = array_sum(array_column($this->items, 'price'));
        $this->paid_amount = $this->orders->paid_amount;
        $this->GetRemainingAmount($this->paid_amount);
        return;
    }

    public function GetRemainingAmount($paid_amount)
    {
        
        // Ensure the values are numeric before performing subtraction
        $billingAmount =  floatval($this->billing_amount);
        $paidAmount = floatval($paid_amount);
        $paidAmount = ltrim($paidAmount, '0');
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

    public function GetCategory($value,$index)
    {
        // Store the currently selected catalogue before resetting
        $previousCatalogue = $this->items[$index]['selectedCatalogue'] ?? null;

        // Reset products, and product_id for the selected item
        $this->items[$index]['product_id'] = null;
        $this->items[$index]['measurements'] = [];
        $this->items[$index]['fabrics'] = [];

        // Fetch categories and products based on the selected collection 
        $this->items[$index]['categories'] = Category::orderBy('title', 'ASC')->where('collection_id', $value)->get();
        $this->items[$index]['products'] = Product::orderBy('name', 'ASC')->where('collection_id', $value)->get();

        if ($value == 1) {
            $catalogues = Catalogue::with('catalogueTitle')->get();
            
            // Store catalogues inside items array
            $this->items[$index]['catalogues'] = $catalogues->map(function ($catalogue) {
                return [
                    'id' => $catalogue->catalogue_title_id,
                    'catalogue_title' =>[ 'title' => $catalogue->catalogueTitle->title ],
                    'page_number' => $catalogue->page_number,
                ];
            })->toArray();
           
        
            // Fetch max page numbers per catalogue
            $this->maxPages[$index] = [];
            foreach ($catalogues as $catalogue) {
                $this->maxPages[$index][$catalogue->catalogue_title_id] = $catalogue->page_number;
            }
            
            if ($previousCatalogue) {
                $selectedCatalogue = collect($this->items[$index]['catalogues'])->firstWhere('id', $previousCatalogue);
                if ($selectedCatalogue) {
                    $this->items[$index]['selectedCatalogue'] = $selectedCatalogue['id']; 
                }
            }

        } else {
            $this->items[$index]['catalogues'] = [];
            $this->maxPages[$index] = [];
        }
    }

    
    public function SelectedCatalogue($catalogueId, $index)
    {
        $this->items[$index]['page_number'] = null; // Reset page number
        $this->maxPages[$index] = []; // Reset max page number

        // Fetch max page number from database
        $maxPage = Catalogue::where('catalogue_title_id', $catalogueId)->value('page_number');

        if ($maxPage) {
            $this->maxPages[$index][$catalogueId] = $maxPage;
        }
    }

    public function validatePageNumber($index)
    {
        if (!isset($this->items[$index]['page_number']) || !isset($this->items[$index]['selectedCatalogue'])) {
            return;
        }
    
        $pageNumber = (int) $this->items[$index]['page_number'];
        $selectedCatalogue = $this->items[$index]['selectedCatalogue'];
    
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

    public function selectProduct($index, $name, $id)
    {
        $this->items[$index]['searchproduct'] = $name;
        $this->items[$index]['product_id'] = $id;
        $this->items[$index]['products'] = [];
        $this->items[$index]['measurements'] = Measurement::where('product_id', $id)
                                                            ->where('status', 1)
                                                            ->orderBy('position','ASC')
                                                            ->get()
                                                            ->toArray();

        $this->items[$index]['fabrics'] = Fabric::join('product_fabrics', 'fabrics.id', '=', 'product_fabrics.fabric_id')
                                            ->where('product_fabrics.product_id', $id)
                                            ->where('fabrics.status', 1)
                                            ->get(['fabrics.*']);

        $product = Product::find($id);
        if (empty($this->items[$index]['selected_collection'])) {
            $this->items[$index]['selected_collection'] = $product && $product->collection->isNotEmpty()
                ? $product->collection->first()->id
                : null;
        }
        
        session()->forget('measurements_error.' . $index);
        if (count($this->items[$index]['measurements']) == 0) {
            session()->flash('measurements_error.' . $index, '🚨 Oops! Measurement data not added for this product.');
            return;
        }
    }

    public function CategoryWiseProduct($categoryId, $index)
    {
        // Reset products for the selected item
        $this->items[$index]['products'] = [];
        $this->items[$index]['product_id'] = null;

        if ($categoryId) {
            // Fetch products based on the selected category and collection
            $this->items[$index]['products'] = Product::where('category_id', $categoryId)
                ->where('collection_id', $this->items[$index]['selected_collection']) // Ensure the selected collection is considered
                ->get();
        }
    }

    public function FindProduct($term, $index)
    {
        $collection = $this->items[$index]['selected_collection'];
        $category = $this->items[$index]['selected_category']; 

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
                ->get();
        }
    
    }
    

    public function toggleShippingAddress()
    {
        // When the checkbox is checked
        if ($this->is_billing_shipping_same) {
            // Copy billing address to shipping address
            $this->shipping_address = $this->billing_address;
            $this->shipping_landmark = $this->billing_landmark;
            $this->shipping_city = $this->billing_city;
            $this->shipping_state = $this->billing_state;
            $this->shipping_country = $this->billing_country;
            $this->shipping_pin = $this->billing_pin;
        } else {
            // Reset shipping address fields
            $this->shipping_address = '';
            $this->shipping_landmark = '';
            $this->shipping_city = '';
            $this->shipping_state = '';
            $this->shipping_country = '';
            $this->shipping_pin = '';
        }
        $this->TabChange($this->activeTab);
    }

    public function TabChange($value)
    {
        // Initialize or reset error classes and messages
        $this->errorClass = [];
        $this->errorMessage = [];
        if ($value== 1) {
            $this->activeTab = $value;
        }
        if ($value > 1) {
            // Validate search
            // if (empty($this->search)) {
            //     $this->errorClass['search'] = 'border-danger';
            //     $this->errorMessage['search'] = 'Please search a country first';
            // } else {
            //     $this->errorClass['search'] = null;
            //     $this->errorMessage['search'] = null;
            // }

            // Validate name
            if (empty($this->name)) {
                $this->errorClass['name'] = 'border-danger';
                $this->errorMessage['name'] = 'Please enter customer name';
            } else {
                $this->errorClass['name'] = null;
                $this->errorMessage['name'] = null;
            }

            // Validate business type
            if (empty($this->selectedBusinessType)) {
                $this->errorClass['selectedBusinessType'] = 'border-danger';
                $this->errorMessage['selectedBusinessType'] = 'Please select a business type';
            } else {
                $this->errorClass['selectedBusinessType'] = null;
                $this->errorMessage['selectedBusinessType'] = null;
            }
    
           
            // Validate Date of Birth
            if (empty($this->dob)) {
                $this->errorClass['dob'] = 'border-danger';
                $this->errorMessage['dob'] = 'Please enter customer date of birth';
            } else {
                $this->errorClass['dob'] = null;
                $this->errorMessage['dob'] = null;
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

        // Validate WhatsApp Number
        if (empty($this->whatsapp_no)) {
            $this->errorClass['whatsapp_no'] = 'border-danger';
            $this->errorMessage['whatsapp_no'] = 'Please enter WhatsApp number';
        } elseif (!preg_match('/^\d{'. $this->mobileLengthWhatsapp .'}$/', $this->whatsapp_no)) {
            $this->errorClass['whatsapp_no'] = 'border-danger';
            $this->errorMessage['whatsapp_no'] = 'WhatsApp number must be exactly ' . $this->mobileLengthWhatsapp . ' digits';
        } else {
            $this->errorClass['whatsapp_no'] = null;
            $this->errorMessage['whatsapp_no'] = null;
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
                $this->errorMessage['billing_address'] = 'Please enter billing address';
            } else {
                $this->errorClass['billing_address'] = null;
                $this->errorMessage['billing_address'] = null;
            }
    
            if (empty($this->billing_city)) {
                $this->errorClass['billing_city'] = 'border-danger';
                $this->errorMessage['billing_city'] = 'Please enter billing city';
            } else {
                $this->errorClass['billing_city'] = null;
                $this->errorMessage['billing_city'] = null;
            }
    
            if (empty($this->billing_country)) {
                $this->errorClass['billing_country'] = 'border-danger';
                $this->errorMessage['billing_country'] = 'Please enter billing country';
            } else {
                $this->errorClass['billing_country'] = null;
                $this->errorMessage['billing_country'] = null;
            }
      
            //  if (strlen($this->billing_pin) != env('VALIDATE_PIN', 6)) {  // Assuming pin should be 6 digits
            //     $this->errorClass['billing_pin'] = 'border-danger';
            //     $this->errorMessage['billing_pin'] = 'Billing pin must be '.env('VALIDATE_PIN', 6).' digits';
            // } else {
            //     $this->errorClass['billing_pin'] = null;
            //     $this->errorMessage['billing_pin'] = null;
            // }
    
            // Validate Shipping Information
            if (empty($this->shipping_address)) {
                $this->errorClass['shipping_address'] = 'border-danger';
                $this->errorMessage['shipping_address'] = 'Please enter shipping address';
            } else {
                $this->errorClass['shipping_address'] = null;
                $this->errorMessage['shipping_address'] = null;
            }
    
            if (empty($this->shipping_city)) {
                $this->errorClass['shipping_city'] = 'border-danger';
                $this->errorMessage['shipping_city'] = 'Please enter shipping city';
            } else {
                $this->errorClass['shipping_city'] = null;
                $this->errorMessage['shipping_city'] = null;
            }
    
            
    
            if (empty($this->shipping_country)) {
                $this->errorClass['shipping_country'] = 'border-danger';
                $this->errorMessage['shipping_country'] = 'Please enter shipping country';
            } else {
                $this->errorClass['shipping_country'] = null;
                $this->errorMessage['shipping_country'] = null;
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

    

    public function checkproductPrice($value, $index)
    {
        $selectedFabricId = $this->items[$index]['selected_fabric'] ?? null;
        if ($selectedFabricId) {
            $fabricData = Fabric::find($selectedFabricId);
            if ($fabricData && floatval($value) < floatval($fabricData->threshold_price)) {
                // Error message for threshold price violation
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
            $this->items[$index]['price'] = 0;
            session()->flash('errorPrice.' . $index, '🚨 Please enter a valid price.');
        }
    
        $this->updateBillingAmount(); // Update billing after validation
    }
    


    public function SameAsMobile(){
        if($this->is_wa_same == 0){
            $this->whatsapp_no = $this->phone;
            $this->is_wa_same = 1;
        }else{
            $this->whatsapp_no = '';
            $this->is_wa_same = 0;
        }
    }
  
    public function copyMeasurements($index) {
        if ($index > 0) {
            $currentProductId = $this->items[$index]['product_id'] ?? null;
            $previousProductId = $this->items[$index - 1]['product_id'] ?? null;
    
            if (!empty($this->items[$index]['copy_previous_measurements'])) {
                if ($currentProductId === $previousProductId && !empty($this->items[$index - 1]['measurements'])) {
                    // Copy measurements if the product is the same
                    $this->items[$index]['measurements'] = $this->items[$index - 1]['measurements']->toArray();
                } else {
                    // Keep structure but clear measurement values
                    if (!empty($this->items[$index]['measurements'])) {
                        foreach ($this->items[$index]['measurements'] as $key => $measurement) {
                            $this->items[$index]['measurements'][$key]['value'] = ''; // Clear only values
                        }
                    }
                    session()->flash('measurements_error.' . $index, 'Measurements cannot be copied as products are different.');
                }
            } else {
                // Clear only values if checkbox is unchecked
                if (!empty($this->items[$index]['measurements'])) {
                    foreach ($this->items[$index]['measurements'] as $key => $measurement) {
                        $this->items[$index]['measurements'][$key]['value'] = '';
                    }
                }
            }
        }
    }
    
    
    

    
    public function update()
    {
        $this->validate();
        // dd($this->items);
        DB::beginTransaction();
        try {
            
            $total_amount = array_sum(array_column($this->items, 'price'));

            // Retrieve user details
            $user = User::find($this->customer_id);
            // dd($user);
            if (!$user) {
                // Create new user if not found
                $user = User::create([
                    'prefix' => $this->prefix,
                    'name' => $this->name,
                    'company_name' => $this->company_name,
                    'employee_rank' => $this->employee_rank,
                    'email' => $this->email,
                    'dob' => $this->dob,
                    'phone' => $this->phone,
                    'whatsapp_no' => $this->whatsapp_no,
                    'user_type' => 1, // Customer
                    'alternative_phone_number_1' => $this->alternative_phone_number_1,
                    'alternative_phone_number_2' => $this->alternative_phone_number_2,
                    'business_type' => $this->selectedBusinessType,
                    'country_id' => $this->selectedCountryId,
                    'country_code' => $this->country_code,
                ]);
            } else {
                // Update existing user
                $user->update([
                    'prefix' => $this->prefix,
                    'name' => $this->name,
                    'company_name' => $this->company_name,
                    'employee_rank' => $this->employee_rank,
                    'email' => $this->email,
                    'dob' => $this->dob,
                   
                    'user_type' => 1, // Customer (if needed, or update as appropriate)
                    'business_type' => $this->selectedBusinessType,
                    'country_id' => $this->selectedCountryId,
                    'country_code' => $this->country_code,


                    // 'country_id' => $this->country_id,
                    'country_code_phone' => $this->selectedCountryPhone,
                    'phone' => $this->phone,
                    'country_code_whatsapp' => $this->selectedCountryWhatsapp,
                    'whatsapp_no' => $this->whatsapp_no,
                    // 'country_code' => $this->country_code,
                    'country_code_alt_1'  => $this->selectedCountryAlt1,
                    'alternative_phone_number_1' => $this->alternative_phone_number_1,
                    'country_code_alt_2'  => $this->selectedCountryAlt2,
                    'alternative_phone_number_2' => $this->alternative_phone_number_2,
                ]);
            }
            // Update or create addresses
            $billingAddress = $user->address()->updateOrCreate(
                ['address_type' => 1], // Billing address
                [
                    'state' => $this->billing_state,
                    'city' => $this->billing_city,
                    'address' => $this->billing_address,
                    'landmark' => $this->billing_landmark,
                    'country' => $this->billing_country,
                    'zip_code' => $this->billing_pin,
                ]
            );

            if (!$this->is_billing_shipping_same) {
                $shippingAddress = $user->address()->updateOrCreate(
                    ['address_type' => 2], // Shipping address
                    [
                        'state' => $this->shipping_state,
                        'city' => $this->shipping_city,
                        'address' => $this->shipping_address,
                        'landmark' => $this->shipping_landmark,
                        'country' => $this->shipping_country,
                        'zip_code' => $this->shipping_pin,
                    ]
                );
            }else{
                $shippingAddress = $billingAddress;
            }
            // $order = Order::find($this->orders->id);
            // Update order details
            $name = $this->name;
            $email = $this->email;
            $billingadd = $this->billing_address;
            
            $billingLandmark= $this->billing_landmark;
            $billingCity= $this->billing_city;
            $billingState= $this->billing_state;
            $billingCountry= $this->billing_country;
            $billingPin= $this->billing_pin;

            $shippingadd = $this->shipping_address;
            $shippingLandmark= $this->shipping_landmark;
            $shippingCity= $this->shipping_city;
            $shippingState= $this->shipping_state;
            $shippingCountry= $this->shipping_country;
            $shippingPin= $this->shipping_pin;

            // $total_amount = $total_amount;
            $order = Order::find($this->orders->id);
            if (!$order) {
                session()->flash('error', 'Order not found.');
                return redirect()->route('admin.order.index');
            }else{
                $previousPaidAmount = $order->paid_amount;
                $order->customer_id = $user->id;
                $order->prefix = $this->prefix;
                $order->customer_name = $this->name;
                $order->customer_email = $this->email;
                $order->billing_address = $billingadd . ', ' . $billingLandmark . ', ' . $billingCity . ', ' . $billingState . ', ' . $billingCountry . ' - ' . $billingPin;
                $order->shipping_address = $this->is_billing_shipping_same
                    ? $billingadd . ', ' . $billingLandmark . ', ' . $billingCity . ', ' . $billingState . ', ' . $billingCountry . ' - ' . $billingPin
                    : $shippingadd . ', ' . $shippingLandmark . ', ' . $shippingCity . ', ' . $shippingState . ', ' . $shippingCountry . ' - ' . $shippingPin;
                $order->total_amount = $total_amount;
                $order->last_payment_date = now();
                $order->created_by = auth()->guard('admin')->user()->id;
                $order->save();
            }
           

            foreach ($this->items as $key=>$item) {
                if (!empty($item['order_item_id'])) {
                    // Find the existing OrderItem by its ID
                    $orderItem = OrderItem::find($item['order_item_id']);
                } else {
                    // Create a new OrderItem for new entries
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $item['product_id'];
                }
                // dd($orderItem->id);
                if ($orderItem) {
                    $orderItem->product_id = $item['product_id'];
                    $orderItem->order_id = $order->id;
                    $orderItem->product_name = $item['searchproduct'];
                    $orderItem->total_price = $item['price'];
                    $orderItem->quantity =1;
                    $orderItem->piece_price = $item['price'];
                    $orderItem->collection = $item['selected_collection'];
                    $orderItem->category = $item['selected_category'];
                    $orderItem->fabrics = $item['selected_fabric'];
                    $orderItem->catalogue_id = !empty($item['selectedCatalogue']) 
                                                ? $item['selectedCatalogue'] 
                                                : null;
                    $orderItem->cat_page_number  = $item['page_number'] ?? null;
                    $orderItem->save();
                    

                    foreach ($item['measurements'] as $measurement) {
                        $measurementValue = $measurement['value'] ?? null;
                        $measurementName = $measurement['title'] ?? null;
                        $measurementShortCode = $measurement['short_code'] ?? null;
                        // Manually check if the OrderMeasurement exists
                        $orderMeasurement = OrderMeasurement::where('order_item_id', $orderItem->id)
                                                            ->where('measurement_name', $measurementName)
                                                            ->first();
                        
                        
                        if ($orderMeasurement) {
                            $orderMeasurement->update([
                                'measurement_value' => $measurementValue,
                            ]);
                        } else {

                            // If the OrderMeasurement doesn't exist, create a new one
                            if(!empty($measurementValue)){
                                $data= OrderMeasurement::create([
                                    'order_item_id' => $orderItem->id,
                                    'measurement_name' =>  $measurementName,
                                    'measurement_title_prefix' =>  $measurementShortCode,
                                    'measurement_value' =>  $measurementValue,
                                ]);
                            }
                           
                        }
                    }
                    $orderItem = OrderItem::where('order_id', $order->id)->where('product_id', $item['product_id'])->first();
                        $orderItem->update([
                            'selected_fabric' => $item['selected_fabric'], // Save selected fabric ID
                        ]);
                }
            }
            // Store or update WhatsApp details if the flags are set
            $existingNumbers = UserWhatsapp::where('user_id', $this->orders->customer->id)->pluck('whatsapp_number')->toArray();

            $updatedNumbers = [];
            
            if ($this->isWhatsappPhone) {
                UserWhatsapp::updateOrCreate(
                    ['user_id' => $this->orders->customer->id, 'whatsapp_number' => $this->phone], // Search criteria
                    ['country_code' => $this->selectedCountryPhone, 'updated_at' => now()]
                );
                $updatedNumbers[] = $this->phone;
            }
            
            if ($this->isWhatsappAlt1) {
                UserWhatsapp::updateOrCreate(
                    ['user_id' => $this->orders->customer->id, 'whatsapp_number' => $this->alternative_phone_number_1], // Search criteria
                    ['country_code' => $this->selectedCountryAlt1, 'updated_at' => now()]
                );
                $updatedNumbers[] = $this->alternative_phone_number_1;
            }
            
            if ($this->isWhatsappAlt2) {
                UserWhatsapp::updateOrCreate(
                    ['user_id' => $this->orders->customer->id, 'whatsapp_number' => $this->alternative_phone_number_2], // Search criteria
                    ['country_code' => $this->selectedCountryAlt2, 'updated_at' => now()]
                );
                $updatedNumbers[] = $this->alternative_phone_number_2;
            }
            
            // Delete records that were not updated
            UserWhatsapp::where('user_id', $this->orders->customer->id)
                ->whereNotIn('whatsapp_number', $updatedNumbers)
                ->delete();
            

            DB::commit();
            session()->flash('success', 'Order has been updated successfully.');
            return redirect()->route('admin.order.index');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating order: ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            dd($e->getMessage());
            session()->flash('error', '🚨 Something went wrong. The operation has been rolled back.');
        }
    }

    

        /**
         * Helper function to calculate total amount
         */
        private function calculateTotalAmount()
        {
            return array_sum(array_column($this->items, 'price'));
        }

        /**
         * Helper function to calculate remaining amount
         */
        private function calculateRemainingAmount()
        {
            return $this->calculateTotalAmount() - $this->paid_amount;
        }

        /**
         * Helper function to format address
         */
        private function formatAddress($address, $landmark, $city, $state, $country, $pin)
        {
            return "{$address}, {$landmark}, {$city}, {$state}, {$country} - {$pin}";
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
            'whatsapp_no',
           
        ]);
    }

    public function render()
    {
        // dd($this->order);
        return view('livewire.order.order-edit' ,[
            'order' => $this->orders
        ]);
    }
}
