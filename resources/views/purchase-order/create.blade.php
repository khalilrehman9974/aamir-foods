<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <link rel="stylesheet" href="{{ asset('plugins/flatpickr/flatpickr.css') }}">
        <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />
        @vite(['resources/scss/light/plugins/flatpickr/custom-flatpickr.scss'])
        @vite(['resources/scss/dark/plugins/flatpickr/custom-flatpickr.scss'])

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link href="../src/plugins/src/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../src/plugins/src/filepond/filepond.min.css">
        <link rel="stylesheet" href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css">

        <link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
        <link href="../src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>


    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">purchaseOrder</li>
                <li class="breadcrumb-item"><a href="{{ route('purchase-order.list') }}">List of Purchase Orders</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('purchase-order.create') }}">Create</a></li>

            </ol>
        </nav>
    </div>
    <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Add Purchase Order</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <div class="widget-content widget-content-area">
                        <form
                            action="{{ !empty($purchaseOrder) ? route('purchase-order.update') : route('purchase-order.store') }}"
                            method="POST" class="row g-3 needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="id" id="id"
                                value="{{ isset($purchaseOrder->id) ? $purchaseOrder->id : '' }}" />
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="dispatch_note" class="form-label">PO # </label>
                                        <input id="invoice_no" type="text" style="color:black;"
                                            value="{{ $poNo }}" class="form-control form-control-sm" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date">
                                            Date</label>
                                        <input id="date" name="date"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} date flatpickr flatpickr-input"
                                            type="text"
                                            value="{{ empty($purchaseOrder->date) ? null : \Illuminate\Support\Carbon::parse($purchaseOrder->date)->format('Y-m-d') }}"
                                            placeholder="Select Date.." readonly="readonly">

                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">
                                    <div class="col-md-6">
                                        <label for="party" class="form-label">Party</label>
                                        <select id="party" type="text" name="party_id"
                                            placeholder="Please Select the Party Name"
                                            class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} "
                                            required>
                                            <option value="">Select
                                            </option>
                                            @foreach ($dropDownData['parties'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('party_id') == $key ? 'selected' : '') || (!empty($purchaseOrder->party_id) ? collect($purchaseOrder->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('party_id')
                                            <span style="color:red". class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="contact_person" class="form-label">Contact
                                            Person
                                        </label>
                                        <input id="contact_person" type="contact_person" name="contact_person"
                                            value="{{ old('contact_person', !empty($purchaseOrder->contact_person) ? $purchaseOrder->contact_person : '') }}"
                                            placeholder="Contact Person Name... "
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 20px;">

                                    <div class="col-md-6 ">
                                        <label for="status">
                                            Status</label>

                                        <select id="status" name="status"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mb-3 select2 custom-select"
                                            required>
                                            {{-- <option value="Pending">Pending
                                                </option>
                                                <option value="Delivered">
                                                    Delivered
                                                </option>
                                                <option value="Cancelled">Cancelled
                                                </option> --}}

                                            <option value="Pending"
                                                {{ old('status', $purchaseOrder->status ?? '') == 'Pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="Delivered"
                                                {{ old('status', $purchaseOrder->status ?? '') == 'Delivered' ? 'selected' : '' }}>
                                                Delivered
                                            </option>
                                            <option value="Cancelled"
                                                {{ old('status', $purchaseOrder->status ?? '') == 'Cancelled' ? 'selected' : '' }}>
                                                Cancelled
                                            </option>

                                        </select>

                                        {{-- <select id="status" type="text" name="status"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mb-3 select2 custom-select"
                                            required>
                                            <option value="Pending">Pending
                                            </option>
                                            <option value="Delivered">
                                                Delivered
                                            </option>
                                            <option value="Cancelled">Cancelled
                                            </option>
                                            {{-- @foreach ($dropDownData['parties'] as $key => $value)
                                                    <option
                                                        value="{{ $key }}"
                                                        {{ (old('party_id') == $key ? 'selected' : '') || (!empty($saleOrder->party_id) ? collect($saleOrder->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                        </select> --}}
                                    </div>
                                </div>

                            </div>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="invoice-detail-items" style="padding: 0px 0px 0px 0px;">

                                    <div class="table-responsive">
                                        <table class="table item-table">
                                            <thead>
                                                <tr>
                                                    <th class="">
                                                    </th>
                                                    <th></th>
                                                    <th style="width: 15%;">Product</th>
                                                    <th class="">M.T</th>
                                                    <th class="">P.T
                                                    </th>
                                                    <th class="" style="width: 10%;">Size</th>
                                                    <th class="" style="width: 10;">Quantity</th>
                                                    <th class="" style="width: 15%;">Price
                                                    </th>
                                                    <th class="text-right" style="width: 10%;">
                                                        Amount
                                                    </th>
                                                    <th class="text-right" style="width: 20%;">
                                                        Remarks
                                                    </th>

                                                </tr>
                                                <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                                </tr>
                                            </thead>
                                            <tbody>

                                                {{-- <tr class="tr_clone validator_0">
                                                        <td class="delete-item-row">
                                                            <ul class="table-controls">
                                                                <li>
                                                                    <a href="javascript:void(0);" class="delete-item"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="" data-original-title="Delete">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="feather feather-x-circle">
                                                                            <circle cx="12" cy="12"
                                                                                r="10">
                                                                            </circle>
                                                                            <line x1="15" y1="9"
                                                                                x2="9" y2="15">
                                                                            </line>
                                                                            <line x1="9" y1="9"
                                                                                x2="15" y2="15">
                                                                            </line>
                                                                        </svg>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="row_id[]" class="row_id"
                                                                value="2" hidden>
                                                        </td>

                                                        <td class="product">
                                                            <select id="product_id" name="product_id[]"
                                                                class="form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} product_2">
                                                                <option selected="">Please
                                                                    select the
                                                                    Items</option>
                                                                @foreach ($dropDownData['products'] as $key => $value)
                                                                    <option value="{{ $key }}"
                                                                        {{ (old('product_id') == $key ? 'selected' : '') || (!empty($purchaseOrderDetail->product_id) ? collect($purchaseOrderDetail->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <br>
                                                        <td class="quantity">
                                                            <input type="text" style="color: black; "
                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_2"
                                                                placeholder="M.T" name="measurement_type[]"
                                                                value="{{ old('measurement_type', !empty($purchaseOrderDetail->measurement_type) ? $purchaseOrderDetail->measurement_type : '') }}"
                                                                id="measurement" readonly>
                                                        </td>
                                                        <td class="quantity">
                                                            <input type="text" style="color: black;"
                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_2"
                                                                id="packing" name="packing_type[]"
                                                                placeholder="P.T"
                                                                value="{{ old('packing_type', !empty($purchaseOrderDetail->packing_type) ? $purchaseOrderDetail->packing_type : '') }}"
                                                                readonly>
                                                        </td>
                                                        <td class="quantity">
                                                            <input type="text" style="color: black;"
                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} size_2"
                                                                id="size" name="size[]" placeholder="Size"
                                                                value="{{ old('size', !empty(@$purchaseOrderDetail->size) ? @$purchaseOrderDetail->size : '') }}"
                                                                readonly>
                                                        </td>

                                                        <td class="quantity">
                                                            <input type="text"
                                                                value="{{ old('quantity', !empty($purchaseOrderDetail->quantity) ? $purchaseOrderDetail->quantity : '') }}"
                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_0"
                                                                name="quantity[]" placeholder="Qty">
                                                        </td>
                                                        <td class="quantity">
                                                            <input type="text" id="rate" name="price[]"
                                                                value="{{ old('price', !empty($purchaseOrderDetail->price) ? $purchaseOrderDetail->price : '') }}"
                                                                class="rate form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} rate rate_2"
                                                                placeholder="Price" required>
                                                        </td>

                                                        <td class="quantity">
                                                            <input type="text" style="color: black;"
                                                                id="amount" name="amount[]"
                                                                value="{{ old('amount', !empty($purchaseOrderDetail->amount) ? $purchaseOrderDetail->amount : '') }}"
                                                                class="amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_2"
                                                                placeholder="Amount" readonly>
                                                        </td>
                                                        <td class="remarks">
                                                            <textarea style="margin-top: 0px;" name="detail_remarks[]"
                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} detail_remarks_2"
                                                                id="detail_remarks" cols="30" placeholder="Remarks">{{ @$purchaseOrderDetail->remarks }}</textarea>
                                                        </td>
                                                    </tr>
                                                @else --}}
                                                @if (!empty($purchaseOrderDetails))
                                                    @foreach ($purchaseOrderDetails as $purchaseOrderDetail)
                                                        @php
                                                            $index = $loop->index + 2; // Starts from 2
                                                        @endphp
                                                        <tr class="tr_clone validator_{{ $index }}">
                                                            <td class="delete-item-row">
                                                                <ul class="table-controls">
                                                                    <li>
                                                                        <a href="javascript:void(0);"
                                                                            class="delete-item" data-toggle="tooltip"
                                                                            data-placement="top" title=""
                                                                            data-original-title="Delete">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="feather feather-x-circle">
                                                                                <circle cx="12" cy="12"
                                                                                    r="10">
                                                                                </circle>
                                                                                <line x1="15" y1="9"
                                                                                    x2="9" y2="15">
                                                                                </line>
                                                                                <line x1="9" y1="9"
                                                                                    x2="15" y2="15">
                                                                                </line>
                                                                            </svg>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="row_id[]" class="row_id"
                                                                    value="{{ $index }}" hidden>
                                                            </td>

                                                            <td class="product">
                                                                <select id="product_id" name="product_id[]"
                                                                    class="form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} product product_{{ $index }}">
                                                                    <option selected="">Please
                                                                        select the
                                                                        Items</option>
                                                                    @foreach ($dropDownData['products'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ (old('product_id') == $key ? 'selected' : '') || (!empty($purchaseOrderDetail->product_id) ? collect($purchaseOrderDetail->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <br>
                                                            <td class="quantity">
                                                                <input type="text" style="color: black; "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_{{ $index }}"
                                                                    placeholder="M.T" name="measurement_type[]"
                                                                    value="{{ old('measurement_type', !empty($purchaseOrderDetail->measurement_type) ? $purchaseOrderDetail->measurement_type : '') }}"
                                                                    id="measurement" readonly>
                                                            </td>
                                                            <td class="quantity">
                                                                <input type="text" style="color: black;"
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_{{ $index }}"
                                                                    id="packing" name="packing_type[]"
                                                                    placeholder="P.T"
                                                                    value="{{ old('packing_type', !empty($purchaseOrderDetail->packing_type) ? $purchaseOrderDetail->packing_type : '') }}"
                                                                    readonly>
                                                            </td>
                                                            <td class="quantity">
                                                                <input type="text" style="color: black;"
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} size_{{ $index }}"
                                                                    id="size" name="size[]" placeholder="Size"
                                                                    value="{{ old('size', !empty(@$purchaseOrderDetail->size) ? @$purchaseOrderDetail->size : '') }}"
                                                                    readonly>
                                                            </td>

                                                            <td class="quantity">
                                                                <input type="text"
                                                                    value="{{ old('quantity', !empty($purchaseOrderDetail->quantity) ? $purchaseOrderDetail->quantity : '') }}"
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_{{ $index }}"
                                                                    name="quantity[]" placeholder="Qty">
                                                            </td>
                                                            <td class="quantity">
                                                                <input type="text" id="rate" name="price[]"
                                                                    value="{{ old('price', !empty($purchaseOrderDetail->price) ? $purchaseOrderDetail->price : '') }}"
                                                                    class="rate form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} rate rate_{{ $index }}"
                                                                    placeholder="Price" required>
                                                            </td>

                                                            <td class="quantity">
                                                                <input type="text" style="color: black;"
                                                                    id="amount" name="amount[]"
                                                                    value="{{ old('amount', !empty($purchaseOrderDetail->amount) ? $purchaseOrderDetail->amount : '') }}"
                                                                    class="amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_{{ $index }}"
                                                                    placeholder="Amount" readonly>
                                                            </td>
                                                            <td class="remarks">
                                                                <textarea style="margin-top: 0px;" name="detail_remarks[]"
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} detail_remarks_{{ $index }}"
                                                                    id="detail_remarks" cols="30" placeholder="Remarks">{{ @$purchaseOrderDetail->detail_remarks }}</textarea>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <a href="javascript:void(0);" class="btn btn-dark additem mt-3"
                                        id="add-item">Add
                                        Item</a>

                                </div>
                                <div class="invoice-detail-note" style="padding: 0px 0px 0px 0px;">

                                    <div class="row">

                                        <div class="col-md-12 align-self-center">

                                            <div class="form-group row invoice-note">
                                                <label for="invoice-detail-notes"
                                                    class="col-sm-12 col-form-label col-form-label-sm mt-3">Remarks</label>
                                                <div class="col-sm-12">
                                                    <textarea class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" id="remarks"
                                                        placeholder='Enter The Remarks' name="remarks" style="height: 88px;">{{ @$purchaseOrder->remarks }}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="col-xl-7 invoice-address-client invoice-detail-total"
                                    style="float: right; padding: 0px 0px 0px 0px; margin-top:5px;">
                                    <div class="invoice-address-client-fields">

                                        <div class="form-group row">
                                            <label for="client-phone"
                                                class="col-md-3 col-form-label col-form-label-sm ">Gross
                                                Amount</label>
                                            <div class="col-md-9">
                                                <input type="number" id="gross-amount" style="color: black;"
                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} gross-amount"
                                                    name="gross_total" id="gross-amount" placeholder="Gross Amount"
                                                    value="{{ old('gross_total', !empty($purchaseOrder->gross_total) ? $purchaseOrder->gross_total : '') }}"
                                                    readonly>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-xl-7 invoice-address-client invoice-detail-total"
                                    style="float: right; padding: 0px 0px 0px 0px; margin-top:5px; ">
                                    <div class="invoice-address-client-fields">

                                        <div class="form-group row">
                                            <label for="client-phone"
                                                class="col-md-3 col-form-label col-form-label-sm ">Tax
                                                Amount</label>
                                            <div class="col-md-9">
                                                <input type="number" id="tax" style="color: black;"
                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} tax"
                                                    name="tax_amount" placeholder="Tax Amount"
                                                    value="{{ old('tax_amount', !empty($purchaseOrder->tax_amount) ? $purchaseOrder->tax_amount : '') }}">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-xl-7 invoice-address-client invoice-detail-total"
                                    style="float: right; padding: 0px 0px 0px 0px; margin-top:5px;">
                                    <div class="invoice-address-client-fields">

                                        <div class="form-group row">
                                            <label for="client-phone"
                                                class="col-md-3 col-form-label col-form-label-sm ">Shipping
                                                Amount</label>
                                            <div class="col-md-9">
                                                <input type="number" id="shipping" style="color: black;"
                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} shipping"
                                                    name="shipping_amount" placeholder="Shipping Amount"
                                                    value="{{ old('shipping_amount', !empty($purchaseOrder->shipping_amount) ? $purchaseOrder->shipping_amount : '') }}">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-xl-7 invoice-address-client invoice-detail-total"
                                    style="float: right; padding: 0px 0px 0px 0px; margin-top:5px;">
                                    <div class="invoice-address-client-fields">

                                        <div class="form-group row">
                                            <label for="client-phone"
                                                class="col-md-3 col-form-label col-form-label-sm ">Other
                                                Amount</label>
                                            <div class="col-md-9">
                                                <input type="number" style="color: black;"
                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} other"
                                                    name="other_amount" id="otherAmount" placeholder="Other Amount"
                                                    value="{{ old('other_amount', !empty($purchaseOrder->other_amount) ? $purchaseOrder->other_amount : '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-7 invoice-address-client invoice-detail-total"
                                    style="float: right; padding: 0px 0px 0px 0px; margin-top:5px;">
                                    <div class="invoice-address-client-fields">

                                        <div class="form-group row">
                                            <label for="client-phone"
                                                class="col-md-3 col-form-label col-form-label-sm ">Net
                                                Amount</label>
                                            <div class="col-md-9">
                                                <input type="text" id="net-amount" style="color: black;"
                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} net-amount"
                                                    name="total_amount" placeholder="Net Amount"
                                                    value="{{ old('total_amount', !empty($purchaseOrder->total_amount) ? $purchaseOrder->total_amount : '') }}"
                                                    readonly>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <a href="{{ route('purchase-order.list') }}" style="float: right;"
                                    class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                    <button type="submit" style="float: right"
                                        class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                        data-bs-container="body" data-bs-placement="right"
                                        data-bs-content="Tooltip on right">
                                        @if (!isset($purchaseOrder))
                                            Save
                                        @else
                                            Update
                                        @endif
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>



    </div>

    <script>
        $(document).on('click', 'body *', function() {
            $('.rate').on("input", function() {
                var row_id = $(this).closest("tr").find(".row_id").val();
                let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
                let price = $(this).closest("tr").find(".rate_" + row_id).val();
                // console.log(row_id + ", " + quantity + ", " + price);
                if (parseInt(quantity) > 0) {
                    $(this).closest("tr").find(".amount_" + row_id).val(quantity * price);
                } else {
                    $(this).closest("tr").find(".amount_" + row_id).val('');
                }
                doAmountTotal();
            });



            $('.delete-item').on("click", function() {
                doAmountTotal();
            });

            function doAmountTotal() {
                $('#total-amount').text("");
                var totalAmount = 0;
                $(".amount").each(function() {
                    if (!isNaN(this.value) && this.value.length != 0) {
                        totalAmount += parseFloat(this.value);
                    }
                });
                $('#gross-amount').val(totalAmount.toFixed(2));
                // $('#net-amount').val(totalAmount.toFixed(2));
            }

            $(".rate, #gross-amount, #tax, #shipping, #otherAmount").on("input", function() {
                var totalAmount = 0;
                $(".amount").each(function() {
                    if (!isNaN(this.value) && this.value.length != 0) {
                        totalAmount += parseFloat(this.value);
                    }
                });
                let tax = $("#tax").val() ? $("#tax").val() : 0;
                let shipping = $("#shipping").val() ? $("#shipping").val() : 0;
                let otherAmount = $("#otherAmount").val() ? $("#otherAmount").val() : 0;

                var totalLessAmount = parseInt(tax) + parseInt(shipping) + parseInt(
                    otherAmount);

                $('#net-amount').val((totalAmount + (totalLessAmount || 0)).toFixed(2));
            })



        });
    </script>

    <script src="{{ asset('js/purchaseOrder.js') }}"></script>




    <script>
        document.getElementsByClassName('additem')[0].addEventListener('click', function() {

            let getTableElement = document.querySelector('.item-table');
            let currentIndex = getTableElement.rows.length;
            let $html = '<tr>' +
                '<td class="delete-item-row">' +
                '<ul class="table-controls">' +
                '<li><a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>' +
                '</ul>' +
                '</td>' +
                '<td><input type="checkbox" name="row_id[]" class="row_id" value="' + currentIndex +
                '" hidden></td>' +
                '<td class="product"> <select id="product_id" name="product_id[]" class="form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} custom-select product_' +
                currentIndex +
                '"> <option selected="">Please select the Items</option>@foreach ($dropDownData['products'] as $key => $value) <option value="{{ $key }}"{{ (old('product_id') == $key ? 'selected' : '') || (!empty($purchaseOrder->product_id) ? collect($purchaseOrder->product_id)->contains($key) : '') ? 'selected' : '' }}>{{ $value }}</option>@endforeach</select> ' +
                '<td class="quantity">' +
                ' <input type="text" style="color: black; "  class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_' +
                currentIndex +
                '" placeholder="M.T" name="measurement_type[]" id="measurement" readonly></td>' +
                '<td class="quantity"><input type="text" style="color: black;" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_' +
                currentIndex +
                '"id="packing"  name="packing_type[]" placeholder="P.T" readonly></td>' +
                '<td class="quantity"><input type="text" style="color: black;" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} size_' +
                currentIndex +
                '"id="size" name="size[]" placeholder="Size" readonly></td>' +
                '<td class="quantity"> <input type="text"  class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_' +
                currentIndex +
                '"name="quantity[]" placeholder="Qty"></td>' +
                '<td class="quantity"><input type="text" id="rate" name="price[]"  class="rate form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} rate_' +
                currentIndex +
                '"placeholder="Price" required></td>' +
                '<td class="quantity"><input type="text" style="color: black;" id="amount" name="amount[]"  class="amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_' +
                currentIndex +
                '"placeholder="Amount" readonly></td>' +
                '<td class="remarks"><textarea style="margin-top: 0px;" name="detail_remarks[]" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} detail_remarks_' +
                currentIndex +
                '"id="detail_remarks" cols="30" placeholder="Remarks"></textarea></td>' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);
            deleteItemRow();
            $('.select2').select2();

            $(document).ready(function() {
                $(".product_" + currentIndex).on('change', function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    var name = this.value;
                    let url = config.routes.getProductSizeDetail + '/' + name;
                    $.ajax({
                        url: url,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $(".size_" + row_id).val(response.size);
                        },
                        complete: function() {
                            $('#loading').css('display', 'none');
                        },
                        error: function(errorThrown) {
                            $('').val('');
                            var errors = errorThrown.responseJSON.errors;
                            Swal.fire({
                                icon: 'error',
                                title: 'Something went wrong',
                            })
                        }
                    })
                });
            });
            $(document).ready(function() {
                $(".product_" + currentIndex).on('change', function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    var name = this.value;
                    let url = config.routes.getProductPackingTypeDetail + '/' + name;
                    $.ajax({
                        url: url,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $(".packing_" + row_id).val(response.name.name);
                        },
                        complete: function() {
                            $('#loading').css('display', 'none');
                        },
                        error: function(errorThrown) {
                            $('').val('');
                            var errors = errorThrown.responseJSON.errors;
                            Swal.fire({
                                icon: 'error',
                                title: 'Something went wrong',
                            })
                        }
                    })
                });
            });

            $(document).ready(function() {
                $(".product_" + currentIndex).on('change', function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    var name = this.value;
                    let url = config.routes.getProductMeasurementTypeDetail + '/' + name;
                    $.ajax({
                        url: url,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $(".measurement_" + currentIndex).val(response.name.name);
                        },
                        complete: function() {
                            $('#loading').css('display', 'none');
                        },
                        error: function(errorThrown) {
                            $('').val('');
                            var errors = errorThrown.responseJSON.errors;
                            Swal.fire({
                                icon: 'error',
                                title: 'Something went wrong',
                            })
                        }
                    })
                });
            });

            $(document).on('click', 'body *', function() {
                $('.rate').on("focusout", function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
                    let price = $(this).closest("tr").find(".rate_" + row_id).val();
                    // console.log(row_id + ", " + quantity + ", " + price);
                    if (parseInt(quantity) > 0) {
                        $(this).closest("tr").find(".amount_" + row_id).val(quantity * price);
                    } else {
                        $(this).closest("tr").find(".amount_" + row_id).val('');
                    }
                    doAmountTotal();
                });



                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#total-amount').text("");
                    var totalAmount = 0;
                    $(".amount").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    $('#gross-amount').val(totalAmount.toFixed(2));
                    // $('#net-amount').val(totalAmount.toFixed(2));
                }

                $(".rate, #gross-amount, #tax, #shipping, #otherAmount").on("input", function() {
                    var totalAmount = 0;
                    $(".amount").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    let tax = $("#tax").val() ? $("#tax").val() : 0;
                    let shipping = $("#shipping").val() ? $("#shipping").val() : 0;
                    let otherAmount = $("#otherAmount").val() ? $("#otherAmount").val() : 0;

                    var totalLessAmount = parseInt(tax) + parseInt(shipping) + parseInt(
                        otherAmount);

                    $('#net-amount').val((totalAmount + (totalLessAmount || 0)).toFixed(2));
                })



            });

            $(document).on('click', 'body *', function() {
                $('.amount').on("focusout", function() {
                    doAmountTotal();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#total-amount').text("");
                    var totalAmount = 0;
                    $(".amount").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    $('#gross-amount').val(totalAmount.toFixed(2));
                }
            });
        })

        deleteItemRow();
        selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
        selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
        selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);

        var f2 = flatpickr(document.getElementById('due'), {
            defaultDate: currentDate.setDate(currentDate.getDate() + 5),
        });
        $('.select2').select2();

        function deleteItemRow() {
            let deleteItem = document.querySelectorAll('.delete-item');
            for (var i = 0; i < deleteItem.length; i++) {
                deleteItem[i].addEventListener('click', function() {
                    this.parentElement.parentNode.parentNode.parentNode.remove();
                })
            }
        }
    </script>
    <script>
        var config = {
            routes: {
                getPartySaleManDetail: "{{ url('sale-order/get-party-sale-man') }}",
                getPartySectorDetail: "{{ url('sale-order/get-party-sale-man-sector') }}",
                getPartyAreaDetail: "{{ url('sale-order/get-party-sale-man-area') }}",
                getProductPackingTypeDetail: "{{ url('purchase-order/get-product-packing-type') }}",
                getProductMeasurementTypeDetail: "{{ url('purchase-order/get-product-measurement-type') }}",
                getProductSizeDetail: "{{ url('purchase-order/get-product-size') }}",
            },
        }
    </script>
    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
        {{-- <script src="{{ asset('plugins/invoice-add/invoice-add.js') }}"></script> --}}
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        {{-- <script src="{{ asset('js/common.js') }}"></script> --}}

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])

    </x-slot>
</x-base-layout>
