<div class="container">
    {{-- <style>
        .ps {
            overflow: inherit !important;
        }
    </style> --}}
    <section class="admin__title">
        <h5>Place Order</h5>
    </section>
    <section>
        <ul class="breadcrumb_menu">
            <li>Sales Management</li>
            <li><a href="{{route('admin.order.new')}}">Place Order</a></li>
            <li class="back-button">
                @if($activeTab==1)
                <a class="btn btn-sm btn-danger select-md text-light font-weight-bold mb-0"
                    href="{{route('admin.order.index')}}" role="button">
                    <i class="material-icons text-white" style="font-size: 15px;">chevron_left</i>
                    <span class="ms-1">Back</span>
                </a>
                @endif
            </li>
        </ul>
    </section>
    <div class="card my-4">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center mb-2">
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                {{-- @if ($activeTab==1 && $salesmanBill == null)
                <div class="badge bg-primary">
                    <a href="{{ route('salesman.index') }}"> <strong>Error:</strong> Please add a bill for this user
                        before placing the order.</a>
                </div>
                @endif --}}
            </div>
        </div>

        <div class="card-body" id="sales_order_data">
            <form wire:submit.prevent="save">
                <div class="{{$activeTab==1?" d-block":"d-none"}}" id="tab1">
                    {{-- checkbox section --}}
                    <div class="mb-3">
                        <label><strong>Select Customer Type:</strong></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"  wire:change="onCustomerTypeChange($event.target.value)" wire:model="customerType" id="newCustomer" value="new" checked>
                            <label class="form-check-label" for="newCustomer">New Customer</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"  wire:change="onCustomerTypeChange($event.target.value)" wire:model="customerType" id="existingCustomer" value="existing">
                            <label class="form-check-label" for="existingCustomer">Existing Customer</label>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        @php
                        $auth = Auth::guard('admin')->user();
                        @endphp
                        {{-- <div class="col-md-4 {{ $auth->is_super_admin==1 ? "" : " d-none" }}"> --}}
                        <div class="col-md-4">
                            <!-- Search Label -->
                            <label for="searchCustomer" class="form-label mb-0">Business Type</label>
                            <select wire:model="selectedBusinessType"
                                class="form-select me-2 form-control form-control-sm border border-1 customer_input"
                                aria-label="Default select example">
                                <option selected hidden>Select Domain</option>
                                @foreach ($Business_type as $domain)
                                <option value="{{$domain->id}}">{{$domain->title}}</option>
                                @endforeach
                            </select>
                            @if(isset($errorMessage['selectedBusinessType']))
                            <div class="text-danger error-message">{{ $errorMessage['selectedBusinessType'] }}</div>
                            @endif
                        </div>
                        {{-- Display Order by and order number --}}
                        <!-- Ordered By Section -->
                        <div class="col-md-4">
                            <label class="form-label"><strong>Ordered By</strong></label>
                            <select
                                class="form-control border border-2 p-2 form-control-sm @error('salesman') border-danger  @enderror"
                                wire:change="changeSalesman($event.target.value)" wire:model="salesman" disabled>
                                <option value="" selected hidden>Choose one..</option>
                                <!-- Set authenticated user as default -->
                                @if(auth()->guard('admin')->check())
                                <option value="{{auth()->guard('admin')->user()->id}}" selected>
                                    {{strtoupper(auth()->guard('admin')->user()->name)}}
                                </option>
                                @endif

                                <!-- Other Salesmen -->
                                @foreach ($salesmen as $salesmans)
                                @if ($salesmans->id != auth()->guard('admin')->user()->id)
                                <option value="{{ $salesmans->id }}">{{ strtoupper($salesmans->name.'
                                    '.$salesmans->surname) }}</option>
                                @endif
                                @endforeach
                            </select>
                            @if(isset($errorMessage['salesman']))
                            <div class="text-danger error-message">{{ $errorMessage['salesman'] }}</div>
                            @endif
                        </div>

                        <!-- Bill Number -->
                        <div class="col-md-4">
                            <label class="form-label"><strong>Bill Number</strong></label>
                            <input type="text" class="form-control form-control-sm text-center border border-1" disabled
                                wire:model="order_number" value="{{ $order_number }}">
                            @if(isset($errorMessage['order_number']))
                            <div class="text-danger error-message">{{ $errorMessage['order_number'] }}</div>
                            @endif
                            {{-- @error('order_number')
                            <div class="text-danger error-message">{{ $message }}</div>
                            @enderror --}}
                        </div>

                        @if ($customerType == 'existing')
                        <!-- Search Label and Select2 -->
                        <div class="col-md-6 mt-2">
                            <div class="d-flex justify-content-between">
                                <!-- Search Label -->
                                <label for="searchCustomer" class="form-label mb-0">Customer</label>
                            </div>

                            <div class="position-relative">
                                <input type="text" wire:keyup="FindCustomer($event.target.value)"
                                    wire:model.debounce.500ms="searchTerm"
                                    class="form-control form-control-sm border border-1 customer_input"
                                    placeholder="Search by customer details or order ID">

                                @if(!empty($searchResults))
                                <div id="fetch_customer_details" class="dropdown-menu show w-100"
                                    style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($searchResults as $customer)
                                    <button class="dropdown-item" type="button"
                                        wire:click="selectCustomer({{ $customer->id }})">
                                        <img src="{{ $customer->profile_image ? asset($customer->profile_image) : asset('assets/img/user.png') }}"
                                            alt=""> {{ucfirst($customer->prefix . " ". $customer->name) }} ({{
                                        $customer->country_code_phone .' '.$customer->phone
                                        }})
                                    </button>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                    </div>

                    <!-- Order Customer Fields... -->
                    @if(session()->has('orders-found') && $orders?->count() > 0)
                    <div class="alert alert-success mt-3">
                        {{ session('orders-found') }}
                    </div>
                    @endif

                    @if (session()->has('no-orders-found'))
                    <div class="alert alert-danger mt-3">
                        {{ session('no-orders-found') }}
                    </div>
                    @endif
                    @if(!empty($orders) && $orders->count())
                    <h6 class="mb-3">Previous Order Details</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr class="text-center">
                                <th>Order Number</th>
                                <th>Customer Name</th>
                                <th>Billing Amount</th>
                                <th>Billing Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr class="text-center">
                                <td>{{ $order->order_number }}</td>
                                <td>
                                    {{ $order->customer ? $order->customer->prefix ." ".$order->customer->name : "" }}
                                </td>
                                <td>{{ $order->total_amount }}</td>
                                <td>{{ $order->last_payment_date }}</td>
                                <td>
                                    <a href="{{ route('admin.order.invoice', $order->id) }}"
                                        class="btn btn-outline-primary btn-sm" target="_blank">
                                        Invoice
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    <!-- Customer Details -->
                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                        <h6 class="badge bg-danger custom_danger_badge">Basic Information</h6>
                    </div>
                    <!-- Customer Details -->
                    <div class="row">
                        <div class="mb-2 col-md-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select wire:model="prefix"
                                    class="form-control form-control-sm border border-1 prefix_select flex-30">
                                    <option value="" selected hidden>Prefix</option>
                                    @foreach (App\Helpers\Helper::getNamePrefixes() as $prefixOption)
                                    <option value="{{$prefixOption}}">{{ $prefixOption }}</option>
                                    @endforeach
                                </select>
                                <input type="text" wire:model="name" id="name"
                                    class="form-control form-control-sm border border-1 p-2 {{ $errorClass['name'] ?? '' }}"
                                    placeholder="Enter customer name">
                            </div>
                            @if(isset($errorMessage['prefix']))
                            <div class="text-danger error-message">{{ $errorMessage['prefix'] }}</div>
                            @endif
                            @if(isset($errorMessage['name']))
                            <div class="text-danger error-message">{{ $errorMessage['name'] }}</div>
                            @endif
                        </div>

                        <div class="mb-2 col-md-4">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" wire:model="company_name" id="company_name"
                                class="form-control form-control-sm border border-1 p-2"
                                placeholder="Enter company name">
                        </div>
                        <div class="mb-2 col-md-2">
                            <label for="employee_rank" class="form-label"> Rank</label>
                            <input type="text" wire:model="employee_rank" id="employee_rank"
                                class="form-control form-control-sm border border-1 p-2" placeholder="Enter rank">
                        </div>

                        <div class="mb-2 col-md-3">
                            <label for="email" class="form-label">Email </label>
                            <input type="email" wire:model="email" id="email"
                                class="form-control form-control-sm border border-1 p-2 {{ $errorClass['email'] ?? '' }}"
                                placeholder="Enter email">
                            @if(isset($errorMessage['email']))
                            <div class="text-danger error-message">{{ $errorMessage['email'] }}</div>
                            @endif
                        </div>
                        <div class="mb-2 col-md-3">
                            <label for="customer_image" class="form-label">Client Image <span class="small text-danger">*</span></label>
                            <input type="file" wire:model="customer_image" id="customer_image"
                                class="form-control form-control-sm border border-1 p-2 {{ $errorClass['customer_image'] ?? '' }}">
                            @if(isset($errorMessage['customer_image']))
                            <div class="text-danger error-message">{{ $errorMessage['customer_image'] }}</div>
                            @endif
                        </div>



                        <div class="mb-2 col-md-3">
                            <label for="dob" class="form-label">Date Of Birth</label>
                            <input type="date" autocomplete="bday" wire:model="dob" id="dob"
                                max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                class="form-control form-control-sm border border-1 p-2 {{ $errorClass['dob'] ?? '' }}">
                            {{-- @if(isset($errorMessage['dob']))
                            <div class="text-danger">{{ $errorMessage['dob'] }}</div>
                            @endif --}}
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-3 col-md-3">
                            <label for="phone" class="form-label">Phone Number <span
                                    class="text-danger">*</span></label>
                            <div class="extention-group">
                                <!-- Country Select Dropdown for Phone -->
                                <select wire:model="selectedCountryPhone"
                                    wire:change="GetCountryDetails($event.target.selectedOptions[0].getAttribute('data-length'), 'phone')"
                                    class="form-control form-control-sm prefix_select flex-30">
                                    <option value="" selected hidden>Code</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country->country_code }}"
                                        data-length="{{ $country->mobile_length }}">
                                        {{ $country->title }} ({{ $country->country_code }})
                                    </option>
                                    @endforeach
                                </select>

                                <!-- Phone Input Field -->
                                <input type="text" wire:model="phone" id="phone"
                                    class="form-control form-control-sm border border-1 p-2 {{ $errorClass['phone'] ?? '' }}"
                                    placeholder="Enter Phone Number" maxlength="{{ $mobileLengthPhone }}">

                                <!-- Error Message -->
                            </div>
                            @if(isset($errorMessage['phone']))
                            <div class="text-danger error-message">{{ $errorMessage['phone'] }}</div>
                            @endif
                            <div class="form-check-label-group">
                                <input type="checkbox" id="is_whatsapp1" wire:model="isWhatsappPhone">
                                <label for="is_whatsapp1" class="form-check-label ms-1">Is Whatsapp</label>
                            </div>
                        </div>

                       

                        <!-- Alternative Phone Number 1 -->
                        <div class="mb-3 col-md-3">
                            <label for="alternative_phone_number_1" class="form-label">Alternative Phone Number
                                1</label>
                            <div class="extention-group">
                                <!-- Country Select Dropdown for Alternative Phone 1 -->
                                <select wire:model="selectedCountryAlt1"
                                    wire:change="GetCountryDetails($event.target.selectedOptions[0].getAttribute('data-length'), 'alt_phone_1')"
                                    class="form-control form-control-sm prefix_select flex-30">
                                    <option value="" selected hidden>Code</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country->country_code }}"
                                        data-length="{{ $country->mobile_length }}">
                                        {{ $country->title }} ({{ $country->country_code }})
                                    </option>
                                    @endforeach
                                </select>

                                <!-- Alternative Phone 1 Input Field -->
                                <input type="text" wire:model="alternative_phone_number_1"
                                    class="form-control form-control-sm border border-1 p-2 {{ $errorClass['alternative_phone_number_1'] ?? '' }}"
                                    placeholder="Alternative Phone No" maxlength="{{ $mobileLengthAlt1 }}">

                                <!-- Error Message -->
                            </div>
                            @if(isset($errorMessage['alternative_phone_number_1']))
                            <div class="text-danger error-message">{{ $errorMessage['alternative_phone_number_1'] }}
                            </div>
                            @endif
                            <div class="form-check-label-group">
                                <input type="checkbox" id="is_whatsapp2" wire:model="isWhatsappAlt1">
                                <label for="is_whatsapp2" class="form-check-label ms-1">Is Whatsapp</label>
                            </div>
                        </div>

                        <!-- Alternative Phone Number 2 -->
                        <div class="mb-3 col-md-3">
                            <label for="alternative_phone_number_2" class="form-label">Alternative Phone Number
                                2</label>
                            <div class="extention-group">
                                <!-- Country Select Dropdown for Alternative Phone 2 -->
                                <select wire:model="selectedCountryAlt2"
                                    wire:change="GetCountryDetails($event.target.selectedOptions[0].getAttribute('data-length'), 'alt_phone_2')"
                                    class="form-control form-control-sm prefix_select flex-30">
                                    <option value="" selected hidden>Code</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country->country_code }}"
                                        data-length="{{ $country->mobile_length }}">
                                        {{ $country->title }} ({{ $country->country_code }})
                                    </option>
                                    @endforeach
                                </select>

                                <!-- Alternative Phone 2 Input Field -->
                                <input type="text" wire:model="alternative_phone_number_2"
                                    class="form-control form-control-sm border border-1 p-2 {{ $errorClass['alternative_phone_number_2'] ?? '' }}"
                                    placeholder="Alternative Phone No" maxlength="{{ $mobileLengthAlt2 }}">
                            </div>
                            @if(isset($errorMessage['alternative_phone_number_2']))
                            <div class="text-danger error-message">{{ $errorMessage['alternative_phone_number_2'] }}
                            </div>
                            @endif
                            <div class="form-check-label-group">
                                <input type="checkbox" id="is_whatsapp3" wire:model="isWhatsappAlt2">
                                <label for="is_whatsapp3" class="form-check-label ms-1">Is Whatsapp</label>
                            </div>
                        </div>


                    </div>

                    <div class="">
                        <div class="">
                            <h6 class="badge bg-danger custom_danger_badge">Address</h6>
                        </div>
                        <div class="pt-0">
                            <div class="admin__content">
                                <aside>
                                    <nav class="text-uppercase font-weight-bold"> Address</nav>
                                </aside>
                                <content>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-3">
                                            <label for="" class="col-form-label">Address <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" id="billing_addr" class="form-control form-control-sm"
                                                wire:model="billing_address" value="">
                                            @if(isset($errorMessage['billing_address']))
                                            <div class="text-danger error-message">{{ $errorMessage['billing_address']
                                                }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-3">
                                            <label for="" class="col-form-label">Landmark</label>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" id="billing_landmark"
                                                class="form-control form-control-sm" wire:model="billing_landmark"
                                                value="">
                                            @if(isset($errorMessage['billing_landmark']))
                                            <div class="text-danger error-message">{{ $errorMessage['billing_landmark']
                                                }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-3">
                                            <label for="" class="col-form-label">City <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" id="billing_city" class="form-control form-control-sm"
                                                wire:model="billing_city" value="">
                                            @if(isset($errorMessage['billing_city']))
                                            <div class="text-danger error-message">{{ $errorMessage['billing_city'] }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-3">
                                            <label for="" class="col-form-label">Country <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" id="billing_country" class="form-control form-control-sm"
                                                wire:model="billing_country" value="">
                                            @if(isset($errorMessage['billing_country']))
                                            <div class="text-danger error-message">{{ $errorMessage['billing_country']
                                                }}</div>
                                            @endif
                                        </div>
                                        <div class="col-3 text-end">
                                            <label for="" class="col-form-label">Pincode</label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" id="billing_pin" class="form-control form-control-sm"
                                                wire:model="billing_pin" value="">
                                            @if(isset($errorMessage['billing_pin']))
                                            <div class="text-danger error-message">{{ $errorMessage['billing_pin'] }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </content>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="{{$activeTab==2?" d-block":"d-none"}}" id="tab2">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-2 mb-md-0">
                            <h6 class="badge bg-danger custom_danger_badge">Product Information</h6>
                        </div>
                    </div>
                    @if ($errors->has('items'))
                    <div class="alert alert-danger">
                        {{ $errors->first('items') }}
                    </div>
                    @endif
                    {{-- Display Order by and order number --}}
                    <!-- Ordered By Section -->
                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Ordered By</strong></label>
                            <input type="text"
                                class="form-control border border-2 p-2 form-control-sm @error('salesman') border-danger  @enderror"
                                value="{{ auth()->guard('admin')->check() && $salesman == auth()->guard('admin')->user()->id ? auth()->guard('admin')->user()->name : optional($salesmen->where('id', $salesman)->first())->name }}"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Bill Number</strong></label>
                            <!-- Remaining Amount -->
                            <input type="text" class="form-control form-control-sm text-center border border-1" disabled
                                value="{{$order_number}}" readonly>
                        </div>
                    </div>

                    <!-- Loop through items -->
                    @foreach($items as $index => $item)
                    <div class="row align-items-top mt-3">
                        <!-- Collection  -->
                        <div class="col-md-2 col-12 mb-3">
                            <label class="form-label"><strong>Collection </strong><span
                                    class="text-danger">*</span></label>
                            <select wire:model="items.{{ $index }}.collection"
                                wire:change="GetCategory($event.target.value, {{ $index }})"
                                class="form-control border border-2 p-2 form-control-sm @error('items.'.$index.'.collection') border-danger @enderror">
                                <option value="" selected hidden>Select collection</option>
                                @foreach($collections as $citems)
                                <option value="{{ $citems->id }}">{{ ucwords($citems->title) }}
                                    @if($citems->short_code)({{ $citems->short_code }})@endif</option>
                                @endforeach
                            </select>
                            @error("items.".$index.".collection")
                            <div class='text-danger'>{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="col-md-2 col-12 mb-3">
                            <label class="form-label"><strong>Category</strong> <span
                                    class="text-danger">*</span></label>
                            <select wire:model="items.{{ $index }}.category"
                                class="form-select form-control-sm border border-1 @error('items.'.$index.'.category') border-danger @enderror"
                                wire:change="CategoryWiseProduct($event.target.value, {{ $index }})">
                                <option value="" selected hidden>Select Category</option>
                                @if (isset($items[$index]['categories']) && count($items[$index]['categories']) > 0)
                                @foreach ($items[$index]['categories'] as $category)
                                <option value="{{ $category['id'] }}">{{ $category['title'] }}</option>
                                @endforeach
                                {{-- @else
                                <option value="" disabled>Select Category</option> --}}
                                @endif
                            </select>
                            @error("items.".$index.".category")
                            <div class="text-danger error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Product -->
                        @if(isset($items[$index]['collection']) && $items[$index]['collection'] == 1)

                        <div class="mb-3 col-md-2">
                            @else
                            <div class="col-md-2 col-12 mb-3">
                                @endif
                                <label class="form-label"><strong>Product</strong></label>
                                <input type="text" wire:keyup="FindProduct($event.target.value, {{ $index }})"
                                    wire:model="items.{{ $index }}.searchproduct"
                                    class="form-control form-control-sm border border-1 customer_input @error('items.'.$index.'.searchproduct') border-danger @enderror"
                                    placeholder="Enter product name">
                                @if (session()->has('errorProduct.' . $index))
                                <p class="text-danger">{{ session('errorProduct.' . $index) }}</p>
                                @endif
                                @if(isset($items[$index]['products']) && count($items[$index]['products']) > 0)
                                <div id="fetch_customer_details" class="dropdown-menu show w-25"
                                    style="max-height: 200px; overflow-y: auto;">
                                    @foreach ($items[$index]['products'] as $product)
                                    <button class="dropdown-item" type="button"
                                        wire:click='selectProduct({{ $index }}, "{{ $product->name }}", {{ $product->id }})'>
                                        <img src="{{ $product->product_image ? asset($product->product_image) : asset('assets/img/cubes.png') }}"
                                            alt=""> {{ $product->name }}({{ $product->product_code }})
                                    </button>
                                    @endforeach
                                </div>
                                @endif
                                @error("items.$index.searchproduct")
                                <div class="text-danger error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            @if(isset($items[$index]['collection']) && $items[$index]['collection'] != 1)
                            <div class="col-md-2 col-12 mb-3">
                                <label class="form-label"><strong>Quantity</strong>
                                    @if($items[$index]['collection'] == 2)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="number"
                                    wire:model="items.{{ $index }}.quantity"
                                    class="form-control form-control-sm border border-1 customer_input
                                        @error('items.' . $index . '.quantity') border-danger @enderror"
                                    placeholder="Enter quantity" min="1">
                                @error('items.' . $index . '.quantity')
                                <div class="text-danger error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            @else
                                {{-- Hidden field for collection 1 --}}
                                <input type="hidden" wire:model="items.{{ $index }}.quantity">
                            @endif

                            @if(isset($items[$index]['collection']) && $items[$index]['collection'] == 1)
                            {{-- Fabric --}}
                            <div class="col-md-2 col-12 mb-3 position-relative">
                                <label class="form-label"><strong>Fabric</strong></label>
                                <input type="text" wire:model="items.{{ $index }}.searchTerm"
                                    wire:keyup="searchFabrics({{ $index }})" class="form-control form-control-sm"
                                    placeholder="Search by fabric name" id="searchFabric_{{ $index }}"
                                    autocomplete="off">
                                @error("items.". $index .".searchTerm")
                                <div class="text-danger error-message">{{ $message }}</div>
                                @enderror

                                @if(!empty($items[$index]['searchResults']))
                                <div class="dropdown-menu show w-100" style="max-height: 187px; max-width: 100px; overflow-y: auto;">
                                    @foreach ($items[$index]['searchResults'] as $fabric)
                                    <button class="dropdown-item fabric_dropdown_item" type="button"
                                        wire:click="selectFabric({{ $fabric->id }}, {{ $index }})">
                                        {{ $fabric->title }}
                                    </button>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            {{-- <div class="col-12 col-md-2"></div> --}}
                            {{-- Price --}}
                            <div class="col-md-2 col-12 mb-3">
                                <div class="d-flex align-items-end">
                                    <!-- Price Input -->
                                    <div>
                                        <label class="form-label"><strong>Price</strong></label>
                                        <input type="text"
                                            wire:keyup="checkproductPrice($event.target.value, {{ $index }})"
                                            wire:model="items.{{ $index }}.price" class="form-control form-control-sm border border-1 customer_input
                                            @if(session()->has('errorPrice.' . $index)) border-danger @endif
                                            @error('items.' . $index . '.price') border-danger  @enderror"
                                            placeholder="Enter Price">
                                    </div>
                                    
                                    <div>
                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-danger btn-sm danger_btn mb-0"
                                            wire:click="removeItem({{ $index }})">
                                            <span class="material-icons">delete</span>
                                        </button>
                                    </div>
                                </div>
                                {{-- <div>hi</div> --}}

                                <!-- Error Messages -->
                                @if(session()->has('errorPrice.' . $index))
                                <div class="text-danger error-message">{{ session('errorPrice.' . $index) }}</div>
                                @endif

                                @error('items.' . $index . '.price')
                                <div class="text-danger error-message">{{ $message }}</div>
                                @enderror

                            </div>
                            
                            {{-- --}}
                            @else
                            {{--Garment item Price --}}
                            <div class="col-md-3 col-12 mb-3">
                                <div class="d-flex align-items-end gap-2">
                                    <div>
                                        <!-- Price Input -->
                                        <label class="form-label"><strong>Price</strong></label>
                                        <input type="text"
                                            wire:keyup="checkproductPrice($event.target.value, {{ $index }})"
                                            wire:model="items.{{ $index }}.price" class="form-control form-control-sm border border-1 customer_input
                                                        @if(session()->has('errorPrice.' . $index)) border-danger @endif
                                                        @error('items.' . $index . '.price') border-danger  @enderror"
                                            placeholder="Enter Price">
                                    </div>
                                    <div>
                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-danger btn-sm danger_btn mb-0"
                                            wire:click="removeItem({{ $index }})"><span
                                                class="material-icons">delete</span>
                                        </button>
                                    </div>
                                </div>
                                <!-- Error Messages -->
                                @if(session()->has('errorPrice.' . $index))
                                <div class="text-danger error-message">{{ session('errorPrice.' . $index) }}</div>
                                @endif

                                @error('items.' . $index . '.price')
                                <div class="text-danger error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            @if(isset($items[$index]['collection']) && $items[$index]['collection'] == 2)
                            <div class="row mb-3">
                                 <div class="col-md-2">
                                    <label for="">Expected Delivery Date</label>
                                    <input type="date" class="form-control form-control-sm border border-1" wire:model="items.{{$index}}.expected_delivery_date"
                                     min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                    @error("items.$index.expected_delivery_date")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label"><strong>Priority Level</strong></label>
                                    <select class="form-control form-control-sm border border-1"
                                            wire:model="items.{{ $index }}.priority">
                                        <option value="" hidden>Select Priority</option>
                                        <option value="Priority">Priority</option>
                                        <option value="Non Priority">Non Priority</option>
                                    </select>
                                    @error("items.$index.priority")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2" >
                                    <label class="form-label"><strong>Item Status</strong></label>
                                    <select class="form-control form-control-sm border border-1"
                                            wire:model="items.{{ $index }}.item_status">
                                        <option value="" hidden>Select Item Status</option>
                                        <option value="Process">Process</option>
                                        <option value="Hold">Hold</option>
                                    </select>
                                    @error("items.$index.item_status")
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>

                        {{-- Append Measurements data --}}
                        @if(isset($this->items[$index]['product_id']) && $items[$index]['collection'] == 1)
                        <div class="row">
                            <div class="col-12 col-md-6 mb-2 mb-md-0 measurement_div">
                                <h6 class="badge bg-danger custom_success_badge">Measurements</h6> <span class="text-danger">*</span>
                                <!-- Checkbox to Copy Previous Measurements -->
                                @if($index > 0)
                                <!-- Show checkbox only for second item onwards -->
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input"
                                        wire:model="items.{{ $index }}.copy_previous_measurements"
                                        wire:change="copyMeasurements({{ $index }})"
                                        id="copy_measurements_{{ $index }}">

                                    <label class="form-check-label" for="copy_measurements_{{ $index }}">
                                        Use previous measurements
                                    </label>
                                </div>
                                @endif
                                <div class="row">
                                    @if(isset($items[$index]['measurements']) &&
                                    count($items[$index]['measurements']) > 0)
                                    @foreach ($items[$index]['measurements'] as $measurement)
                                    <div class="col-md-3">
                                        {{-- {{dd($measurement)}} --}}
                                        <div class="measurement-col">
                                            <label>{{ $measurement['title'] }}
                                                <strong>[{{$measurement['short_code']}}]</strong></label>
                                            <input type="hidden"
                                                wire:model="items.{{ $index }}.get_measurements.{{ $measurement['id'] }}.title"
                                                value="{{ $measurement['title'] }}">
                                            <input type="number" required
                                                class="form-control form-control-sm border border-1 customer_input measurement_input"
                                                wire:model="items.{{ $index }}.get_measurements.{{ $measurement['id'] }}.value"
                                                  wire:keyup="validateMeasurement({{ $index }}, {{ $measurement['id'] }})">
                                            @error('items.' . $index . '.get_measurements.' .$measurement['id'].'.value')
                                            <div class="text-danger error-message">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                    @if (session()->has('measurements_error.' . $index))
                                    <div class="alert alert-danger mt-2">
                                        {{ session('measurements_error.' . $index) }}
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="row">
                                    {{-- Catalogue --}}
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label"><strong>Catalogue</strong></label>
                                        <select wire:model="items.{{ $index }}.selectedCatalogue"
                                            class="form-control form-control-sm border border-1 @error('items.'.$index.'.selectedCatalogue') border-danger @enderror"
                                            wire:change="SelectedCatalogue($event.target.value, {{ $index }})">
                                            <option value="" selected hidden>Select Catalogue</option>
                                            @foreach($catalogues[$index] ?? [] as $id => $title)
                                            <option value="{{ $id }}">{{ $title }}
                                                @if(isset($maxPages[$index][$id]))
                                                (1 - {{ $maxPages[$index][$id] }})
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                        @error("items." .$index. ".selectedCatalogue")
                                        <div class="text-danger error-message">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Page number --}}
                                    <div class="mb-3 col-md-3">
                                        <label class="form-label"><strong>Page Number</strong></label>
                                        <input type="number" wire:model="items.{{$index}}.page_number"
                                            wire:keyup="validatePageNumber($event.target.value,{{ $index }})"
                                            id="page_number"
                                            class="form-control form-control-sm border border-2 @error('items.'.$index.'.page_number') border-danger @enderror"
                                            min="1"
                                            max="{{ isset($items[$index]['selectedCatalogue']) && isset($maxPages[$index][$items[$index]['selectedCatalogue']]) ? $maxPages[$index][$items[$index]['selectedCatalogue']] : '' }}">
                                        @error("items.".$index.".page_number")
                                        <div class="text-danger error-message">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Page Item --}}
                                    <div class="mb-3 col-md-5">
                                        @if(isset($catalogue_page_item) && !empty($catalogue_page_item[$index]))
                                        <label class="form-label"><strong>Page Item</strong></label>

                                        <select wire:model="items.{{$index}}.page_item"
                                            class="form-control form-control-sm border border-2 @error('items.'.$index.'.page_item') border-danger @enderror">
                                            <option value="" selected hidden>Select Page Item</option>
                                            @foreach($pageItems[$index] ?? [] as $id => $item)
                                            <option value="{{ $item->catalog_item  }}">
                                                {{ $item->catalog_item }}
                                            </option>
                                            @endforeach
                                        </select>

                                        @error("items.".$index.".page_item")
                                        <div class="text-danger error-message">{{ $message }}</div>
                                        @enderror
                                        @endif
                                    </div>

                                </div>
                                @if(isset($items[$index]['collection']) && $items[$index]['collection'] == 1)
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                         <label for="">Expected Delivery Date</label>
                                         <input type="date" class="form-control form-control-sm border border-1" wire:model="items.{{$index}}.expected_delivery_date"
                                         min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                         @error("items.$index.expected_delivery_date")
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- Fittings --}}
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Fittings</strong></label>
                                        <select class="form-control form-control-sm border border-1"
                                                wire:model="items.{{ $index }}.fitting">
                                            <option value="" hidden>Select Fitting</option>
                                            <option value="Regular Fit">Regular Fit</option>
                                            <option value="Slim Fit">Slim Fit</option>
                                            <option value="Loose Fit">Loose Fit</option>
                                        </select>
                                        @error("items.$index.fitting")
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Priority Level --}}
                                    <div class="col-md-4">
                                        <label class="form-label"><strong>Priority Level</strong></label>
                                        <select class="form-control form-control-sm border border-1"
                                                wire:model="items.{{ $index }}.priority">
                                            <option value="" hidden>Select Priority</option>
                                            <option value="Priority">Priority</option>
                                            <option value="Non Priority">Non Priority</option>
                                        </select>
                                        @error("items.$index.priority")
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4" >
                                        <label class="form-label"><strong>Item Status</strong></label>
                                        <select class="form-control form-control-sm border border-1"
                                                wire:model="items.{{ $index }}.item_status">
                                            <option value="" hidden>Select Item Status</option>
                                            <option value="Process">Process</option>
                                            <option value="Hold">Hold</option>
                                        </select>
                                        @error("items.$index.item_status")
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            @endif

                                
                                <div class="row">
                                    {{-- Image Upload Section --}}
                                    <div class="mb-3 col-12">
                                        <div class="d-flex align-items-start gap-3 flex-wrap">
                                            {{-- Image Preview on Left --}}
                                            @if (!empty($imageUploads[$index]))
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($imageUploads[$index] as $imgIndex => $img)
                                                <div style="position: relative; width: 70px;">
                                                    <img src="{{ $img->temporaryUrl() }}" class="img-thumbnail" style="width: 100%;" />
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger rounded-circle p-1 position-absolute top-0 end-0"
                                                        style="width: 22px; height: 22px; font-size: 12px; display: flex; align-items: center; justify-content: center;"
                                                        wire:click="removeUploadedImage({{ $index }}, {{ $imgIndex }})">
                                                        &times;
                                                    </button>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif

                                            {{-- Upload Button on Right --}}
                                            <div class="ms-auto text-end">
                                                <button type="button" class="btn btn-cta btn-sm"
                                                    onclick="document.getElementById('catalog-upload-{{ $index }}').click()">
                                                    <i class="material-icons text-white" style="font-size: 15px;">add</i>
                                                    Upload Images
                                                </button>
                                                <input type="file" id="catalog-upload-{{ $index }}" multiple
                                                    wire:model="imageUploads.{{ $index }}" accept="image/*" class="d-none" />
                                                @error('imageUploads.*')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Voice Upload Section --}}
                                    <div class="mb-3 col-12">
                                        <div class="d-flex align-items-start gap-3 flex-wrap">
                                            {{-- Voice Preview on Left --}}
                                            @if (!empty($voiceUploads[$index]))
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($voiceUploads[$index] as $voiceIndex => $voice)
                                                <div style="width: 150px; position: relative;">
                                                    <audio controls style="width: 100%;">
                                                        <source src="{{ $voice->temporaryUrl() }}" type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger rounded-circle p-1 position-absolute top-0 end-0"
                                                        style="width: 22px; height: 22px; font-size: 12px; display: flex; align-items: center; justify-content: center;"
                                                        wire:click="removeUploadedVoice({{ $index }}, {{ $voiceIndex }})">
                                                        &times;
                                                    </button>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif

                                            {{-- Upload Button on Right --}}
                                            <div class="d-flex align-items-center gap-3">
                                                <!-- Upload Voice Button -->
                                                <button type="button" class="btn btn-cta btn-sm"
                                                    onclick="document.getElementById('voice-upload-{{ $index }}').click()">
                                                    <i class="material-icons text-white" style="font-size: 15px;">mic</i>
                                                    Upload Voice
                                                </button>

                                                <!-- OR separator -->
                                                <span class="fw-bold text-muted">OR</span>

                                                <!-- Start / Stop Buttons -->
                                                <div class="ms-auto text-end d-flex gap-2">
                                                    <button type="button" class="btn btn-cta btn-sm"
                                                        onclick="startRecording({{ $index }});" id="startBtn_{{ $index }}">
                                                        Start Recording
                                                        <i class="material-icons text-white" style="font-size: 15px;">record_voice_over</i>
                                                    </button>
                                                    <button type="button" class="btn btn-cta btn-sm"
                                                        onclick="stopRecording({{ $index }});" id="stopBtn_{{ $index }}" disabled>
                                                        Stop Recording
                                                        <i class="material-icons text-white" style="font-size: 15px;">stop_circle</i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="ms-auto text-end">

                                                <input type="file" id="voice-upload-{{ $index }}" multiple
                                                    wire:model="voiceUploads.{{ $index }}" accept="audio/*" class="d-none" />
                                                @error('voiceUploads.*')
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        @else

                        @endif
                        <div class="row">
                            <div class="col-12 col-md-2">
                                <label class="form-label"><strong>Remarks</strong></label>
                                <textarea type="text" wire:model="items.{{ $index }}.remarks"
                                    class="form-control form-control-sm border border-1 customer_input"
                                    placeholder="Enter Product Remarks"></textarea>
                                @error("items.".$index.".remarks")
                                <div class="text-danger error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endforeach
                        <!-- Add Item Button -->
                        <div class="row align-items-end my-4">
                            <div class="col-md-8 col-12"></div>
                            <div class="col-md-4 col-12">
                                <table>
                                    <tr>
                                        <td colspan="2">
                                            <table style="width:100%;">
                                                <tbody>
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            @if (session()->has('errorAmount'))
                                                            <div class="alert alert-danger">
                                                                {{ session('errorAmount') }}
                                                            </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: end;">
                                                            <button type="button" class="btn btn-cta btn-sm"
                                                                wire:click="addItem">
                                                                <i class="material-icons text-white"
                                                                    style="font-size: 15px;">add</i>
                                                                Add Item
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-70"><label class="form-label"><strong>Air Mail</strong></label>
                                        </td>
                                        <td>
                                            <!-- Sub Total -->
                                            <input type="number" class="form-control form-control-sm"
                                                wire:model="air_mail" wire:keyup="updateBillingAmount"
                                                placeholder="Enter air mail cost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-70"><label class="form-label"><strong>Total Amount</strong></label>
                                        </td>
                                        <td>
                                            <!-- Sub Total -->
                                            <input type="text" class="form-control form-control-sm text-center"
                                                wire:model="billing_amount" disabled
                                                value="{{ number_format($billing_amount, 2) }}">
                                        </td>
                                    </tr>

                                    
                                </table>
                            </div>
                            <div class="col-md-4 col-12"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center mb-3">
                        @if($activeTab>1)
                        <button type="button" class="btn btn-dark mx-2 btn-sm"
                            wire:click="TabChange({{$activeTab-1}})"><i
                                class="material-icons text-white">chevron_left</i>Previous</button>
                        <button type="submit" class="btn btn-primary mx-2 btn-sm"><i
                                class="material-icons text-white">add</i>Generate Order</button>
                        @endif
                        @if($activeTab==1)
                        <button type="button" id="nextTab" class="btn btn-sm btn-success mx-2"
                            wire:click="TabChange({{$activeTab+1}})">Next<i
                                class="material-icons text-white">chevron_right</i></button>
                        @endif

                    </div>
            </form>
            <!-- Tabs content -->
        </div>
    </div>

