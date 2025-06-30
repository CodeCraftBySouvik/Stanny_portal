<di class="container">
    <section class="admin__title">
        <h5>Order detail</h5>
    </section>
    <section>
        <ul class="breadcrumb_menu">
            <li><a href="{{route('admin.order.index')}}">Orders</a></li>
            <li>Order detail :- <span>#{{$order->order_number}}</span></li>
            <li class="back-button">
                <a href="{{route('admin.order.index',$order->customer_id)}}"
                    class="btn btn-sm btn-danger select-md text-light font-weight-bold mb-0">Back </a>
            </li>
        </ul>
    </section>
    <div id="export-area">
    <div class="card shadow-sm mb-2">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group mb-3">
                        <h6>Order Information</h6>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="small m-0">

                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="small m-0"><strong>Order Amount :</strong></p>
                            </div>
                            <div class="col-sm-8">
                                <p class="small m-0">{{number_format($order->total_amount, 2)}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="small m-0"><strong>Order Time :</strong></p>
                            </div>
                            <div class="col-sm-8">
                                <p class="small m-0">{{ $order->created_at->format('d M Y h:i A') }}</p>
                            </div>
                        </div>



                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group mb-3">
                        <h6>Customer Details</h6>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="small m-0"><strong>Person Name :</strong></p>
                            </div>
                            <div class="col-sm-8">
                                <p class="small m-0">{{$order->customer_name}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="small m-0"><strong>Company Name :</strong></p>
                            </div>
                            <div class="col-sm-8">
                                <p class="small m-0">{{$order->customer?$order->customer->company_name:"---"}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="small m-0"><strong>Rank :</strong></p>
                            </div>
                            <div class="col-sm-8">
                                <p class="small m-0">{{$order->customer?$order->customer->employee_rank:"---"}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="small m-0"><strong>Email :</strong></p>
                            </div>
                            <div class="col-sm-8">
                                <p class="small m-0"> {{$order->customer_email}} </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="small m-0"><strong>Mobile :</strong></p>
                            </div>
                            <div class="col-sm-8">
                                <p class="small m-0"> {{$order->customer? $order->customer->phone: ""}}</p>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-sm-4">
                                <p class="small m-0"><strong>WhatsApp :</strong></p>
                            </div>
                            <div class="col-sm-8">
                                <p class="small m-0"> {{$order->customer? $order->customer->whatsapp_no: ""}}</p>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-sm-4">
                                <p class="small m-0"><strong> Address :</strong></p>
                            </div>
                            <div class="col-sm-8">
                                <p class="small m-0">{{$order->billing_address}}</p>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-sm-4">
                                <p class="small m-0"><strong>Shipping Address :</strong></p>
                            </div>
                            <div class="col-sm-8">
                                <p class="small m-0">{{$order->shipping_address}}</p>
                            </div>
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm mb-2">
        <div class="table-responsive">
            <div class="card-body">
                <table class="table table-sm ledger">
                    <thead>
                        <tr>
                            <th class="" rowspan="1" colspan="1" style="width: 65px;" aria-label="price">Collection</th>
                            <th class="w-50 " rowspan="1" colspan="1" style="width: 328px;" aria-label="products">Order
                                Items</th>
                            <th class="" rowspan="1" colspan="1" style="width: 65px;" aria-label="price">price</th>
                            <th class="" rowspan="1" colspan="1" style="width: 50px;" aria-label="qty">
                                qty</th>
                            <th class="" rowspan="1" colspan="1" style="width: 80px;" aria-label="total">total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($orderItems->isNotEmpty())

                        @foreach ($orderItems as $item)
                        {{-- {{dd()}} --}}
                        <tr class="odd" style="background-color: #f2f2f2;">
                            <td>{{$item['collection_title']}}</td>
                            <td class="">
                                <div class="d-flex justify-content-start align-items-center product-name">
                                    <div class="me-3">
                                        @if (!empty($item['product_image']))
                                        <div class="avatar avatar-sm rounded-2 bg-label-secondary">
                                            <img src="{{ asset('storage/' . $item['product_image']) }}"
                                                alt="Product Image" class="rounded-2">
                                        </div>
                                        @else
                                        <div class="avatar avatar-sm rounded-2 bg-label-secondary">
                                            <img src="{{asset('assets/img/cubes.png')}}" alt="Default Image"
                                                class="rounded-2">
                                        </div>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span
                                            class="text-nowrap text-heading fw-medium">{{$item['product_name']}}</span>
                                    </div>
                                </div>
                            </td>
                            <td><span>{{number_format($item['price'], 2)}}</span></td>
                            <td><span>{{$item['quantity']}}</span></td>
                            <td><span>{{number_format($item['price']*$item['quantity'], 2)}}</span></td>
                        </tr>
                        @if(!empty($item['deliveries']) && count($item['deliveries'])>0)
                        <tr>
                            <td colspan="5">
                                <div class="col-12 mb-2 measurement_div" style="background: #fdfdfd !important;">
                                    <h6 class="badge bg-danger custom_success_badge">Delivery Logs</h6>
                                    <div class="row">
                                        <table class="table table-sm ledger">
                                            <thead>
                                                <tr>
                                                    <th   rowspan="1" colspan="1"  aria-label="products" style="width: 5%;">
                                                        Sl No</th>
                                                    <th  rowspan="1" colspan="1"  aria-label="products">Delivery
                                                        Date</th>
                                                        <th  rowspan="1" colspan="1"  aria-label="products">
                                                            Status</th>

                                                        <th  rowspan="1" colspan="1"  aria-label="products">Delivered BY (Production)</th>
                                                    <th class="" rowspan="1" colspan="1"  aria-label="qty">
                                                        qty</th>
                                                        <th  rowspan="1" colspan="1" aria-label="products">
                                                            Remarks</th>
                                                    <th class="" rowspan="1" colspan="1"  aria-label="total">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item['deliveries'] as $index=> $delivery_data)
                                                <tr class="odd" style="background-color: #f2f2f2;">
                                                    <td >{{ ++$index }}</td>
                                                    <td >{{ date('d-m-Y h:i A ',timestamp: strtotime($delivery_data['delivered_at'])) }}</td>
                                                    <td>


                                                        @if($delivery_data['status']=='Pending')
                                                        <span class="badge bg-primary">Pending</span>
                                                        @endif
                                                        @if($delivery_data['status']=='Received by Sales Team')
                                                        <span class="badge bg-warning">Received by Sales Team</span>
                                                        @endif
                                                        @if($delivery_data['status']=='Rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                        @endif
                                                        @if($delivery_data['status']=='Alteration Required')
                                                        <span class="badge bg-info">Alteration Required</span>
                                                        @endif
                                                        @if($delivery_data['status']=='Delivered')
                                                        <span class="badge bg-success">Delivered</span>
                                                        @endif
                                                    </td>

                                                    <td>{{ $delivery_data['user']['name'] }}</td>

                                                    <td>
                                                        @if ($delivery_data['collection_id'] == 1)
                                                           {{ $delivery_data['fabric_quantity'] ?? '' }}
                                                        @else
                                                           {{ $delivery_data['delivered_quantity'] ?? '' }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" class="btn btn-info" title="{{ $delivery_data['remarks'] }}">Show Remarks Click Here</button>
                                                    </td>
                                                    <td>
                                                        @if($delivery_data['status']=='Pending')
                                                        <a
                                                            wire:click="$dispatch('mark-as-received', {Id: {{ $delivery_data['id'] }}})"
                                                            class="btn btn-outline-warning select-md btn_outline" data-toggle="tooltip">Receive by Sales Team</a>
                                                        @endif
                                                         @if($delivery_data['status']=='Received by Sales Team')
                                                            <a href="javascript:void(0)"
                                                            wire:click="$dispatch('delivered-to-customer', {orderId: '{{ $order->id }}',Id:{{ $delivery_data['id'] }} })"
                                                            class="btn btn-outline-success select-md btn_outline"  >Delivery to Customer
                                                            </a>
                                                        @endif


                                                    </td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </td>

                        </tr>
                        @endif
                        @if($item['collection_id']==1)
                            <tr>
                                <td colspan="2">
                                    <div class="col-12 mb-2 measurement_div" style="background: #fdfdfd !important;">
                                        <h6 class="badge bg-danger custom_success_badge">Measurements</h6>
                                        <div class="row">

                                            @foreach ($item['measurements'] as $index => $measurement)
                                            <div class="col-md-3">
                                                <label>
                                                    {{$measurement['measurement_name']}}
                                                    <strong>[{{$measurement['measurement_title_prefix']}}]</strong>
                                                </label>
                                                <input type="text"
                                                    class="form-control form-control-sm border border-1 customer_input text-center measurement_input"
                                                    value="{{ $measurement['measurement_value'] }}">
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </td>
                                <td colspan="3" class="pt-4" style="vertical-align: text-top !important;">
                                    <p>FABRIC : <strong>{{$item['fabrics']->title}}</strong></p>
                                    <p>CATLOGUE : <strong>{{ optional(optional($item['catalogue'])->catalogueTitle)->title }}</strong> (PAGE:
                                        <strong>{{$item['cat_page_number']}}</strong>)
                                    </p>
                                    @if(!empty($item['remarks']))
                                    <p>Remark :
                                        <strong>{{$item['remarks']}}</strong>
                                    </p>
                                    @endif
                                    @if(!empty($item['catlogue_image']['image_path']))
                                    <p>CATLOGUE IMAGE :
                                        <div class="d-flex justify-content-center">
                                            <a target="_blank" href="{{ asset('storage/'.$item['catlogue_image']['image_path']) }}">
                                                <img src="{{ asset('storage/'.$item['catlogue_image']['image_path']) }}" style="width:150px;height:150px;" class="img-fluid rounded shadow border border-secondary" alt="Styled Image">

                                            </a>

                                        </div>
                                    </p>
                                    @endif
                                    @if(!empty($item['voice_remark']['voices_path']))
                                    <p>VOICE REMARKS :
                                                <audio controls>
                                                    <source src="{{ asset('storage/'.$item['voice_remark']['voices_path']) }}" type="audio/mpeg">
                                                  Your browser does not support the audio element.
                                                  </audio>


                                    </p>
                                    @endif


                                </td>
                            </tr>
                        @endif
                        @endforeach
                        @else
                        <tr>
                            <td>
                                <p>No items found for this order.</p>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-left"><small>Total Amount:</small></td>
                            <td><strong>{{number_format($order->total_amount, 2)}}</strong></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-left"><small>Paid Amount:</small></td>
                            <td><strong>{{number_format($order->paid_amount, 2)}}</strong></td>
                        </tr>
                        @if ($order->remaining_amount > 0)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-left"><small>Remaining Amount:</small></td>
                            <td><strong class="text-danger">{{number_format($order->remaining_amount, 2)}}</strong></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
{{-- Modal Content  --}}

    <div wire:ignore.self class="modal fade" id="DeliveryModal" tabindex="-1" aria-labelledby="stockEntryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockEntryModalLabel">Delivery Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">



                <div class="card">
                    <form wire:submit.prevent="deliveredToCustomerPartial">
                        <div class="card-body">
                            <h6>Delivery Status</h6>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">
                                        <strong>Status</strong> <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('status') is-invalid @enderror" wire:model="status" >
                                        <option value="">Select Status</option>
                                        <option value="Delivered">Delivered</option>
                                        <option value="Alteration Required">Alteration Required</option>
                                        <option value="Reject">Rejected</option>

                                    </select>
                                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">
                                        <strong>Remarks</strong> <span class="text-danger">*</span>
                                    </label>

                                    <textarea class="form-control @error('remarks') is-invalid @enderror"" wire:model="remarks"></textarea>
                                    @error('remarks') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="row mb-3">


                                <div class="col-md-2 mt-4">
                                    <button class="btn btn-outline-success select-md">Submit</button>

                                </div>
                            </div>
                        </div>
                   </form>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button"  class="btn btn-primary">Save Stock</button> --}}
            </div>
            </div>
        </div>
    </div>


</div>



@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Initialize all tooltips on the page
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
window.addEventListener('delivered-to-customer', event => {
        const { Id,orderId } = event.detail;
        let myModal = new bootstrap.Modal(document.getElementById('DeliveryModal'));
        myModal.show();
        Livewire.dispatch('openDeliveryModal', { Id, orderId });

    });
    window.addEventListener('close-delivery-modal', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('DeliveryModal'));
        if (modal) {
            modal.hide();
        }

        // ✅ Custom tracking logic here
        console.log("Delivery modal closed via Livewire event");

        // Optional: Reset modal content
        document.querySelector('#DeliveryModal form').reset();
        Swal.fire({
        title: "Success",
        text: "Customer Delivery Status updated successfully",
        icon: "success"
        }).then((result) => {
    if (result.isConfirmed) {
        // Reload the page
        window.location.reload();
    }
});;
    });
    window.addEventListener('mark-as-received', event => {
    const {Id } = event.detail;
    Swal.fire({
            title: "Are you sure?",
            text: "The Production Team has marked this item as delivered. Please confirm that you have received it. Once confirmed, this action cannot be undone.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, received by sales team!"
            }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('markReceivedConfirmed', {Id});

            }
            })

    });
</script>

@endpush


