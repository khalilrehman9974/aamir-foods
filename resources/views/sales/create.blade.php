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
        <link rel="stylesheet" href="{{ asset('plugins/flatpickr/flatpickr.css') }}">
        <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />
        @vite(['resources/scss/light/plugins/flatpickr/custom-flatpickr.scss'])
        @vite(['resources/scss/dark/plugins/flatpickr/custom-flatpickr.scss'])


        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link href="../src/plugins/src/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../src/plugins/src/filepond/filepond.min.css">
        <link rel="stylesheet" href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css">

        <link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
        <link href="../src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">

    </x-slot>

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>


    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sale</li>
                <li class="breadcrumb-item"><a href="{{ route('sale.sales') }}">List of Sales</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sale.create') }}">Create</a></li>

            </ol>
        </nav>
    </div>

    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Add Sale Invoice</h4>
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
                                                        action="{{ !empty($sale) ? route('sale.update') : route('sale.store') }}"
                                                        method="POST" class="row g-3 needs-validation" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($sale->id) ? $sale->id : '' }}" />

                                                        <div class="form-group">
                                                            <div class="row  invoice-content">
                                                                <div class="col-lg-0 col-4">
                                                                    <label for="dispatch_note"
                                                                        class="form-label">Invoice# </label>
                                                                    <input id="invoice_no" type="text"
                                                                        style="color:black;" name="invoice_no"
                                                                        value="{{ $invoiceNo }}"
                                                                        class="form-control form-control-sm" readonly>
                                                                </div>

                                                                <div class="col-lg-0 col-4">
                                                                    <label for="dispatch_note" class="form-label">Sale
                                                                        Order #</label>
                                                                    <input id="sale_order" type="sale_order"
                                                                        name="sale_order_number" style="color:black;"
                                                                        value="{{ $dispatchNote->sale_order_number }}"
                                                                        class="form-control form-control-sm" readonly>
                                                                </div>

                                                                <div class="col-lg-0 col-4">
                                                                    <label for="dispatch_note"
                                                                        class="form-label">Dispatch Note# </label>
                                                                    <input id="dispatch_note" type="dispatch_note"
                                                                        name="dispatch_note_number" style="color:black;"
                                                                        value="{{ $dispatchNote->id }}"
                                                                        class="form-control form-control-sm" readonly>
                                                                </div>
                                                            </div>
                                                            <br>

                                                            <div class="row">
                                                                <div class="col-lg-0 col-6" style="float: right">
                                                                    <label for="date">
                                                                        Date</label>
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="date" style="color:black;"
                                                                        name="date"
                                                                        value="{{ $dispatchNote->date }}"
                                                                        placeholder="Select The Date" readonly>
                                                                </div>

                                                                <div class="col-lg-0 col-6" style="float: right">

                                                                    <label for="inputState"
                                                                        class="form-label">Party</label>
                                                                    <select id="party" name="party_id"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} party"
                                                                        required>
                                                                        @foreach ($parties as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                @php
$isSelected = old('party_id') == $key || $dispatchNote->pluck('party_id')->contains($key); @endphp
                                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>


                                                                </div>


                                                            </div>
                                                            <br>
                                                            <div class="row">
                                                                <div class="col-lg-0 col-6">
                                                                    <label for="inputState" class="form-label">Sales
                                                                        Man</label>

                                                                    <select id="saleMan" name="saleman"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} saleman"
                                                                        required>
                                                                        @foreach ($saleMans as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                @php
$isSelected = old('saleman') == $key || $dispatchNote->pluck('saleman')->contains($key); @endphp
                                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-lg-0 col-6">
                                                                    <label for="inputState"
                                                                        class="form-label">Belt</label>

                                                                    <select id="sector" name="sector"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} sector"
                                                                        required>
                                                                        @foreach ($sectors as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                @php
$isSelected = old('sector') == $key || $dispatchNote->pluck('sector')->contains($key); @endphp
                                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                            </div>

                                                            <br>
                                                            <div class="row">
                                                                <div class="col-lg-0 col-6">
                                                                    <label for="inputState" class="form-label">Area
                                                                    </label>

                                                                    <select id="area" name="area"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} area"
                                                                        required>
                                                                        @foreach ($areas as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                @php
$isSelected = old('area') == $key || $dispatchNote->pluck('area')->contains($key); @endphp
                                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-0 col-6">
                                                                    <label for="inputState"
                                                                        class="form-label">Delivered To</label>


                                                                    <select id="delivered_to" name="delivered_to"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} delivered_to"
                                                                        required>
                                                                        @foreach ($deliveredToParties as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                @php
$isSelected = old('delivered_to') == $key || $dispatchNote->pluck('delivered_to')->contains($key); @endphp
                                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="row">
                                                                <div class="col-lg-0 col-6">
                                                                    <label for="inputState"
                                                                        class="form-label">Transporter</label>


                                                                    <select id="transporter" name="transporter_id"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} transporter"
                                                                        required>
                                                                        @foreach ($transporters as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                @php
$isSelected = old('transporter_id') == $key || $dispatchNote->pluck('transporter_id')->contains($key); @endphp
                                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-0 col-6">
                                                                    <label for="vehicle_no" class="form-label">Vehicle
                                                                        No</label>
                                                                    <input id="vehicle_no" type="vehicle_no"
                                                                        name="vehicle_no" style="color:black;"
                                                                        value="{{ $dispatchNote->vehicle_no }}"
                                                                        placeholder="Please Enter the Vehicle No "
                                                                        class="form-control form-control-sm" readonly>
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="row">
                                                                <div class="col-lg-0 col-6">
                                                                    <label for="driver_name" class="form-label">Driver
                                                                        Name</label>
                                                                    <input id="driver_name" type="driver_name"
                                                                        name="driver_name" style="color:black;"
                                                                        value="{{ $dispatchNote->driver_name }}"
                                                                        placeholder="Please Enter the Driver Name "
                                                                        class="form-control form-control-sm" readonly>
                                                                </div>


                                                                <div class="col-lg-0 col-6">
                                                                    <label for="bilty_no" class="form-label">Bilty
                                                                        No</label>
                                                                    <input id="bility_no" type="bilty_no"
                                                                        name="bilty_no" style="color:black;"
                                                                        value="{{ $dispatchNote->bility_no }}"
                                                                        placeholder="Please Enter the Area "
                                                                        class="form-control form-control-sm" readonly>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="invoice-detail-terms"
                                                            style="padding: 0px 0px 0px 0px !important;">
                                                            <div class="tab-content mt-5" id="pills-tabContent">
                                                                <div class="invoice-detail-items"
                                                                    style="padding:0px 0px 0px 0px !important;">

                                                                    <div class="table-responsive">
                                                                        <table class="table item-table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>
                                                                                    </th>
                                                                                    <th></th>
                                                                                    <th scope="col"
                                                                                        style="width: 30%">
                                                                                        Product</th>
                                                                                    <th class="">
                                                                                        P.T</th>
                                                                                    <th class="">
                                                                                        M.T</th>
                                                                                    <th class="">
                                                                                        Quantity</th>
                                                                                    <th class="">
                                                                                        Dzns
                                                                                    </th>
                                                                                    <th class="text-right">
                                                                                        Total
                                                                                        Dzns</th>
                                                                                    <th class="text-right">
                                                                                        Rate
                                                                                    </th>

                                                                                    <th class="text-right"
                                                                                        style="width: 10%">
                                                                                        Amount
                                                                                    </th>

                                                                                </tr>
                                                                                <tr aria-hidden="true"
                                                                                    class="mt-3 d-block table-row-hidden">
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>

                                                                                @if (!empty($dispatchNoteDetails))

                                                                                    @foreach ($dispatchNoteDetails as $dispatchNoteDetail)
                                                                                        {{-- {{dd($dispatchNoteDetail);}} --}}
                                                                                        @php
                                                                                            $index = $loop->index + 2; // Starts from 2
                                                                                        @endphp
                                                                                        <tr
                                                                                            class="tr_clone validator_{{ $index }}">
                                                                                            <td class="delete-item-row"
                                                                                                style="padding: 0 px 0 px;">
                                                                                                <ul
                                                                                                    class="table-controls">
                                                                                                    <li>
                                                                                                        <a href="javascript:void(0);"
                                                                                                            class="delete-item"
                                                                                                            data-toggle="tooltip"
                                                                                                            data-placement="top"
                                                                                                            title=""
                                                                                                            data-original-title="Delete">
                                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                                width="24"
                                                                                                                height="24"
                                                                                                                viewBox="0 0 24 24"
                                                                                                                fill="none"
                                                                                                                stroke="currentColor"
                                                                                                                stroke-width="2"
                                                                                                                stroke-linecap="round"
                                                                                                                stroke-linejoin="round"
                                                                                                                class="feather feather-x-circle">
                                                                                                                <circle
                                                                                                                    cx="12"
                                                                                                                    cy="12"
                                                                                                                    r="10">
                                                                                                                </circle>
                                                                                                                <line
                                                                                                                    x1="15"
                                                                                                                    y1="9"
                                                                                                                    x2="9"
                                                                                                                    y2="15">
                                                                                                                </line>
                                                                                                                <line
                                                                                                                    x1="9"
                                                                                                                    y1="9"
                                                                                                                    x2="15"
                                                                                                                    y2="15">
                                                                                                                </line>
                                                                                                            </svg>
                                                                                                        </a>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="text"
                                                                                                    name="row_id[]"
                                                                                                    class="row_id"
                                                                                                    value="{{ $index }}"
                                                                                                    hidden>
                                                                                            </td>
                                                                                            <td class="product"
                                                                                                style="padding: 0 px 0 px !important;">
                                                                                                {{-- <select id="product_id"
                                                                                                    name="product_id[]"
                                                                                                    style="color: black;"
                                                                                                    class="mb-3 form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} product_{{ $index }}"
                                                                                                    >
                                                                                                    <option
                                                                                                        selected="">
                                                                                                        Please
                                                                                                        select
                                                                                                        the
                                                                                                        product
                                                                                                    </option>
                                                                                                    @foreach ($products as $key => $value)
                                                                                                        <option
                                                                                                            value="{{ $key }}"
                                                                                                            {{ (old('product_id') == $key ? 'selected' : '') || (!empty($dispatchNoteDetail->product_id) ? collect($dispatchNoteDetail->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                            {{ $value }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select> --}}

                                                                                                <select
                                                                                                    class="mb-3 form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                                    disabled>
                                                                                                    <option selected>
                                                                                                        {{ $products[$dispatchNoteDetail->product_id] ?? 'Product Not Found' }}
                                                                                                        {{-- Show Product Name --}}
                                                                                                    </option>
                                                                                                </select>

                                                                                                {{-- Hidden field to store the Product ID --}}
                                                                                                <input type="hidden"
                                                                                                    name="product_id[]"
                                                                                                    value="{{ $dispatchNoteDetail->product_id }}">

                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="text"
                                                                                                    style="color: black;"
                                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_{{ $index }}"
                                                                                                    id="packing"
                                                                                                    value="{{ old('packing_type', !empty($dispatchNoteDetail->packing_type) ? $dispatchNoteDetail->packing_type : '') }}"
                                                                                                    name="packing_type[]"
                                                                                                    placeholder="P.T"
                                                                                                    readonly>
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="text"
                                                                                                    style="color: black;"
                                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_{{ $index }}"
                                                                                                    placeholder="M.T"
                                                                                                    value="{{ old('measurement_type', !empty($dispatchNoteDetail->measurement_type) ? $dispatchNoteDetail->measurement_type : '') }}"
                                                                                                    name="measurement_type[]"
                                                                                                    id="measurement"
                                                                                                    readonly>
                                                                                            </td>
                                                                                            <td class="quantity"
                                                                                                style="padding: 0 px 0 px !important;">
                                                                                                <input type="text"
                                                                                                    id="quantity"
                                                                                                    class="qty form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_{{ $index }}"
                                                                                                    value="{{ old('quantity', !empty($dispatchNoteDetail->quantity) ? $dispatchNoteDetail->quantity : '') }}"
                                                                                                    name="quantity[]"
                                                                                                    style="color:black;"
                                                                                                    placeholder="Qty"
                                                                                                    readonly>
                                                                                            </td>

                                                                                            <td class="total_unit">
                                                                                                <input type="text"
                                                                                                    id="dzn"
                                                                                                    name="dzns[]"
                                                                                                    style="color: black;"
                                                                                                    value="{{ old('dzn', !empty($dispatchNoteDetail->dzn) ? $dispatchNoteDetail->dzn : '') }}"
                                                                                                    class="dozen form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} dozen_{{ $index }}"
                                                                                                    placeholder="Dzn"
                                                                                                    required readonly>
                                                                                            </td>
                                                                                            <td class="total_dzns">
                                                                                                <input type="text"
                                                                                                    id="total_dzns"
                                                                                                    style="color: black;"
                                                                                                    name="total_dzns[]"
                                                                                                    value="{{ old('total_dzn', !empty($dispatchNoteDetail->total_dzn) ? $dispatchNoteDetail->total_dzn : '') }}"
                                                                                                    class="totDzn form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} totDzn_{{ $index }}"
                                                                                                    placeholder="Tot.Dzns"
                                                                                                    readonly>
                                                                                            </td>

                                                                                            <td class="rate">
                                                                                                <input type="number"
                                                                                                    id="rate"
                                                                                                    name="rate[]"
                                                                                                    {{-- value="{{ old('rate', !empty($pricesArray->price) ? $pricesArray->price : '') }}" --}}
                                                                                                    value="{{ old('rate', isset($pricesArray[$dispatchNoteDetail->product_id]) ? $pricesArray[$dispatchNoteDetail->product_id] : '') }}"
                                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} rate rate_{{ $index }}"
                                                                                                    placeholder="Rate"
                                                                                                    required>
                                                                                            </td>

                                                                                            <td class="amount">
                                                                                                <input type="text"
                                                                                                    id="amount"
                                                                                                    style="color: black;"
                                                                                                    value="{{ old('amount', !empty($dispatchNoteDetail->amount) ? $dispatchNoteDetail->amount : '') }}"
                                                                                                    name="amount[]"
                                                                                                    class="amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_{{ $index }}"
                                                                                                    placeholder="Amount"
                                                                                                    readonly>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @endif
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                    <a href="javascript:void(0);"
                                                                        class="btn btn-dark additem" id="add-item"
                                                                        hidden>Add
                                                                        Item</a>

                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-12" style="float: right;">
                                                                            <div style="width: 40%; float: right;">
                                                                                <label for="client-phone">Tot.
                                                                                    Boray</label>
                                                                                <input type="text"
                                                                                    style="color: black;"
                                                                                    value="{{ old('total_boray', !empty($dispatchNote->total_boray) ? $dispatchNote->total_boray : '') }}"
                                                                                    id="boray-amount"
                                                                                    name="total_boray"
                                                                                    class="quantity-amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                    placeholder="Tot.Boray" readonly>

                                                                                <label for="client-phone">Tot.
                                                                                    Carton</label>
                                                                                <input type="text"
                                                                                    style="color: black;"
                                                                                    value="{{ old('total_carton', !empty($dispatchNote->total_carton) ? $dispatchNote->total_carton : '') }}"
                                                                                    id="carton-amount"
                                                                                    name="total_carton"
                                                                                    class="quantity-amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                    placeholder="Tot.Carton" readonly>
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
                                                                                    <textarea class="form-control" id="remarks" name="remarks" placeholder='Enter The Remarks' style="height: 88px;">{{ @$saleOrder->remarks }}</textarea>
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
                                                                                    <label for=""><b>Gross.
                                                                                            Amount
                                                                                            :</b></label>
                                                                                </div>
                                                                                <div class="col-md-4"
                                                                                    style="width: 70%; float: right; margin-left:10%;">
                                                                                    {{-- <label for="client-phone">Tot.
                                                                                        Amount</label> --}}
                                                                                    <input type="text"
                                                                                        style="color: black;"
                                                                                        id="gross-amount"
                                                                                        name="gross_bill"
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
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
                                                                                    <label for=""><b>Carriage
                                                                                            :</b></label>
                                                                                </div>
                                                                                <div class="col-md-4"
                                                                                    style="width: 70%; float: right; margin-left:10%;">
                                                                                    {{-- <label for="client-phone">Tot.
                                                                                        Amount</label> --}}
                                                                                    <input type="text"
                                                                                        style="color: black;"
                                                                                        id="carriage-amount"
                                                                                        name="carriage"
                                                                                        value=" {{ $dispatchNote->carriage }} "
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
                                                                                    <label for=""><b>Offer/
                                                                                            Discount:</b></label>
                                                                                </div>
                                                                                <div class="col-md-4"
                                                                                    style="width: 70%; float: right; margin-left:10%;">
                                                                                    <input type="number"
                                                                                        style="color: black;"
                                                                                        id="discount-amount"
                                                                                        name="discount"
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} discount"
                                                                                        placeholder="Discount.Amount">
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
                                                                                    <label
                                                                                        for=""><b>Commission:</b></label>
                                                                                </div>
                                                                                <div class="col-md-4"
                                                                                    style="width: 70%; float: right; margin-left:10%;">
                                                                                    {{-- <label for="client-phone">Tot.
                                                                                        Amount</label> --}}
                                                                                    <input type="number"
                                                                                        style="color: black;"
                                                                                        id="commission-amount"
                                                                                        name="commission"
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} commission"
                                                                                        placeholder="Commission.Amount">
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
                                                                                    <label for=""><b>Net Amount
                                                                                            :</b></label>
                                                                                </div>
                                                                                <div class="col-md-4"
                                                                                    style="width: 70%; float: right; margin-left:10%;">
                                                                                    {{-- <label for="client-phone">Tot.
                                                                                        Amount</label> --}}
                                                                                    <input type="text"
                                                                                        style="color: black;"
                                                                                        id="net-amount"
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
                                                        </div>
                                                        <div class="form-group">
                                                            <a href="{{ route('sale.sales') }}" style="float: right;"
                                                                class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                            @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                            @endif
                                                            <button type="submit" style="float: right"
                                                                class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                data-bs-container="body" data-bs-placement="right"
                                                                data-bs-content="Tooltip on right">
                                                                @if (!isset($sale))
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
    <script src="{{ asset('js/saleInvoice.js') }}"></script>



    <script>
        var config = {
            routes: {
                getPartyCode: "{{ url('sale/get-product-rate') }}",
            },
        }
    </script>

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </x-slot>
</x-base-layout>
