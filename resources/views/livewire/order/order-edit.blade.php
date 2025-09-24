<div class="container">
    {{-- <style>
        .breadcrumb_menu li a {
            color: #fff !important;
        }
    </style> --}}
    <section class="admin__title">
        <h5>Update Order <span class="badge bg-success custom_success_badge">{{env('ORDER_PREFIX') .
                $order_number}}</span></h5>
    </section>
    <section>
        <ul class="breadcrumb_menu">
            <li>Sales Management</li>
            <li><a href="{{route('admin.order.edit',$orders->id)}}">Update Order</a></li>
            <li class="back-button">
                @if($activeTab==1)
                <a class="btn btn-sm btn-danger select-md text-light font-weight-bold mb-0"
                    href="{{route('admin.order.index')}}" role="button">
                    <i class="material-icons" style="font-size: 15px;">chevron_left</i>
                    <span class="ms-1">Back</span>
                </a>
                @endif
            </li>
        </ul>
    </section>
    <div class="card my-4">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                @if($activeTab==1 && $salesmanBill == null)
                <div class="badge bg-primary">
                    <a href="{{ route('salesman.index') }}"> <strong>Error:</strong> Please add a bill for this user
                        before placing the order.</a>
                </div>
                @endif
            </div>
        </div>
        <div class="card-body" id="sales_order_data">
            <form wire:submit.prevent="update">
                <div class="{{$activeTab==1?" d-block":"d-none"}}" id="tab1">
                    <div class="row d-flex justify-content-between align-items-center mb-2">
                        <!-- Customer Information Badge -->
                        <div class="col-md-4">
                            <h6 class="badge bg-danger custom_danger_badge mb-0">Basic Information</h6>
                        </div>
                        <div class="col-md-8 d-flex justify-content-end gap-3">
                            {{--
                            <div>
                                <!-- Search Label -->
                                <label for="searchCustomer" class="form-label mb-0">Country</label>
                                <div class="position-relative">
                                    <input type="text" wire:keyup="FindCountry($event.target.value)"
                                        wire:model.debounce.500ms="search"
                                        class="form-control form-control-sm border border-1 customer_input"
                                        placeholder="Search By Country">
                                    @if(isset($errorMessage['search']))
                                    <div class="text-danger">{{ $errorMessage['search'] }}</div>
                                    @endif
                                    @if(!empty($filteredCountries))
                                    <div id="fetch_customer_details" class="dropdown-menu show w-100"
                                        style="max-height: 200px; overflow-y: auto;">
                                        @foreach ($filteredCountries as $countries)
                                        <button class="dropdown-item" type="button"
                                            wire:click="selectCountry({{ $countries->id }})">
                                            {{$countries->title}}({{$countries->country_code}})
                                        </button>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div> --}}
                            <div class="section-header-filter">
                                <!-- Search Label -->
                                <label for="searchCustomer" class="form-label mb-0">Business Type:&nbsp;</label>
                                <select wire:model="selectedBusinessType" class="form-select form-control"
                                    aria-label="Default select example">
                                    <option selected hidden>Select Domain</option>
                                    @foreach ($Business_type as $domain)
                                    <option value="{{$domain->id}}">{{$domain->title}}</option>
                                    @endforeach
                                </select>
                                @if(isset($errorMessage['selectedBusinessType']))
                                <div class="text-danger">{{ $errorMessage['selectedBusinessType'] }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Customer Details -->
                    {{-- <div class="container"> --}}
                        <!-- Customer Details -->
                        <div class="row">
                            <div class="mb-2 col-md-3">
                                <input type="hidden" name="customer_id" wire:model="customer_id">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select wire:model="prefix" class="form-control form-control-sm border border-1"
                                        style="max-width: 60px;">
                                        <option value="" selected hidden>Prefix</option>
                                        @foreach (App\Helpers\Helper::getNamePrefixes() as $prefixOption)
                                        <option value="{{$prefixOption}}">{{ $prefixOption }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" wire:model="name" id="name"
                                        class="form-control form-control-sm border border-1 p-2 {{ $errorClass['name'] ?? '' }}"
                                        placeholder="Enter Customer Name">
                                </div>
                                @if(isset($errorMessage['name']))
                                <div class="text-danger">{{ $errorMessage['name'] }}</div>
                                @endif
                            </div>

                            <div class="mb-2 col-md-4">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" wire:model="company_name" id="company_name"
                                    class="form-control form-control-sm border border-1 p-2"
                                    placeholder="Enter Company Name">
                            </div>

                            <div class="mb-2 col-md-2">
                                <label for="employee_rank" class="form-label"> Rank</label>
                                <input type="text" wire:model="employee_rank" id="employee_rank"
                                    class="form-control form-control-sm border border-1 p-2" placeholder="Enter Rank">
                            </div>

                            <div class="mb-2 col-md-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" wire:model="email" id="email"
                                    class="form-control form-control-sm border border-1 p-2 {{ $errorClass['email'] ?? '' }}"
                                    placeholder="Enter Email">
                                @if(isset($errorMessage['email']))
                                <div class="text-danger">{{ $errorMessage['email'] }}</div>
                                @endif
                            </div>
                            
                             <div class="mb-2 col-md-3">
                                <label for="customer_image" class="form-label">Client Image <span class="small text-danger">*</span></label>
                                <input type="file" wire:model="customer_image" id="customer_image"
                                    class="form-control form-control-sm border border-1 p-2 {{ $errorClass['customer_image'] ?? '' }}">
                                @if(isset($errorMessage['customer_image']))
                                <div class="text-danger error-message">{{ $errorMessage['customer_image'] }}</div>
                                @endif
                                 @if ($customer_image)
                                    <div class="mt-2">
                                        <img src="{{ asset($customer_image) }}" alt="" class="img-thumbnail" width="100">
                                    </div>
                                @endif
                           </div>

                            <div class="mb-2 col-md-3">
                                <label for="dob" class="form-label">Date Of Birth </label>
                                <input type="date" wire:model="dob" id="dob" max="{{date('Y-m-d')}}"
                                    class="form-control form-control-sm border border-1 p-2 {{ $errorClass['dob'] ?? '' }}">
                                {{-- @if(isset($errorMessage['dob']))
                                <div class="text-danger">{{ $errorMessage['dob'] }}</div>
                                @endif --}}
                            </div>
                            <!-- Phone Number -->
                            <div class="mb-2 col-md-3">
                                <label for="mobile" class="form-label">Phone Number</label>
                                <div class="input-group input-group-sm" id="parent_mobile" wire:ignore>
                                    <input id="mobile" type="tel" class="form-control tel-code-input" style="width:286px;">
                                    <!-- hidden Livewire bindings -->
                                    <input type="hidden" wire:model="phone_code" id="phone_code">
                                    <input type="hidden" wire:model="phone" id="phone">

                                </div>

                                @if(isset($errorMessage['phone']))
                                    <div class="text-danger error-message">{{ $errorMessage['phone'] }}</div>
                                @enderror
                                <div class="form-check-label-group">
                                    <input type="checkbox" id="is_whatsapp1" wire:model="isWhatsappPhone">
                                    <label for="is_whatsapp1" class="form-check-label ms-1">Is Whatsapp</label>
                                </div>
                            </div>

                            <!-- Alternative Phone Number 1 -->
                            <div class="mb-2 col-md-3">
                                <label for="alt_phone_1" class="form-label">Alternative Phone 1</label>
                                <div class="input-group input-group-sm" id="parent_alt_phone_code_1" wire:ignore>
                                    <input id="alt_phone_1" type="tel" class="form-control tel-code-input" style="width:269px;">
                                    <input type="hidden" wire:model="alt_phone_code_1" id="alt_phone_code_1">
                                    <input type="hidden" wire:model="alternative_phone_number_1" id="alt_phone_hidden_1">
                                </div>
                                @if(isset($errorMessage['alternative_phone_number_1']))
                                    <div class="text-danger error-message">{{ $errorMessage['alternative_phone_number_1'] }}</div>
                                @endif
                                <div class="form-check-label-group">
                                    <input type="checkbox" id="is_whatsapp2" wire:model="isWhatsappAlt1">
                                    <label for="is_whatsapp2" class="form-check-label ms-1">Is Whatsapp</label>
                                </div>
                            </div>

                            <!-- Alternative Phone Number 2 -->
                            <div class="mb-2 col-md-3">
                                <label for="alt_phone_2" class="form-label">Alternative Phone 2</label>
                                <div class="input-group input-group-sm" id="parent_alt_phone_code_2" wire:ignore>
                                    <input id="alt_phone_2" type="tel" class="form-control tel-code-input" style="width:269px;">
                                    <input type="hidden" wire:model="alt_phone_code_2" id="alt_phone_code_2">
                                    <input type="hidden" wire:model="alternative_phone_number_2" id="alt_phone_hidden_2">
                                </div>
                                @if(isset($errorMessage['alternative_phone_number_2']))
                                    <div class="text-danger error-message">{{ $errorMessage['alternative_phone_number_2'] }}</div>
                                @endif
                                <div class="form-check-label-group">
                                    <input type="checkbox" id="is_whatsapp3" wire:model="isWhatsappAlt2">
                                    <label for="is_whatsapp3" class="form-check-label ms-1">Is Whatsapp</label>
                                </div>
                            </div>
                        </div>

                    {{-- </div> --}}

                    <div class="">
                        <div class="">
                            <h6 class="badge bg-danger custom_danger_badge">Address</h6>
                        </div>
                        <div class="pt-0">

                            <div class="admin__content">
                                {{-- Billing Address --}}
                                <aside>
                                    <nav class="text-uppercase font-weight-bold">Address</nav>
                                </aside>
                                <content>
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-3">
                                            <label for="billing_address" class="col-form-label"> Address <span
                                                    class="text-danger">*</span>
                                            </label>
                                        </div>
                                        <div class="col-9">
                                            <input wire:model="billing_address" id="billing_address" cols="30" rows="3"
                                                class="form-control form-control-sm border border-1 p-2 {{ $errorClass['billing_address'] ?? '' }}"
                                                placeholder="Enter billing address">
                                            @if(isset($errorMessage['billing_address']))
                                            <div class="text-danger">{{ $errorMessage['billing_address'] }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-2 align-items-center">
                                        <div class="col-3">
                                            <label for="billing_landmark" class="form-label">Landmark</label>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" wire:model="billing_landmark" id="billing_landmark"
                                                class="form-control form-control-sm border border-1 p-2"
                                                placeholder="Enter landmark">
                                        </div>
                                    </div>

                                    <div class="row mb-2 align-items-center">
                                        <div class="col-3">
                                            <label for="billing_city" class="form-label">City <span
                                                    class="text-danger">*</span></label>
                                        </div>
                                        <div class="col-9">
                                            <input type="text" wire:model="billing_city" id="billing_city"
                                                class="form-control form-control-sm border border-1 p-2 {{ $errorClass['billing_city'] ?? '' }}"
                                                placeholder="Enter city">
                                            @if(isset($errorMessage['billing_city']))
                                            <div class="text-danger">{{ $errorMessage['billing_city'] }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-2 align-items-center">
                                        <div class="col-3">
                                            <label for="billing_country" class="form-label">Country <span
                                                    class="text-danger">*</span>
                                            </label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" wire:model="billing_country" id="billing_country"
                                                class="form-control form-control-sm border border-1 p-2 {{ $errorClass['billing_country'] ?? '' }}"
                                                placeholder="Enter country">
                                            @if(isset($errorMessage['billing_country']))
                                            <div class="text-danger">{{ $errorMessage['billing_country'] }}</div>
                                            @endif
                                        </div>
                                        <div class="col-3 text-end">
                                            <label for="billing_pin" class="form-label">Pincode </label>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" wire:model="billing_pin" id="billing_pin"
                                                class="form-control form-control-sm border border-1 p-2 {{ $errorClass['billing_pin'] ?? '' }}"
                                                placeholder="Enter PIN">
                                            @if(isset($errorMessage['billing_pin']))
                                            <div class="text-danger">{{ $errorMessage['billing_pin'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </content>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="{{ $activeTab == 2 ? 'd-block' : 'd-none' }}" id="tab2">
                    <div class="row">
                        <div class="col-12 col-md-12 mb-2">
                            <h6 class="badge bg-danger custom_danger_badge">Product Information</h6>
                        </div>
                    </div>
                    
                    <!-- Loop through items -->
                    @foreach($items as $index => $item)
                     @php
                        $status = $item['status'] ?? null;
                        $tlStatus = $item['tl_status'] ?? null;
                    @endphp
                    <div class="row align-items-center my-3">
                        <!-- Collection -->
                        <div class="mb-2 col-md-2">
                            <label class="form-label"><strong>Collection </strong><span
                                    class="text-danger">*</span></label>
                            <select wire:model="items.{{ $index }}.selected_collection"
                                wire:change="GetCategory($event.target.value, {{ $index }})"
                                class="form-control border border-2 p-2 form-control-sm">
                                <option value="" selected hidden>Select collection</option>
                                @foreach($collections as $citems)
                                <option value="{{ $citems->id }}" {{$item['selected_collection']==$citems->id ?
                                    "selected" : ""}}>{{ ucwords($citems->title) }}
                                    @if($citems->short_code)
                                    ({{ $citems->short_code }})
                                    @endif
                                </option>
                                @endforeach
                            </select>
                            @error("items.".$index.".selected_collection")
                            <p class='text-danger inputerror'>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-2 col-md-2">
                            <label class="form-label"><strong>Category</strong></label>
                            <select wire:model="items.{{ $index }}.selected_category"
                                class="form-select form-control-sm border border-1"
                                wire:change="CategoryWiseProduct($event.target.value, {{ $index }})">
                                <option value="" selected hidden>Select Category</option>
                                @foreach ($item['categories'] as $category)
                                <option value="{{ $category->id }}" {{$item['selected_category']==$category->
                                    id?"selected":""}}>{{ $category->title }}</option>
                                @endforeach
                            </select>
                            @error("items.".$index.".selected_category")
                            <p class="text-danger inputerror">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Product -->
                        @if(isset($item['selected_collection']) && $item['selected_collection'] == 1)
                        <div class="mb-2 col-md-2">
                            @else
                            
                            <div class="mb-2 col-md-3">
                                @endif
                                <label class="form-label"><strong>Product</strong></label>
                                <input type="text" wire:keyup="FindProduct($event.target.value, {{ $index }})"
                                    wire:model="items.{{ $index }}.searchproduct"
                                    class="form-control form-control-sm border border-1 customer_input"
                                    placeholder="Enter product name" value="{{ $item['searchproduct'] }}">

                                @error("items.".$index.".searchproduct")
                                <p class="text-danger inputerror">{{ $message }}</p>
                                @enderror

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
                            </div>
                            @if(isset($item['selected_collection']) && $item['selected_collection'] != 1)
                                {{-- Quantity field shown for all except collection 1 --}}
                                <div class="col-md-2 col-12 mb-3">
                                    <label class="form-label"><strong>Quantity</strong><span class="text-danger">*</span></label>
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
                                {{-- Hidden quantity field for collection 1 to preserve value --}}
                                <input type="hidden" wire:model="items.{{ $index }}.quantity">
                            @endif

                            @if(isset($item['selected_collection']) && $item['selected_collection'] == 1)
                            <!-- Fabrics -->
                            <div class="mb-2 col-12 col-md-2">
                                <label class="form-label"><strong>Fabric</strong></label>
                                <input type="text" wire:model="items.{{ $index }}.searchTerm"
                                    wire:keyup="searchFabrics({{ $index }})" class="form-control form-control-sm"
                                    placeholder="Search by fabric name" id="searchFabric_{{ $index }}"
                                    value="{{ optional(collect($items[$index]['fabrics'])->firstWhere('id', $items[$index]['selected_fabric']))->title }}"
                                    autocomplete="off" >
                                @error("items.". $index .".searchTerm")
                                <div class="text-danger">{{ $message }}</div>
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
                            {{-- Price --}}
                            <div class="mb-2 col-12 col-md-2">
                                <div class="d-flex align-items-end gap-2">
                                    <!-- Price Input -->
                                    <div>
                                        <label class="form-label"><strong>Price</strong></label>
                                        <input type="text"
                                            wire:keyup="checkproductPrice($event.target.value, {{ $index }})"
                                            wire:model="items.{{ $index }}.price" class="form-control form-control-sm border border-1 customer_input
                                            @if(session()->has('errorPrice.' . $index)) border-danger @endif
                                            @error('items.' . $index . '.price') border-danger  @enderror"
                                            placeholder="Enter Price" >
                                    </div>
                                    <div>
                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-danger btn-sm danger_btn mb-0"
                                            wire:click="removeItem({{ $index }})" >
                                            <span class="material-icons">delete</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Error Messages -->
                                @if(session()->has('errorPrice.' . $index))
                                <div class="text-danger">{{ session('errorPrice.' . $index) }}</div>
                                @endif

                                @error('items.' . $index . '.price')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror


                            </div>
                            @else
                            <div class="col-12 col-md-2 mb-2">
                                <div class="d-flex align-items-end gap-2 justify-content-end">
                                    <div>
                                        <!-- Price Input -->
                                        <label class="form-label"><strong>Price</strong></label>
                                        <input type="text"
                                            wire:keyup="checkproductPrice($event.target.value, {{ $index }})"
                                            wire:model="items.{{ $index }}.price"
                                            class="form-control form-control-sm border border-1 customer_input @if(session()->has('errorPrice.' . $index)) border-danger @endif @error('items.' . $index . '.price') border-danger @enderror"
                                            placeholder="Enter Price" >
                                    </div>
                                    <div>
                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-danger btn-sm danger_btn mb-0"
                                            wire:click="removeItem({{ $index }})" ><span
                                                class="material-icons">delete</span>
                                        </button>
                                    </div>
                                </div>
                                <!-- Error Messages -->
                                @if(session()->has('errorPrice.' . $index))
                                <div class="text-danger">{{ session('errorPrice.' . $index) }}</div>
                                @endif

                                @error('items.' . $index . '.price')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                                @if(isset($item['selected_collection']) && $items[$index]['selected_collection'] == 2)
                                    <div class="row mb-3">
                                        <div class="col-md-2">
                                            <label for="">Delivery Date</label>
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
                                        <div class="col-md-2">
                                            <label class="form-label"><strong>Item Status</strong></label>
                                            <select class="form-control form-control-sm border border-1"
                                                    wire:model="items.{{ $index }}.item_status"
                                                    @if($status === 'Process' && $tlStatus === 'Approved') disabled @endif>
                                                <option value="" hidden>Select Item Status</option>
                                                <option value="Process">Process</option>
                                                <option value="Hold">Hold</option>
                                            </select>
                                            @error("items.$index.item_status")
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label"><strong>Remarks</strong></label>
                                            <textarea type="text" wire:model="items.{{ $index }}.remarks"
                                                class="form-control form-control-sm border border-1 customer_input"
                                                placeholder="Enter Product Remarks" rows="4"></textarea>
                                            @error("items.".$index.".remarks")
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                        <!-- Measurements -->
                        @if(isset($this->items[$index]['product_id']) && $items[$index]['selected_collection'] == 1)
                        <div class="row">
                            <div class="col-12 col-md-6 mb-2 mb-md-0">
                                <div class="measurement_div">
                                    <h6 class="badge bg-danger custom_success_badge">Measurements</h6>

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

                                    <!-- Display error if copying measurements failed due to product mismatch -->
                                    @if (session()->has('measurements_error.' . $index))
                                    <div class="alert alert-danger">
                                        {{ session('measurements_error.' . $index) }}
                                    </div>
                                    @endif
                                    @endif

                                    <div class="row">
                                        @if(isset($items[$index]['measurements']) && count($items[$index]['measurements']) >
                                        0)
                                        @foreach ($items[$index]['measurements'] as $key => $measurement)
                                        <div class="col-md-3">
                                            <div class="measurement-col">
                                                <label>
                                                    {{ isset($measurement['title']) ? $measurement['title'] : 'N/A' }}
                                                    <strong>[{{ isset($measurement['short_code']) ? $measurement['short_code'] :
                                                        '' }}]</strong>
                                                </label>
                                                <input type="number" required
                                                    class="form-control form-control-sm border border-1 customer_input measurement_input"
                                                    wire:model="items.{{ $index }}.measurements.{{ $key }}.value"
                                                     wire:keyup="validateMeasurement({{ $index }}, {{ $key }})">
                                                @error("items.{$index}.measurements.{$key}.value")
                                                <div class="text-danger">{{ $message }}</div>
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
                            </div>
                            <!-- Catalogue -->
                            @if(isset($item['selected_collection']) && $item['selected_collection'] == 1)
                            <div class="col-12 col-md-6">
                                <div class="row">
                                    {{-- Catalogue --}}
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label"><strong>Catalogue</strong></label>
                                        <select wire:model="items.{{ $index }}.selectedCatalogue"
                                            class="form-control form-control-sm border border-1 @error('items.'.$index.'.selectedCatalogue') border-danger @enderror"
                                            wire:change="SelectedCatalogue($event.target.value, {{ $index }})">
                                            <option value="" selected hidden>Select Catalogue</option>
                                            @foreach($item['catalogues'] ?? [] as $cat_log)
                                            <option value="{{ $cat_log['catalogue_title_id'] }}">
                                                {{ $cat_log['catalogue_title']['title'] ?? 'No Title' }} (1 - {{
                                                $cat_log['page_number'] }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error("items." .$index. ".selectedCatalogue")
                                        <div class="text-danger inputerror">{{ $message }}</div>
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
                                            max="{{ isset($item['selectedCatalogue']) && isset($maxPages[$index][$item['selectedCatalogue']]) ? $maxPages[$index][$item['selectedCatalogue']] : '' }}">
                                        @error("items.".$index.".page_number")
                                        <div class="text-danger inputerror">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    {{-- Page item --}}
                                    <div class="mb-3 col-md-5">
                                        @if(isset($catalogue_page_item) && !empty($catalogue_page_item[$index]))
                                        <label class="form-label"><strong>Page Item</strong></label>
                                        <select wire:model="items.{{$index}}.page_item"
                                            class="form-control form-control-sm border border-2 @error('items.'.$index.'.page_item') border-danger @enderror">
                                            <option value="" selected hidden>Select Page Item</option>
                                            @if(!empty($items[$index]['pageItems']))
                                                @foreach($items[$index]['pageItems'] ?? [] as $pageItems)
                                                <option value="{{ $pageItems }}" {{$items[$index]['pageItems']==$pageItems
                                                    ? 'selected' : '' }}>
                                                    {{ $pageItems }}
                                                </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error("items.".$index.".page_item")
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        @endif
                                    </div>
                                    {{-- Expected Delivery Date,Fittings,Priority Level --}}
                                    {{-- {{dd($item)}} --}}
                                    @if(isset($item['selected_collection']) && $item['selected_collection'] == 1)
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <label for="">Delivery Date</label>
                                                <input type="date" class="form-control form-control-sm border border-1" wire:model="items.{{$index}}.expected_delivery_date"
                                                
                                                min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                                @error("items.$index.expected_delivery_date")
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            {{-- Fittings --}}
                                            <div class="col-md-3">
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
                                            <div class="col-md-3">
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
                                            <div class="col-md-3" >
                                                <label class="form-label"><strong>Item Status</strong></label>
                                                <select class="form-control form-control-sm border border-1"
                                                        wire:model="items.{{ $index }}.item_status"  
                                                        @if($status === 'Process' && $tlStatus === 'Approved') disabled @endif>
                                                    <option value="" hidden>Select Item Status</option>
                                                    <option value="Process">Process</option>
                                                    <option value="Hold">Hold</option>
                                                </select>
                                                @error("items.$index.item_status")
                                                <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        @if(count($extra_measurement)>0)
                                            <div class="row mb-4">
                                                @if($extra_measurement[$index] == 'mens_jacket_suit')
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Vents</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.vents"
                                                            wire:change="validateSingle('items.{{ $index }}.vents')">
                                                            <option value="" hidden>Select Vents</option>
                                                            <option value="1 Vent">1 Vent</option>
                                                            <option value="2 Vents">2 Vents</option>
                                                        </select>
                                                        @error("items.$index.vents")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label><strong>Client Name</strong></label>
                                                        <select class="form-control form-control-sm"
                                                            wire:model="items.{{ $index }}.client_name_required"
                                                            wire:change="validateSingle('items.{{ $index }}.client_name_required')">
                                                            <option value="" hidden>Select</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        @error("items.$index.client_name_required") 
                                                            <div class="text-danger">{{ $message }}</div> 
                                                        @enderror
                                                    </div>

                                                    @if(!empty($items[$index]['client_name_required']) && $items[$index]['client_name_required'] == 'Yes')
                                                        <div class="col-md-3">
                                                            <label><strong>Where?</strong></label>
                                                            <select class="form-control form-control-sm"
                                                                wire:model="items.{{ $index }}.client_name_place">
                                                                <option value="" hidden>Select</option>
                                                                <option value="Inside Jacket">Inside Jacket</option>
                                                                <option value="Inside Suit">Inside Suit</option>
                                                            </select>
                                                            @error("items.$index.client_name_place") 
                                                                <div class="text-danger">{{ $message }}</div> 
                                                            @enderror
                                                        </div>
                                                    @endif
                                                @elseif($extra_measurement[$index] == 'ladies_jacket_suit')
                                                    <!-- Vents Required -->
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Vents Required?</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.vents_required"
                                                            wire:change="validateSingle('items.{{ $index }}.vents_required')">
                                                            <option value="" hidden>Select Option</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        @error("items.$index.vents_required")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        {{-- <span class="tooltip-text">
                                                                    Specify whether vents are required for the {{strtoupper($items[$index]['searchproduct'])}}.
                                                        </span> --}}
                                                    </div>

                                                    <!-- Number of Vents (only if required) -->
                                                    @if(!empty($items[$index]['vents_required']) &&
                                                    $items[$index]['vents_required'] == 'Yes')
                                                        <div class="col-md-3 tooltip-wrapper">
                                                            <label class="form-label"><strong>How Many Vents?</strong></label>
                                                            <select class="form-control form-control-sm border border-1"
                                                                wire:model="items.{{ $index }}.vents_count"
                                                                wire:change="validateSingle('items.{{ $index }}.vents_count')">
                                                                {{-- <option value="" hidden>Select Count</option> --}}
                                                                <option value="1" selected>1 Vent</option>
                                                                <option value="2">2 Vents</option>
                                                            </select>
                                                            @error("items.$index.vents_count")
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                            {{-- <span class="tooltip-text">
                                                                            Choose how many vents if required.
                                                                        </span> --}}
                                                        </div>
                                                    @endif
                                                    <div class="col-md-3">
                                                        <label><strong>Client Name</strong></label>
                                                        <select class="form-control form-control-sm"
                                                            wire:model="items.{{ $index }}.client_name_required"
                                                            wire:change="validateSingle('items.{{ $index }}.client_name_required')">
                                                            <option value="" hidden>Select</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        @error("items.$index.client_name_required") 
                                                            <div class="text-danger">{{ $message }}</div> 
                                                        @enderror
                                                    </div>

                                                    @if(!empty($items[$index]['client_name_required']) && $items[$index]['client_name_required'] == 'Yes')
                                                        <div class="col-md-3">
                                                            <label><strong>Where?</strong></label>
                                                            <select class="form-control form-control-sm"
                                                                wire:model="items.{{ $index }}.client_name_place">
                                                                <option value="" hidden>Select</option>
                                                                <option value="Inside Jacket">Inside Jacket</option>
                                                                <option value="Inside Suit">Inside Suit</option>
                                                            </select>
                                                            @error("items.$index.client_name_place") 
                                                                <div class="text-danger">{{ $message }}</div> 
                                                            @enderror
                                                        </div>
                                                    @endif
                                                @elseif($extra_measurement[$index] == 'trouser')
                                                    <!-- Fold Cuff -->
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Fold Cuff</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.fold_cuff_required"
                                                            wire:change="validateSingle('items.{{ $index }}.fold_cuff_required')">
                                                            <option value="" hidden>Select Option</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        @error("items.$index.fold_cuff_required")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror

                                                        {{-- <span class="tooltip-text">Specify if trouser fold cuff is needed.</span> --}}
                                                    </div>

                                                    @if(!empty($items[$index]['fold_cuff_required']) &&
                                                    $items[$index]['fold_cuff_required'] == 'Yes')
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Fold Cuff (cm)</strong></label>
                                                        <input type="number" min="1"
                                                            class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.fold_cuff_size"
                                                            wire:change="validateSingle('items.{{ $index }}.fold_cuff_size')">
                                                        @error("items.$index.fold_cuff_size")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        {{-- <span class="tooltip-text">Enter fold cuff size in cm.</span> --}}
                                                    </div>
                                                    @endif

                                                    <!-- Pleats -->
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Pleats</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.pleats_required"
                                                            wire:change="validateSingle('items.{{ $index }}.pleats_required')">
                                                            <option value="" hidden>Select Option</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        @error("items.$index.pleats_required")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        {{-- <span class="tooltip-text">Select if pleats are needed.</span> --}}
                                                    </div>

                                                    @if(!empty($items[$index]['pleats_required']) &&
                                                    $items[$index]['pleats_required'] == 'Yes')
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>How Many Pleats?</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.pleats_count"
                                                            wire:change="validateSingle('items.{{ $index }}.pleats_count')">
                                                            <option value="" hidden>Select Count</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                        @error("items.$index.pleats_count")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        {{-- <span class="tooltip-text">Choose number of pleats.</span> --}}
                                                    </div>
                                                    @endif

                                                    <!-- Back Pocket -->
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Back Pocket</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.back_pocket_required"
                                                            wire:change="validateSingle('items.{{ $index }}.back_pocket_required')">
                                                            <option value="" hidden>Select Option</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        @error("items.$index.back_pocket_required")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        {{-- <span class="tooltip-text">Specify if back pockets are needed.</span> --}}
                                                    </div>

                                                    @if(!empty($items[$index]['back_pocket_required']) &&
                                                    $items[$index]['back_pocket_required'] == 'Yes')
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>How Many Pockets?</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.back_pocket_count"
                                                            wire:change="validateSingle('items.{{ $index }}.back_pocket_count')">
                                                            <option value="" hidden>Select Count</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                        </select>
                                                        @error("items.$index.back_pocket_count")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        {{-- <span class="tooltip-text">Choose number of back pockets.</span> --}}
                                                    </div>
                                                    @endif

                                                    <!-- Adjustable Belt -->
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Adjustable Belt</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.adjustable_belt"
                                                            wire:change="validateSingle('items.{{ $index }}.adjustable_belt')">
                                                            <option value="" hidden>Select Option</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        @error("items.$index.adjustable_belt")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        {{-- <span class="tooltip-text">Add adjustable side belt option.</span> --}}
                                                    </div>

                                                    <!-- Suspender Button -->
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Suspender Buttons</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.suspender_button"
                                                            wire:change="validateSingle('items.{{ $index }}.suspender_button')">
                                                            <option value="" hidden>Select Option</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        @error("items.$index.suspender_button")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        {{-- <span class="tooltip-text">Specify if suspender buttons are needed.</span> --}}
                                                    </div>

                                                    <!-- Trouser Position -->
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Trouser Position</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.trouser_position"
                                                            wire:change="validateSingle('items.{{ $index }}.trouser_position')">
                                                            <option value="" hidden>Select Position</option>
                                                            <option value="High Waist">High Waist</option>
                                                            <option value="Normal">Normal</option>
                                                            <option value="Low Waist">Low Waist</option>
                                                        </select>
                                                        @error("items.$index.trouser_position")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        {{-- <span class="tooltip-text">Select waist position for trousers.</span> --}}
                                                    </div>
                                                @elseif($extra_measurement[$index] == 'shirt')
                                                    <!-- Sleeves -->
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Sleeves</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.sleeves"
                                                            wire:change="validateSingle('items.{{ $index }}.sleeves')">
                                                            <option value="" hidden>Select Option</option>
                                                            <option value="L/S">Long Sleeve (L/S)</option>
                                                            <option value="H/S">Half Sleeve (H/S)</option>
                                                        </select>
                                                        @error("items.$index.sleeves")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Collar -->
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Collar</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.collar"
                                                            wire:change="validateSingle('items.{{ $index }}.collar')">
                                                            <option value="" hidden>Select Option</option>
                                                            <option value="Normal">Normal Collar</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                        @error("items.$index.collar")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    @if(!empty($items[$index]['collar']) && $items[$index]['collar'] == 'Other')
                                                        <div class="col-md-3 tooltip-wrapper">
                                                            <label class="form-label"><strong>Collar Style</strong></label>
                                                            <input type="text"
                                                                class="form-control form-control-sm border border-1"
                                                                wire:model="items.{{ $index }}.collar_style"
                                                                wire:change="validateSingle('items.{{ $index }}.collar_style')"
                                                                placeholder="Enter Collar Style">
                                                            @error("items.$index.collar_style")
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    @endif

                                                    <!-- Pocket -->
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Pocket</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.pocket"
                                                            wire:change="validateSingle('items.{{ $index }}.pocket')">
                                                            <option value="" hidden>Select Option</option>
                                                            <option value="With Pocket">With Pocket</option>
                                                            <option value="Without Pocket">Without Pocket</option>
                                                        </select>
                                                        @error("items.$index.pocket")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Cuffs -->
                                                    <div class="col-md-3 tooltip-wrapper">
                                                        <label class="form-label"><strong>Cuffs</strong></label>
                                                        <select class="form-control form-control-sm border border-1"
                                                            wire:model="items.{{ $index }}.cuffs"
                                                            wire:change="validateSingle('items.{{ $index }}.cuffs')">
                                                            <option value="" hidden>Select Option</option>
                                                            <option value="Regular">Regular Cuffs</option>
                                                            <option value="French">French Fold Cuffs</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                        @error("items.$index.cuffs")
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    @if(!empty($items[$index]['cuffs']) && $items[$index]['cuffs'] == 'Other')
                                                        <div class="col-md-3 tooltip-wrapper">
                                                            <label class="form-label"><strong>Cuff Style</strong></label>
                                                            <input type="text"
                                                                class="form-control form-control-sm border border-1"
                                                                wire:model="items.{{ $index }}.cuff_style"
                                                                wire:change="validateSingle('items.{{ $index }}.cuff_style')"
                                                                placeholder="Enter Cuff Style">
                                                            @error("items.$index.cuff_style")
                                                            <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                    <div class="col-md-3">
                                                        <label><strong>Client Name</strong></label>
                                                        <select class="form-control form-control-sm"
                                                            wire:model="items.{{ $index }}.client_name_required"
                                                            wire:change="validateSingle('items.{{ $index }}.client_name_required')">
                                                            <option value="" hidden>Select</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                        @error("items.$index.client_name_required") 
                                                            <div class="text-danger">{{ $message }}</div> 
                                                        @enderror
                                                    </div>

                                                    @if(!empty($items[$index]['client_name_required']) && $items[$index]['client_name_required'] == 'Yes')
                                                        <div class="col-md-3">
                                                            <label><strong>Where?</strong></label>
                                                            <select class="form-control form-control-sm"
                                                                wire:model="items.{{ $index }}.client_name_place">
                                                                <option value="" hidden>Select</option>
                                                                <option value="On Cuff">On Cuff</option>
                                                                <option value="On Pocket">On Pocket</option>
                                                                <option value="On Pocket Space">On Pocket Space</option>
                                                            </select>
                                                            @error("items.$index.client_name_place") 
                                                                <div class="text-danger">{{ $message }}</div> 
                                                            @enderror
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <label class="form-label"><strong>Remarks</strong></label>
                                                <textarea type="text" wire:model="items.{{ $index }}.remarks"
                                                    class="form-control form-control-sm border border-1 customer_input"
                                                    placeholder="Enter Product Remarks"></textarea>
                                                @error("items.".$index.".remarks")
                                                <div class="text-danger error-message">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3 col-12">
                                    <div class="d-flex justify-content-between flex-wrap gap-3 align-items-start">
                                        <div class="d-flex flex-column gap-2 flex-grow-1">
                                            {{-- Image Preview --}}
                                            @if (!empty($existingImages[$index]))
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($existingImages[$index] as $image)
                                                <div style="position: relative; width: 70px;">
                                                    <img src="{{ asset('storage/' . $image) }}" class="img-thumbnail"
                                                        style="width: 100%;" />
                                                    <button type="button"
                                                        class="btn btn-sm rounded-circle p-1 btn-danger position-absolute top-0 end-0"
                                                        style="width: 22px; height: 22px; font-size: 12px; display: flex; align-items: center; justify-content: center;"
                                                        wire:click="removeImage({{ $index }}, '{{ $loop->index }}')">
                                                        &times;
                                                    </button>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                            {{-- Show newly uploaded temporary images --}}
                                            @if (!empty($imageUploads[$index]))
                                            @foreach ($imageUploads[$index] as $imgIndex => $img)
                                            <div style="position: relative; width: 70px">
                                                <img src="{{ $img->temporaryUrl() }}" class="img-thumbnail"
                                                    style="width: 100%; height: 100%; object-fit: cover;" />
                                                <button type="button"
                                                    class="btn btn-sm rounded-circle p-1 btn-danger position-absolute top-0 end-0"
                                                    style="width: 22px; height: 22px; font-size: 12px; display: flex; align-items: center; justify-content: center;"
                                                    wire:click="removeUploadedImage({{ $index }}, {{ $imgIndex }})">
                                                    &times;
                                                </button>
                                            </div>
                                            @endforeach
                                            @endif

                                            @if (!empty($existingVideos[$index]))
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($existingVideos[$index] as $video)

                                                <div style="position: relative; width: 150px;">
                                                    <audio controls style="width: 100%;">
                                                        <source src="{{ asset('storage/' . $video) }}"
                                                            type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger rounded-circle p-1 position-absolute top-0 end-0"
                                                        style="width: 24px; height: 24px; font-size: 14px; display: flex; align-items: center; justify-content: center;"
                                                        wire:click="removeVideo({{ $index }}, '{{ $loop->index }}')">
                                                        &times;
                                                    </button>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif

                                            {{-- Newly Uploaded Voice Preview --}}
                                            @if (!empty($voiceUploads[$index]))
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                @foreach ($voiceUploads[$index] as $voiceIndex => $voice)
                                                <div style="position: relative; width: 150px;">
                                                    <audio controls style="width: 100%;">
                                                        <source src="{{ $voice->temporaryUrl() }}" type="audio/mpeg">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                    <button type="button"
                                                        class="btn btn-sm rounded-circle p-1 btn-danger position-absolute top-0 end-0"
                                                        style="width: 22px; height: 22px; font-size: 12px; display: flex; align-items: center; justify-content: center;"
                                                        wire:click="removeUploadedVoice({{ $index }}, {{ $voiceIndex }})">
                                                        &times;
                                                    </button>
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column align-items-end gap-2">
                                            {{-- Upload Image --}}

                                            <button type="button" class="btn btn-cta btn-sm"
                                                onclick="document.getElementById('catalog-upload-{{ $index }}').click()" 
                                                >
                                                <i class="material-icons text-white" style="font-size: 15px;">add</i>
                                                Upload Images
                                            </button>
                                             {{-- Upload Voice --}}
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

                                            @error('imageUploads.*')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            @error('voiceUploads.*')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Hidden File Input --}}
                                        <input type="file" id="catalog-upload-{{ $index }}" multiple
                                            wire:model="imageUploads.{{ $index }}" accept="image/*" class="d-none" />
                                        {{-- Voice Upload --}}
                                        <input type="file" id="voice-upload-{{ $index }}" multiple
                                            wire:model="voiceUploads.{{ $index }}" accept="audio/*" class="d-none" />
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>
                        @else

                        @endif
                        @endforeach
                        <!-- Add Item Button and Payment Section -->
                        <div class="row align-items-end mb-4" style="justify-content: end;">
                            <div class="col-md-4" style="text-align: -webkit-center;">
                                <table>
                                    <tr>
                                        <td>
                                            @if (session()->has('errorAmount'))
                                            <div class="alert alert-danger">
                                                {{ session('errorAmount') }}
                                            </div>
                                            @endif
                                        </td>
                                        <td style="text-align: end;">
                                            <button type="button" class="btn btn-cta btn-sm" wire:click="addItem"><i
                                                    class="material-icons text-white"
                                                    style="font-size: 15px;">add</i>Add Item</button>
                                        </td>
                                    </tr>
                                    {{-- @if ($air_mail !== null && $air_mail !== '') --}}
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
                                    {{-- @endif --}}
                                    <tr>
                                        <td class="w-70"><label class="form-label"><strong>Total Amount</strong></label>
                                        </td>
                                        <td>
                                            <!-- Sub Total -->
                                            <input type="text" class="form-control form-control-sm"
                                                wire:model="billing_amount" disabled
                                                value="{{ number_format($billing_amount, 2) }}">
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center mb-3">
                        @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif
                        @if($activeTab>1)
                        <button type="button" class="btn btn-dark mx-2 btn-sm"
                            wire:click="TabChange({{$activeTab-1}})"><i
                                class="material-icons text-white">chevron_left</i>Previous</button>
                        <button type="submit" class="btn btn-primary mx-2 btn-sm"><i
                                class="material-icons text-white">add</i>Update Order</button>
                        @endif
                        @if($activeTab==1)
                        <button type="button" class="btn btn-cta mx-2 btn-sm"
                            wire:click="TabChange({{$activeTab+1}})">Next<i
                                class="material-icons text-white">chevron_right</i></button>
                        @endif

                    </div>
            </form>

        </div>
    </div>
</div>
 @push('js')
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
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/js/intlTelInput.min.js"></script>
<script>
    
    $(function () {
        // Main Phone
        initIntlTelInput("#mobile", "phone", "phone_code");

        // Alternative Phone 1
        initIntlTelInput("#alt_phone_1", "alternative_phone_number_1", "alt_phone_code_1");

        // Alternative Phone 2
        initIntlTelInput("#alt_phone_2", "alternative_phone_number_2", "alt_phone_code_2");
    });

    function initIntlTelInput(selector, phoneModel, codeModel, defaultCountry = "cf") {
        var input = $(selector);

        input.intlTelInput({
            initialCountry: defaultCountry,  // Central African Republic by default
            preferredCountries: ["us", "gb", "in", "cf"],
            separateDialCode: true
        });

        // On input change (number typing)
        input.on("input", function () {
            let number = input.val().replace(/\D/g, ''); // only digits
            @this.set(phoneModel, number);
        });

        // On country change
        input.on("countrychange", function () {
            let code = "+" + input.intlTelInput("getSelectedCountryData").dialCode;
            @this.set(codeModel, code);
            @this.call('CountryCodeSet', selector, code);
        });

        // Set default on load
        let defaultCode = "+" + input.intlTelInput("getSelectedCountryData").dialCode;
        @this.set(codeModel, defaultCode);
        @this.call('CountryCodeSet', selector, defaultCode);
    }
    // Already existing
    window.addEventListener('update_input_max_length', function (event) {
        let itemId = event.detail[0].id;
        let mobile_length = event.detail[0].mobile_length;
        if (itemId && mobile_length) {
            document.querySelector(itemId).setAttribute("maxlength", mobile_length);
        }
    });

    function collectPreferredMap(parentId) {
        let map = {};
        $(`#${parentId} .country-list li.country`).each(function () {
            let dialCode = $(this).data("dial-code");
            let iso2 = $(this).data("country-code");
            map["+" + dialCode] = iso2;
        });

        return map;
    }

    // Example
    $(function () {
        // collect once on load
        window.preferredMap = collectPreferredMap("parent_mobile");
        console.log(window.preferredMap); 
        // 👉 { "+1": "us", "+44": "gb", "+91": "in", "+236": "cf" }
    });

    window.addEventListener('update_input_code_number', function (event) {
        let itemId = event.detail[0].id;
        let dialCode = event.detail[0].dialCode;
        let number = event.detail[0].number || '';
        console.log("Event received:", itemId, dialCode, number);
        if (itemId && dialCode) {
            // find corresponding parent wrapper
            let parentId = "";
            if (itemId === "#mobile") {
                parentId = "#parent_mobile";
            } else if (itemId === "#alt_phone_1") {
                parentId = "#parent_alt1";
            } else if (itemId === "#alt_phone_2") {
                parentId = "#parent_alt2";
            }

            if (parentId) {
                let countryCode = window.preferredMap[dialCode]; // e.g. "in"
                console.log("Updating:", countryCode, dialCode);
                if (countryCode) {
                    // ✅ Update flag inside .selected-flag
                    $(`${parentId} .selected-flag .iti-flag`)
                        .attr("class", `iti-flag ${countryCode}`);

                    // ✅ Update dial code inside .selected-flag
                    $(`${parentId} .selected-flag .selected-dial-code`).text(dialCode);

                    // ✅ Optionally set value to input
                    $(`${itemId}`).val(number);
                }
            }
        }
    });
</script>
@endpush
