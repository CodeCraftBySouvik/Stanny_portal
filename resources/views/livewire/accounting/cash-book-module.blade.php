<div class="container">
    <style>
        .wallet-negative {
            color: red;
            font-size: 1.5rem;
        }

        /* Hide details by default */
        .store_details_column {
            display: none;
        }
    </style>
    <section class="admin__title">
        <h5>Cashbook</h5>
    </section>
    <section>
        <ul class="breadcrumb_menu">
            <li>Accounting</li>
            <li><a href=""> Cashbook</a></li>
            <li class="back-button"></li>
        </ul>
    </section>
    <div class="search__filter">
        <div class="row justify-content-end">
            <div class="col-auto">
                <div class="d-flex align-items-center gap-2">
                    @php
                        $user = Auth::guard('admin')->user();
                    @endphp
                    @if ($user && $user->is_super_admin == 1)
                    <div class="mb-4 position-relative">
                        <label for="searchStaff" class="form-label   mb-0">Staff</label>
                        <input type="text" wire:model.debounce.300ms="searchStaff"
                            class="form-control select-md bg-white" placeholder="Staff name"
                            wire:keyup="SearchStaff($event.target.value)">
                        
                        @if (!empty($staffSuggestions))
                        <ul class="list-group position-absolute z-index-1 w-100"
                            style="max-height: 200px; overflow-y:auto;">
                            @forelse ($staffSuggestions as $staff)
                            <li class="list-group-item list-group-item-action" style="cursor: pointer;"
                                wire:click="selectStaff({{ $staff->id }}, '{{ $staff->name }}')">
                                {{ $staff->name }}
                            </li>
                            @empty
                               <li class="list-group-item text-muted">No results found</li>
                            @endforelse
                        </ul>
                        @endif
                    </div>
                    @endif
                    {{-- Start Date --}}
                    <div class="mb-4">
                        <label for="start_date" class="form-label mb-1">Start Date</label>
                        <input type="date" wire:model="start_date" id="start_date"
                            wire:change="AddStartDate($event.target.value)" class="form-control select-md bg-white">
                    </div>
                    <!-- End Date -->
                    <div class="mb-4">
                        <label for="end_date" class="form-label mb-1">End Date</label>
                        <input type="date" wire:model="end_date" id="end_date" class="form-control select-md bg-white"
                            wire:change="AddEndDate($event.target.value)">
                    </div>
                    {{-- Clear Button --}}
                    <div class="mb-0">
                        <label class="form-label mb-1 d-block">&nbsp;</label> <!-- invisible label to align button -->
                        <a href="{{ route('admin.accounting.cashbook_module') }}"
                            class="btn btn-outline-danger select-md">Clear</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="filter">
        <div class="row align-items-center justify-content-end">
            <div class="col-auto">
                {{-- <p class="text-sm font-weight-bold">{{$total}} Items</p> --}}
            </div>
        </div>
    </div>
    {{-- <div class="card"> --}}
        {{-- <div class="card-body"> --}}
            @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            <div class="row">
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                    <div class="card data-card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl  position-absolute">
                                <i class="material-icons opacity-10">attach_money</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Total Collection</p>
                                <h4 class="mb-0">{{ number_format($totalCollections, 2) }}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        {{-- <div class="card-footer p-3">

                        </div> --}}
                    </div>
                </div>
                {{-- Total Expenses --}}
                {{-- @if ($totalCollections > 0 || $totalExpenses > 0) --}}
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                    <div class="card data-card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl  position-absolute">
                                <i class="material-icons opacity-10">money_off</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Total Expenses</p>
                                <h4 class="mb-0">{{ number_format($totalExpenses, 2) }}</h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        {{-- <div class="card-footer p-3 d-flex justify-content-between align-items-center">
                            <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span>
                                than
                                yesterday</p>
                        </div> --}}
                    </div>
                </div>
                {{-- @endif --}}
                {{-- Total Wallet --}}
                {{-- @if ($totalCollections > 0 || $totalExpenses > 0) --}}
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                    <div class="card data-card">
                        <div class="card-header p-3 pt-2">
                            <div
                                class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl  position-absolute">
                                <i class="material-icons opacity-10">account_balance_wallet</i>
                            </div>
                            <div class="text-end pt-1">
                                <p class="text-sm mb-0 text-capitalize">Total Wallet</p>

                                <h4 class="mb-0 {{ $totalWallet < 0 ? 'wallet-negative' : 'text-dark' }}">
                                    {{ number_format($totalWallet, 2) }}
                                </h4>
                            </div>
                        </div>
                        <hr class="dark horizontal my-0">
                        {{-- <div class="card-footer p-3 d-flex justify-content-between align-items-center">
                            <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span>
                                than
                                yesterday</p>
                        </div> --}}
                    </div>
                </div>
                {{-- @endif --}}
            </div>
            {{-- Add Payment Receipt button --}}
            <div class="d-flex justify-content-end mt-4">
                <a href="{{route('admin.accounting.add_payment_receipt')}}" class="btn btn-sm btn-success select-md">
                    <i class="material-icons">add</i> Add Payment Receipt
                </a>
            </div>
            {{-- Payment Collection table --}}
            <div class="card">
                <div class="card-header">
                    <h5>Payment Collection Details</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Voucher No</th>
                                    <th>Payment Date</th>
                                    <th>Collected By</th>
                                    <th>Customer</th>
                                    <th>Collection Amount</th>
                                    <th>Collected From</th>
                                    <th>Approval</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paymentCollections as $index => $collection)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $collection->voucher_no }}</td>
                                    <td>{{ \Carbon\Carbon::parse($collection->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ optional($collection->user)->name ?? 'N/A' }}</td>
                                    <td>{{ optional($collection->customer)->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($collection->collection_amount, 2) }}</td>
                                    <td>{{ $collection->payment_type }}</td>
                                    <td>
                                        @if($collection->is_ledger_added)
                                        <span class="badge bg-success">Approved</span>
                                        @endif

                                    </td>
                                    <td>
                                        @if (!empty($collection->is_ledger_added))
                                        <a href="#" wire:click="$dispatch('confirm-revoke',{{ $collection->id }})"
                                            class="btn btn-outline-warning select-md btn_outline">Revoke</a>
                                        @endif
                                        <button wire:click="downloadInvoice({{ $collection->id }})"
                                            class="btn btn-outline-primary select-md btn_outline">Download
                                            Receipt</button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No collection records found in selected date
                                        range.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <nav aria-label="Page navigation">
                            {{-- {{ $paymentCollections->links() }} --}}
                        </nav>
                    </div>
                </div>
            </div>
            {{-- Add Expense button --}}
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.accounting.add_depot_expense') }}" class="btn btn-sm btn-success select-md"><i
                        class="material-icons">add</i>Add Expense</a>

                </a>
            </div>
            {{-- Expense table --}}
            <div class="card">
                <div class="card-header">
                    <h5>Expenses List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Expense Date</th>
                                    <th>Transaction ID</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $i = empty(Request::get('page')) || Request::get('page') == 1 ? 1 :
                                (((Request::get('page')-1)*$paginate)+1);
                                @endphp
                                @forelse ($paymentExpenses as $key=>$item)
                                @php
                                $ExpenseAt = "";
                                $ExpenseType = "";

                                $expenseData =($item->staff_id ? DB::table('users')->where('id',
                                $item->staff_id)->first() :
                                ($item->customer_id ? DB::table('users')->where('id', $item->customer_id)->first() :
                                ($item->supplier_id ? DB::table('suppliers')->where('id', $item->supplier_id)->first() :
                                null)));



                                $expenseType = $item->expense_id ? DB::table('expences')->where('id',
                                $item->expense_id)->first() : null;
                                $ExpenseType = $expenseType ? $expenseType->title : "";
                                @endphp
                                <tr class="store_details_row">
                                    <td>{{$i}}</td>
                                    <td>@if($item->payment_date){{date('d/m/Y', strtotime($item->payment_date))}}@endif
                                    </td>
                                    <td>{{ $item->voucher_no }}</td>
                                    <td>Rs. {{number_format((float)$item->amount, 2, '.', '')}} (
                                        {{ucwords($item->bank_cash)}} )</td>
                                    <td>
                                        <a href="{{ route('admin.accounting.edit_depot_expense', $item->id) }}"
                                            class="btn btn-outline-success select-md">Edit</a>
                                    </td>
                                </tr>
                                <tr>

                                    <td colspan="5" class="store_details_column">

                                        <div class="store_details">

                                            <table class="table">

                                                <tr>

                                                    <td><span>Amount: <strong>Rs. {{number_format((float)$item->amount,
                                                                2, '.', '')}}</strong></span></td>

                                                    @php
                                                    $expenseAt = '';

                                                    if ($item->stuff_id && $item->staff) {
                                                    $expenseAt = 'Staff Name: <strong>' . ucwords($item->staff->name)
                                                        .'</strong>';
                                                    } elseif ($item->customer_id && $item->customer) {
                                                    $expenseAt = 'Customer Name: <strong>' .
                                                        ucwords($item->customer->name) .'</strong>';
                                                    } elseif ($item->supplier_id && $item->supplier) {
                                                    $expenseAt = 'Supplier Name: <strong>' .
                                                        ucwords($item->supplier->name) .'</strong>';
                                                    }
                                                    @endphp

                                                    <td>{!! $expenseAt !!}</strong></span></td>


                                                    @if (!empty($item->payment_mode))

                                                    <td><span>Payment Mode: <strong>{{
                                                                ucwords($item->payment_mode)}}</strong></span></td>

                                                    @endif

                                                    @if (!empty($item->bank_name))

                                                    <td><span>Bank: <strong>{{
                                                                ucwords($item->bank_name)}}</strong></span></td>

                                                    @endif

                                                    @if (!empty($item->chq_utr_no))

                                                    <td><span>Cheque / UTR No: <strong>{{
                                                                ucwords($item->chq_utr_no)}}</strong></span></td>

                                                    @endif

                                                    @if (!empty($item->narration))

                                                    <td><span>Narration: <strong>{{
                                                                ucwords($item->narration)}}</strong></span></td>

                                                    @endif

                                                </tr>

                                                <tr>

                                                    @if (!empty($item->created_by))

                                                    <td><span>Created By: <strong>{{
                                                                ucwords($item->creator?$item->creator->name:"
                                                                ")}}</strong></span></td>

                                                    <td><span>Created At: <strong>{{ date('d/m/Y h:i A',
                                                                strtotime($item->created_at)) }}</strong></span></td>
                                                    @endif
                                                    @if($ExpenseAt)
                                                    <td><span>Expense At: <strong>{{ $ExpenseAt }}</strong></span></td>
                                                    @endif
                                                    @if($ExpenseType)
                                                    <td><span>Expense: <strong>{{ $ExpenseType }}</strong></span></td>
                                                    @endif
                                                </tr>

                                                <tr>

                                                    @if (!empty($item->updater))

                                                    <td><span>Updated By: <strong>{{
                                                                ucwords($item->updater->name)}}</strong></span></td>

                                                    <td><span>Updated At: <strong>{{ date('d/m/Y h:i A',
                                                                strtotime($item->updated_at)) }}</strong></span></td>

                                                    @endif

                                                </tr>

                                            </table>

                                        </div>

                                    </td>

                                </tr>
                                @php $i++; @endphp
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No collection records found in selected date
                                        range.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{--
        </div> --}}
        {{-- </div> --}}
    <div class="loader-container" wire:loading>
        <div class="loader"></div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
                $(document).on('click', '.store_details_row', function () {
                $(this).next("tr").find(".store_details_column").toggle();
            });
        });
    window.addEventListener('confirm-revoke', event => {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will revoke the payment and reset linked invoices and payments!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, revoke it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('revoke-payment-confirmed', { id: event.detail });
            }
        });
    });

   
</script>

</div>