<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />

    <!--  BEGIN CUSTOM STYLE FILE  -->
    <link rel="stylesheet" href="../src/plugins/src/filepond/filepond.min.css">
    <link rel="stylesheet" href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css">

    <link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />

    </x-slot>

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>


    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Purchase Return</li>
                <li class="breadcrumb-item"><a href="{{ route('purchase-return.list') }}">List of Purchase Returns</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('purchase-return.create') }}">Create</a></li>

            </ol>
        </nav>
    </div>

    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Create Purchase Return Invoice</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <div class="simple-pill">

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
                                <div id="basic" class="col-lg-12">
                                    <div class="statbox widget box box-shadow">
                                        <div class="widget-content widget-content-area">
                                            <div class="row">
                                                <div class="col-lg-12 col-12 ">
                                                    <form
                                                        action="{{ !empty($purchaseReturn) ? route('purchase-return.update') : route('purchase-return.save') }}"
                                                        method="POST" class="row g-3 needs-validation" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($purchaseReturn->id) ? $purchaseReturn->id : '' }}" />
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-lg-0 col-4 ">
                                                                    <label for="po_no" class="form-label">PO No:
                                                                    </label>
                                                                    <input id="po_no" type="number" name="purchase_order_no" style="color: black"
                                                                        value="{{$purchaseReturn->purchase_order_no}}"
                                                                        placeholder="PO No... "
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                        required readonly>
                                                                </div>
                                                                <div class="col-lg-0 col-4 ">
                                                                    <label for="purchase_invoice_no_" class="form-label">Purchase Invoice No:
                                                                    </label>
                                                                    <input id="purchase_invoice_no_" type="text" name="purchase_invoice_no"
                                                                        value="{{$purchaseReturn->id}}" style="color: black"
                                                                        placeholder="Purchase Invoice No... "
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                        required readonly>
                                                                </div>

                                                                <div class="col-lg-0 col-4 ">
                                                                    <label for="invoice_no" class="form-label">Purchase Return Invoice No:</label>
                                                                    <input id="invoice_no" type="number"
                                                                        value="{{$currentId}}" style="color: black"
                                                                        placeholder="Purchase Return No... "
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                        required readonly>
                                                                </div>
                                                            </div>

                                                            <br>

                                                            <div class="row">
                                                                <div class="col-lg-0 col-6 ">
                                                                    <label for="date">
                                                                        Date</label>
                                                                    <input type="text"
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                        id="date" name="date" value="{{$date}}"
                                                                        placeholder="Select The Date">
                                                                </div>
                                                                <div class="col-lg-0 col-6 ">
                                                                    <label for="party_id"
                                                                        class="form-label">Supplier Name:</label>
                                                                    <select id="party_id" type="text"
                                                                        name="party_id"
                                                                        placeholder="Please Select the Party Name"
                                                                        class="form-control select2 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mb-3 custom-select"
                                                                        required>
                                                                        @foreach ($parties as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ (old('party_id') == $key ? 'selected' : '') || (!empty($purchaseReturn->party_id) ? collect($purchaseReturn->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    {{-- <div class="invalid-feedback">
                                                                            Please Select the Sector.
                                                                        </div> --}}
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="row">
                                                                <div class="col-lg-0 col-6 ">
                                                                    <label for="bill_no" class="form-label">Bill
                                                                        Number </label>
                                                                    <input id="supplier_bill_no" type="text" name="supplier_bill_no"
                                                                        value="{{ old('supplier_bill_no', !empty($purchaseReturn->supplier_bill_no) ? $purchaseReturn->supplier_bill_no : '') }}"
                                                                        placeholder="Please Enter the Bilty No " style="color: black;"
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} "
                                                                        required readonly>
                                                                </div>
                                                                <div class="col-lg-0 col-6 ">
                                                                    <label for="transporter_id"
                                                                        class="form-label">Transporter</label>
                                                                    <select id="transporter_id" type="text"
                                                                        name="transporter_id"
                                                                        placeholder="Please Select the Transporter"
                                                                        class="form-control select2 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mb-3 custom-select">
                                                                        @foreach ($transporters as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ (old('transporter_id') == $key ? 'selected' : '') || (!empty($purchaseReturn->transporter_id) ? collect($purchaseReturn->transporter_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    {{-- <div class="invalid-feedback">
                                                                            Please Select the Sector.
                                                                        </div> --}}
                                                                </div>


                                                            </div>

                                                            <br>

                                                            <div class="row">


                                                                <div class="form-group col-md-6 ">
                                                                    <label for="unloaded_by">Unloaded By:</label>
                                                                    <input type="text" name="unloaded_by" style="color: black;"
                                                                        value="{{ old('unloaded_by', !empty($purchaseReturn->unloaded_by) ? $purchaseReturn->unloaded_by : '') }}"
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unloaded_by"
                                                                        id="unloaded_by" placeholder="Unloaded By" readonly>
                                                                    @error('unloaded_by')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                            </div>
                                                            <br>

                                                        </div>

                                                        <div class="tab-content" id="pills-tabContent">
                                                            <div class="invoice-detail-items" style="padding: 0px 0px 0px 0px;">

                                                                <div class="table-responsive">
                                                                    <table class="table item-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="">
                                                                                </th>
                                                                                <th>
                                                                                </th>
                                                                                <th style="width: 20%;">Product</th>
                                                                                <th style="width: 8%;">P.T</th>
                                                                                <th style="width: 8%;">M.T</th>
                                                                                <th style="width: 8%;">Size</th>
                                                                                <th class="" style="width: 13%;">
                                                                                    Bags/
                                                                                    Units</th>
                                                                                <th class=""style="width: 13%;">
                                                                                    Measurement Type</th>
                                                                                <th class="">
                                                                                    Received Qty</th>
                                                                                <th class="" style="width: 13%;">
                                                                                    Price</th>
                                                                                <th class="" style="width: 13%;">
                                                                                    Amount</th>


                                                                            </tr>
                                                                            <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if (!empty($purchaseReturnDetails))
                                                                                @foreach ($purchaseReturnDetails as $purchaseReturnDetail)
                                                                                    @php
                                                                                        $index = $loop->index + 2; // Starts from 2
                                                                                    @endphp
                                                                                    <tr class="tr_clone validator_{{ $index }}">
                                                                                        <td class="delete-item-row">
                                                                                            <ul class="table-controls">
                                                                                                <li>
                                                                                                    <a href="javascript:void(0);"
                                                                                                        class="delete-item"
                                                                                                        data-toggle="tooltip"
                                                                                                        data-placement="top" title=""
                                                                                                        data-original-title="Delete">
                                                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                            width="24" height="24"
                                                                                                            viewBox="0 0 24 24" fill="none"
                                                                                                            stroke="currentColor"
                                                                                                            stroke-width="2"
                                                                                                            stroke-linecap="round"
                                                                                                            stroke-linejoin="round"
                                                                                                            class="feather feather-x-circle">
                                                                                                            <circle cx="12"
                                                                                                                cy="12" r="10">
                                                                                                            </circle>
                                                                                                            <line x1="15"
                                                                                                                y1="9" x2="9"
                                                                                                                y2="15">
                                                                                                            </line>
                                                                                                            <line x1="9"
                                                                                                                y1="9" x2="15"
                                                                                                                y2="15">
                                                                                                            </line>
                                                                                                        </svg>
                                                                                                    </a>
                                                                                                </li>
                                                                                            </ul>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="text" name="row_id[]"
                                                                                                class="row_id" value="{{ $index }}"
                                                                                                hidden>
                                                                                        </td>

                                                                                        <td class="product">
                                                                                            <select id="product_id" name="product_id[]"
                                                                                                class="form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} product product_{{ $index }}">
                                                                                                <option selected="">Please
                                                                                                    select the
                                                                                                    Items</option>
                                                                                                @foreach ($dropDownData['products'] as $key => $value)
                                                                                                    <option value="{{ $key }}"
                                                                                                        {{ (old('product_id') == $key ? 'selected' : '') || (!empty($purchaseReturnDetail->product_id) ? collect($purchaseReturnDetail->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                        {{ $value }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </td>
                                                                                        <br>
                                                                                        <td class="quantity">
                                                                                            <input type="text" style="color: black;"
                                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_{{ $index }}"
                                                                                                id="packing" name="packing_type[]"
                                                                                                placeholder="P.T"
                                                                                                value="{{ old('packing_type', !empty($purchaseReturnDetail->packing_type) ? $purchaseReturnDetail->packing_type : '') }}"
                                                                                                readonly>
                                                                                        </td>
                                                                                        <td class="quantity">
                                                                                            <input type="text" style="color: black; "
                                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_{{ $index }}"
                                                                                                placeholder="M.T" name="measurement_type[]"
                                                                                                value="{{ old('measurement_type', !empty($purchaseReturnDetail->measurement_type) ? $purchaseReturnDetail->measurement_type : '') }}"
                                                                                                id="measurement" readonly>
                                                                                        </td>

                                                                                        <td class="quantity">
                                                                                            <input type="text" style="color: black;"
                                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} size_{{ $index }}"
                                                                                                id="size" name="size[]"
                                                                                                placeholder="Size"
                                                                                                value="{{ old('size', !empty(@$purchaseReturnDetail->size) ? @$purchaseReturnDetail->size : '') }}"
                                                                                                readonly>
                                                                                        </td>

                                                                                        <td class="bagsQuantity">
                                                                                            <input type="number" id="bags"
                                                                                                name="bags[]" value="{{ old('bags', !empty(@$purchaseReturnDetail->bags) ? @$purchaseReturnDetail->bags : '') }}"
                                                                                                style="color: black;"
                                                                                                class="bags form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} bags bags_{{ $index }}"
                                                                                                placeholder="Bags">
                                                                                        </td>
                                                                                        <td class="measurementQuantity">
                                                                                            <input type="number" id="measurementType" style="color: black;"
                                                                                                name="measurementType[]" value="{{ old('measurementType', !empty(@$purchaseReturnDetail->measurementType) ? @$purchaseReturnDetail->measurementType : '') }}"
                                                                                                class="measurementType form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurementType measurementType_{{ $index }}"
                                                                                                placeholder="M.T">
                                                                                        </td>

                                                                                        <td class="quantity">
                                                                                            <input type="text" style="color: black;" id="qty"
                                                                                                value="{{ old('quantity', !empty($purchaseReturnDetail->quantity) ? $purchaseReturnDetail->quantity : '') }}"
                                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty qty_{{ $index }}"
                                                                                                name="quantity[]" placeholder="Qty"
                                                                                                >
                                                                                        </td>
                                                                                        <td class="quantity">
                                                                                            <input type="number" id="price"
                                                                                                name="price[]" value="{{ old('price', !empty($purchaseReturnDetail->price) ? $purchaseReturnDetail->price : '') }}"
                                                                                                class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price price_{{ $index }}"
                                                                                                placeholder="Price" required>
                                                                                        </td>

                                                                                        <td class="quantity">
                                                                                            <input type="text" style="color: black;"
                                                                                                id="amount" name="amount[]"
                                                                                                value="{{ old('amount', !empty($purchaseReturnDetail->amount) ? $purchaseReturnDetail->amount : '') }}"
                                                                                                class="amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_{{ $index }}"
                                                                                                placeholder="Amount" readonly>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach

                                                                            @endif

                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                {{-- <a class="btn btn-dark additem">Add
                                                                    Item</a> --}}
                                                            </div>

                                                            {{-- <div class="col-md-12"> --}}

                                                            {{-- <div class="col-md-6" style="float: right; padding-left: 15%;">
                                                                <div class="row">
                                                                    <div class="col-md-2">
                                                                        <label for="client-phone">Tot Qty:</label>
                                                                    </div>
                                                                    <div class="col-md-4" style="width: 60%;">

                                                                        <input type="text" style="color: black;"
                                                                            id="total_quantity" name="total_quantity"
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            id="client-phone" placeholder="Tot.Qty" readonly>
                                                                    </div>
                                                                </div>

                                                            </div> --}}


                                                            {{-- </div> --}}


                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-12" style="float: right;">
                                                                        <div style="width: 40%; float: right;">
                                                                            <label for="client-phone">Tot Qty:</label>
                                                                            <input type="text" style="color: black;"
                                                                            id="total_quantity" name="total_quantity" value=" {{$purchaseReturn->total_quantity}} "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            id="client-phone" placeholder="Tot.Qty" readonly>


                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>

                                                            <div class="invoice-detail-note"
                                                                style="padding: 0px 0px 0px 0px !important;">

                                                                <div class="row">

                                                                    <div class="col-md-12 align-self-center">

                                                                        <div class="form-group row invoice-note">
                                                                            <label for="invoice-detail-notes"
                                                                                class="col-sm-12 col-form-label col-form-label-sm">Remarks</label>
                                                                            <div class="col-sm-12">
                                                                                <textarea class="form-control" id="remarks" name="remarks" placeholder='Enter The Remarks' style="height: 88px;">{{ @$purchaseReturn->remarks }}</textarea>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>
                                                            <div class="col-md-12" style="margin-top: 20px;">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                    </div>
                                                                    <div class="col-md-6" style="float: right; ">
                                                                        <div class="row">
                                                                            <div class="col-md-2 class-label"
                                                                                style="color: black;">
                                                                                {{-- Tot. Amount --}}
                                                                                <label for=""><b>Gross.Amount:</b></label>
                                                                            </div>
                                                                            <div class="col-md-4"
                                                                                style="width: 70%; float: right; margin-left:10%;">
                                                                                {{-- <label for="client-phone">Tot.
                                                                                    Amount</label> --}}
                                                                                <input type="text"
                                                                                    style="color: black;"
                                                                                    id="gross-amount"
                                                                                    name="gross_bill"
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} gross-amount"
                                                                                    placeholder="Gross.Amount"
                                                                                    readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: 10px;">
                                                                    <div class="col-md-6">
                                                                    </div>
                                                                    <div class="col-md-6" style="float: right; ">
                                                                        <div class="row">
                                                                            <div class="col-md-2 class-label"
                                                                                style="color: black;">
                                                                                <label for=""><b>Carriage:</b></label>
                                                                            </div>
                                                                            <div class="col-md-4"
                                                                                style="width: 70%; float: right; margin-left:10%;">
                                                                                {{-- <label for="client-phone">Tot.
                                                                                    Amount</label> --}}
                                                                                <input type="text"
                                                                                    style="color: black;"
                                                                                    id="carriage-amount"
                                                                                    name="carriage"
                                                                                    value=" {{ $purchaseReturn->carriage }} "
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} carriage"
                                                                                    placeholder="Carriage.Amount"
                                                                                    readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" style="margin-top: 10px;">
                                                                    <div class="col-md-6">
                                                                    </div>
                                                                    <div class="col-md-6" style="float: right; ">
                                                                        <div class="row">
                                                                            <div class="col-md-2 class-label"
                                                                                style="color: black;">
                                                                                {{-- Tot. Amount --}}
                                                                                <label for=""><b>Tax:</b></label>
                                                                            </div>
                                                                            <div class="col-md-4"
                                                                                style="width: 70%; float: right; margin-left:10%;">
                                                                                <input type="text"
                                                                                    style="color: black;"
                                                                                    id="tax-amount"
                                                                                    name="tax"  value=" {{ $purchaseReturn->tax }} "
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} tax"
                                                                                    placeholder="tax.Amount">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row" style="margin-top: 10px;">
                                                                    <div class="col-md-6">
                                                                    </div>
                                                                    <div class="col-md-6" style="float: right; ">
                                                                        <div class="row">
                                                                            <div class="col-md-2 class-label"
                                                                                style="color: black;">
                                                                                {{-- Tot. Amount --}}
                                                                                <label for=""><b>Net Amount:</b></label>
                                                                            </div>
                                                                            <div class="col-md-4"
                                                                                style="width: 70%; float: right; margin-left:10%;">
                                                                                {{-- <label for="client-phone">Tot.
                                                                                    Amount</label> --}}
                                                                                <input type="text"
                                                                                    style="color: black;"
                                                                                    id="net-amount"  value=" {{ $purchaseReturn->net_amount }} "
                                                                                    name="net_amount"
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                    placeholder="Net.Amount"
                                                                                    readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="form-group">
                                                            <a href="{{ route('purchase-return.list') }}" style="float: right;"
                                                                class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                            @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                            @endif
                                                            <button type="submit" style="float: right"
                                                                class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                data-bs-container="body" data-bs-placement="right"
                                                                data-bs-content="Tooltip on right">
                                                                @if (!isset($purchaseReturn))
                                                                    Save
                                                                @else
                                                                    Update
                                                                @endif
                                                            </button>
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

    <script src="{{ asset('js/purchaseReturnInvoice.js') }}"></script>

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    </x-slot>

</x-base-layout>
