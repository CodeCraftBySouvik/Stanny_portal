<div class="container">
    <section class="admin__title">
        <h5>Order History</h5>
    </section>
    <section>
        <div class="search__filter">
            <div class="row align-items-center justify-content-end">
                <div class="col-auto">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto" style="margin-top: -27px;">
                            <label for="" class="date_lable">Start Date</label>
                            <input type="date" wire:model="start_date" wire:change="AddStartDate($event.target.value)"
                                class="form-control select-md bg-white" placeholder="Start Date">
                        </div>
                        <div class="col-auto" style="margin-top: -27px;">
                            <label for="" class="date_lable">End date</label>
                            <input type="date" wire:model="end_date" wire:change="AddEndDate($event.target.value)"
                                class="form-control select-md bg-white" placeholder="End Date">
                        </div>
                        <div class="col-auto" style="margin-top: -27px;">
                            <label for="" class="date_lable">Status</label>
                            <select class="form-control select-md bg-white" wire:change="setStatus($event.target.value)">
                                <option value="">Status</option>
                                <option value="Approval Pending">Approval Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Ready for Delivery">Ready for Delivery</option>
                                <option value="Partial Delivered By Production">Partial Delivered By Production</option>
                                <option value="Fully Delivered By Production">Fully Delivered By Production</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Returned">Returned</option>
                                <option value="Delivered to Customer">Delivered to Customer</option>
                                <option value="Partial Delivered to Customer">Partial Delivered to Customer</option>
                                <option value="Approved By TL">Approved By TL</option>


                            </select>
                        </div>
                        <div class="col-md-auto mt-3">
                            <a href="{{route('admin.order.new')}}" class="btn btn-outline-success select-md">Place New
                                Order</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <p class="text-sm font-weight-bold">{{count($orders)}} Items</p>
                </div>

                <div class="col-auto">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto mt-0">
                            <input type="text" wire:model="search" class="form-control select-md bg-white search-input" id="customer"
                                placeholder="Search by customer detail or Order number" value="" style="width: 350px;"
                                wire:keyup="FindCustomer($event.target.value)">
                        </div>
                        <div class="col-auto mt-0">
                            <select wire:model="created_by" class="form-control select-md bg-white"
                                wire:change="CollectedBy($event.target.value)">
                                <option value="" hidden="" selected="">Placed By</option>
                                @foreach($placed_by as $user)
                                    @if(in_array($user->id, $usersWithOrders))
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto mt-3">
                            <button type="button" wire:click="resetForm"
                                class="btn btn-outline-danger select-md">Clear</button>
                        </div>
                        <div class="col-auto">
                            <a href="javscript:void(0)" wire:click="export" class="btn btn-outline-success select-md"><i
                                    class="fas fa-file-csv me-1"></i>Export</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="card my-2">
        <div class="card-header pb-0">
            <div class="row">
                @if(session()->has('message'))
                    <div class="alert alert-success" id="flashMessage">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <div class="table-responsive p-0">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Order #</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Customer Details</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Order Amount</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Placed By</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="align-center">
                                    <span class="text-dark text-sm font-weight-bold mb-0">{{ env('ORDER_PREFIX'). $order->order_number }}</span><br>
                                    <p class="small text-muted mb-1 badge bg-warning">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                                </td>
                                <td>
                                    <p class="small text-muted mb-1">
                                        <span>Name: <strong>{{ucwords($order->prefix ." ". $order->customer_name)}}</strong> </span>
                                        <br>
                                        <span>Mobile : <strong>{{$order->customer? $order->customer->country_code_phone.' '.$order->customer->phone:""}}</strong> </span> <br>
                                        <!--<span>WhatsApp : <strong>{{$order->customer?$order->customer->country_code_whatsapp.' '.$order->customer->whatsapp_no:""}}</strong> </span>-->
                                    </p>
                                </td>
                                <td><p class="text-xs font-weight-bold mb-0">{{ $order->total_amount }}</p></td>
                                <td>
                                   <p class="small text-muted mb-1 text-uppercase">{{$order->createdBy?strtoupper($order->createdBy->name .' '.$order->createdBy->surname):""}}</p>
                                </td>

                                <td>

                                    <span class="badge bg-{{ $order->status_class }}">{{ $order->status_label }}</span>
                                </td>
                            <td class="text-center">
                                @php
                                    $userDesignationId = auth()->guard('admin')->user()->designation;
                                @endphp
                               
                                    @if(empty($order->packingslip))
                                        @if($order->status!="Cancelled")
                                            {{-- @if (in_array($userDesignationId,[1,4])) --}}
                                                {{-- @if($userDesignationId == 4 && $order->status=='Approval Pending')
                                                <a href="{{route('admin.order.add_order_slip', $order->id)}}" class="btn btn-outline-primary select-md btn_action btn_outline">Approve Order (TL)</a>
                                                @endif
                                                @if($userDesignationId==1 && $order->status=='Approved By TL')
                                                <a href="{{route('admin.order.add_order_slip', $order->id)}}" class="btn btn-outline-primary select-md btn_action btn_outline">Approve Order(Admin)</a>
                                                @endif --}}
                                            {{-- @endif --}}
                                            {{-- <a href="{{route('admin.order.edit', $order->id)}}" class="btn btn-outline-success select-md btn_outline" data-toggle="tooltip">Edit</a>

                                            <button  wire:click="confirmCancelOrder({{ $order->id }})"
                                            class="btn btn-outline-danger select-md btn_outline">Cancel Order</button > --}}

                                                {{-- New Code By Souvik --}}
                                             @if($userDesignationId == 1 && $order->status == 'Approved By TL')
                                                {{-- <a href="{{ route('admin.order.add_order_slip', $order->id) }}" class="btn btn-outline-success select-md btn_outline">
                                                    Approve Order
                                                </a> --}}

                                                <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-outline-success select-md btn_outline">
                                                    Edit
                                                </a>

                                                <button wire:click="confirmCancelOrder({{ $order->id }})" class="btn btn-outline-danger select-md btn_outline">
                                                    Cancel Order
                                                </button>
                                            @endif

                                            {{-- (Optional) TL Approve button --}}
                                            @if($userDesignationId == 4 && $order->status == 'Approval Pending')
                                                <a href="{{ route('admin.order.add_order_slip', $order->id) }}" class="btn btn-outline-success select-md btn_outline">
                                                    Approve Order
                                                </a>
                                                <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-outline-success select-md btn_outline">
                                                    Edit
                                                </a>

                                                <button wire:click="confirmCancelOrder({{ $order->id }})" class="btn btn-outline-danger select-md btn_outline">
                                                    Cancel Order
                                                </button>
                                            @endif

                                            {{-- Designation 2(Sales Person): Show only Edit and Cancel if status is Approval Pending --}}
                                            @if($userDesignationId == 2 && $order->status == 'Approval Pending')
                                                <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-outline-success select-md btn_outline">
                                                    Edit
                                                </a>
                                                <button wire:click="confirmCancelOrder({{ $order->id }})" class="btn btn-outline-danger select-md btn_outline">
                                                    Cancel Order
                                                </button>
                                            @endif
                                                {{-- New Code end By Souvik --}}
                                        @endif
                                    @else
                                         {{-- ✅ Admin override: Show Approve/Edit/Cancel even if slip exists and status is Approved By TL --}}
                                        @if($userDesignationId == 1 && $order->status == 'Approved By TL')
                                            <a href="{{ route('admin.order.add_order_slip', $order->id) }}" class="btn btn-outline-success select-md btn_outline">
                                                Approve Order
                                            </a>
                                            <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-outline-success select-md btn_outline">
                                                Edit
                                            </a>
                                            <button wire:click="confirmCancelOrder({{ $order->id }})" class="btn btn-outline-danger select-md btn_outline">
                                                Cancel Order
                                            </button>

                                        @else
                                            @if ($userDesignationId == 2 && $order->hasPendingItemsForApproval())
                                                <a href="{{ route('admin.order.edit', $order->id) }}" class="btn btn-outline-success select-md btn_outline">
                                                    Edit
                                                </a>
                                            @endif
                                            @if($userDesignationId == 1 && $order->hasPendingItemsForApproval())
                                                <a href="{{ route('admin.order.add_order_slip', $order->id) }}" class="btn btn-outline-success select-md btn_outline">
                                                Approve Order
                                            </a>
                                            @endif
                                            @if($userDesignationId == 4 && $order->hasPendingItemsForApproval())
                                                <a href="{{ route('admin.order.add_order_slip', $order->id) }}" class="btn btn-outline-success select-md btn_outline">
                                                   Approve Order
                                                </a>
                                            @endif
                                            
                                            <a href="{{route('admin.order.download_invoice',$order->id)}}" target="_blank" class="btn btn-outline-primary select-md btn_outline">Invoice</a>    
                                            <a href="{{route('admin.order.download_bill',$order->id)}}" target="_blank" class="btn btn-outline-primary select-md btn_outline">Bill</a>
                                        @endif

                                    @endif
                                     @if ($order->invoice_type=="invoice")
                                       <a href="{{route('admin.order.view',$order->id)}}" class="btn btn-outline-success select-md btn_action btn_outline">Details</a>
                                    @endif
                                    <a href="{{route('admin.order.log',$order->id)}}"><button class="btn btn-outline-info select-md btn_action btn_outline">Logs</button></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
    @if(empty($search))
    <div class="loader-container" wire:loading>
        <div class="loader"></div>
    </div>
    @endif

</div>
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.addEventListener('confirmCancel', function(event) {
            console.log("Received confirmCancel Event:", event.detail);

            if (event.detail && event.detail.orderId) {
                console.log("Order ID from Event:", event.detail.orderId);
            } else {
                console.error("Order ID is missing in the event.");
                return;
            }

            if (confirm('Are you sure you want to cancel the order?')) {
                console.log("Dispatching cancelOrder event with Order ID:", event.detail.orderId);
                Livewire.dispatch('cancelOrder', { orderId: event.detail.orderId });
            }
        });
    });




    </script>
@endpush