</div>
<script>
    const mediaRecorders = {};
    const audioChunksMap = {};

    //  Assigns a file to a hidden file input so Livewire can pick it up
    function assignFileToInput(index, file) {
        const dt = new DataTransfer();
        dt.items.add(file);

        const input = document.getElementById(`voice-upload-${index}`);
        input.files = dt.files;

        // Trigger Livewire to pick it up
        const event = new Event('change', { bubbles: true });
        input.dispatchEvent(event);

        console.log(` File assigned to input #voice-upload-${index}`);
    }

    // Start recording
    async function startRecording(index) {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        const mediaRecorder = new MediaRecorder(stream);
        const chunks = [];

        mediaRecorder.ondataavailable = (e) => {
            chunks.push(e.data);
        };

        mediaRecorder.onstop = () => {
            const blob = new Blob(chunks, { type: 'audio/webm' });
            const file = new File([blob], `recording_${index}.webm`, { type: 'audio/webm' });

            assignFileToInput(index, file); //  assign file to Livewire input
        };

        mediaRecorders[index] = mediaRecorder;
        audioChunksMap[index] = chunks;

        mediaRecorder.start();

        // Optional UI state
        document.getElementById(`startBtn_${index}`).disabled = true;
        document.getElementById(`stopBtn_${index}`).disabled = false;
    }

    // Stop recording
    function stopRecording(index) {
        if (mediaRecorders[index]) {
            mediaRecorders[index].stop();
        }

        // Optional UI state
        document.getElementById(`startBtn_${index}`).disabled = false;
        document.getElementById(`stopBtn_${index}`).disabled = true;
    }
    function assignFileToInput(index, file) {
    const dt = new DataTransfer();
    dt.items.add(file);

    const input = document.getElementById(`voice-upload-${index}`);
    input.files = dt.files;

    input.dispatchEvent(new Event('change', { bubbles: true }));
}

</script>
<script>
    window.addEventListener('error_message', event => {
        setTimeout(() => {
            let errorElement = document.querySelector(".error-message");
            if (errorElement) {
                errorElement.scrollIntoView({ behavior: "smooth", block: "center" });
            }
        }, 100);
    });

document.addEventListener("DOMContentLoaded", function() {
  document.addEventListener("click", function(event) {
    if (event.target.closest("#nextTab")) {
      setTimeout(function () {
        const scrollContainer = document.querySelector('#sales_order_data'); // Replace with real selector
        if (scrollContainer) {
          scrollContainer.scrollTo({
            top: 0,
            behavior: "smooth"
          });
        } else {
          console.log("Scroll container not found");
        }
      }, 300);
    }
  });
});


</script>