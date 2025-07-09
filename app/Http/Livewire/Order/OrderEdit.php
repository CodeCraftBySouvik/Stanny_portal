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
use App\Models\OrderItemCatalogueImage;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Models\UserWhatsapp;
use App\Models\Page;
use App\Models\CataloguePageItem;
use App\Models\OrderItemVoiceMessage;
use App\Models\Changelog;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use App\Helpers\Helper;

use App\Services\ChangeTracker;


class OrderEdit extends Component
{
    use WithFileUploads;

    public $searchTerm = '';
    public $searchResults = [];
    public $errorClass = [];
    public $collections = [];
    public $errorMessage = [];
    public $activeTab = 1;
    public $items = [];

    public $FetchProduct = 1;
    public $maxPages = [];
    public $salesman;

    public $customers = null;
    public $orders;
    public $is_wa_same, $prefix, $name, $company_name,$employee_rank, $email,$customer_image, $dob, $customer_id, $phone ,$alternative_phone_number_1, $alternative_phone_number_2,
    $selectedCountryPhone, $selectedCountryWhatsapp, $selectedCountryAlt1 , $selectedCountryAlt2 ,$mobileLengthPhone, $mobileLengthWhatsapp, $mobileLengthAlt1, $mobileLengthAlt2,
    $countries,$isWhatsappPhone,$isWhatsappAlt1,$isWhatsappAlt2;

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
    public $catalogue_page_item = [];
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
    public $air_mail;
    public $imageUploads = [];
    public $existingImages = [];
    public $voiceUploads = [];
    public $existingVideos = [];

