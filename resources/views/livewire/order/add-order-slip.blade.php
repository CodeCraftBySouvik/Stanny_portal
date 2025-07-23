<div class="container">
    <section class="admin__title">
        <h5>Confirm Order</h5>
    </section>
    <section>
        <ul class="breadcrumb_menu">
            <li><a href="{{route('admin.order.index')}}">Orders</a></li>
            <li>Order No:- <span>#{{$order->order_number}}</span></li>
            <li class="back-button">
                <a href="{{route('admin.order.index')}}" class="btn btn-sm btn-danger select-md text-light font-weight-bold mb-0">Back </a>
            </li>
          </ul>
    </section>
    <form wire:submit.prevent="submitForm">
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
                                    <p class="small m-0">{{number_format($order_detail->total_amount, 2)}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="small m-0"><strong>Order Time :</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="small m-0">{{ $order_detail->created_at->format('d M Y h:i A') }}</p>
                                </div>
                            </div>

                            <div class="row">

                                <div>


                                </div>
                            </div>



                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group mb-3">
                            @php
                                $hasDelivered = false;
                                foreach ($orderItemsNew as $key => $item) {
                                foreach ($item['deliveries'] as $delivery) {
                                    if($delivery['status'] == 'Delivered'){
                                            $hasDelivered = true;
                                            break 2;   // exit both loops
                                    }
                                }
                                }
                            @endphp
                            <div class="d-flex justify-content-between align-items-center">
                                <h6>Customer Details</h6>
                                @if ($hasDelivered)
                                    <p>
                                        <a class="btn btn-outline-success select-md" href="{{ route('orders.generatePdf', $order_detail->id) }}" target="_blank">Download</a>
                                    </p>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="small m-0"><strong>Person Name :</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="small m-0">{{$order_detail->customer_name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="small m-0"><strong>Company Name :</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="small m-0">{{$order_detail->customer?$order_detail->customer->company_name:"---"}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="small m-0"><strong>Rank :</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="small m-0">{{$order_detail->customer?$order_detail->customer->employee_rank:"---"}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="small m-0"><strong>Email :</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="small m-0"> {{$order_detail->customer_email}} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="small m-0"><strong>Mobile :</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="small m-0"> {{$order_detail->customer? $order_detail->customer->phone: ""}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <p class="small m-0"><strong> Address :</strong></p>
                                </div>
                                <div class="col-sm-8">
                                    <p class="small m-0">{{$order_detail->billing_address}}</p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
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
                            @foreach($order->items as $key=>$order_item)
                            @php
                                $magrin = '';
                                if($key!=0){
                                    $magrin = "margin-bottom: 20px;";
                                }
                            @endphp
                            <div class="col-sm-3">
                                <table>
                                    <tr>
                                        <td>
                                            <span class="text-sm badge bg-primary sale_grn_sl" style="{{$magrin}}">{{$key+1}}</span>
                                        </td>
                                        <td class="w-100">
                                            <div class="form-group mb-3">
                                            @if($key==0)
                                                <label>Product</label>
                                            @endif
                                            <div class="position-relative">
                                                <input type="hidden" wire:model="order_item.{{$key}}.price" class="form-control form-control-sm">
                                                <input type="hidden" wire:model="air_mail" class="form-control form-control-sm">
                                                <input type="hidden" wire:model="order_item.{{$key}}.id" class="form-control form-control-sm" value="{{$order_item->id}}">
                                                <input type="text" value="{{$order_item->product_name}}" class="form-control form-control-sm border border-1 customer_input" {{$readonly}}>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                </table>

                            </div>
                            @php
                                $user = auth()->guard('admin')->user();
                            @endphp
                            <div class="{{$user->designation == 1 ? 'col-sm-2' : 'col-sm-3'}}">
                                <div class="form-group mb-3">
                                    @if($key==0)
                                        <label>Quantity</label>
                                    @endif
                                    <input type="text" class="form-control form-control-sm" value="{{$order_item->quantity}}" disabled {{$readonly}}>
                                </div>
                            </div>
                            <div class="{{$user->designation == 1 ? 'col-sm-2' : 'col-sm-3'}}">
                                <div class="form-group mb-3">
                                    @if($key==0)
                                        <label for="">Price</label>
                                    @endif
                                    <input type="text" class="form-control form-control-sm" value="{{$order_item->piece_price}}" disabled>
                                    
                                </div>
                            </div>
                             <div class="col-sm-2">
                                <div class="form-group mb-3">
                                    @if($key==0)
                                        <label>Status</label>
                                    @endif
                                    @php
                                        $isApprovedByTL = $order_item->status === 'Process' && $order_item->tl_status === 'Approved';
                                         $isApprovedByAdmin = $order_item->status === 'Process' && $order_item->admin_status === 'Approved';
                                    @endphp
                                           <input type="text"
                                            class="form-control form-control-sm text-white fw-bold rounded-pill text-center
                                                    {{ $isApprovedByAdmin ? 'bg-primary' : ($isApprovedByTL ? 'bg-info' : ($order_item->status === 'Process' ? 'bg-success' : 'bg-danger')) }}"
                                           value="{{ $isApprovedByAdmin ? 'Approved by SuperAdmin' : ($isApprovedByTL ? 'Approved by TL' : $order_item->status) }}"
                                            disabled
                                            {{$readonly}}>
                                </div>
                            </div>
                            <div class="col-sm-1">
                               
                                @php
                                    //  Get the full user object once
                                    $user = auth()->guard('admin')->user();

                                    //  Compare against full user id
                                    $createdByThisAdmin = $order->created_by == $user->id;

                                    $isApprovedByTL = $order_item->status === 'Process' && $order_item->tl_status === 'Approved';
                                @endphp

                                 {{--  Admin checkbox when TL has approved --}}
                                 @if ($user->designation == 1 && $isApprovedByTL)
                                      <input type="checkbox" wire:model="order_item.{{$key}}.admin_approved"
                                        wire:change="updateAdminStatus({{ $key }})"
                                        {{$isApprovedByAdmin ? 'disabled checked' : ''}}>
                                @elseif($user->designation == 4)
                                {{--  TL checkbox for approving Process items --}}
                                    @if($order_item->status == 'Process')
                                        <input type="checkbox" wire:model="order_item.{{$key}}.tl_approved"
                                         wire:change="updateTlStatus({{ $key }})" 
                                         {{$isApprovedByAdmin ? 'disabled checked' : ''}}>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                @else
                                 {{--  For others: Show tl_status as badge --}}
                                    @if (!$isApprovedByTL)
                                        <span class="badge {{ $order_item->tl_status == 'Approved' ? 'bg-success' : ($order_item->tl_status == 'Hold' ? 'bg-danger' : 'bg-secondary') }}">
                                            {{ $order_item->tl_status ?? 'Pending' }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                            {{-- Team Dropdown start--}}
                            {{-- Only Admin Can select the team  --}}
                            @if ($user->designation == 1)
                            <div class="col-sm-2">
                                <div class="form-group mb-3">
                                    @if($key == 0)
                                        <label>Team</label>
                                    @endif
                                    
                                    <select wire:model="order_item.{{ $key }}.team" class="form-control form-control-sm" @if(!empty($order_item[$key]['team'])) disabled @endif>
                                        <option value="" selected hidden>Select Team</option>
                                        <option value="sales">Sales Team</option>
                                        <option value="production">Production Team</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                            {{-- Team Dropdown end--}}
                            {{-- Start the measurement section --}}
                            @if($order_item->collection == 1 && !empty($order_item->measurements))
                            <div class="row">
                                <div class="col-sm-7">
                                <div class="section-title" style="background: black; color: white; padding: 5px 10px; border-radius: 5px; display: inline-block; margin-bottom: 10px;">Measurements</div>

                                @php
                                    $measurements = collect($order_item['measurements'])->mapWithKeys(function($m) {
                                        return [$m['measurement_name'] . ' [' . $m['measurement_title_prefix'] . ']' => $m['measurement_value']];
                                    });
                                    $chunks = array_chunk($measurements->toArray(), 5, true);
                                @endphp

                                <table width="100%" cellspacing="0" cellpadding="6">
                                    @foreach($chunks as $row)
                                        <tr>
                                            @foreach($row as $label => $value)
                                                <td style="padding: 8px; vertical-align: top;">
                                                    <div style="font-size: 11px; font-weight: bold; margin-bottom: 3px;">{{ $label }}</div>
                                                    <div style="
                                                        border: 1px solid #ccc;
                                                        padding: 6px;
                                                        background: #fff;
                                                        font-size: 12px;
                                                        border-radius: 4px;
                                                        min-height: 25px;
                                                        text-align: center;">
                                                        {{ $value }}
                                                    </div>
                                                </td>
                                            @endforeach
                                            @for ($i = count($row); $i < 5; $i++)
                                                <td></td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                </table>
                                </div>
                                <div class="col-sm-5">
                                    <p><strong>Fabric:</strong> {{ $order_item['fabric']->title ?? 'N/A' }}</p>
                                    <p><strong>Catalogue:</strong>
                                        {{ optional(optional($order_item['catalogue'])->catalogueTitle)->title ?? 'N/A' }}
                                        (Page: {{ $order_item['cat_page_number'] ?? 'N/A' }})
                                    </p>
                                </div>
                            </div>

                        @endif

                            @endforeach


                            {{-- Air mail --}}
                            @if($order->air_mail > 0)
                            @php
                              $air_mail_price = round($order->air_mail);
                            @endphp
                            <div class="col-sm-6">
                                <table>
                                    <tr>
                                        <td>
                                            <span class="text-sm badge bg-primary sale_grn_sl">{{$order->items->count() +1}}</span>
                                        </td>
                                        <td class="w-100">
                                            <div class="form-group mb-3">
                                                <label>AIR MAIL</label>
                                                <div class="position-relative">
                                                    <input type="text" value="AIR MAIL" class="form-control form-control-sm border border-1 customer_input" readonly>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group mb-3">
                                    <label>Quantity</label>
                                    <input type="text" class="form-control form-control-sm" value="1" readonly>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group mb-3">
                                    <label>Price</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ $air_mail_price }}" readonly>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group text-end">
                        <span>ORDER AMOUNT <span class="text-danger">({{$actual_amount}})</span></span>
                         @if($user && $user->designation == 1)
                           <button wire:click="setTeamAndSubmit" class="btn btn-sm btn-success">Approve Order</button>
                        @else
                            <button type="submit" id="submit_btn"
                                class="btn btn-sm btn-success"><i class="material-icons text-white" style="font-size: 15px;">add</i>Confirm
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="card mt-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group mb-3">
                            <label for="" id="">Customer <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="text" wire:model="customer"
                                    class="form-control form-control-sm border border-1 customer_input"
                                    placeholder="Search customer by name, mobile, order ID" {{$readonly}}>
                                    <input type="hidden" wire:model="customer_id" value="">
                                    <input type="hidden" wire:model="staff_id" value="">
                                    @if(isset($errorMessage['customer_id']))
                                        <div class="text-danger">{{ $errorMessage['customer_id'] }}</div>
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-3">
                            <label for="">Voucher No</label>
                            <input type="text" wire:model="voucher_no"
                                class="form-control form-control-sm" disabled {{$readonly}}>
                                @if(isset($errorMessage['voucher_no']))
                                    <div class="text-danger">{{ $errorMessage['voucher_no'] }}</div>
                                @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-3">
                            <label for="">Date <span class="text-danger">*</span></label>
                            <input type="date" wire:model="payment_date" id="payment_date" max="{{date('Y-m-d')}}"
                                class="form-control form-control-sm" value="{{date('Y-m-d')}}">
                                @if(isset($errorMessage['payment_date']))
                                    <div class="text-danger">{{ $errorMessage['payment_date'] }}</div>
                                @endif
                        </div>
                    </div>
                </div>
                <div class="row justify-content-{{$activePayementMode=="cash"?"end":"start"}}">
                    <div class="col-sm-4">
                        <div class="form-group mb-3">
                            <label for="">Mode of Payment <span class="text-danger">*</span></label>
                            <select wire:model="payment_mode" class="form-control form-control-sm" id="payment_mode" wire:change="ChangePaymentMode($event.target.value)">
                                <option value="" selected hidden>Select One</option>
                                <option value="cheque">Cheque</option>
                                <option value="neft">NEFT</option>
                                <option value="cash">Cash</option>
                            </select>
                            @if(isset($errorMessage['payment_mode']))
                                <div class="text-danger">{{ $errorMessage['payment_mode'] }}</div>
                            @endif
                        </div>
                    </div>
                    @if($activePayementMode!=="cash")
                    <div class="col-sm-4">
                        <div class="form-group mb-3">
                            <label for="">Cheque No / UTR No </label>
                            <input type="text" value="" wire:model="chq_utr_no" class="form-control form-control-sm"
                                maxlength="100">
                                @if(isset($errorMessage['chq_utr_no']))
                                    <div class="text-danger">{{ $errorMessage['chq_utr_no'] }}</div>
                                @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group mb-3">
                            <label for="">Bank Name </label>
                            <div id="bank_search">
                                <input type="text" id="" placeholder="Search Bank" wire:model="bank_name"
                                    value=""
                                    class="form-control bank_name form-control-sm" maxlength="200">
                                    @if(isset($errorMessage['bank_name']))
                                        <div class="text-danger">{{ $errorMessage['bank_name'] }}</div>
                                    @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row justify-content-end">
                    <div class="col-sm-2">
                        <div class="form-group mb-3">
                            <label for="">Actual Amount <span class="text-danger">*</span></label>
                            <input type="text" value="" maxlength="20" wire:model="actual_amount" class="form-control form-control-sm" {{$readonly}}>
                            @if(isset($errorMessage['actual_amount']))
                                <div class="text-danger">{{ $errorMessage['actual_amount'] }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group mb-3">
                            <label for="">Paid Amount<span class="text-danger">*</span></label>
                            <input type="text" value="" maxlength="20" wire:model="amount" class="form-control form-control-sm">
                            @if(isset($errorMessage['amount']))
                                <div class="text-danger">{{ $errorMessage['amount'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group text-end">
                        <button type="submit" id="submit_btn"
                            class="btn btn-sm btn-success"><i class="material-icons text-white" style="font-size: 15px;">add</i>Save</button>
                    </div>
                </div>
            </div>
        </div> --}}
    </form>
</div>
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function validateNumber(input) {
        // Remove any characters that are not digits or a single decimal point
        input.value = input.value.replace(/[^0-9.]/g, '');

        // Ensure only one decimal point is allowed
        const parts = input.value.split('.');
        if (parts.length > 2) {
        input.value = parts[0] + '.' + parts[1];
        }
    }

        //     document.addEventListener('DOMContentLoaded', function () {
        //     const btn = document.getElementById('confirmBtn');
        //     if (btn) {
        //         btn.addEventListener('click', function () {
        //             Swal.fire({
        //                 title: 'Select Confirmation Team',
        //                 html: `
        //                     <div class="text-start">
        //                         <div class="form-check">
        //                             <input class="form-check-input" type="radio" name="confirm_team" id="salesTeam" value="sales" checked>
        //                             <label class="form-check-label" for="salesTeam">Sales Team</label>
        //                         </div>
        //                         <div class="form-check">
        //                             <input class="form-check-input" type="radio" name="confirm_team" id="productionTeam" value="production">
        //                             <label class="form-check-label" for="productionTeam">Production Team</label>
        //                         </div>
        //                     </div>
        //                 `,
        //                 icon: 'warning',
        //                 showCancelButton: true,
        //                 confirmButtonText: 'Confirm',
        //                 cancelButtonText: 'Cancel',
        //             preConfirm: () => {
        //                     const selectedTeam = document.querySelector('input[name="confirm_team"]:checked');
        //                     if (!selectedTeam) {
        //                         Swal.showValidationMessage(`Please select a team`);
        //                         return false;
        //                     }
        //                     return selectedTeam.value;
        //                 }
        //             }).then((result) => {
        //                 if (result.isConfirmed) {
        //                     // Pass selected team value to Livewire
        //                     const selectedTeam = result.value;
        //                     @this.call('setTeamAndSubmit', selectedTeam);
        //                 }
        //             });
        //         });
        //     }
        // });

        
</script>

@endpush

