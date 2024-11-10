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
                                                                                #:{{ @$maxid }}
                                                                                {{ @$currentid }}</h4>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="inputState" class="form-label">Sale
                                                                            Order#</label>
                                                                        <input type="text" id="saleOrder"
                                                                            name="sale_order_number"
                                                                            value="{{ old('sale_order_number', !empty($note->sale_order_number) ? $note->sale_order_number : '') }}"
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} saleOrder"
                                                                            placeholder="Please Enter The SO Number"
                                                                            aria-label="Please Enter The SO Number">
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label for="inputState"
                                                                            class="form-label">Date</label>
                                                                        {{-- <input type="text" id="date" name="date" value="{{ old('date', !empty($note->date) ? $note->date : '') }}"
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} flatpickr flatpickr-input"
                                                                        placeholder="date" aria-label="date" > --}}

                                                                        <input id="date" name="date"
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} date flatpickr flatpickr-input"
                                                                            type="text" data-date-format="d-m-Y"
                                                                            value="{{ empty($note->date) ? null : \Illuminate\Support\Carbon::parse($note->date)->format('d-m-Y') }}"
                                                                            {{-- value="{{ old('date', !empty($note->date) ? $note->date : '') }}" --}}
                                                                            placeholder="Select Date.." readonly>
                                                                    </div>

                                                                </div>


                                                                <br>
                                                                <div class="row">
                                                                    <div class="col-md-6">

                                                                        <label for="inputState"
                                                                            class="form-label">Party</label>
                                                                        <select id="party_id" name="party_id"
                                                                            class="form-select select2 mb-3 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                            <option selected="">Please select the
                                                                                Party</option>
                                                                            @foreach ($dropDownData['parties'] as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    {{ (old('party_id') == $key ? 'selected' : '') || (!empty($note->party_id) ? collect($note->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                    {{ $value }}</option>
                                                                            @endforeach
                                                                        </select>


                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="inputState" class="form-label">Sales
                                                                            Man</label>

                                                                        <input type="text"
                                                                            style="color: black; background-color: white;"
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            id="saleMan"
                                                                            placeholder="Sale Man Name..." readonly>
                                                                        {{-- <select id="sale_man_id" name="sale_man_id"
                                                                        class="form-select select2 mb-3 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                        <option selected="">Please select the
                                                                            product</option>
                                                                        @foreach ($dropDownData['saleMans'] as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ (old('sale_man_id') == $key ? 'selected' : '') || (!empty($note->sale_man_id) ? collect($note->sale_man_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select> --}}
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                {{-- <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="inputState"
                                                                        class="form-label">Transporter</label>
                                                                    <select id="transporter_id" name="transporter_id"
                                                                        class="form-select select2 mb-3 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                        <option selected="">Please select the
                                                                            Transporter</option>
                                                                        @foreach ($dropDownData['transporters'] as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ (old('transporter_id') == $key ? 'selected' : '') || (!empty($note->transporter_id) ? collect($note->transporter_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="inputState" class="form-label">Driver
                                                                        Contact
                                                                        Number</label>
                                                                    <input type="text"
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                        placeholder="contact_no" id="contact_no"
                                                                        name="contact_no"
                                                                        value="{{ old('contact_no', !empty($note->contact_no) ? $note->contact_no : '') }}"
                                                                        aria-label="Enter The Contact Number">
                                                                </div>
                                                            </div>
                                                            <br>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="inputState" class="form-label">Bilty
                                                                        No</label>
                                                                    <input type="text"
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                        id="bilty_no" name="bilty_no"
                                                                        value="{{ old('bilty_no', !empty($note->bilty_no) ? $note->bilty_no : '') }}"
                                                                        placeholder="bilty_no" aria-label="bilty_no">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="inputState"
                                                                        class="form-label">Fare</label>
                                                                    <input type="text"
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                        placeholder="Fare" id="Fare"
                                                                        value="{{ old('fare', !empty($note->fare) ? $note->fare : '') }}"
                                                                        name="fare" aria-label="fare">
                                                                </div> --}}

                                                                {{-- <div class="col-md-4">
                                                                    <label for="inputState" class="form-label">Total
                                                                        Amount</label>
                                                                    <input type="text" class="form-control"
                                                                        id="total_amount" name="total_amount"
                                                                        placeholder="Total Amount"
                                                                        aria-label="Total Amount" readonly>
                                                                </div>
                                                            </div> --}}
                                                                <br>

                                                                <div class="tab-content" id="pills-tabContent">
                                                                    <div class="invoice-detail-items">

                                                                        <div class="table-responsive">

                                                                            <table class="table item-table">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="">
                                                                                        </th>
                                                                                        <th>Product</th>
                                                                                        <th class="">
                                                                                            Quantity</th>
                                                                                        <th class="">
                                                                                            Dzns</th>
                                                                                        <th class="">
                                                                                            Tot. Dzns</th>
                                                                                        <th class="">Remarks</th>


                                                                                    </tr>
                                                                                    <tr aria-hidden="true"
                                                                                        class="mt-3 d-block table-row-hidden">
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($dispatchNotes as $note)
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
                                                                                                <select id="product_id"
                                                                                                    name="product_id[]"
                                                                                                    class="mb-3 form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                                                    <option
                                                                                                        selected="">
                                                                                                        Please select
                                                                                                        the
                                                                                                        product</option>
                                                                                                    @foreach ($dropDownData['products'] as $key => $value)
                                                                                                        <option
                                                                                                            value="{{ $key }}"
                                                                                                            {{ (old('product_id') == $key ? 'selected' : '') || (!empty($note->product_id) ? collect($note->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                            {{ $value }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>

                                                                                                <input type="text"
                                                                                                    style="color: black;"
                                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_2"
                                                                                                    id="packing"
                                                                                                    placeholder="P.T"
                                                                                                    readonly>

                                                                                                <input type="text"
                                                                                                    style="color: black;"
                                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_2"
                                                                                                    placeholder="M.T"
                                                                                                    id="measurement"
                                                                                                    readonly>
                                                                                            </td>

                                                                                            <td class="quantity">
                                                                                                <input type="text"
                                                                                                    id="quantity"
                                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_2"
                                                                                                    value="{{ old('quantity', !empty($saleOrder->quantity) ? $saleOrder->quantity : '') }}"
                                                                                                    name="quantity[]"
                                                                                                    placeholder="Qty">
                                                                                            </td>

                                                                                            <td class="total_unit">
                                                                                                <input type="text"
                                                                                                    id="dzn"
                                                                                                    name="dzn[]"
                                                                                                    value="{{ old('dzn', !empty($saleOrder->dzn) ? $saleOrder->dzn : '') }}"
                                                                                                    class="dozen form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} dozen_2"
                                                                                                    placeholder="Dzn">
                                                                                            </td>
                                                                                            <td class="total_dzns">
                                                                                                <input type="text"
                                                                                                    id="total_dzns"
                                                                                                    style="color: black;"
                                                                                                    name="total_dzn[]"
                                                                                                    value="{{ old('total_dzn', !empty($saleOrder->total_dzn) ? $saleOrder->total_dzn : '') }}"
                                                                                                    class="totDzn form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} totDzn_2"
                                                                                                    placeholder="Tot.Dzns"
                                                                                                    readonly>
                                                                                            </td>
                                                                                            <td class="unit">
                                                                                                <textarea id="unit" type="text" name="remarks[]" placeholder="Please Enter Remarks "
                                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mt-0">{{ @$note->remarks }}</textarea>
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
                                                                {{-- <div class="col-xl-6 invoice-address-client invoice-detail-total mt-3"
                                                                    style="float:right">
                                                                    <div class="invoice-address-client-fields">
                                                                        <div class="form-group row">
                                                                            <label for="gross-amount"
                                                                                class="col-sm-4 col-form-label col-form-label-sm ">Tot.
                                                                                Quantity
                                                                            </label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text"
                                                                                    id="quantity-amount"
                                                                                    style="color: black;"
                                                                                    class="quantity-amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  "
                                                                                    id="quantity-amount"
                                                                                    placeholder="Total Quantity"
                                                                                    readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for="unit-amount"
                                                                                class="col-sm-4 col-form-label col-form-label-sm ">Tot.
                                                                                Units
                                                                            </label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="unit-amount"
                                                                                    style="color: black;"
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unit-amount "
                                                                                    id="unit-amount"
                                                                                    placeholder="Total Unit" readonly>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div> --}}
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
        $(document).ready(function() {
            $('.saleOrder').on('keypress', function(e) {
                if (e.which === 13) { // Enter key pressed
                    e.preventDefault(); // Prevent default form submission
                    fetchData();
                }
            });

            function fetchData() {
                var name = $('#saleOrder').val();
                let url = config.routes.saleOrderData + '/' + name;
                // $.ajax({
                //     url: url,
                //     method: 'GET',
                //     data: { saleOrder: saleOrdersData },
                //     headers: {
                //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //     },
                //     success: function(data) {
                //         displayData(data);
                //     },
                //     error: function(xhr) {
                //         console.error('Error fetching data:', xhr);
                //     }
                // });

                $.ajax({
                    url: url,
                    method: 'GET',

                    success: function(orderMasterData) {
                        // $('#results').empty(); // Clear previous results
                        $('#date').val(orderMasterData.date);
                        $('#party_id').val(orderMasterData.party_id);
                        // $('#date').val(orderMasterData.date);
                        // Display master data
                        // $('#results').append('<div> ' + orderMasterData.date + '</div>');
                        // $('#results').append('<div>Description: ' + master.description + '</div>');

                        // Display detail data
                        // $('#results').append('<h4>Details:</h4>');
                        // master.details.forEach(function(detail) {
                        // $('#results').append('<div>Detail: ' + detail.detail_name +
                        //     '</div>'); // Adjust to match your detail field
                        // });
                    },
                    // error: function(xhr, status, error) {
                    //     $('#results').empty();
                    //     $('#results').alert('No data found');
                    // }
                    error: function(errorThrown) {
                        $('#message').val('');
                        var errors = errorThrown.responseJSON.errors;
                        Swal.fire({
                            icon: 'error',
                            title: 'No data found',
                        })
                    }
                });


                // $.ajax({
                //     url: url,
                //     type: 'GET',
                //     headers: {
                //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //     },
                //     success: function(response) {
                //         // $(this).closest("tr").find(".packing" + row_id).val(response.name.name);
                //         $(".packing_" + row_id).val(response.name.name);
                //     },
                //     complete: function() {
                //         $('#loading').css('display', 'none');
                //     },
                //     error: function(errorThrown) {
                //         $('').val('');
                //         var errors = errorThrown.responseJSON.errors;
                //         Swal.fire({
                //             icon: 'error',
                //             title: 'Something went wrong',
                //         })
                //     }
                // })
            }

            function displayData(data) {
                const container = $('#dataContainer');
                container.empty(); // Clear previous data
                data.forEach(item => {
                    container.append(`<p>${item.name}</p>`); // Adjust according to your data structure
                });
            }
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
                '<td class="product"><select id="product_id" name="product_id[]" class="form-control select2 custom-select form-control-sm"><option selected = "" > Please select the product </option> @foreach ($dropDownData['products'] as $key => $value) <option value = "{{ $key }}"{{ (old('product_id') == $key ? 'selected' : '') || (!empty($note->product_id) ? collect($note->product_id)->contains($key) : '') ? 'selected' : '' }}> {{ $value }}</option> @endforeach < /select> <input id="packing" style="color: black; " type="text" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} " packing_' +
                currentIndex +
                '" placeholder="P.T" readonly><input type="text" style="color: black; " placeholder="M.T" id="measurement" class = "measurement form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_' +
                currentIndex + '" readonly> </td> ' +
                '<td class="qty">' +
                ' <input type="text" name="quantity[]" id="quantity" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_' +
                currentIndex +
                '" placeholder="Qty"></td>' +
                // '<td class="total_unit"> </td>' +
                // '<td class="total"></td>' +
                '<td class="dozen"> <input type="text" name="dzn[]" id="dzn" class = "dozen form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} dozen_' +
                currentIndex +
                ' "  placeholder="Dzns "></td>' +
                '<td class="totDzn"><input type="text" style="color: black;" name="total_dzn[]" class="totDzn form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} totDzn_' +
                currentIndex + ' " placeholder="Tot Dzn" readonly></td>' +
                '<td class="unit" >' +
                '<textarea type="text" name="remarks[]" class="form-control  {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mt-0" placeholder="Please Enter remarks "></textarea>' +
                ' </td>' +
                // '<td class="text-right amount"><span class="editable-amount"><span class="currency"></span> <span class="amount">0.00</span></td>' +
                // '<td class="text-right rate"><input type="text" class="form-control  {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" placeholder="Rate"></td>'+
                // '<td class="text-right amount"><span class="editable-amount"><span class="currency"></span> <span class="amount">0.00</span></td>' +
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
                $('.dozen').on("focusout", function() {
                    //
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    let quantity = $(this).closest("tr").find(".qty_" + row_id).val()
                    let dzns = $(this).closest("tr").find(".dozen_" + row_id).val();
                    if (parseInt(quantity) > 0) {

                        $(this).closest("tr").find(".totDzn_" + row_id).val(quantity * dzns);
                    } else {
                        $(this).closest("tr").find(".totDzn_" + row_id).val('');
                    }
                    doAmountTotal();
                });


                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#total-amount').text("");
                    // console.log('in do amount total');
                    var totalAmount = 0;
                    $(".amount").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    $('#gross-amount').val(totalAmount.toFixed(2));
                    $('#net-amount').val(totalAmount.toFixed(2));
                }

                $("#freight, #scheme, #commission").on("focusout", function() {
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
                $('.amount').on("focusout", function() {
                    doAmountTotal();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#total-amount').text("");
                    // console.log('in do amount total');
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
                $('.amount').on("focusout", function() {
                    doAmountTotal();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#total-amount').text("");
                    // console.log('in do amount total');
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
                saleOrderData: "{{ url('dispatch-note/get-sale-order-data') }}"

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
        {{-- <script src="{{ asset('js/dispatchNote.js') }}"></script> --}}

        {{-- <script src="{{ asset('plugins/global/vendors.min.js') }}"></script> --}}
        @vite(['resources/assets/js/elements/custom-search.js'])
    </x-slot>

</x-base-layout>
