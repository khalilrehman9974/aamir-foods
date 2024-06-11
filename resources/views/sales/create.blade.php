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


        <!--  END CUSTOM STYLE FILE  -->
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
                            <h4>Add Sale</h4>
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
                                                        <input type="hidden" name="saleId" id="saleId"
                                                            value="{{ isset($sale->id) ? $sale->id : '' }}" />


                                                        {{-- <div class="invoice-detail-terms">

                                                            <div class="row justify-content-between">

                                                                <div class="col-md-3">

                                                                    <div class="form-group mb-4">
                                                                        <label for="number">Dispatch Note#</label>
                                                                        <input type="text"
                                                                            class="form-control form-control-sm"
                                                                            id="number" placeholder="">
                                                                    </div>

                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-4">
                                                                        <label for="date">Invoice Date</label>
                                                                        <input type="text"
                                                                            class="form-control flatpickr-input {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            id="date" placeholder="Add date picker"
                                                                            value="{{ $invoiceNo }}"
                                                                            readonly="readonly">
                                                                    </div>

                                                                </div>

                                                                <div class="col-md-3">
                                                                    <div class="form-group mb-4">
                                                                        <label for="due">Due Date</label>
                                                                        <input type="text"
                                                                            class="form-control form-control-sm flatpickr-input"
                                                                            id="due" placeholder="None"
                                                                            readonly="readonly">
                                                                    </div>

                                                                </div>

                                                            </div>

                                                        </div> --}}

                                                        <div class="form-group">
                                                            <div class="row  invoice-content">
                                                                <div class="col-lg-0 col-4">
                                                                    <label for="dispatch_note"
                                                                        class="form-label">Invoice# </label>
                                                                    <input id="invoice_no" type="text"
                                                                        name="invoice_no" value="{{ $invoiceNo }}"
                                                                        class="form-control form-control-sm" readonly>
                                                                </div>

                                                                <div class="col-lg-0 col-4">
                                                                    <label for="dispatch_note"
                                                                        class="form-label">Dispatch Note# </label>
                                                                    <input id="dispatch_note" type="dispatch_note"
                                                                        name="dispatch_note"
                                                                        value="{{ old('party_id', !empty($sale->party_id) ? $sale->party_id : '') }}"
                                                                        class="form-control form-control-sm">
                                                                </div>

                                                                <div class="col-lg-0 col-4" style="float: right">
                                                                    <label for="date">
                                                                        Date</label>
                                                                    <input type="text"
                                                                        class="form-control form-control-sm"
                                                                        id="date" placeholder="Select The Date">
                                                                </div>

                                                                <div class="row">

                                                                    <div class="col-lg-0 col-6 ">
                                                                        <label for="party_id"
                                                                            class="form-label  select2">Party</label>
                                                                        <select id="party_id" type="text"
                                                                            name="party_id"
                                                                            placeholder="Please Select the Party Name"
                                                                            class="form-control select2 mb-3 custom-select form-control-sm"
                                                                            required>
                                                                            <option value="">Select</option>
                                                                            @foreach ($dropDownData['parties'] as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    {{ (old('party_id') == $key ? 'selected' : '') || (!empty($sale->party_id) ? collect($sale->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                    {{ $value }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="invalid-feedback">
                                                                            Please Select the Sector.
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-0 col-6 ">
                                                                        <label for="saleman_id"
                                                                            class="form-label">Sale
                                                                            Man</label>
                                                                        <select id="saleman_id" type="text"
                                                                            name="saleman_id"
                                                                            value="{{ old('saleman_id', !empty($sale->saleman_id) ? $sale->saleman_id : '') }}"
                                                                            placeholder="Please Select the Sale Man"
                                                                            class="form-control select2 form-control-sm"
                                                                            required>
                                                                            <option value="">Select</option>
                                                                            @foreach ($dropDownData['saleMans'] as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    {{ (old('saleman_id') == $key ? 'selected' : '') || (!empty($sale->saleman_id) ? collect($sale->saleman_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                    {{ $value }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        {{-- <div class="invalid-feedback">
                                                                                        Please Select the Sector.
                                                                                    </div> --}}
                                                                    </div>
                                                                </div>
                                                                <div class="row">

                                                                    <div class="col-lg-0 col-6">
                                                                        <label for="bilty_no" class="form-label">Bilty
                                                                            Number </label>
                                                                        <input id="bilty_no" type="bilty_no"
                                                                            name="bilty_no"
                                                                            value="{{ old('bilty_no', !empty($sale->bilty_no) ? $sale->bilty_no : '') }}"
                                                                            placeholder="Please Enter the Bilty No "
                                                                            class="form-control form-control-sm"
                                                                            required>
                                                                    </div>
                                                                    <div class="col-lg-0 col-6 ">
                                                                        <label for="transporter_id"
                                                                            class="form-label">Transporter</label>
                                                                        <select id="transporter_id" type="text"
                                                                            name="transporter_id"
                                                                            value="{{ old('transporter_id', !empty($sale->transporter_id) ? $sale->transporter_id : '') }}"
                                                                            placeholder="Please Select the Transporter"
                                                                            class="form-control select2 form-control mb-3 custom-select form-control-sm"
                                                                            required>
                                                                            <option value="">Select</option>
                                                                            @foreach ($dropDownData['transporters'] as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    {{ (old('transporter_id') == $key ? 'selected' : '') || (!empty($sale->transporter_id) ? collect($sale->transporter_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                    {{ $value }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        {{-- <div class="invalid-feedback">
                                                                                        Please Select the Sector.
                                                                                    </div> --}}
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-0 col-12 ">
                                                                    <label for="delivered_to"
                                                                        class="form-label">Delivered
                                                                        To</label>
                                                                    <input id="delivered_to" type="text"
                                                                        name="delivered_to"
                                                                        value="{{ old('delivered_to', !empty($sale->delivered_to) ? $sale->delivered_to : '') }}"
                                                                        placeholder="Delivered to.. "
                                                                        class="form-control form-control-sm" required>
                                                                </div>
                                                            </div>

                                                            <div class="tab-content" id="pills-tabContent">
                                                                <div class="invoice-detail-items">

                                                                    <div class="table-responsive">
                                                                        <table class="table item-table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th class="">
                                                                                    </th>
                                                                                    <th>Product</th>
                                                                                    <th class="">Qty</th>
                                                                                    <th class="">Unit</th>
                                                                                    <th class="">Total Unit</th>
                                                                                    <th class="">Rate</th>
                                                                                    <th class="text-right">
                                                                                        Amount
                                                                                    </th>

                                                                                </tr>
                                                                                <tr aria-hidden="true"
                                                                                    class="mt-3 d-block table-row-hidden">
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach ($saleDetails as $sale)
                                                                                    <tr>
                                                                                        <td class="delete-item-row">
                                                                                            <ul class="table-controls">
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
                                                                                        <td class="description">
                                                                                            <select id="product_id"
                                                                                                type="text"
                                                                                                name="product_id[]"
                                                                                                class="form-control select2 mb-3 custom-select form-control-sm">
                                                                                                <option value="">
                                                                                                    Select</option>
                                                                                                @foreach ($dropDownData['products'] as $key => $value)
                                                                                                    <option
                                                                                                        value="{{ $key }}"
                                                                                                        {{ (old('product_id') == $key ? 'selected' : '') || (!empty($sale->product_id) ? collect($sale->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                        {{ $value }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>

                                                                                        </td>

                                                                                        <td class="text-right qty">
                                                                                            <input type="text"
                                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                                value="{{ old('quantity', !empty($sale->quantity) ? $sale->quantity : '') }}"
                                                                                                placeholder="Qty"
                                                                                                name="quantity[]">
                                                                                        </td>
                                                                                        <td class="unit">
                                                                                            <input type="text"
                                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                                placeholder="unit"
                                                                                                value="{{ old('unit', !empty($sale->unit) ? $sale->unit : '') }}"
                                                                                                name="unit[]">
                                                                                        </td>
                                                                                        <td class="text-right amount">
                                                                                            <span
                                                                                                class="editable-amount"><span
                                                                                                    class="currency"></span>
                                                                                                <span
                                                                                                    class="amount">0.00</span></span>
                                                                                        </td>
                                                                                        <td class="rate">
                                                                                            <input type="text"
                                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                                                placeholder="Rate"
                                                                                                value="{{ old('rate', !empty($sale->rate) ? $sale->rate : '') }}"
                                                                                                name="rate[]">
                                                                                        </td>
                                                                                        <td class="text-right amount">
                                                                                            <span
                                                                                                class="editable-amount"><span
                                                                                                    class="currency"></span>
                                                                                                <span
                                                                                                    class="amount">0.00</span></span>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach
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

                                                                        <div class="col-md-12 align-self-center">

                                                                            <div class="form-group row invoice-note">
                                                                                <label for="invoice-detail-notes"
                                                                                    class="col-sm-12 col-form-label col-form-label-sm">Remarks</label>
                                                                                <div class="col-sm-12">
                                                                                    <textarea class="form-control" id="remarks" placeholder='Enter The Remarks' style="height: 88px;"></textarea>
                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                    </div>

                                                                </div>
                                                                <div class="col-xl-5 invoice-address-client invoice-detail-total"
                                                                    style="float: right">
                                                                    <div class="invoice-address-client-fields">

                                                                        <div class="form-group row">
                                                                            <label for="client-name"
                                                                                class="col-sm-4 col-form-label col-form-label-sm">Gross
                                                                                Bill</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text"
                                                                                    id="gross-amount"
                                                                                    class="form-control form-control-sm"
                                                                                    id="client-name" readonly>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="client-email"
                                                                                class="col-sm-4 col-form-label col-form-label-sm">-
                                                                                Freight</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="freight"
                                                                                    class="form-control form-control-sm"
                                                                                    id="client-email" placeholder="">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="client-address"
                                                                                class="col-sm-4 col-form-label col-form-label-sm">-
                                                                                Scheme</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="scheme"
                                                                                    class="form-control form-control-sm"
                                                                                    id="client-address"
                                                                                    placeholder="">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="client-phone"
                                                                                class="col-sm-4 col-form-label col-form-label-sm">-
                                                                                Commission</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="commission"
                                                                                    class="form-control form-control-sm"
                                                                                    id="client-phone" placeholder="">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="client-phone"
                                                                                class="col-sm-4 col-form-label col-form-label-sm">Net
                                                                                Amount</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="net-amount"
                                                                                    class="form-control form-control-sm"
                                                                                    readonly>
                                                                            </div>
                                                                        </div>

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
        <script src="{{ asset('js/common.js') }}"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])


    </x-slot>
    {{-- <script>
        document.getElementsByClassName('additem')[0].addEventListener('click', function() {

            // e.preventDefault();
            var $tr = $(this).closest('.tr_clone');
            var $clone = $tr.clone();
            $clone.find(':text').val('');
            $clone.find("input").val("").end();
            $tr.append().after($clone);

            var numItems = $('.tr_clone').length;
            var nextId = parseInt(numItems) - parseInt(1);
            console.log(nextId);
            $clone.removeClass("validator_0");
            $clone.addClass("validator_" + nextId);
            $(this).attr('data-item-number', "");
            $clone.find(".row_id").val(nextId);
            $clone.find(".purchase_id").removeClass("purchase_id_0");
            $clone.find(".purchase_id").addClass("purchase_id_" + nextId);
            $clone.find(".quantity").removeClass("quantity_0");
            $clone.find(".quantity").addClass("quantity_" + nextId);
            $clone.find(".price").removeClass("price_0");
            $clone.find(".price").addClass("price_" + nextId);
            $clone.find(".amount").removeClass("amount_0");
            $clone.find(".amount").addClass("amount_" + nextId);

            //remove old select2 span and trigger new event for select2
            /*  $('.tr_clone').find("select.product_id").next(".select2-container").remove();
              $('.tr_clone').find("select.product_id").next(".select2-container").remove();
              $('.tr_clone').find(".product_id").select2();*/

            if (parseInt(nextId) > 0) {
                $clone.find(".delete-row").removeClass("delete_row_0");
                $clone.find(".delete-row").addClass("delete_row_" + nextId);
                $(".delete_row_" + nextId).show();
            }
            $(".purchase_id_" + nextId).val("");
            // doQuantityTotal();
            // doAmountTotal();
            /* let getTableElement = document.querySelector('.item-table');
             let currentIndex = getTableElement.rows.length;
             let $html = '<tr>' +
                 '<td class="delete-item-row">' +
                 '<ul class="table-controls">' +
                 '<li><a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>' +
                 '</ul>' +
                 '</td>' +
                 '<td><input type="checkbox" name="row_id[]" class="row_id" value="' + currentIndex + '" hidden></td>' +
                 {{-- '<td class="product"><select id="product_id" type="text" name="product_id[]" value="{{ old('product_id', !empty($saleDetail->product_id) ? $saleDetail->product_id : '') }}"' +
             placeholder="Please Select the Product"
             class="form-control select2 form-control mb-3 custom-select product_id_0"
             required>
             <option value="">Select</option>
             if ( /*___directives_script_0___*/
        ) {
            <
            option value = "{{ $key }}"
            {{ (old('product_id') == $key ? 'selected' : '') || (!empty($saleDetail->product_id) ? collect($saleDetail->product_id)->contains($key) : '') ? 'selected' : '' }}
                >
                {{ $value }} < /option>
            @endforeach <
                /select> < /
                td > ' +
            '<td class="rate">' +
            '<input type="text" class="form-control  form-control-sm qty_' + currentIndex +
                '" placeholder="Qty"></td>' +
                '<td class="text-right qty"><input type="text" class="form-control  form-control-sm unit_' +
                currentIndex +
                '" placeholder="unit"></td>' +
                '<td class="text-right amount"><span class="editable-amount"><span class="currency"></span><span class="amount">0.00</span></span></td>' +
                '<td class="text-right rate"><input type="text" class="form-control  form-control-sm rate_' +
                currentIndex +
                ' rate" placeholder="Rate"></td>' +
                '<td><input type="text" id="amount" name="amount[]" class="form-control form-control-sm amount_' +
                currentIndex + ' amount" placeholder="Amount" style="width: 210px !important;"></td>' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);

            */

            deleteItemRow();

        })

        selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
        selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
        selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);

        var f2 = flatpickr(document.getElementById('due'), {
            defaultDate: currentDate.setDate(currentDate.getDate() + 5),
        });

        $(document).ready(function() {

            console.log('here');
            /*$("#party_id")
                .select2({
                    dropdownAutoWidth: true,
                    minimumResultsForSearch: -1,
                    dropdownCssClass: "country-select-dropdown",
                    dropdownParent: $("#slectParent").parent()
                })
                .on("select2:open", function (e) {
                    var container = $(".select2-container .select2-dropdown");
                    setTimeout(function () {
                        container.addClass("country-select-dropdown-open");
                    }, 300);
                })
                .on("select2:closing", function (e) {
                    var container = $(".select2-container .select2-dropdown");
                    container.removeClass("country-select-dropdown-open");
                });*/
        });
    </script> --}}
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
                '<td class="description"> <select id="product_id" type="text" name="product_id[]" value="{{ old('product_id', !empty($sale->product_id) ? $sale->product_id : '') }}" placeholder="Please Select the Product" class="form-control select2 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mb-3 custom-select"> <option value=""> Select</option> @foreach ($dropDownData['products'] as $key => $value)<option value="{{ $key }}" {{ (old('product_id') == $key ? 'selected' : '') || (!empty($sale->product_id) ? collect($sale->product_id)->contains($key) : '') ? 'selected' : '' }}> {{ $value }} </option>@endforeach </select>' +
                '<td class="rate">' +
                '<input type="text" class="form-control  {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" placeholder="Qty" name="quantity[]">' +
                ' </td>' +
                '<td class="text-right qty"><input type="text" class="form-control  {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" placeholder="unit" name="unit[]"></td>' +
                '<td class="text-right amount"><span class="editable-amount"><span class="currency"></span> <span class="amount">0.00</span></td>' +
                '<td class="text-right rate"><input type="text" class="form-control  {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" placeholder="Rate" name="rate[]"></td>' +
                '<td class="text-right amount"><span class="editable-amount"><span class="currency"></span> <span class="amount">0.00</span></td>' +
                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
                // '<input class="form-check-input inbox-chkbox contact-chkbox" type="checkbox">' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);
            deleteItemRow();

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
    </script>
</x-base-layout>
