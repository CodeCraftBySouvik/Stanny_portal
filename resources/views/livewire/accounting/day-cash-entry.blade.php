<div class="container">
    <section class="admin__title">
        <h5>Daily Cash Entry</h5>
    </section>
    <section>
        <ul class="breadcrumb_menu">
            <li>Daily Cash Entry</li>
            <li></li>
            <!-- <li>Create Customer</li> -->
        </ul>
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <!-- <p class="text-sm font-weight-bold">Items</p> -->
            </div>
        </div>
    </section>

    <div class="row mb-4">
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
            <div class="row">
                <div class="col-12">
                    <div class="card my-4">
                        <div class="card-header pb-0">
                            <div class="row">
                                @if (session()->has('success'))
                                    <div class="alert alert-success" id="flashMessage">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session()->has('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card my-4">
                                            <div class="card-body px-0 pb-2 mx-4">

                                                <form wire:submit.prevent="submit">
                                                    <div class="row">


                                                        <label class="form-label"> User <span
                                                                class="text-danger">*</span></label>
                                                        <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                                            <select wire:model="staff_id"
                                                                class="form-control @error('staff_id') is-invalid @enderror" wire:change="fetchBalance($event.target.value)">
                                                                <option value="">Choose an user</option>
                                                                @foreach ($staffs as $staff)
                                                                    <option value="{{ $staff->id }}">
                                                                        {{ ucwords($staff->name) }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @error('user_id')
                                                            <p class='text-danger'>{{ $message }}</p>
                                                        @enderror
                                                        <label class="form-label"> Type <span
                                                                class="text-danger">*</span></label>
                                                        <div
                                                            class="ms-md-auto pe-md-3 d-flex align-items-center mb-2  @error('entry_type') is-invalid @enderror">
                                                            <select class="form-control" wire:model="entry_type" wire:change="setEntryType($event.target.value)">
                                                                <option value="">Select Type</option>
                                                                <option value="collect">Collect</option>
                                                                <option value="given">Given</option>

                                                            </select>
                                                        </div>
                                                        @error('entry_type')
                                                            <p class='text-danger'>{{ $message }}</p>
                                                        @enderror
                                                        <label class="form-label"> Current Wallet Balance<span
                                                                class="text-danger">*</span></label>
                                                        <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                                            <input type="text"
                                                                class="form-control "
                                                                wire:model="totalWallet" placeholder="Current Wallet Balance">
                                                        </div>
                                                        @error('todo_date')
                                                            <p class='text-danger'>{{ $message }}</p>
                                                        @enderror
                                                        @if (!empty($entry_type))
                                                        <label class="form-label">
                                                            @if ($entry_type=='collect')
                                                            Collected Amount
                                                            @endif
                                                             @if ($entry_type=='given')
                                                            Given Amount
                                                            @endif
                                                            <span
                                                                class="text-danger">*</span></label>
                                                        <div class="ms-md-auto pe-md-3 d-flex align-items-center mb-2">
                                                            <input type="text"
                                                                class="form-control  @error('amount') is-invalid @enderror"
                                                                wire:model="amount" placeholder="ToDo Date">
                                                        </div>
                                                        @error('amount')
                                                            <p class='text-danger'>{{ $message }}</p>
                                                        @enderror
                                                        @endif


                                                        <div class="mb-2 text-end mt-4">
                                                            <button type="submit"
                                                                class="btn btn-sm btn-success select-md"
                                                                wire:loading.attr="disabled">
                                                                <span>Create</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                            </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
</div>
