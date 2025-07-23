<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3  bg-gradient-dark custom-sideber-design"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center company-logo" href=" {{ route('admin.dashboard') }} ">
            <img src="{{ asset('assets') }}/img/stanny_logo.png" class="h-100" alt="main_logo">
            {{-- <span class="ms-2 font-weight-bold text-white">Radhey International</span> --}}
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            {{-- {{dd($modules)}} --}}
            {{-- @foreach($modules as $module)
            <li class="nav-item">
                <a class="nav-link text-white {{ in_array(Route::currentRouteName(), $module['route']) ? 'active ' : '' }}"
                    href="{{ isset($module['route'][0]) ? route($module['route'][0]) : '#' }}">
                    <!-- Default to the first route -->
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">{{ $module['icon'] }}</i>
                    </div>
                    <span class="nav-link-text ms-1">{{ $module['name'] }}</span>
                </a>
            </li>
            @endforeach --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'admin.dashboard' ? 'active ' : '' }}" href="{{route('admin.dashboard')}}">
                    <!-- Default to the first route -->
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            @if ($this->hasPermissionByParent('customer_management'))
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('admin/customers*') ? 'active ' : '' }}" href="{{route('customers.index')}}">
                    <!-- Default to the first route -->
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">group</i>
                    </div>
                    <span class="nav-link-text ms-1">Customer Management</span>
                </a>
            </li>
            @endif
            @if ($this->hasPermissionByParent('supplier_management'))
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('admin/suppliers*') ? 'active ' : '' }}" href="{{route('suppliers.index')}}">
                    <!-- Default to the first route -->
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">store</i>
                    </div>
                    <span class="nav-link-text ms-1">Supplier Management</span>
                </a>
            </li>
            @endif
            {{-- Production management --}}
            @if ($this->hasPermissionByParent('production_management'))
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('admin/production*') ? 'active ' : '' }}"
                        href="#OrderManagementSubmenu" data-bs-toggle="collapse"
                        aria-expanded="{{ Request::is('admin/orders*') ? 'true' : 'false' }}">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">shopping_cart</i>
                        </div>
                        <span class="nav-link-text ms-1">Production Management</span>
                    </a>
                </li>
                <ul id="OrderManagementSubmenu"
                class="collapse list-unstyled ms-4 {{ Request::is('admin/production*') ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('admin/production') ? 'active ' : '' }}"
                        href="{{route('production.order.index')}}">
                        Production Orders
                    </a>
                </li>
            </ul>
            @endif
            @if ($this->hasPermissionByParent('order_management'))
            {{-- Order Management --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('admin/orders*') ? 'active ' : '' }}"
                    href="#OrderManagementSubmenu" data-bs-toggle="collapse"
                    aria-expanded="{{ Request::is('admin/orders*') ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">shopping_cart</i>
                    </div>
                    <span class="nav-link-text ms-1">Order Management</span>
                </a>
            </li>

            <!-- Submenu -->
            <ul id="OrderManagementSubmenu"
                class="collapse list-unstyled ms-4 {{ Request::is('admin/orders*') ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('admin/orders') ? 'active ' : '' }}"
                        href="{{route('admin.order.index')}}">
                        All Orders
                    </a>
                </li>
                <a class="nav-link text-white {{ Request::is('admin/orders/new') ? 'active ' : '' }}"
                    href="{{route('admin.order.new')}}">
                    Place Order
                </a>
                <a class="nav-link text-white {{ Request::is('admin/orders/invoice') ? 'active ' : '' }}"
                    href="{{route('admin.order.invoice.index')}}">
                    Invoices
                </a>
                <a class="nav-link text-white {{ Request::is('admin/orders/invoice/add') ? 'active ' : '' }}"
                    href="{{route('admin.order.invoice.add')}}">
                    Instant Invoices
                </a>
                 <a class="nav-link text-white {{ Request::is('admin/orders/proformas') ? 'active ' : '' }}"
                    href="{{route('admin.order.proformas.index')}}">
                    Proformas
                </a>
                <a class="nav-link text-white {{ Request::is('admin/orders/cancel-order') ? 'active ' : '' }}"
                    href="{{route('admin.order.cancel-order.index')}}">
                    Cancel Order
                </a>
            </ul>
            @endif
            @if ($this->hasPermissionByParent('accounting_management'))
            {{-- Expense management --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->is('admin/accounting*') ? 'active bg-gradient-primary' : '' }}"
                    href="#AccountManagementSubmenu" data-bs-toggle="collapse"
                    aria-expanded="{{ request()->is('admin/accounting*') ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">account_balance</i>
                    </div>
                    <span class="nav-link-text ms-1">Account Management</span>
                </a>
            </li>
            <ul id="AccountManagementSubmenu"
                class="collapse list-unstyled ms-4 {{ request()->is('admin/accounting*') ? 'show' : '' }}">

                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->is('admin/accounting/payment-collection') ? 'active' : '' }}"
                        href="{{ route('admin.accounting.payment_collection') }}">
                        Payment Collections
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->is('admin/accounting/add-payment-receipt') ? 'active' : '' }}"
                        href="{{ route('admin.accounting.add_payment_receipt') }}">
                        Add Payment Receipt
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->is('admin/accounting/depot-expense/list') ? 'active' : '' }}"
                    href="{{ route('admin.accounting.list.depot_expense') }}">
                    Depot Expense
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->is('admin/accounting/list-opening-balance') ? 'active' : '' }}"
                        href="{{ route('admin.accounting.list_opening_balance') }}">
                        Customer Opening Balance
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link text-white {{ request()->is('admin/accounting/cashbook-module') ? 'active' : '' }}"
                        href="{{ route('admin.accounting.cashbook_module') }}">
                       Cashbook Module
                    </a>
                </li>
            </ul>
            @endif
            @if ($user->id==1)
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Master Modules</h6>
            </li>
            {{-- Business Type --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('admin/business-type*') || in_array(Route::currentRouteName(), ['business_type.index']) ? 'active ' : '' }}"
                    href="#businessTypeSubmenu" data-bs-toggle="collapse"
                    aria-expanded="{{ in_array(Route::currentRouteName(), ['business_type.index']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt</i>
                    </div>
                    <span class="nav-link-text ms-1">Master Management</span>
                </a>
            </li>
            <ul id="businessTypeSubmenu"
                class="collapse list-unstyled ms-4 {{ in_array(Route::currentRouteName(), ['business_type.index','country.index']) ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'business_type.index' ? 'active ' : '' }}"
                        href="{{route('business_type.index')}}">
                        Business Type
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'country.index' ? 'active ' : '' }}"
                        href="{{route('country.index')}}">
                        Country
                    </a>
                </li>
            </ul>
            @endif
            @if ($this->hasPermissionByParent('purchase_order_management'))
            {{-- Purchase Order --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('admin/purchase-order*') || in_array(Route::currentRouteName(), ['purchase_order.index','purchase_order.create']) ? 'active ' : '' }}"
                    href="#purchaseOrderSubmenu" data-bs-toggle="collapse"
                    aria-expanded="{{ in_array(Route::currentRouteName(), ['purchase_order.index','purchase_order.create']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">receipt_long</i>
                    </div>
                    <span class="nav-link-text ms-1">Purchase Order</span>
                </a>
            </li>
            <ul id="purchaseOrderSubmenu"
                class="collapse list-unstyled ms-4 {{ in_array(Route::currentRouteName(), ['purchase_order.index','purchase_order.create','purchase_order.generate_grn']) ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'purchase_order.index' ? 'active ' : '' }}"
                        href="{{route('purchase_order.index')}}">
                        PO
                    </a>
                </li>

            </ul>
            @endif
            @if ($this->hasPermissionByParent('stock_management'))
            {{-- Stock Management --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ in_array(Route::currentRouteName(), ['stock.index']) ? 'active ' : '' }}"
                    href="#StockManagementSubmenu" data-bs-toggle="collapse"
                    aria-expanded="{{ in_array(Route::currentRouteName(), ['stock.index']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">inventory</i>
                    </div>
                    <span class="nav-link-text ms-1">Stock Management</span>
                </a>
            </li>

            <!-- Submenu -->
            <ul id="StockManagementSubmenu"
                class="collapse list-unstyled ms-4 {{ in_array(Route::currentRouteName(), ['stock.index']) ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'stock.index' ? 'active ' : '' }}"
                        href="{{ route('stock.index') }}">
                        Stock Logs
                    </a>
                </li>
            </ul>
            @endif
            @if ($this->hasPermissionByParent('product_management'))
            {{-- Product Management --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ Request::is('admin/products*') || in_array(Route::currentRouteName(), ['product.view', 'product.gallery', 'product.add', 'product.update', 'admin.categories', 'admin.subcategories', 'measurements.index', 'product.fabrics','admin.collections.index']) ? 'active ' : '' }}"
                    href="#productManagementSubmenu" data-bs-toggle="collapse"
                    aria-expanded="{{ in_array(Route::currentRouteName(), ['product.view','product.gallery','product.add','product.update','admin.categories','admin.subcategories','measurements.index','product.gallery','product.fabrics','admin.collections.index']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">category</i>
                    </div>
                    <span class="nav-link-text ms-1">Product Management</span>
                </a>
            </li>

            <!-- Submenu -->
            <ul id="productManagementSubmenu"
                class="collapse list-unstyled ms-4 {{ in_array(Route::currentRouteName(), ['product.view', 'product.gallery', 'product.add', 'product.update', 'admin.categories', 'admin.subcategories', 'measurements.index', 'product.fabrics','admin.collections.index','admin.fabrics.index','product.catalogue']) ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('admin/products/catalogue') ? 'active ' : '' }}"
                        href="{{route('product.catalogue')}}">
                        Catalogue
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('admin/products/collections') ? 'active ' : '' }}"
                        href="{{route('admin.collections.index')}}">
                        Collections
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Request::is('admin/products/categories*') ? 'active ' : '' }}"
                        href="{{route('admin.categories')}}">
                        Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'admin.fabrics.index' ? 'active ' : '' }}"
                        href="{{route('admin.fabrics.index')}}">
                        Fabrics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'product.view' ? 'active ' : '' }}"
                        href="{{route('product.view')}}">
                        Products
                    </a>
                </li>
            </ul>
            @endif
            @if ($this->hasPermissionByParent('branch_management'))
            <li class="nav-item">
                <a class="nav-link text-white {{ in_array(Route::currentRouteName(), ['staff.designation','staff.index','staff.add','branch.index','salesman.index']) ? 'active ' : '' }}"
                    href="#StaffManagementSubmenu" data-bs-toggle="collapse"
                    aria-expanded="{{ in_array(Route::currentRouteName(), ['staff.designation','staff.index','staff.add','branch.index','salesman.index']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">assignment_ind</i>
                    </div>
                    <span class="nav-link-text ms-1">Branch Management</span>
                </a>
            </li>

            <!-- Submenu -->
            <ul id="StaffManagementSubmenu"
                class="collapse list-unstyled ms-4 {{ in_array(Route::currentRouteName(), ['staff.designation','staff.index','staff.add','staff.update','staff.view','staff.task','staff.task.add','staff.cities.add','salesman.index','branch.index']) ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'branch.index' ? 'active ' : '' }}"
                        href="{{route('branch.index')}}">
                        Branch
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'staff.designation' ? 'active ' : '' }}"
                        href="{{route('staff.designation')}}">
                        Designation
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ in_array(Route::currentRouteName(), ['staff.index','staff.add','staff.update','staff.view','staff.task','staff.task.add','staff.cities.add']) ? 'active ' : '' }}"
                        href="{{route('staff.index')}}">
                        Staff
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'salesman.index' ? 'active ' : '' }}"
                        href="{{route('salesman.index')}}">
                        Staff Bill Book
                    </a>
                </li>
            </ul>
            @endif
            @if ($this->hasPermissionByParent('expense_management'))
            {{-- Expense management --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ in_array(Route::currentRouteName(), ['expense.index']) ? 'active ' : '' }}"
                    href="#ExpenseManagementSubmenu" data-bs-toggle="collapse"
                    aria-expanded="{{ in_array(Route::currentRouteName(), ['expense.index']) ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">attach_money</i>
                    </div>
                    <span class="nav-link-text ms-1">Expense Management</span>
                </a>
            </li>
            <ul id="ExpenseManagementSubmenu"
                class="collapse list-unstyled ms-4 {{ in_array(Route::currentRouteName(), ['expense.index']) ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'expense.index' && request()->get('parent_id') == 1 ? 'active ' : '' }}"
                        href="{{ route('expense.index', ['parent_id' => 1]) }}">
                        Recurring
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ Route::currentRouteName() == 'expense.index' && request()->get('parent_id') == 2 ? 'active ' : '' }}"
                        href="{{ route('expense.index', ['parent_id' => 2]) }}">
                        Non Recurring
                    </a>
                </li>
            </ul>
            @endif

            @if ($this->hasPermissionByParent('report_management'))
            {{-- Report management --}}
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->is('admin/report*') ? 'active bg-gradient-primary' : '' }}"
                    href="#ReportManagementSubmenu" data-bs-toggle="collapse" aria-expanded="{{ request()->is('admin/report*') ? 'true' : 'false' }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">account_balance</i>
                    </div>
                    <span class="nav-link-text ms-1">Report Management</span>
                </a>
            </li>
            <ul id="ReportManagementSubmenu" class="collapse list-unstyled ms-4 {{ request()->is('admin/report*') ? 'show' : '' }}">
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->is('admin/report/user-ledger') ? 'active' : '' }}"
                    href="{{ route('admin.report.user_ledger') }}">
                       User Ledger
                    </a>
                </li>
            </ul>
            @endif
 <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'admin.todo-list' ? 'active ' : '' }}" href="{{route('todo-list.todo-list')}}">
                    <!-- Default to the first route -->
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1">ToDo List</span>
                </a>
            </li>

        </ul>
    </div>
    {{-- <div class="sidenav-footer position-absolute w-100 bottom-0 ">
        <div class="mx-3">
            <a class="btn bg-gradient-secondary w-100" href="javascript:;">
                <livewire:auth.logout />
            </a>
        </div>
    </div> --}}
</aside>
