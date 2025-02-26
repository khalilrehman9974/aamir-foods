<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>
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
        <link href="../src/plugins/src/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
        <link href="../src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">

    </x-slot>
    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>


    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sale Orders</li>
                <li class="breadcrumb-item"><a href="{{ route('sale-order.list') }}">List of Sale Orders</a></li>
                <li class="breadcrumb-item"><a href="{{ route('sale-order.create') }}">Create</a></li>

            </ol>
        </nav>
    </div>

    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h2>Add Sale Order</h2>
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
                                                        action="{{ !empty($saleOrder) ? route('sale-order.update') : route('sale-order.save') }}"
                                                        method="POST" autocomplete="off" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($saleOrder->id) ? $saleOrder->id : '' }}" />

                                                        <div class="row justify-content-between">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-6 mt-3">
                                                                        <label for="">
                                                                            <h4>Sale Order Number
                                                                                #:{{ @$maxid }}
                                                                                {{ @$currentid }}</h4>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="date">
                                                                            Date</label>
                                                                        <input type="text"
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            id="date" name="date"
                                                                            value="{{ old('date', !empty($saleOrder->date) ? $saleOrder->date : '') }}"
                                                                            placeholder="Select The Date" required>
                                                                        @error('date')
                                                                            <span style="color:red"
                                                                                class="invalid-feedback">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-0 col-12 ">
                                                                    <div class="row">
                                                                        {{-- <div class="col-md-6 mt-2">
                                                                            <label for="party"
                                                                                class="form-label">Party</label>
                                                                            <select id="party" type="text"
                                                                                name="party_id"
                                                                                placeholder="Please Select the Party Name"
                                                                                class="form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} select2 custom-select"
                                                                                required>
                                                                                <option value="">Select
                                                                                </option>
                                                                                @foreach ($dropDownData['parties'] as $key => $value)
                                                                                    <option value="{{ $key }}"
                                                                                        {{ (old('party_id') == $key ? 'selected' : '') || (!empty($saleOrder->party_id) ? collect($saleOrder->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                        {{ $value }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('party_id')
                                                                                <span style="color:red"
                                                                                    class="invalid-feedback">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div> --}}
                                                                        <div class="col-md-6 mt-2">
                                                                            <label for="party"
                                                                                class="form-label">Party</label>
                                                                            <select id="party" type="text"
                                                                                name="party_id"
                                                                                placeholder="Please Select the Party Name"
                                                                                class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} party"
                                                                                required>
                                                                                <option value="">Select
                                                                                </option>
                                                                                @foreach ($dropDownData['parties'] as $key => $value)
                                                                                    <option value="{{ $key }}"
                                                                                        {{ (old('party_id') == $key ? 'selected' : '') || (!empty($saleOrder->party_id) ? collect($saleOrder->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                        {{ $value }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('party_id')
                                                                                <span style="color:red".
                                                                                    class="invalid-feedback">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="col-md-6 mt-2">

                                                                            <label for="saleMan">
                                                                                Sale Man</label>
                                                                            @if (!empty($saleOrder))
                                                                                <select name="saleman"
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} saleMan"
                                                                                    id="saleMan">
                                                                                    @foreach ($saleMans as $key => $value)
                                                                                        <option
                                                                                            value="{{ $key }}"
                                                                                            {{ (old('saleman') == $key ? 'selected' : '') || (!empty($saleOrder->saleman) ? collect($saleOrder->saleman)->contains($key) : '') ? 'selected' : '' }}>
                                                                                            {{ $value }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            @else
                                                                                {{-- <input type="text"
                                                                                style="color: black; background-color: white;"
                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                id="saleMan" name="saleman"
                                                                                value="{{ old('saleman', !empty($saleOrder->saleman) ? $saleOrder->saleman : '') }}"
                                                                                placeholder="Sale Man Name..."
                                                                                readonly> --}}
                                                                                <select name="saleman"
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} saleMan"
                                                                                    id="saleMan">

                                                                                    <!-- Options will be populated dynamically -->
                                                                                </select>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-0 col-12 ">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 col-lg-6 mt-2">
                                                                            <label class="form-label"
                                                                                for="product-title-input">Belt</label>
                                                                            @if (empty($saleOrder))

                                                                                <select id="sector-dropdown"
                                                                                    name="belt"
                                                                                    class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} sector-dropdown"
                                                                                    required>
                                                                                </select>

                                                                            @else

                                                                                <select id="sector-dropdown"
                                                                                    name="belt"
                                                                                    class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} sector-dropdown"
                                                                                    required>

                                                                                    @foreach ($sectors as $key => $value)
                                                                                        <option
                                                                                            value="{{ $key }}"
                                                                                            @php
                                                                                            $isSelected = old('belt') == $key || $saleOrder->pluck('belt')->contains($key); @endphp
                                                                                            {{ $isSelected ? 'selected' : '' }}>
                                                                                            {{ $value }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-xl-6 col-lg-6 mt-2">
                                                                            <label class="form-label"
                                                                                for="product-title-input">Area</label>
                                                                            @if (empty($saleOrder))
                                                                                <select id="area-dropdown"
                                                                                    name="area"
                                                                                    class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} area-dropdown"
                                                                                    required>
                                                                                    <option value="">-- Select
                                                                                        Areas --
                                                                                    </option>
                                                                                </select>
                                                                            @else
                                                                                {{-- <select id="area-dropdown" name="area" class="form-select"
                                                                                    required>
                                                                                    @foreach ($sectors as $key => $value)
                                                                                        <option value="{{ $key }}"
                                                                                            {{ $key == old('sector') ? 'selected' : '' }}>
                                                                                            {{ $value }}</option>
                                                                                    @endforeach
                                                                                </select> --}}
                                                                                <select id="area-dropdown"
                                                                                    name="area"
                                                                                    class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} area-dropdown"
                                                                                    required>
                                                                                    {{-- @foreach ($areas as $key => $value)
                                                                                        <option
                                                                                            value="{{ $key }}"
                                                                                            {{ (old('area') == $key ? 'selected' : '') || (!empty($saleOrder->area) ? collect($saleOrder->belt)->contains($key) : '') ? 'selected' : '' }}>
                                                                                            {{ $value }}
                                                                                        </option>
                                                                                    @endforeach --}}

                                                                                    @foreach ($areas as $key => $value)
                                                                                        <option
                                                                                            value="{{ $key }}"
                                                                                            @php
                                                                                            $isSelected = old('area') == $key || $saleOrder->pluck('area')->contains($key); @endphp
                                                                                            {{ $isSelected ? 'selected' : '' }}>
                                                                                            {{ $value }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                    {{-- @foreach ($zones as $key => $value)
                                                                                        <option value="{{ $key }}"
                                                                                            {{ !empty($saleManZones) && $saleManZones->zone_id == $key ? 'selected' : '' }}
                                                                                            {{ $key == old('zone_id') ? 'selected' : '' }}>
                                                                                            {{ $value }}</option>
                                                                                    @endforeach --}}
                                                                                </select>
                                                                            @endif
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-0 col-12 ">
                                                                    <div class="row">
                                                                        <div class="col-xl-6 col-lg-6 mt-2">
                                                                            <label class="form-label"
                                                                                for="product-title-input">Delivered
                                                                                To:</label>
                                                                            @if (empty($saleOrder))
                                                                                <select id="delivered-to-dropdown"
                                                                                    name="delivered_to"
                                                                                    class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} delivered-to-dropdown">
                                                                                    {{-- <option value="select-all"
                                                                                        class="select-all-option">Select All
                                                                                    </option> --}}
                                                                                </select>
                                                                            @else

                                                                                <select id="delivered-to-dropdown"
                                                                                    name="delivered_to"
                                                                                    class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} delivered-to-dropdown">
                                                                                    @foreach ($deliverdToParties as $key => $value)
                                                                                        <option
                                                                                            value="{{ $key }}"
                                                                                            {{ (old('delivered_to') == $key ? 'selected' : '') || (!empty($saleOrder->delivered_to) ? collect($saleOrder->delivered_to)->contains($key) : '') ? 'selected' : '' }}>
                                                                                            {{ $value }}
                                                                                        </option>
                                                                                    @endforeach

                                                                                </select>
                                                                            @endif
                                                                        </div>


                                                                        <div class="col-md-6 mt-2">
                                                                            <label for="status">
                                                                                Status</label>

                                                                            <select id="status" name="status"
                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mb-3 select2 custom-select"
                                                                                required>


                                                                                <option value="Pending"
                                                                                    {{ old('status', $saleOrder->status ?? '') == 'Pending' ? 'selected' : '' }}>
                                                                                    Pending
                                                                                </option>
                                                                                <option value="Approved"
                                                                                    {{ old('status', $saleOrder->status ?? '') == 'Approved' ? 'selected' : '' }}>
                                                                                    Approved
                                                                                </option>
                                                                                <option value="Dispatched"
                                                                                    {{ old('status', $saleOrder->status ?? '') == 'Dispatched' ? 'selected' : '' }}>
                                                                                    Dispatched
                                                                                </option>
                                                                                <option value="Cancelled"
                                                                                    {{ old('status', $saleOrder->status ?? '') == 'Cancelled' ? 'selected' : '' }}>
                                                                                    Cancelled
                                                                                </option>

                                                                            </select>



                                                                            {{-- <select id="status" type="text"
                                                                                name="status"
                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mb-3 select2 custom-select"
                                                                                required>
                                                                                <option value="Pending">Pending
                                                                                </option>
                                                                                <option value="Delivered">
                                                                                    Delivered
                                                                                </option>
                                                                                <option value="Cancelled">Cancelled
                                                                                </option> --}}
                                                                            {{-- @foreach ($dropDownData['parties'] as $key => $value)
                                                                                        <option
                                                                                            value="{{ $key }}"
                                                                                            {{ (old('party_id') == $key ? 'selected' : '') || (!empty($saleOrder->party_id) ? collect($saleOrder->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                            {{ $value }}
                                                                                        </option>
                                                                                    @endforeach --}}
                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="invoice-detail-terms"
                                                                    style="padding: 0px 0px 0px 0px !important;">
                                                                    <div class="tab-content mt-2"
                                                                        id="pills-tabContent">
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
                                                                                                style="width: 20%">
                                                                                                Product</th>
                                                                                            <th class="">
                                                                                                P.T</th>
                                                                                            <th class="">
                                                                                                M.T</th>
                                                                                            <th class="">
                                                                                                Quantity</th>
                                                                                            {{-- <th class="">
                                                                                                Packing Type</th> --}}
                                                                                            <th class="">
                                                                                                Dzns
                                                                                            </th>
                                                                                            <th class="text-right"
                                                                                                style="width: 10%">
                                                                                                Total
                                                                                                Dzns</th>
                                                                                            <th class="text-right">
                                                                                                Rate
                                                                                            </th>

                                                                                            <th class="text-right"
                                                                                                style="width: 15%">
                                                                                                Amount
                                                                                            </th>

                                                                                        </tr>
                                                                                        <tr aria-hidden="true"
                                                                                            class="mt-3 d-block table-row-hidden">
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>

                                                                                        @if (!empty($saleOrderDetails))
                                                                                            @foreach ($saleOrderDetails as $saleOrderDetail)
                                                                                                @php
                                                                                                    $index =
                                                                                                        $loop->index +
                                                                                                        2; // Starts from 2
                                                                                                @endphp
                                                                                                <tr
                                                                                                    class="tr_clone validator_{{ $index }}">
                                                                                                    <td
                                                                                                        class="delete-item-row">
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
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            name="row_id[]"
                                                                                                            class="row_id"
                                                                                                            value="{{ $index }}"
                                                                                                            hidden>
                                                                                                    </td>
                                                                                                    <td
                                                                                                        class="product">
                                                                                                        <select
                                                                                                            id="product"
                                                                                                            type="text"
                                                                                                            name="product_id[]"
                                                                                                            placeholder="Please Select the Product"
                                                                                                            class="{{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} form-control  mb-3 select2 custom-select product product_{{ $index }}"
                                                                                                            required>
                                                                                                            <option
                                                                                                                value="">
                                                                                                                Select
                                                                                                                the
                                                                                                                Product
                                                                                                            </option>
                                                                                                            @foreach ($products as $key => $value)
                                                                                                                <option
                                                                                                                    value="{{ $key }}"
                                                                                                                    {{ (old('product_id') == $key ? 'selected' : '') || (!empty($saleOrderDetail->product_id) ? collect($saleOrderDetail->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                                    {{ $value }}
                                                                                                                </option>
                                                                                                            @endforeach
                                                                                                        </select>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            style="color: black;"
                                                                                                            class="packing form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_{{ $index }}"
                                                                                                            id="packing"
                                                                                                            value="{{ old('packing_type', !empty($saleOrderDetail->packing_type) ? $saleOrderDetail->packing_type : '') }}"
                                                                                                            name="packing_type[]"
                                                                                                            placeholder="P.T"
                                                                                                            readonly>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            style="color: black;"
                                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_{{ $index }}"
                                                                                                            placeholder="M.T"
                                                                                                            value="{{ old('measurement_type', !empty($saleOrderDetail->measurement_type) ? $saleOrderDetail->measurement_type : '') }}"
                                                                                                            name="measurement_type[]"
                                                                                                            id="measurement"
                                                                                                            readonly>
                                                                                                    </td>

                                                                                                    <td
                                                                                                        class="quantity">
                                                                                                        <input
                                                                                                            type="number"
                                                                                                            id="quantity"
                                                                                                            class="qty form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_{{ $index }}"
                                                                                                            value="{{ old('quantity', !empty($saleOrderDetail->quantity) ? $saleOrderDetail->quantity : '') }}"
                                                                                                            name="quantity[]"
                                                                                                            placeholder="Qty"
                                                                                                            required>
                                                                                                    </td>

                                                                                                    <td
                                                                                                        class="total_unit">
                                                                                                        <input
                                                                                                            type="number"
                                                                                                            id="dzn"
                                                                                                            name="dzn[]"
                                                                                                            value="{{ old('dzn', !empty($saleOrderDetail->dzn) ? $saleOrderDetail->dzn : '') }}"
                                                                                                            class="dozen form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} dozen_{{ $index }}"
                                                                                                            placeholder="Dzn"
                                                                                                            required>
                                                                                                    </td>
                                                                                                    <td
                                                                                                        class="total_dzns">
                                                                                                        <input
                                                                                                            type="number"
                                                                                                            id="total_dzns"
                                                                                                            style="color: black;"
                                                                                                            name="total_dzn[]"
                                                                                                            value="{{ old('total_dzn', !empty($saleOrderDetail->total_dzn) ? $saleOrderDetail->total_dzn : '') }}"
                                                                                                            class="totDzn form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} totDzn_{{ $index }}"
                                                                                                            placeholder="Tot.Dzns"
                                                                                                            readonly>
                                                                                                    </td>

                                                                                                    <td class="rate">
                                                                                                        <input
                                                                                                            type="number"
                                                                                                            id="rate"
                                                                                                            name="rate[]"
                                                                                                            value="{{ old('rate', !empty($saleOrderDetail->rate) ? $saleOrderDetail->rate : '') }}"
                                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} rate rate_{{ $index }}"
                                                                                                            placeholder="Rate"
                                                                                                            required>
                                                                                                    </td>

                                                                                                    <td class="amount">
                                                                                                        <input
                                                                                                            type="number"
                                                                                                            id="amount"
                                                                                                            style="color: black;"
                                                                                                            value="{{ old('amount', !empty($saleOrderDetail->amount) ? $saleOrderDetail->amount : '') }}"
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
                                                                                class="btn btn-dark additem"
                                                                                id="add-item">Add
                                                                                Item</a>

                                                                        </div>

                                                                        <div class="col-md-12">
                                                                            <div class="row">
                                                                                <div class="col-md-8"
                                                                                    style="float: right;">
                                                                                    <div
                                                                                        style="width: 40%; float: right;">
                                                                                        <label for="client-phone">Tot.
                                                                                            Boray</label>
                                                                                        <input type="text"
                                                                                            style="color: black;"
                                                                                            value="{{ old('total_boray', !empty($saleOrder->total_boray) ? $saleOrder->total_boray : '') }}"
                                                                                            id="boray-amount"
                                                                                            name="total_boray"
                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} boray-amount"
                                                                                            placeholder="Tot.Boray"
                                                                                            readonly>

                                                                                        <label for="client-phone">Tot.
                                                                                            Carton</label>
                                                                                        <input type="text"
                                                                                            style="color: black;"
                                                                                            value="{{ old('total_carton', !empty($saleOrder->total_carton) ? $saleOrder->total_carton : '') }}"
                                                                                            id="carton-amount"
                                                                                            name="total_carton"
                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} carton-amount"
                                                                                            placeholder="Tot.Carton"
                                                                                            readonly>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="col-md-4"
                                                                                    style="float: right; ">
                                                                                    <div
                                                                                        style="width: 80%; float: right;">
                                                                                        <label for="client-phone">Tot.
                                                                                            Amount</label>
                                                                                        <input type="text"
                                                                                            style="color: black;"
                                                                                            value="{{ old('total_amount', !empty($saleOrder->total_amount) ? $saleOrder->total_amount : '') }}"
                                                                                            id="net-amount"
                                                                                            name="total_amount"
                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                            id="client-phone"
                                                                                            placeholder="Tot.Amount"
                                                                                            readonly>
                                                                                    </div>

                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                        <div class="invoice-detail-note"
                                                                            style="padding: 0px 0px 0px 0px !important;">

                                                                            <div class="row">

                                                                                <div
                                                                                    class="col-md-12 align-self-center">

                                                                                    <div
                                                                                        class="form-group row invoice-note">
                                                                                        <label
                                                                                            for="invoice-detail-notes"
                                                                                            class="col-sm-12 col-form-label col-form-label-sm">Remarks</label>
                                                                                        <div class="col-sm-12">
                                                                                            <textarea class="form-control" id="remarks" name="remarks" placeholder='Enter The Remarks' style="height: 88px;">{{ @$saleOrder->remarks }}</textarea>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>

                                                                        @if (!empty($saleOrder))
                                                                            <div
                                                                                class="col-lg-0 col-12 form-group mb-4">
                                                                                <div class="multiple-file-upload mt-5">
                                                                                    <label for="invoice-detail-notes"
                                                                                        class="col-sm-12 col-form-label col-form-label-sm">Upload
                                                                                        Images</label>
                                                                                    <input type="file"
                                                                                        class="filepond file-upload-multiple form-control"
                                                                                        name="images[]" multiple
                                                                                        data-allow-reorder="true"
                                                                                        data-max-file-size="3MB"
                                                                                        data-max-files="3">
                                                                                </div>
                                                                                <div class="media">
                                                                                    <div class="avatar me-2"
                                                                                        style="width: 10rem; height: 10rem;">


                                                                                        @if ($images == null)
                                                                                            <img alt="avatar"
                                                                                                src="{{ asset('images/no-attachments.png') }}"
                                                                                                class="rounded-circle" />
                                                                                        @else
                                                                                            @foreach ($images as $image)
                                                                                                <img alt="avatar"
                                                                                                    src="{{ asset('images/saleOrder/') . '/' . @$image->images }}"
                                                                                                    class="rounded-circle" />
                                                                                            @endforeach
                                                                                        @endif


                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="multiple-file-upload mt-5">
                                                                                <label for="invoice-detail-notes"
                                                                                    class="col-sm-12 col-form-label col-form-label-sm">Upload
                                                                                    Images</label>
                                                                                <input type="file"
                                                                                    class="filepond file-upload-multiple form-control"
                                                                                    name="images[]" multiple
                                                                                    data-allow-reorder="true"
                                                                                    data-max-file-size="3MB"
                                                                                    data-max-files="3">
                                                                            </div>
                                                                        @endif







                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>



                                                        <div class="form-group">
                                                            <a href="{{ route('sale-order.list') }}"
                                                                style="float: right;"
                                                                class="btn btn-dark rounded bs-popover ml-2 mt-2  mb-4">Cancel</a>
                                                            @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                                <button type="submit" style="float: right"
                                                                    class="btn btn-success  rounded bs-popover me-1 mt-2 mb-4 "
                                                                    data-bs-container="body" data-bs-placement="right"
                                                                    data-bs-content="Tooltip on right">
                                                                    @if (!isset($saleOrder))
                                                                        Save
                                                                    @else
                                                                        Update
                                                                    @endif
                                                                </button>
                                                        </div>
                                                        @endif

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

    <script src="{{ asset('js/saleOrder.js') }}"></script>

    <script>
        $(document).on('click', 'body *', function() {
            $('.qty').on("input", function() {
                updatePackingTotals();
            });
        });
    </script>

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
                '<td class="product"> <select id="product" type = "text" name = "product_id[]" class ="form-control select2 custom-select form-control-sm product product_' +
                currentIndex +
                '" placeholder = "Please Select the Product" required ><option value = "" >Select the Product </option>  </select> ' +
                ' </td> ' +
                '<td class="packingType" ><input type="text" id="packing" name="packing_type[]" style="color: black; "  class = "packing form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_' +
                currentIndex +
                '" placeholder="P.T" readonly></td>' +
                '<td class="measurementType" ><input type="text" style="color: black; " placeholder="M.T" name="measurement_type[]" id="measurement" class = "measurement form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_' +
                currentIndex + '" readonly> </td> ' +
                '<td class="qty2">' +
                ' <input type="number" name="quantity[]" id="quantity" class = "qty form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_' +
                currentIndex +
                '" placeholder="Qty" required></td>' +

                '<td class="dozen"> <input type="number" name="dzn[]" id="dzn" class = "dozen form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} dozen_' +
                currentIndex +
                ' "  placeholder="Dzns " required></td>' +
                '<td class="totDzn"><input type="number" style="color: black;" name="total_dzn[]" class="totDzn form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} totDzn_' +
                currentIndex + ' " placeholder="Tot Dzn" readonly></td>' +
                '<td class="rate"><input type="number"  id="rate" name="rate[]" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} rate rate_' +
                currentIndex + ' " placeholder="Rate" required></td>' +
                '<td class="amount"><input type="number"  id="amount" style="color: black;" name="amount[]" class="amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_' +
                currentIndex + ' " placeholder="Amount" readonly></td>' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';
            $(".item-table tbody").append($html);
            deleteItemRow();
            // $('.select2').select2();
            $('.product_' + currentIndex).select2();
            fetchProducts(currentIndex);

            $(document).on('click', 'body *', function() {
                $('.qty_' + currentIndex).on("input", function() {
                    updatePackingTotals();
                });
            });





            $(document).ready(function() {
                $(".product_" + currentIndex).on('change', function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    var productSelected = '.product_' + row_id;
                    var name = this.value;
                    let url = config.routes.getProductMeasurementTypeDetail + '/' + name;
                    $.ajax({
                        url: url,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        success: function(response) {
                            $(".measurement_" + row_id).val(response.name.name);
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
                $('.product_' + currentIndex).select2();
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

            $(document).on('click', 'body *', function() {
                $('.dozen').on("input", function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
                    let dzns = $(this).closest("tr").find(".dozen_" + row_id).val();
                    if (parseInt(quantity) > 0) {
                        $(this).closest("tr").find(".totDzn_" + row_id).val(quantity * dzns);
                    } else {
                        $(this).closest("tr").find(".totDzn_" + row_id).val('');
                    }
                    let totalDzn = $(this).closest("tr").find(".totDzn_" + row_id).val();
                    let price = $(this).closest("tr").find(".rate_" + row_id).val();
                    if (parseInt(totalDzn) > 0) {
                        $(this).closest("tr").find(".amount_" + row_id).val(totalDzn * price);
                    } else {
                        $(this).closest("tr").find(".amount_" + row_id).val('');
                    }
                    doAmountTotal();
                });
                $('.rate').on("input", function() {

                    var row_id = $(this).closest("tr").find(".row_id").val();
                    let totalDzn = $(this).closest("tr").find(".totDzn_" + row_id).val();
                    let price = $(this).closest("tr").find(".rate_" + row_id).val();
                    if (parseInt(totalDzn) > 0) {
                        $(this).closest("tr").find(".amount_" + row_id).val(totalDzn * price);
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
                    $('#net-amount').val(totalAmount.toFixed(2));
                }

                $("#freight, #scheme, #commission").on("input", function() {
                    var totalAmount = 0;
                    $(".amount").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    let freight = $("#freight").val() ? $("#freight").val() : 0;
                    let scheme = $("#scheme").val() ? $("#scheme").val() : 0;
                    let commission = $("#commission").val() ? $("#commission").val() : 0;

                    var totalLessAmount = parseInt(freight) + parseInt(scheme) + parseInt(
                        commission);
                    $('#net-amount').val(totalLessAmount ? totalAmount.toFixed(2) -
                        totalLessAmount : totalAmount.toFixed(2));
                })



            });

            $(document).on('click', 'body *', function() {
                $('.amount').on("input", function() {
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


            $(document).on('click', 'body *', function() {
                $('.amount').on("input", function() {
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

        function deleteItemRow() {
            let deleteItem = document.querySelectorAll('.delete-item');
            for (var i = 0; i < deleteItem.length; i++) {
                deleteItem[i].addEventListener('click', function() {
                    this.parentElement.parentNode.parentNode.parentNode.remove();
                })
            }
        }

        function updatePackingTotals() {
            let totalBorayAmount = 0;
            let totalCartonAmount = 0;

            // Loop through all rows to calculate total Boray and Carton quantities
            $(".item-table tbody tr").each(function() {
                let packingType = $(this).find('.packing').val().toLowerCase();

                let quantity = parseFloat($(this).find('.qty').val()) || 0;

                // Check the packing type and sum the quantities
                if (packingType === 'boray') {
                    totalBorayAmount += quantity;
                } else if (packingType === 'carton') {
                    totalCartonAmount += quantity;
                }
            });

            // Update the totals in the respective fields
            $('#boray-amount').val(totalBorayAmount.toFixed(2));
            $('#carton-amount').val(totalCartonAmount.toFixed(2));
        }



        function fetchProducts(index) {
            let partyId = $(".party").val();
            if (!partyId) {
                alert("Please select a party first.");
                return;
            }

            $.ajax({
                url: config.routes.getProducts,
                type: "GET",
                data: {
                    party_id: partyId
                },
                dataType: 'json',
                beforeSend: function() {
                    $(`.product_${index}`).html('<option>Loading...</option>').prop('disabled', true);
                },
                success: function(result) {
                    let productSelect = $(`.product_${index}`);
                    // productSelect.html('<option value="">Select the Product</option>');
                    productSelect.empty().append('<option value="">Select the Product</option>');

                    $.each(result.products, function(key, data) {
                        productSelect.append(`<option value="${data.id}">${data.name}</option>`);
                    });

                    productSelect.prop('disabled', false);
                    productSelect.trigger('change.select2'); // Refresh Select2
                },
                error: function() {
                    $(`.product_${index}`).html('<option>Failed to load products</option>').prop('disabled',
                        false);
                }
            });
        }



        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('.party').on('change', function() {
                var name = this.value;
                let url = config.routes.getPartySaleManDetail;

                $('.saleMan').html('');
                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        party_id: name,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {

                        $.each(result.saleManName, function(key, data) {
                            $('.saleMan').append(
                                '<option value="' +
                                data.id +
                                '"selected>' + data.name +
                                '</option>');
                        });

                    }
                });
            });
            $('.party').on('change', function() {
                var idParty = this.value;
                $(".sector-dropdown").html('');
                $(".area-dropdown").html('');
                $.ajax({
                    url: config.routes.getPartySectorDetail,
                    type: "GET",
                    data: {
                        party_id: idParty,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('.sector-dropdown').html(
                            '<option value="">-- Select Sector --</option>'
                        );

                        $.each(result.sectors, function(key, data) {
                            $('.sector-dropdown').append(
                                '<option value="' +
                                data.id +
                                '">' + data.name +
                                '</option>');
                        });

                    }
                });

            });
            $('.party').on('change', function() {
                var idParty = this.value;
                $(".sector-dropdown").html('');
                $(".area-dropdown").html('');
                $(".delivered-to-dropdown").html('');
                $.ajax({
                    url: config.routes.getDeliveredToParty,
                    type: "GET",
                    data: {
                        party_id: idParty,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('.delivered-to-dropdown').html(
                            '<option value="">-- Select Delivered To Party --</option>'
                        );

                        $.each(result.parties, function(key, data) {
                            $('.delivered-to-dropdown').append(
                                '<option value="' +
                                data.id +
                                '">' + data.party_name +
                                '</option>');
                        });

                    }
                });

            });

            $('.sector-dropdown').on('change', function() {
                var idSector = this.value;
                var idParty = $('.party').val();
                $(".area-dropdown").html('');
                $.ajax({
                    url: config.routes.getPartyAreaDetail,
                    type: "GET",
                    data: {
                        party_id: idParty,
                        sector_id: idSector,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('.area-dropdown').html(
                            '<option value="">-- Select Area --</option>'
                        );

                        $.each(result.areas, function(key, data) {
                            $('.area-dropdown').append(
                                '<option value="' +
                                data.id +
                                '">' + data.name +
                                '</option>');
                        });

                    }
                });
            });

        });
    </script>
    <script>
        .invoice - detail - items {
            padding: 0 px!important;
            padding: 0 px 0 px!important;
        }
    </script>

    <script>
        var config = {
            routes: {
                getProducts: "{{ url('sale-order/get-products') }}",
                getPartySaleManDetail: "{{ url('sale-order/get-party-sale-man') }}",
                getPartySectorDetail: "{{ url('sale-order/get-party-sale-man-sector') }}",
                getPartyAreaDetail: "{{ url('sale-order/get-party-sale-man-area') }}",
                getProductPackingTypeDetail: "{{ url('sale-order/get-product-packing-type') }}",
                getProductMeasurementTypeDetail: "{{ url('sale-order/get-product-measurement-type') }}",
                getDeliveredToParty: "{{ url('sale-order/get-delivered-to-party') }}",
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
        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
    </x-slot>
</x-base-layout>