    public function mount($id)
    {
        $this->orders = Order::with(['items.measurements'])->findOrFail($id); // Fetch the order by ID

    //    dd($this->orders->customer->id);
        if ($this->orders) {
            $this->customer_image = $this->orders->customer_image;
            $this->order_number = $this->orders->order_number;
            $this->customer_id = $this->orders->customer_id;
            $this->name = $this->orders->customer_name;
            $this->email = $this->orders->customer_email;
            $this->dob = optional($this->orders->customer)->dob;
            $this->billing_address = $this->orders->billing_address;
            $this->air_mail = (int)$this->orders->air_mail;
            $this->phone = optional($this->orders->customer)->phone;
            $this->alternative_phone_number_1 = optional($this->orders->customer)->alternative_phone_number_1;
            $this->alternative_phone_number_2 = optional($this->orders->customer)->alternative_phone_number_2;
            $this->countries = Country::where('status',1)->get();
            $this->selectedCountryPhone =  optional($this->orders->customer)->country_code_phone;
            $this->selectedCountryWhatsapp =  optional($this->orders->customer)->country_code_whatsapp;
            $this->selectedCountryAlt1 =  optional($this->orders->customer)->country_code_alt_1;
            $this->selectedCountryAlt2 =  optional($this->orders->customer)->country_code_alt_2;

            // Set mobile lengths based on selected countries
            $this->mobileLengthPhone = Country::where('country_code', $this->selectedCountryPhone)->value('mobile_length') ?? '';
            $this->mobileLengthWhatsapp = Country::where('country_code', $this->selectedCountryWhatsapp)->value('mobile_length') ?? '';
            $this->mobileLengthAlt1 = Country::where('country_code', $this->selectedCountryAlt1)->value('mobile_length') ?? '';
            $this->mobileLengthAlt2 = Country::where('country_code', $this->selectedCountryAlt2)->value('mobile_length') ?? '';
            if($this->orders->customer){
                 $this->isWhatsappPhone = UserWhatsapp::where('user_id',$this->orders->customer->id)->where('whatsapp_number',$this->phone)->exists();
                 $this->isWhatsappAlt1 = UserWhatsapp::where('user_id',$this->orders->customer->id)->where('whatsapp_number',$this->alternative_phone_number_1)->exists();
                 $this->isWhatsappAlt2 = UserWhatsapp::where('user_id',$this->orders->customer->id)->where('whatsapp_number',$this->alternative_phone_number_2)->exists();
            }else{
                $this->isWhatsappPhone = false;
                $this->isWhatsappAlt1 = false;
            }

            // dd( $this->phone);



            $this->catalogues = Catalogue::with('catalogueTitle')->get()->toArray();

            $this->items = $this->orders->items->map(function ($item) {
                $selected_titles = OrderMeasurement::where('order_item_id', $item->id)->pluck('measurement_name')->toArray();
                $selected_values = OrderMeasurement::where('order_item_id', $item->id)->pluck('measurement_value')->toArray();
                $fabrics = Fabric::join('product_fabrics', 'product_fabrics.fabric_id', '=', 'fabrics.id')
                                    ->where('product_fabrics.product_id', $item->product_id)
                                    ->select('fabrics.id', 'fabrics.title')
                                    ->get();

                // Get the selected fabric object if exists
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

                $pageItems = [];
                    if (!empty($item->catalogue_id) && !empty($item->cat_page_number)) {
                        $pageItems = CataloguePageItem::join('pages', 'catalogue_page_items.page_id', '=', 'pages.id')
                        ->where('catalogue_page_items.catalogue_id', $item->catalogue_id)
                        ->where('pages.page_number', $item->cat_page_number)
                        ->pluck('catalogue_page_items.catalog_item','catalogue_page_items.id')
                        ->toArray();
                    }
                return [
                    'order_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'searchproduct' => $item->product_name,
                    // 'air_mail'  => $item->air_mail,
                    'quantity'  => $item->quantity,
                    'price' => round($item->piece_price),
                    'remarks' => $item->remarks,
                    'item_status' => $item->status,
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
                    'pageItems' => $pageItems,
                    'page_item' => $item->cat_page_item,
                    'expected_delivery_date' => $item->expected_delivery_date,
                    'fitting' => $item->collection == 1 ? $item->fittings : '',
                    'priority' => $item->priority_level,
                    'status'  =>  $item->status,
                    'tl_status' => $item->tl_status,
                    'item_status' => $item->status, 

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


        $this->Business_type = BusinessType::all();
        $this->selectedCountryId = optional($this->orders->customer)->country_id;
        $this->search = Country::where('id',optional($this->orders->customer)->country_id)->pluck('title');


        $this->selectedBusinessType = optional($this->orders->customer)->business_type;
        $this->customer_id = $this->orders->customer_id;
        $this->prefix = $this->orders->prefix;
        $this->name = $this->orders->customer_name;
        $this->company_name = optional($this->orders->customer)->company_name;
        $this->employee_rank = optional($this->orders->customer)->employee_rank;
        $this->email = $this->orders->customer_email;
        $this->dob = optional($this->orders->customer)->dob;

        $this->customers = User::where('user_type', 1)->where('status', 1)->orderBy('name', 'ASC')->get();
        $this->categories = Category::where('status', 1)->orderBy('title', 'ASC')->get();
        $this->collections = Collection::orderBy('title', 'ASC')->whereIn('id',[1,2])->get();

        $this->paid_amount = $this->orders->paid_amount;
        $this->billing_amount =  $this->orders->total_amount;
        $this->remaining_amount =  $this->orders->remaining_amount;
        $this->payment_mode = $this->orders->payment_mode;
        $this->salesmanBill = SalesmanBilling::where('salesman_id',auth()->guard('admin')->user()->id)->first();


        foreach ($this->items as $index => $item) {
            // dd($item);
            if(!empty($item['page_item'])){
                $this->catalogue_page_item[$index] = $item['page_item'];
            }
            $this->existingImages[$index] = OrderItemCatalogueImage::where('order_item_id', $item['order_item_id'])->pluck('image_path')->toArray();
            $this->existingVideos[$index] = OrderItemVoiceMessage::where('order_item_id', $item['order_item_id'])->pluck('voices_path')->toArray();
            $this->imageUploads[$index] = [];
            $this->items[$index]['copy_previous_measurements'] = false; // Ensure checkbox is not selected
        }
    }

    public function updatedItems($value, $key)
    {
        [$index, $field] = explode('.', $key);

        if ($field === 'selected_collection' && $this->items[$index]['selected_collection'] == 1) {
            $this->items[$index]['quantity'] = 1;
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
            'pageItems' => [],
            'page_item' => null,
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
        'items.*.price' => 'required|numeric|min:1',
        'items.*.selectedCatalogue' => 'required_if:items.*.selected_collection,1',
        'items.*.page_number' => 'required_if:items.*.selected_collection,1',
    ];

    protected function messages(){
        return [
             'items.*.selected_category.required' => 'Please select a category for the item.',
             'items.*.searchproduct.required' => 'Please select a product for the item.',
             'items.*.selectedCatalogue.required_if' => 'Please select a catalogue for the item.',
             'items.*.page_number.required_if' => 'Please select a page for the item.',
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
        $itemTotal = array_sum(array_column($this->items, 'price'));
        $airMail = floatval($this->air_mail);
        $this->billing_amount = $airMail > 0 ? ($itemTotal + $airMail) : $itemTotal;
        $this->paid_amount = $this->billing_amount;
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
                session()->flash('errorAmount', 'ðŸš¨ The paid amount exceeds the billing amount.');
            }
        } else {
            $this->paid_amount = 0;

            session()->flash('errorAmount', 'ðŸš¨ Please add item amount first.');
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

    public function validatePageNumber($value,$index)
    {
        if (!isset($this->items[$index]['page_number']) || !isset($this->items[$index]['selectedCatalogue'])) {
            return;
        }

        $pageNumber = (int) $this->items[$index]['page_number'];
        $selectedCatalogue = $this->items[$index]['selectedCatalogue'];

        // Fetch page items dynamically
         $this->items[$index]['pageItems'] = CataloguePageItem::join('pages', 'catalogue_page_items.page_id', '=', 'pages.id')
                                                            ->where('catalogue_page_items.catalogue_id', $selectedCatalogue)
                                                            ->where('pages.page_number', $pageNumber)
                                                            ->pluck('catalogue_page_items.catalog_item')
                                                            ->toArray();
                                                            // If no items found, reset selected page item
        if(count($this->items[$index]['pageItems'])>0){
            $this->catalogue_page_item[$index]=  $value;
        }else{
            $this->catalogue_page_item[$index] = "";
        }

        if (empty($this->items[$index]['pageItems'])) {
            $this->items[$index]['page_item'] = null;
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
            session()->flash('measurements_error.' . $index, 'ðŸš¨ Oops! Measurement data not added for this product.');
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
            session()->flash('errorProduct.' . $index, 'ðŸš¨ Please select a collection before searching for a product.');
            return;
        }

        if (empty($category)) {
            session()->flash('errorProduct.' . $index, 'ðŸš¨ Please select a category before searching for a product.');
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


    public function TabChange($value)
    {
        // Initialize or reset error classes and messages
        $this->errorClass = [];
        $this->errorMessage = [];
        if ($value== 1) {
            $this->activeTab = $value;
        }
        if ($value > 1) {
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
                    "The price for fabric '{$fabricData->title}' cannot be less than its threshold price of {$fabricData->threshold_price}.");
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
            session()->flash('errorPrice.' . $index, 'ðŸš¨ Please enter a valid price.');
        }

        $this->updateBillingAmount(); // Update billing after validation
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


    public function removeImage($index, $imageIndex)
    {

        $orderItemId = $this->items[$index]['order_item_id'];


        $imagePath = OrderItemCatalogueImage::where('order_item_id', $orderItemId)
            ->skip($imageIndex)
            ->value('image_path');

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);


            OrderItemCatalogueImage::where('order_item_id', $orderItemId)
                ->where('image_path', $imagePath)
                ->delete();

            unset($this->existingImages[$index][$imageIndex]);
            $this->existingImages[$index] = array_values($this->existingImages[$index]);
        }
    }


    public function removeUploadedImage($index, $imageIndex){
        // Remove the image from the uploaded images array
        unset($this->imageUploads[$index][$imageIndex]);
        $this->imageUploads[$index] = array_values($this->imageUploads[$index]);
    }

    public function removeVideo($index, $audioIndex){
        ChangeTracker::setOrderId($this->orders->id);
        $orderItemId = $this->items[$index]['order_item_id'];
        $audioPath = OrderItemVoiceMessage::where('order_item_id', $orderItemId)
        ->skip($audioIndex)
        ->value('voices_path');

        if ($audioPath) {
            Storage::disk('public')->delete($audioPath);


            OrderItemVoiceMessage::where('order_item_id', $orderItemId)
            ->where('voices_path', $audioPath)
            ->get()
            ->each(function ($message) {
                $message->delete(); // ✅ This triggers the `deleted` event
            });

            unset($this->existingVideos[$index][$audioIndex]);
            $this->existingVideos[$index] = array_values($this->existingVideos[$index]);
        }
    }

    public function removeUploadedVoice($index, $voiceIndex){
        // Remove the audio from the uploaded audio array
        unset($this->voiceUploads[$index][$voiceIndex]);
        $this->voiceUploads[$index] = array_values($this->voiceUploads[$index]);
    }

    public function validateMeasurement($itemIndex, $measurementKey)
    {
        $value = trim($this->items[$itemIndex]['measurements'][$measurementKey]['value'] ?? '');

        $errorKey = "items.$itemIndex.measurements.$measurementKey.value";

        if ($value === '') {
            $this->addError($errorKey, 'Measurement value is required.');
        } elseif (!is_numeric($value)) {
            $this->addError($errorKey, 'Measurement must be numeric.');
        } elseif (floatval($value) < 1) {
            $this->addError($errorKey, 'Measurement must be a number greater than 0.');
        } else {
             $this->resetValidation([$errorKey]);   // clears the error when it becomes valid
        }
    }


    public function update()
    {
        $this->validate();
        DB::beginTransaction();
        try {

            $total_product_amount = array_sum(array_column($this->items, 'price'));
            $total_amount = collect($this->items)->reduce(function ($carry, $item) {
                return $carry + ((float) $item['price'] * (int) $item['quantity']);
            }, 0);
            $airMail = floatval($this->air_mail);
            $total_amount += $airMail;
            ChangeTracker::setOrderId($this->orders->id);

            // Retrieve user details
            $user = User::find($this->customer_id);
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
                    'business_type' => $this->selectedBusinessType,
                    'country_id' => $this->selectedCountryId,
                    'country_code' => $this->country_code,


                    // 'country_id' => $this->country_id,
                    'country_code_phone' => $this->selectedCountryPhone,
                    'phone' => $this->phone,
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

            // Update order details
            $name = $this->name;
            $email = $this->email;
            $billingadd = $this->billing_address;

            $billingLandmark= $this->billing_landmark;
            $billingCity= $this->billing_city;
            $billingState= $this->billing_state;
            $billingCountry= $this->billing_country;
            $billingPin= $this->billing_pin;

            $order = Order::find($this->orders->id);
            //die(ChangeTracker::getOrderId().'llll');
            if (!$order) {
                session()->flash('error', 'Order not found.');
                return redirect()->route('admin.order.index');
            }else{
                $previousPaidAmount = $order->paid_amount;
                $order->customer_id = $user->id;
                $order->prefix = $this->prefix;
                $order->customer_name = $this->name;
                $order->customer_email = $this->email;
                if(!empty($this->customer_image) and !is_string($this->customer_image))
                {
                    $order->customer_image = Helper::handleFileUpload($this->customer_image, "client_image");
                }

                $order->billing_address = $billingadd . ', ' . $billingLandmark . ', ' . $billingCity . ', ' . $billingState . ', ' . $billingCountry . ' - ' . $billingPin;
                $order->total_product_amount = $total_product_amount;
                $order->air_mail = $airMail;
                $order->total_amount = $total_amount;
                $order->last_payment_date = now();
                $order->created_by = auth()->guard('admin')->user()->id;
                $order->save();
            }


            foreach ($this->items as $key=>$item) {
                if($item['selected_collection'] == 1 && is_null($item['page_item'])){
                    $page = Page::where('page_number', $item['page_number'])
                                ->where('catalogue_id', $item['selectedCatalogue'])
                                ->first();
                    $exists_pages = CataloguePageItem::where('page_id', $page->id)->get();
                    if($exists_pages->isNotEmpty() && empty($item['page_item'])){
                        $this->addError("items.$key.page_item", "Please select a page item for this page.");
                        return false;
                    }
                }
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
                    $orderItem->remarks = $item['remarks'] ?? null;
                    $orderItem->status = $item['item_status'] ?? null;
                    $orderItem->quantity = $item['quantity'] ?? null;
                    $itemPrice = floatval($item['price']);
                    $orderItem->total_price = $itemPrice * $orderItem->quantity;
                    $orderItem->piece_price = $item['price'];
                    $orderItem->collection = $item['selected_collection'];
                    $orderItem->category = $item['selected_category'];
                    $orderItem->fabrics = $item['selected_fabric'];
                    $orderItem->catalogue_id = !empty($item['selectedCatalogue'])
                                                ? $item['selectedCatalogue']
                                                : null;
                    $orderItem->quantity = ($item['selected_collection'] == 1) ? 1 : $item['quantity'];
                    $orderItem->fittings  = ($item['selected_collection'] == 1) ? $item['fitting'] : null;
                    $orderItem->priority_level  = $item['priority'];
                    $orderItem->expected_delivery_date  = $item['expected_delivery_date'];
                    $orderItem->cat_page_number  = $item['page_number'] ?? null;
                    $orderItem->cat_page_item  = $item['page_item'] ?? null;
                      $loggedInAdmin = auth()->guard('admin')->user();
                    if ($orderItem->status === 'Process') {
                        if ($loggedInAdmin->designation == 1) {
                            // Admin is creating the order
                            $orderItem->tl_status = 'Approved';
                            $orderItem->admin_status = 'Approved';
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
                    $orderItem->save();

                    if ($orderItem) {
                        if (!empty($this->imageUploads[$key])) {
                            foreach ($this->imageUploads[$key] as $uploadedImage) {
                                $path = $uploadedImage->store('uploads/order_item_catalogue_images', 'public');
                                OrderItemCatalogueImage::create([
                                    'order_item_id' => $orderItem->id,
                                    'image_path' => $path
                                ]);
                            }
                            // Clear uploaded images after saving
                            $this->imageUploads[$key] = [];
                        }
                    }

                    if ($orderItem) {
                        if (!empty($this->voiceUploads[$key])) {
                            foreach ($this->voiceUploads[$key] as $uploadedVoice) {
                                $path = $uploadedVoice->store('uploads/order_item_voice_messages', 'public');
                                OrderItemVoiceMessage::create([
                                    'order_item_id' => $orderItem->id,
                                    'voices_path' => $path
                                ]);
                            }
                            // Clear uploaded audios after saving
                            $this->voiceUploads[$key] = [];
                        }
                    }


                    foreach ($item['measurements'] as $measurement) {
                        if (!isset($measurement['value']) || $measurement['value'] === '' ) {
                            session()->flash('measurements_error.' . $key, "🚨 Oops! All measurement data should be mandatory.");
                            return;
                        }elseif (!is_numeric($measurement['value'])) {
                            session()->flash('measurements_error.' . $key, "🚨 Oops! Measurement must be numeric.");
                            return;
                        } elseif (floatval($measurement['value']) < 1) {
                            session()->flash('measurements_error.' . $key, "🚨 Oops! Measurement must be greater than 0.");
                            return;
                        }

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

            // Maintain Log




            DB::commit();
            session()->flash('success', 'Order has been updated successfully.');
            //ChangeTracker::clear(); // to prevent accidental leakage into other requests
            return redirect()->route('admin.order.index');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating order: ' . $e->getMessage());
            session()->flash('error', $e->getMessage());
            dd($e->getMessage());
            session()->flash('error', 'ðŸš¨ Something went wrong. The operation has been rolled back.');
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
