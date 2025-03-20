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
        {{-- <link rel="stylesheet" href="../src/plugins/src/filepond/filepond.min.css">
        <link rel="stylesheet" href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css"> --}}

        {{-- <link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" /> --}}
        <link href="../src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->


    {{-- <!-- BREADCRUMB --> --}}
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dispatch Note</li>
            </ol>
        </nav>
    </div>
    <div class="row layout-top-spacing">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show mb-4 " role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <svg> ...
                    </svg>
                </button>
                {{ session()->get('message') }}
            </div>
        @endif
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Create Dispatch Note</h4>
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
                                                    <form class="row g-3"
                                                        action="{{ !empty($note) ? route('dispatch-note.update') : route('dispatch-note.save') }}"
                                                        method="POST" enctype="multipart/form-data" autocomplete="off">
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($note->id) ? $note->id : '' }}" />
                                                        <div id="results">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-6 mt-3">
                                                                        <label for="">
                                                                            <h4>Dispatch Note Number
                                                                                #:{{ @$maxid }}</h4>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="inputState" class="form-label">Sale
                                                                            Order#</label>
                                                                        <input type="text" id="saleOrder"
                                                                            name="sale_order_number"
                                                                            style="color: black; "
                                                                            value="{{ $sale_Order->id }}"
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} saleOrder"
                                                                            placeholder="Please Enter The SO Number"
                                                                            readonly>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Date</label>
                                                                        <input id="date" name="date"
                                                                            style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} date flatpickr "
                                                                            type="text" data-date-format="d-m-Y"
                                                                            value="{{ $sale_Order->date }}"
                                                                            placeholder="Select Date.." readonly>
                                                                    </div>

                                                                </div>


                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-md-6">

                                                                        <label for="inputState"
                                                                            class="form-label">Party</label>
                                                                        <select id="party" name="party_id"
                                                                            class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} party"
                                                                            required>
                                                                            @foreach ($parties as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    @php
$isSelected = old('party_id') == $key || $sale_Order->pluck('party_id')->contains($key); @endphp
                                                                                    {{ $isSelected ? 'selected' : '' }}>
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>


                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="inputState" class="form-label">Sales
                                                                            Man</label>

                                                                        <select id="saleMan" name="saleman"
                                                                            class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} saleman"
                                                                            required>
                                                                            @foreach ($saleMans as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    @php
$isSelected = old('saleman') == $key || $sale_Order->pluck('saleman')->contains($key); @endphp
                                                                                    {{ $isSelected ? 'selected' : '' }}>
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Belt</label>

                                                                        <select id="sector" name="sector"
                                                                            class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} sector"
                                                                            required>
                                                                            @foreach ($sectors as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    @php
$isSelected = old('belt') == $key || $sale_Order->pluck('belt')->contains($key); @endphp
                                                                                    {{ $isSelected ? 'selected' : '' }}>
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Area
                                                                        </label>

                                                                        <select id="area" name="area"
                                                                            class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} area"
                                                                            required>
                                                                            @foreach ($areas as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    @php
$isSelected = old('area') == $key || $sale_Order->pluck('area')->contains($key); @endphp
                                                                                    {{ $isSelected ? 'selected' : '' }}>
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Delivered To</label>


                                                                        <select id="delivered_to" name="delivered_to"
                                                                            class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} delivered_to"
                                                                            >
                                                                            <option value="">Same As Party</option>
                                                                            @foreach ($deliveredToParties as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    @php
$isSelected = old('delivered_to') == $key || $sale_Order->pluck('delivered_to')->contains($key); @endphp
                                                                                    {{ $isSelected ? 'selected' : '' }}>
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="transporter"
                                                                            class="form-label">Transporter</label>
                                                                        <select id="transporter" type="text"
                                                                            name="transporter_id"
                                                                            placeholder="Please Select Transporter"
                                                                            class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} transporter"
                                                                            required>
                                                                            <option value="">Select
                                                                            </option>
                                                                            @foreach ($dropDownData['transporters'] as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    {{ (old('transporter_id') == $key ? 'selected' : '') || (!empty($note->transporter_id) ? collect($note->transporter_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        @error('transporter_id')
                                                                            <span style="color:red".
                                                                                class="invalid-feedback">
                                                                                <strong>{{ $message }}</strong>
                                                                            </span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Vehicle No</label>
                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="Vehicle No:" id="vehicle_no"
                                                                            name="vehicle_no">
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Bilty No</label>
                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="bility No:" id="bility_no"
                                                                            name="bility_no">
                                                                    </div>

                                                                </div>
                                                                <br>
                                                                <div class="row">


                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Driver Name</label>
                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="Driver Name:"
                                                                            id="driver_name" name="driver_name">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Carriage</label>
                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="Carriage" id="carriage"
                                                                            name="carriage">
                                                                    </div>
                                                                </div>
                                                                <br>

                                                                <div class="tab-content" id="pills-tabContent">
                                                                    <div class="invoice-detail-items"
                                                                        style="padding: 0px 0px 0px 0px !important;">

                                                                        <div class="table-responsive">

                                                                            <table class="table item-table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="">
                                                                                        </th>
                                                                                        <th>
                                                                                        </th>
                                                                                        <th>Product</th>
                                                                                        <th class="">
                                                                                            P.T</th>
                                                                                        <th class="">
                                                                                            M.T</th>
                                                                                        <th class="">
                                                                                            Quantity</th>
                                                                                        <th class="">
                                                                                            Dzn</th>
                                                                                        <th class="">
                                                                                            Tot Dzns</th>
                                                                                        <th class=""
                                                                                            style="width: 25%;">Remarks
                                                                                        </th>


                                                                                    </tr>
                                                                                    <tr aria-hidden="true"
                                                                                        class="mt-3 d-block table-row-hidden">
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($saleOrderDetails as $saleOrderDetail)
                                                                                        @php
                                                                                            $index = $loop->index + 2; // Starts from 2
                                                                                        @endphp
                                                                                        <tr>
                                                                                            <td
                                                                                                class="delete-item-row">
                                                                                                <ul
                                                                                                    class="table-controls">
                                                                                                    <li><a href="javascript:void(0);"
                                                                                                            class="delete-item"
                                                                                                            data-toggle="tooltip"
                                                                                                            data-placement="top"
                                                                                                            title=""
                                                                                                            data-original-title="Delete"><svg
                                                                                                                xmlns="http://www.w3.org/2000/svg"
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
                                                                                                            </svg></a>
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
                                                                                            <td class="product">
                                                                                                <select id="product_id"
                                                                                                    name="product_id[]"
                                                                                                    class="mb-3 form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} product_{{ $index }}">
                                                                                                    <option
                                                                                                        selected="">
                                                                                                        Please select
                                                                                                        the
                                                                                                        product</option>
                                                                                                    @foreach ($products as $key => $value)
                                                                                                        <option
                                                                                                            value="{{ $key }}"
                                                                                                            {{ (old('product_id') == $key ? 'selected' : '') || (!empty($saleOrderDetail->product_id) ? collect($saleOrderDetail->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                            {{ $value }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </td>
                                                                                            <td class="packing">
                                                                                                <input type="text"
                                                                                                    style="color: black;"
                                                                                                    class="packing form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  packing_{{ $index }}"
                                                                                                    id="packing"
                                                                                                    value="{{ $saleOrderDetail->packing_type }}"
                                                                                                    name="packing_type[]"
                                                                                                    placeholder="P.T"
                                                                                                    readonly>
                                                                                            </td>
                                                                                            <td class="measurement">
                                                                                                <input type="text"
                                                                                                    style="color: black;"
                                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_{{ $index }}"
                                                                                                    placeholder="M.T"
                                                                                                    value="{{ $saleOrderDetail->measurement_type }}"
                                                                                                    name="measurement_type[]"
                                                                                                    id="measurement"
                                                                                                    readonly>
                                                                                            </td>


                                                                                            <td
                                                                                                class="text-right unit">
                                                                                                <input id="quantity"
                                                                                                    type="number"
                                                                                                    name="quantity[]"
                                                                                                    value="{{ old('quantity', !empty($saleOrderDetail->quantity) ? $saleOrderDetail->quantity : '') }}"
                                                                                                    placeholder="Quantity.... "
                                                                                                    class="qty form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_{{ $index }}">
                                                                                            </td>
                                                                                            <td
                                                                                                class="text-right unit">
                                                                                                <input id="dzn"
                                                                                                    type="text"
                                                                                                    name="dzn[]"
                                                                                                    value="{{ old('dzn', !empty($saleOrderDetail->dzn) ? $saleOrderDetail->dzn : '') }}"
                                                                                                    placeholder="Dzn.... "
                                                                                                    class="dzn form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} dzn_{{ $index }}">
                                                                                            </td>
                                                                                            <td
                                                                                                class="text-right unit">
                                                                                                <input id="total_dzn"
                                                                                                    type="text"
                                                                                                    style="color: black;"
                                                                                                    name="total_dzn[]"
                                                                                                    value="{{ old('total_dzn', !empty($saleOrderDetail->total_dzn) ? $saleOrderDetail->total_dzn : '') }}"
                                                                                                    placeholder="Tot. Dzn.... "
                                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} totalDzn_{{ $index }}"
                                                                                                    readonly>
                                                                                            </td>

                                                                                            <td class="unit">
                                                                                                <textarea id="unit" type="text" name="remarks[]" placeholder="Please Enter Remarks "
                                                                                                    class="mt-0 form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} remarks_{{ $index }}"></textarea>
                                                                                            </td>

                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>

                                                                        <a href="javascript:void(0)"
                                                                            class="btn btn-dark additem">Add
                                                                            Item</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-12" style="float: right;">
                                                                    <div style="width: 40%; float: right;">
                                                                        <label for="client-phone">Tot.
                                                                            Boray</label>
                                                                        <input type="text" style="color: black;"
                                                                            value="{{ old('total_boray', !empty($sale_Order->total_boray) ? $sale_Order->total_boray : '') }}"
                                                                            id="boray-amount" name="total_boray"
                                                                            class="quantity-amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} boray-amount"
                                                                            placeholder="Tot.Boray" readonly>

                                                                        <label for="client-phone">Tot.
                                                                            Carton</label>
                                                                        <input type="text" style="color: black;"
                                                                            value="{{ old('total_carton', !empty($sale_Order->total_carton) ? $sale_Order->total_carton : '') }}"
                                                                            id="carton-amount" name="total_carton"
                                                                            class="quantity-amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} carton-amount"
                                                                            placeholder="Tot.Carton" readonly>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>

                                                        @if (!empty($note))
                                                            <div class="col-lg-0 col-12 form-group mb-4">
                                                                <div class="multiple-file-upload mt-5">
                                                                    <label for="invoice-detail-notes"
                                                                        class="col-sm-12 col-form-label col-form-label-sm">Upload
                                                                        Images</label>
                                                                    <input type="file"
                                                                        class="filepond file-upload-multiple form-control"
                                                                        name="images[]" multiple
                                                                        data-allow-reorder="true"
                                                                        data-max-file-size="3MB" data-max-files="3">
                                                                </div>
                                                                <div class="media">
                                                                    <div class="avatar me-2"
                                                                        style="width: 10rem; height: 10rem;">


                                                                        @if ($note == null)
                                                                            <img alt="avatar"
                                                                                src="{{ asset('images/no-attachments.png') }}"
                                                                                class="rounded-circle" />
                                                                        @else
                                                                            @foreach ($note as $image)
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
                                                                    name="images[]" multiple data-allow-reorder="true"
                                                                    data-max-file-size="3MB" data-max-files="3">
                                                            </div>
                                                        @endif

                                                        <div class="invoice-detail-terms">

                                                            <div class="row">
                                                                <div class="col-xl-12 ">
                                                                    <a href="{{ route('dispatch-note.list') }}"
                                                                        style="float:right;"
                                                                        class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                                    <button type="submit" style="float: right"
                                                                        class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 mr-5"
                                                                        data-bs-container="body"
                                                                        data-bs-placement="right"
                                                                        data-bs-content="Tooltip on right">
                                                                        @if (!isset($note))
                                                                            Save
                                                                        @else
                                                                            Update
                                                                        @endif
                                                                    </button>
                                                                    {{-- @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)

                                                                        @endif --}}
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
    <script src="{{ asset('js/dispatchNote.js') }}"></script>

    <script>
        //     let getTableElement = document.querySelector('.item-table');
        //     let currentIndex = getTableElement.rows.length;

        //     $('.dzn').on("focusout", function() {
        //         var row_id = $(this).closest("tr").find(".row_id").val();
        //         console.log(currentIndex);
        //         let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
        //         let dzns = $(this).closest("tr").find(".dzn_" + row_id).val();
        //         if (parseInt(quantity) > 0) {
        //             $(this).closest("tr").find('.totalDzn_' + row_id).val(quantity * dzns);
        //         } else {
        //             $(this).closest("tr").find(".totalDzn_" + row_id).val('');
        //         }
        //     });
        // });

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
                '<td class="product"><select id="product_id" name="product_id[]" class="form-control select2 custom-select form-control-sm product_' +
                currentIndex +
                '  "><option selected = "" > Please select the product </option> @foreach ($products as $key => $value) <option value = "{{ $key }}"{{ (old('product_id') == $key ? 'selected' : '') || (!empty($note->product_id) ? collect($note->product_id)->contains($key) : '') ? 'selected' : '' }}> {{ $value }}</option> @endforeach < /select> ' +
                '</td> ' +
                '<td class="packing" >' +
                '<input id="packing" style="color: black; " type="text" name="packing_type[]" class = "packing form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  packing_' +
                currentIndex + '" placeholder="P.T" readonly></td>' +
                '<td class="measurement" >' +
                '<input type="text" style="color: black; " placeholder="M.T" id="measurement" name="measurement_type[]" class = "measurement form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_' +
                currentIndex + '" readonly> </td> ' +
                '<td class="text-right unit" >' +
                '<input type="text" name="quantity[]" class="qty form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_' +
                currentIndex + '" placeholder="Quantity.... ">' +
                ' </td>' +
                '<td class="dzn">' +
                '<input id="dzn" type="text" name="dzn[]"  placeholder="Dzn.... "class="dzn form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} dzn_' +
                currentIndex + '"></td>' +
                '<td class="text-right unit"> <input id="total_dzn" style="color: black;" type="text" name="total_dzn[]"  placeholder="Tot. Dzn.... " class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} totalDzn_' +
                currentIndex + '" readonly></td>' +
                '<td class="text-right unit" >' +
                '<textarea type="text" name="remarks[]" class="form-control  {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mt-0 remarks_' +
                currentIndex + '" placeholder="Please Enter remarks "></textarea>' +
                ' </td>' +

                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
                // '<input class="form-check-input inbox-chkbox contact-chkbox" type="checkbox">' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);
            deleteItemRow();
            $('.select2').select2();

            $(document).on('click', 'body *', function() {
                $('.qty_' + currentIndex).on("focusout", function() {
                    updatePackingTotals();
                });
            });
            $(document).ready(function() {
                $('.delete-item').on("click", function() {
                    updatePackingTotals();
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
                $('.dzn, .qty').on("input", function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    let quantity = $(this).closest("tr").find(".qty_" + row_id).val() || 0;
                    let dzns = $(this).closest("tr").find(".dzn_" + row_id).val() || 0;
                    if (parseInt(quantity) > 0) {
                        let total = quantity * dzns || 0;
                        $(this).closest("tr").find(".totalDzn_" + row_id).val(total);
                    } else {
                        $(this).closest("tr").find(".totalDzn_" + row_id).val('');
                    }
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
                            $(".packing_" + currentIndex).val(response.name.name);
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


        })

        deleteItemRow();

        selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
        selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
        selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);
        $('.select2').select2();

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

        $(document).on('click', 'body *', function() {
            $('.dzn_' + currentIndex).on("focusout", function() {
                var row_id = $(this).closest("tr").find(".row_id").val();
                let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
                let dzns = $(this).closest("tr").find(".dzn_" + row_id).val();
                if (parseInt(quantity) > 0) {
                    $(this).closest("tr").find(".totalDzn_" + row_id).val(quantity * dzns);
                } else {
                    $(this).closest("tr").find(".totalDzn_" + row_id).val('');
                }
            });
        });

        function updatePackingTotals() {
            let totalBorayAmount = 0;
            let totalCartonAmount = 0;

            // Loop through all rows to calculate total Boray and Carton quantities
            $(".item-table tbody tr").each(function() {
                // let packingType = $(this).find('.packing').val().toLowerCase();
                let packingType = $('#packing', this).val().toLowerCase();

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

        $(document).ready(function() {
            $('.select2').select2();


            // $(document.body).on("change", ".product", function() {
            //     $('.select2').select2();
            // });
        });
    </script>

    <script>
        var config = {
            routes: {
                saleOrderData: "{{ url('dispatch-note/get-sale-order-data') }}",
                getProductPackingTypeDetail: "{{ url('dispatch-note/get-product-packing-type') }}",
                getProductMeasurementTypeDetail: "{{ url('dispatch-note/get-product-measurement-type') }}",

            },
        }
    </script>
    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>

        {{-- <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script> --}}

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
