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
                            <div class="col-sm-6">
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
                            <div class="col-sm-3">
                                <div class="form-group mb-3">
                                    @if($key==0)
                                        <label>Quantity</label>
                                    @endif
                                    <input type="text" class="form-control form-control-sm" value="{{$order_item->quantity}}" disabled {{$readonly}}>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group mb-3">
                                    @if($key==0)
                                        <label for="">Price</label>
                                    @endif
                                    <input type="text" class="form-control form-control-sm" value="{{$order_item->piece_price}}" disabled>
                                    {{-- @if(isset($errorMessage["order_item.$key.quantity"]))
                                        <div class="text-danger">{{ $errorMessage["order_item.$key.quantity"] }}</div>
                                    @endif --}}
                                </div>
                            </div>

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
                        <button type="submit" id="submit_btn"
                            class="btn btn-sm btn-success"><i class="material-icons text-white" style="font-size: 15px;">add</i>Confirm</button>
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
</script>
@endpush

