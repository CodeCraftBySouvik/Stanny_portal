<div class="container">
    <!-- Navbar -->
    <!-- End Navbar -->
    <section class="admin__title">
        <h5>Purchase Order</h5>
    </section>
    <section>
        <ul class="breadcrumb_menu">
            <li>Purchase Order</li>
            <li><a href="{{route('purchase_order.create')}}">PO</a></li>
            <li class="back-button"></li>
          </ul>
    </section>
    <div class="search__filter">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <p class="text-sm font-weight-bold">{{count($data)}} Items</p>
            </div>
            <div class="col-auto">
                <div class="row g-3 align-items-center">
                    <div class="col-auto mt-0">
                        <input type="text" wire:model="search" class="form-control select-md bg-white search-input" id="customer"
                            placeholder="Search by supplier name or PO number" value=""
                            style="width: 350px;"  wire:keyup="FindCustomer($event.target.value)">
                    </div>
                    @if(!empty($search))
                    <div class="col-auto mt-3">
                        <button type="button" wire:click="resetForm" class="btn btn-outline-danger select-md">Clear</button>
                    </div>
                    @endif
                    <div class="col-md-auto mt-3">
                        <a href="{{route('purchase_order.create')}}" class="btn btn-outline-success select-md">Add New
                            PO</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
                <div class="card my-4">
                    <div class="card-body pb-0">
                        <!-- Display Success Message -->
                        @if (session('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Display Error Message -->
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="table-responsive p-0">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Ordered At</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">PO Number</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Net Amount</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Supplier</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-10">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $purchaseOrder)
                                    <tr>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $purchaseOrder->created_at?->format('d-m-Y') ?? 'N/A' }}
                                            </p>
                                        </td>
                                        <td>
                                            <div class="badge bg-success">{{ $purchaseOrder->unique_id }}</div>
                                        </td>
                                        <td>{{ $purchaseOrder->total_price }}
                                        </td>
                                        <td>
                                            {{ $purchaseOrder->supplier? $purchaseOrder->supplier->name : "" }}
                                        </td>
                                        <td>
                                            @if ($purchaseOrder->status == 0)
                                                <span class ="badge bg-warning"><span>Pending</span></span>
                                            @elseif ($purchaseOrder->status == 1)
                                                <span class ="badge bg-success"><span>Received</span></span>    
                                                
                                            @endif
                                            
                                        </td>
                                        
                                        <td class="align-middle action_tab">
                                           {{-- <a href="{{route('purchase_order.generate_pdf',['purchase_order_id'=>$purchaseOrder->id])}}" target="_blank" class="btn btn-outline-primary select-md btn_action btn_outline">
                                                PDF
                                            </a>--}}
                                            <button wire:click="downloadPdf({{ $purchaseOrder->id }})" class="btn btn-outline-primary select-md btn_outline">PDF</button>
                                            @if($purchaseOrder->status == 0)
                                                <a href="{{route('purchase_order.edit',$purchaseOrder->id)}}" class="btn btn-outline-primary select-md btn_action btn_outline" data-toggle="tooltip" data-original-title="Edit product">
                                                    Edit 
                                                </a>
                                                {{-- <button wire:click="deleteProduct({{ $purchaseOrder->id }})" class="btn btn-outline-danger btn-sm custom-btn-sm mb-0">Delete</button> --}}
                                            
                                                <a href="{{route('purchase_order.generate_grn',['purchase_order_id'=>$purchaseOrder->id])}}" class="btn btn-outline-primary select-md btn_action btn_outline">
                                                    Generate GRN
                                                </a>
                                            @endif
                                                <a href="{{route('purchase_order.details',['purchase_order_id'=>$purchaseOrder->id])}}" class="btn btn-outline-primary select-md btn_action btn_outline">
                                                    Details
                                                </a>
                                               
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <span class="text-xs text-secondary mb-0">No products found.</span>
                                        </td>
                                    </tr>
                                    @endforelse 
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $data->links() }} 
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>


