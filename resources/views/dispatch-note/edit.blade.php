<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>

        <!--  BEGIN CUSTOM STYLE FILE  -->
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
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($note->id) ? $note->id : '' }}" />
                                                        <div id="results">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-6 mt-3">
                                                                        <label for="">
                                                                            <h4>Dispatch Note Number
                                                                                #:{{ @$currentid }}</h4>
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
                                                                            value="{{ old('sale_order_number', !empty($note->sale_order_number) ? $note->sale_order_number : '') }}"
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
                                                                            value="{{ old('date', !empty($note->date) ? $note->date : '') }}"
                                                                            placeholder="Select Date.." readonly>
                                                                    </div>

                                                                </div>


                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-md-6">

                                                                        <label for="inputState"
                                                                            class="form-label">Party</label>
                                                                        <input id="party_id" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} "
                                                                            type="text" name="party_id"
                                                                             value="{{ old('party_id', !empty($note->party_id) ? $note->party_id : '') }}"
                                                                            placeholder="Select Party.." readonly>


                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Sales
                                                                            Man</label>

                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            id="saleMan" name="saleman"
                                                                            value="{{ old('saleman', !empty($note->saleman) ? $note->saleman : '') }}"
                                                                            placeholder="Sale Man Name..." readonly>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Belt</label>
                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="sector" name="sector"
                                                                            value="{{ old('sector', !empty($note->sector) ? $note->sector : '') }}"
                                                                            readonly>

                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Area
                                                                        </label>
                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="Area" id="area"
                                                                            name="area"
                                                                            value="{{ old('area', !empty($note->area) ? $note->area : '') }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Delivered To</label>
                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            id="delivered_to" name="delivered_to"
                                                                            value="{{ old('delivered_to', !empty($note->delivered_to) ? $note->delivered_to : '') }}"
                                                                            placeholder="Deliverd To" readonly>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Vehicle No</label>
                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="Vehicle No:" id="vehicle_no"
                                                                            value="{{ old('vehicle_no', !empty($note->vehicle_no) ? $note->vehicle_no : '') }}"
                                                                            name="vehicle_no">
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Builty No</label>
                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="bility No:" id="bility_no"
                                                                            value="{{ old('bility_no', !empty($note->bility_no) ? $note->bility_no : '') }}"
                                                                            name="bility_no">
                                                                    </div>


                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Driver Name</label>
                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="Driver Name:" value="{{ old('driver_name', !empty($note->driver_name) ? $note->driver_name : '') }}"
                                                                            id="driver_name" name="driver_name">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Carriage</label>
                                                                        <input type="text" style="color: black; "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="Carriage" id="carriage"
                                                                            value="{{ old('carriage', !empty($note->carriage) ? $note->carriage : '') }}"
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
                                                                                        <th>Product</th>
                                                                                        <th class="">
                                                                                            P.T</th>
                                                                                        <th class="">
                                                                                            M.T</th>
                                                                                        <th class="">
                                                                                            Quantity</th>
                                                                                        <th class="">Remarks</th>


                                                                                    </tr>
                                                                                    <tr aria-hidden="true"
                                                                                        class="mt-3 d-block table-row-hidden">
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>

                                                                                    @if (!empty($dispatchNotes))
                                                                                        @foreach ($dispatchNotes as $dispatchNote)
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
                                                                                                <td class="product">
                                                                                                    <select
                                                                                                        id="product_id"
                                                                                                        name="product_id[]"
                                                                                                        class="mb-3 form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} product_2">
                                                                                                        <option
                                                                                                            selected="">
                                                                                                            Please
                                                                                                            select
                                                                                                            the
                                                                                                            product
                                                                                                        </option>
                                                                                                        @foreach ($dropDownData['products'] as $key => $value)
                                                                                                            <option
                                                                                                                value="{{ $key }}"
                                                                                                                {{ (old('product_id') == $key ? 'selected' : '') || (!empty($dispatchNote->product_id) ? collect($dispatchNote->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                                {{ $value }}
                                                                                                            </option>
                                                                                                        @endforeach
                                                                                                    </select>
                                                                                                </td>
                                                                                                <td class="packing">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        style="color: black;"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_2"
                                                                                                        id="packing"
                                                                                                        name="packing_type[]"
                                                                                                        value="{{ old('packing_type', !empty($dispatchNote->packing_type) ? $dispatchNote->packing_type : '') }}"
                                                                                                        placeholder="P.T"
                                                                                                        readonly>
                                                                                                </td>
                                                                                                <td
                                                                                                    class="measurement">
                                                                                                    <input
                                                                                                        type="text"
                                                                                                        style="color: black;"
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_2"
                                                                                                        placeholder="M.T"
                                                                                                        name="measurement_type[]"
                                                                                                        id="measurement"
                                                                                                        value="{{ old('measurement_type', !empty($dispatchNote->measurement_type) ? $dispatchNote->measurement_type : '') }}"
                                                                                                        readonly>
                                                                                                </td>


                                                                                                <td
                                                                                                    class="text-right unit">
                                                                                                    <input
                                                                                                        id="quantity"
                                                                                                        type="number"
                                                                                                        name="quantity[]"
                                                                                                        value="{{ old('quantity', !empty($dispatchNote->quantity) ? $dispatchNote->quantity : '') }}"
                                                                                                        placeholder="Quantity.... "
                                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty2">
                                                                                                </td>

                                                                                                <td class="unit">
                                                                                                    <textarea id="unit" type="text" name="remarks[]" placeholder="Please Enter Remarks "
                                                                                                        class="mt-0 form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} remarks_2">{{ @$dispatchNote->remarks }}</textarea>
                                                                                                </td>

                                                                                            </tr>
                                                                                        @endforeach

                                                                                    @endif
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
                                                                            value="{{ old('total_boray', !empty($note->total_boray) ? $note->total_boray : '') }}"
                                                                            id="boray-amount" name="total_boray"
                                                                            class="quantity-amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="Tot.Boray">

                                                                        <label for="client-phone">Tot.
                                                                            Carton</label>
                                                                        <input type="text" style="color: black;"
                                                                            value="{{ old('total_carton', !empty($note->total_carton) ? $note->total_carton : '') }}"
                                                                            id="carton-amount" name="total_carton"
                                                                            class="quantity-amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            placeholder="Tot.Carton">
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>
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
                '<td class="product"><select id="product_id" name="product_id[]" class="form-control select2 custom-select form-control-sm product_' +
                currentIndex +
                '  "><option selected = "" > Please select the product </option> @foreach ($dropDownData['products'] as $key => $value) <option value = "{{ $key }}"{{ (old('product_id') == $key ? 'selected' : '') || (!empty($dispatchNote->product_id) ? collect($dispatchNote->product_id)->contains($key) : '') ? 'selected' : '' }}> {{ $value }}</option> @endforeach < /select> ' +
                '</td> ' +
                '<td class="packing" >' +
                '<input id="packing" style="color: black; " type="text" name="packing_type[]" value="{{ old('packing_type', !empty($dispatchNote->packing_type) ? $dispatchNote->packing_type : '') }}" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  packing_' +
                currentIndex + '" placeholder="P.T" readonly></td>' +
                '<td class="measurement" >' +
                '<input type="text" style="color: black; " placeholder="M.T" id="measurement" name="measurement_type[]" value="{{ old('measurement_type', !empty($dispatchNote->measurement_type) ? $dispatchNote->measurement_type : '') }}" class = "measurement form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_' +
                currentIndex + '" readonly> </td> ' +
                '<td class="text-right unit" >' +
                '<input type="text" name="quantity[]" value="{{ old('quantity', !empty($dispatchNote->quantity) ? $dispatchNote->quantity : '') }}" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_' +
                currentIndex + '" placeholder="Quantity.... ">' +
                ' </td>' +
                '<td class="text-right unit" >' +
                '<textarea type="text" name="remarks[]" class="form-control  {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mt-0 remarks_' +
                currentIndex + '" placeholder="Please Enter remarks ">{{ @$dispatchNote->remarks }}</textarea>' +
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

            $(document).ready(function() {
                $(".product_" + currentIndex).on('change', function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    var productSelected = '.product_' + currentIndex;
                    console.log(productSelected);
                    var name = $(productSelected + ' :selected').text();
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
            $(document).ready(function() {
                $(".product_" + currentIndex).on('change', function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    var productSelected = '.product_' + currentIndex;
                    var name = $(productSelected + ' :selected').text();
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
            $(document).on('click', 'body *', function() {
                $('.qty').on("focusout", function() {
                    doAmountTotal2();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal2();
                });

                function doAmountTotal2() {
                    $('#boray-amount').val("");
                    $('#carton-amount').val("");
                    var totalBorayAmount = 0;
                    console.log(totalBorayAmount);
                    var totalCartonAmount = 0;
                    console.log(totalCartonAmount);
                    var packingIndex = '.packing_' + currentIndex;

                    $(".qty").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            var totalAmount = parseFloat(this.value);

                            if (packingIndex.value === "Boray") {
                                // console.log(packingType.value== Boray);
                                totalBorayAmount += totalAmount;
                            } else if (packingIndex.value === "Carton") {
                                // console.log(packingType.value== Carton);
                                totalCartonAmount += totalAmount;
                            }

                        }
                    });

                    // if (document.getElementById('packing').value == "Carton") {
                    //     $('#carton-amount').val(totalAmount.toFixed(2));
                    // }
                    // if (document.getElementsByClassName('packing').value == "Boray") {
                    //     $('#boray-amount').val(totalAmount.toFixed(2));
                    // }

                    // Assign calculated total amounts to their respective fields
                    if (packingIndex.value === "Carton") {
                        $('#carton-amount').val(totalCartonAmount.toFixed(2));
                    }

                    if (packingIndex.value === "Boray") {
                        $('#boray-amount').val(totalBorayAmount.toFixed(2));
                    }
                }
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
        <script src="{{ asset('js/dispatchNote.js') }}"></script>

        {{-- <script src="{{ asset('plugins/global/vendors.min.js') }}"></script> --}}
        @vite(['resources/assets/js/elements/custom-search.js'])
    </x-slot>

</x-base-layout>
