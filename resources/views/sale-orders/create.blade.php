<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>
    <x-slot:headerFiles>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                            <h4>Add Sale Order</h4>
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
                                                        method="POST" autocomplete="off"
                                                        class="row g-3 needs-validation" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($saleOrder->id) ? $saleOrder->id : '' }}" />
                                                        <div class="invoice-detail-terms">
                                                            <div class="row justify-content-between">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
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
                                                                                placeholder="Select The Date">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-0 col-12 ">
                                                                        <div class="row">


                                                                            <div class="col-md-6 mt-5">
                                                                                <label for="party"
                                                                                    class="form-label">Party</label>
                                                                                <select id="party" type="text"
                                                                                    name="party_id"
                                                                                    placeholder="Please Select the Party Name"
                                                                                    class="form-control select2 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mb-3 custom-select"
                                                                                    required>
                                                                                    <option value="">Select
                                                                                    </option>
                                                                                    @foreach ($dropDownData['parties'] as $key => $value)
                                                                                        <option
                                                                                            value="{{ $key }}"
                                                                                            {{ (old('party_id') == $key ? 'selected' : '') || (!empty($saleOrder->party_id) ? collect($saleOrder->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                            {{ $value }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <div class="invalid-feedback">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 mt-5">
                                                                                <label for="saleMan">
                                                                                    Sale Man</label>
                                                                                <input type="text"
                                                                                    style="color: black; background-color: white;"
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                    id="saleMan"
                                                                                    placeholder="Sale Man Name..."
                                                                                    readonly>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-0 col-12 ">
                                                                        <div class="row">
                                                                            <div class="col-md-6 mt-5">
                                                                                <label for="sector">
                                                                                    Belt</label>
                                                                                <input type="text"
                                                                                    style="color: black; background-color: white;"
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                    id="sector"
                                                                                    placeholder="Sector Name..."
                                                                                    readonly>
                                                                            </div>
                                                                            <div class="col-md-6 mt-5">
                                                                                <label for="date">
                                                                                    Area</label>
                                                                                <input type="text"
                                                                                    style="color: black; background-color: white;"
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                    id="area"
                                                                                    placeholder="Area Name..."
                                                                                    readonly>
                                                                            </div>

                                                                        </div>
                                                                    </div>



                                                                    <div class="tab-content mt-5"
                                                                        id="pills-tabContent">
                                                                        <div class="invoice-detail-items">

                                                                            <div class="table-responsive">
                                                                                <table class="table item-table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th class="">
                                                                                            </th>
                                                                                            <th></th>
                                                                                            <th>Product</th>
                                                                                            <th class="">
                                                                                                Quantity</th>
                                                                                            <th class="">
                                                                                                Packing Type</th>
                                                                                            <th class="">
                                                                                                Measurement Type
                                                                                            </th>
                                                                                            <th class="">total
                                                                                                Dzns</th>
                                                                                            <th class="text-right">
                                                                                                Price
                                                                                            </th>

                                                                                            <th class="text-right">
                                                                                                Amount
                                                                                            </th>

                                                                                        </tr>
                                                                                        <tr aria-hidden="true"
                                                                                            class="mt-3 d-block table-row-hidden">
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        @if (empty($saleOrders))
                                                                                            @foreach ($saleOrders as $saleOrder)
                                                                                                <tr
                                                                                                    class="tr_clone validator_0">
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
                                                                                                    <td><input
                                                                                                            type="text"
                                                                                                            name="row_id[]"
                                                                                                            class="row_id"
                                                                                                            value="0"
                                                                                                            hidden>
                                                                                                    </td>
                                                                                                    <td
                                                                                                        class="product">
                                                                                                        <select
                                                                                                            id="product"
                                                                                                            type="text"
                                                                                                            name="product_id[]"
                                                                                                            placeholder="Please Select the Product"
                                                                                                            class="product form-control select2 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} custom-select product0"
                                                                                                            required>
                                                                                                            <option
                                                                                                                value="">
                                                                                                                Select
                                                                                                                the
                                                                                                                Product
                                                                                                            </option>
                                                                                                            @foreach ($dropDownData['products'] as $key => $value)
                                                                                                                <option
                                                                                                                    value="{{ $key }}"
                                                                                                                    {{ (old('product_id') == $key ? 'selected' : '') || (!empty($saleOrder->product_id) ? collect($saleOrder->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                                    {{ $value }}
                                                                                                                </option>
                                                                                                            @endforeach
                                                                                                        </select>
                                                                                                    </td>

                                                                                                    <td
                                                                                                        class="quantity">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            id="quantity0"
                                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_0"
                                                                                                            value="{{ old('quantity', !empty($saleOrder->quantity) ? $saleOrder->quantity : '') }}"
                                                                                                            name="quantity[]"
                                                                                                            placeholder="Qty">
                                                                                                    </td>
                                                                                                    <td class="unit">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            id="packing"
                                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unit_0"
                                                                                                            value="{{ old('packingType', !empty($saleOrder->packingType) ? $saleOrder->packingType : '') }}"
                                                                                                            name="packingType[]"
                                                                                                            placeholder="P.T">
                                                                                                    </td>
                                                                                                    <td
                                                                                                        class="total_unit">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            id="measurement"
                                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unit_0"
                                                                                                            value="{{ old('measurementType', !empty($saleOrder->measurementType) ? $saleOrder->measurementType : '') }}"
                                                                                                            name="measurementType[]"
                                                                                                            placeholder="M T">
                                                                                                    </td>
                                                                                                    <td
                                                                                                        class="total_unit">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            id="total_quantity"
                                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unit_0"
                                                                                                            value="{{ old('total_quantity', !empty($saleOrder->total_quantity) ? $saleOrder->total_quantity : '') }}"
                                                                                                            name="total_quantity[]"
                                                                                                            placeholder="Tot Dzns">
                                                                                                    </td>
                                                                                                    <td
                                                                                                        class="total_unit">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            id="price"
                                                                                                            name="price[]"
                                                                                                            value="{{ old('price', !empty($saleOrder->price) ? $saleOrder->price : '') }}"
                                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} "
                                                                                                            placeholder="Price">
                                                                                                    </td>
                                                                                                    <td
                                                                                                        class="total_unit">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            id="amount"
                                                                                                            name="amount[]"
                                                                                                            value="{{ old('amount', !empty($saleOrder->amount) ? $saleOrder->amount : '') }}"
                                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_0 "
                                                                                                            placeholder="Price">
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        @else
                                                                                            <tr
                                                                                                class="tr_clone validator_0">
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
                                                                                                <td><input
                                                                                                        type="text"
                                                                                                        name="row_id[]"
                                                                                                        class="row_id"
                                                                                                        value="0"
                                                                                                        hidden>
                                                                                                </td>
                                                                                                <td class="product">
                                                                                                    <select
                                                                                                        id="product"
                                                                                                        type="text"
                                                                                                        name="product_id[]"
                                                                                                        placeholder="Please Select the Product"
                                                                                                        class="product form-control select2 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} custom-select"
                                                                                                        required>
                                                                                                        <option
                                                                                                            value="">
                                                                                                            Select
                                                                                                            the
                                                                                                            Product
                                                                                                        </option>
                                                                                                        @foreach ($dropDownData['products'] as $key => $value)
                                                                                                            <option
                                                                                                                value="{{ $key }}"
                                                                                                                {{ (old('product_id') == $key ? 'selected' : '') || (!empty($saleOrder->product_id) ? collect($saleOrder->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                                {{ $value }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </td>

                                                                                                <td class="quantity">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="quantity"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_0"
                                                                                                        value="{{ old('quantity', !empty($saleOrder->quantity) ? $saleOrder->quantity : '') }}"
                                                                                                        name="quantity[]"
                                                                                                        placeholder="Qty">
                                                                                                </td>
                                                                                                <td class="unit">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="packing"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unit_0"
                                                                                                        value="{{ old('packingType', !empty($saleOrder->packingType) ? $saleOrder->packingType : '') }}"
                                                                                                        name="packingType[]"
                                                                                                        placeholder="P.T">
                                                                                                </td>
                                                                                                <td class="total_unit">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="measurement"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unit_0"
                                                                                                        value="{{ old('measurementType', !empty($saleOrder->measurementType) ? $saleOrder->measurementType : '') }}"
                                                                                                        name="measurementType[]"
                                                                                                        placeholder="M T">
                                                                                                </td>
                                                                                                <td class="total_unit">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="total_quantity"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unit_0"
                                                                                                        value="{{ old('total_quantity', !empty($saleOrder->total_quantity) ? $saleOrder->total_quantity : '') }}"
                                                                                                        name="total_quantity[]"
                                                                                                        placeholder="Tot Dzns">
                                                                                                </td>
                                                                                                <td class="total_unit">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="price"
                                                                                                        name="price[]"
                                                                                                        value="{{ old('price', !empty($saleOrder->price) ? $saleOrder->price : '') }}"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} "
                                                                                                        placeholder="Price">
                                                                                                </td>
                                                                                                <td class="total_unit">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="amount"
                                                                                                        name="amount[]"
                                                                                                        value="{{ old('amount', !empty($saleOrder->amount) ? $saleOrder->amount : '') }}"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_0 "
                                                                                                        placeholder="Price">
                                                                                                </td>
                                                                                            </tr>

                                                                                        @endif
                                                                                        {{-- @foreach ($saleOrders as $saleOrder)
                                                                                            <tr
                                                                                                class="tr_clone validator_0">
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
                                                                                                <td><input
                                                                                                        type="text"
                                                                                                        name="row_id[]"
                                                                                                        class="row_id"
                                                                                                        value="0"
                                                                                                        hidden>
                                                                                                </td>
                                                                                                <td class="product">
                                                                                                    <select
                                                                                                        id="product"
                                                                                                        type="text"
                                                                                                        name="product_id[]"
                                                                                                        placeholder="Please Select the Product"
                                                                                                        class="product form-control select2 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} custom-select"
                                                                                                        required>
                                                                                                        <option
                                                                                                            value="">
                                                                                                            Select
                                                                                                            the
                                                                                                            Product
                                                                                                        </option>
                                                                                                        @foreach ($dropDownData['products'] as $key => $value)
                                                                                                            <option
                                                                                                                value="{{ $key }}"
                                                                                                                {{ (old('product_id') == $key ? 'selected' : '') || (!empty($saleOrder->product_id) ? collect($saleOrder->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                                {{ $value }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </td>

                                                                                                <td class="quantity">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="quantity"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_0"
                                                                                                        value="{{ old('quantity', !empty($saleOrder->quantity) ? $saleOrder->quantity : '') }}"
                                                                                                        name="quantity[]"
                                                                                                        placeholder="Qty">
                                                                                                </td>
                                                                                                <td class="unit">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="packing"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unit_0"
                                                                                                        value="{{ old('packingType', !empty($saleOrder->packingType) ? $saleOrder->packingType : '') }}"
                                                                                                        name="packingType[]"
                                                                                                        placeholder="P.T">
                                                                                                </td>
                                                                                                <td class="total_unit">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="measurement"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unit_0"
                                                                                                        value="{{ old('measurementType', !empty($saleOrder->measurementType) ? $saleOrder->measurementType : '') }}"
                                                                                                        name="measurementType[]"
                                                                                                        placeholder="M T">
                                                                                                </td>
                                                                                                <td class="total_unit">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="total_quantity"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unit_0"
                                                                                                        value="{{ old('total_quantity', !empty($saleOrder->total_quantity) ? $saleOrder->total_quantity : '') }}"
                                                                                                        name="total_quantity[]"
                                                                                                        placeholder="Tot Dzns">
                                                                                                </td>
                                                                                                <td class="total_unit">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="price"
                                                                                                        name="price[]"
                                                                                                        value="{{ old('price', !empty($saleOrder->price) ? $saleOrder->price : '') }}"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} "
                                                                                                        placeholder="Price">
                                                                                                </td>
                                                                                                <td class="total_unit">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        id="amount"
                                                                                                        name="amount[]"
                                                                                                        value="{{ old('amount', !empty($saleOrder->amount) ? $saleOrder->amount : '') }}"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_0 "
                                                                                                        placeholder="Price">
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach --}}
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>

                                                                            <a href="javascript:void(0);"
                                                                                class="btn btn-dark additem"
                                                                                id="add-item">Add
                                                                                Item</a>

                                                                        </div>


                                                                        <div class="invoice-detail-note">

                                                                            <div class="row">

                                                                                <div
                                                                                    class="col-md-12 align-self-center">

                                                                                    <div
                                                                                        class="form-group row invoice-note">
                                                                                        <label
                                                                                            for="invoice-detail-notes"
                                                                                            class="col-sm-12 col-form-label col-form-label-sm">Remarks</label>
                                                                                        <div class="col-sm-12">
                                                                                            <textarea class="form-control" id="remarks" name="remarks" placeholder='Enter The Remarks' style="height: 88px;"></textarea>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>

                                                                            </div>

                                                                        </div>
                                                                        <div class="col-xl-5 invoice-address-client invoice-detail-total"
                                                                            style="float: right">
                                                                            <div class="invoice-address-client-fields">



                                                                                <div class="form-group row">
                                                                                    <label for="client-phone"
                                                                                        class="col-sm-4 col-form-label col-form-label-sm">Net
                                                                                        Amount</label>
                                                                                    <div class="col-sm-8">
                                                                                        <input type="text"
                                                                                            id="net-amount"
                                                                                            name="total_amount"
                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                            id="client-phone"
                                                                                            placeholder="">
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>



                                                        <div class="form-group">
                                                            <a href="{{ route('sale-order.list') }}"
                                                                style="float: right;"
                                                                class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                            @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                                <button type="submit" style="float: right"
                                                                    class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
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


    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
        <script src="{{ asset('plugins/invoice-add/invoice-add.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>


        <script>
            var config = {
                routes: {
                    getPartySaleManDetail: "{{ url('sale-order/get-party-sale-man') }}",
                    getPartySectorDetail: "{{ url('sale-order/get-party-sale-man-sector') }}",
                    getPartyAreaDetail: "{{ url('sale-order/get-party-sale-man-area') }}",
                    getProductPackingTypeDetail: "{{ url('sale-order/get-product-packing-type') }}",
                    getProductMeasurementTypeDetail: "{{ url('sale-order/get-product-measurement-type') }}",

                },
            }
        </script>

    </x-slot>
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
                // data-id=currentIndex
                '<td class="product"> <select id="product" type = "text" class = "product" name = "product_id[]" data-id=' +
                currentIndex +
                ' placeholder = "Please Select the Product"   required ><option value = "" >Select the Product </option> @foreach ($dropDownData['products'] as $key => $value)<option value = "{{ $key }}" {{ (old('product_id') == $key ? 'selected' : '') || (!empty($saleOrder->product_id) ? collect($saleOrder->product_id)->contains($key) : '') ? 'selected' : '' }} >{{ $value }} </option> @endforeach </select> ' +
                '<td class="quantity">' +
                ' <input type="text" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} quantity" name="quantity[]" id="quantity' +
                currentIndex +
                '" placeholder="Qty"></td>' +
                '<td class="total_unit"> <input id="packing" type="text" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} "  name = "packingType[]"' +
                currentIndex +
                '" placeholder="P.T"></td>' +
                '<td class="total_unit"><input type="text" id="measurement" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} "  placeholder="M.T" name = "measurementType[]" < /td>' +
                '<td class="total_unit"> <input type="text" name = "total_quantity[]" id="total_quantity" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} " ' +
                currentIndex +
                ' "  placeholder="Tot. Dzns" data-id = "0" ></td>' +
                '<td class="total_unit"><input type="text"  id="price" name="price[]" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} ' +
                currentIndex + ' " placeholder="Price" ></td>' +
                '<td class="total_unit"><input type="text"  id="amount" name="amount[]" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}' +
                currentIndex + ' amount" placeholder="amount" ></td>' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);
            deleteItemRow();

        })

        selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
        selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
        selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);

        var f2 = flatpickr(document.getElementById('due'), {
            defaultDate: currentDate.setDate(currentDate.getDate() + 5),
        });
        // $(document).getElementsByClassName(function() {

        // });
    </script>
    <script>
        $('.product').on('change', function() {
            console.log("here");
            var name = $('.product :selected').text();
            // var index =$(this).data('id');
            var index = $(this).find(':selected').attr('data-id')
            // console.log();
            let url = config.routes.getProductMeasurementTypeDetail + '/' + name;
            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#measurement" + index).val(response.measurement_type_id);
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

        })
    </script>
</x-base-layout>
