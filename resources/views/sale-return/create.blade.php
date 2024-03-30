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
                <li class="breadcrumb-item active" aria-current="page">Sale Return</li>
                <li class="breadcrumb-item"><a href="{{ route('sale-return.sales-return') }}">List of Sale Returns</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('sale-return.create') }}">Create</a></li>

            </ol>
        </nav>
    </div>

    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Create Sale Return</h4>
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
                                                        action="{{ !empty($sale) ? route('sale-return.update') : route('sale-return.store') }}"
                                                        method="POST" class="row g-3 needs-validation" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="saleId" id="saleId"
                                                            value="{{ isset($sale->id) ? $sale->id : '' }}" />
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-6 ">
                                                                    <label for="dispatch_note"
                                                                        class="form-label">Dispatch Note# </label>
                                                                    <input id="dispatch_note" type="dispatch_note"
                                                                        name="dispatch_note"
                                                                        value="{{ old('dispatch_note', !empty($sale->dispatch_note) ? $sale->dispatch_note : '') }}"
                                                                        placeholder="Please Enter The Dispatch Note # "
                                                                        class="form-control" required>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="date">
                                                                        Date</label>
                                                                    <input type="text" class="form-control"
                                                                        id="date" placeholder="Select The Date">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 ">
                                                                    <label for="party_id"
                                                                        class="form-label">Party</label>
                                                                    <select id="party_id" type="text"
                                                                        name="party_id"
                                                                        value="{{ old('party_id', !empty($sale->party_id) ? $sale->party_id : '') }}"
                                                                        placeholder="Please Select the Party Name"
                                                                        class="form-control select2 custom-select"
                                                                        required>
                                                                        <option value="">Select</option>
                                                                        @foreach ($dropDownData['parties'] as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ (old('party_id') == $key ? 'selected' : '') || (!empty($sale->party_id) ? collect($sale->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="invalid-feedback">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 ">
                                                                    <label for="saleman_id" class="form-label">Sale
                                                                        Man</label>
                                                                    <select id="saleman_id" type="text"
                                                                        name="saleman_id"
                                                                        value="{{ old('saleman_id', !empty($sale->saleman_id) ? $sale->saleman_id : '') }}"
                                                                        placeholder="Please Select the Sale Man"
                                                                        class="form-control select2 form-control custom-select"
                                                                        required>
                                                                        <option value="">Select</option>
                                                                        @foreach ($dropDownData['saleMans'] as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ (old('saleman_id') == $key ? 'selected' : '') || (!empty($sale->saleman_id) ? collect($sale->saleman_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="invalid-feedback">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6 ">
                                                                    <label for="bilty_no" class="form-label">Bilty
                                                                        Number </label>
                                                                    <input id="bilty_no" type="bilty_no"
                                                                        name="bilty_no"
                                                                        value="{{ old('bilty_no', !empty($sale->bilty_no) ? $sale->bilty_no : '') }}"
                                                                        placeholder="Please Enter the Bilty No "
                                                                        class="form-control" required>
                                                                </div>
                                                                <div class="col-md-6 ">
                                                                    <label for="transporter_id"
                                                                        class="form-label">Transporter</label>
                                                                    <select id="transporter_id" type="text"
                                                                        name="transporter_id"
                                                                        value="{{ old('transporter_id', !empty($sale->transporter_id) ? $sale->transporter_id : '') }}"
                                                                        placeholder="Please Select the Transporter"
                                                                        class="form-control select2 form-control mb-3 custom-select"
                                                                        required>
                                                                        <option value="">Select</option>
                                                                        @foreach ($dropDownData['transporters'] as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ (old('transporter_id') == $key ? 'selected' : '') || (!empty($sale->transporter_id) ? collect($sale->transporter_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="invalid-feedback">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <label for="deliverd_to" class="form-label">Deliverd
                                                                    To</label>
                                                                <input id="deliverd_to" type="text"
                                                                    name="deliverd_to"
                                                                    value="{{ old('deliverd_to', !empty($sale->deliverd_to) ? $sale->deliverd_to : '') }}"
                                                                    placeholder="Deliverd to.. " class="form-control"
                                                                    required>
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
                                                                                <th class="">
                                                                                    Qty</th>
                                                                                <th class="">
                                                                                    unit</th>
                                                                                <th class="">
                                                                                    Total Unit</th>
                                                                                <th class="">
                                                                                    Rate</th>
                                                                                <th class="text-right">
                                                                                    Amount</th>

                                                                            </tr>
                                                                            <tr aria-hidden="true"
                                                                                class="mt-3 d-block table-row-hidden">
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
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
                                                                                <td class="product">
                                                                                    <select id="product_id"
                                                                                        type="text"
                                                                                        name="product_id[]"
                                                                                        placeholder="Please Select the Product"
                                                                                        class="form-control select2 form-control-sm custom-select"
                                                                                        required>
                                                                                        <option value="">
                                                                                            Select the Product</option>
                                                                                        @foreach ($dropDownData['products'] as $key => $value)
                                                                                            <option
                                                                                                value="{{ $key }}"
                                                                                                {{ (old('product_id') == $key ? 'selected' : '') || (!empty($saleOrder->product_id) ? collect($saleOrder->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                {{ $value }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </td>

                                                                                <td class="text-right qty">
                                                                                    <input type="text"
                                                                                        class="form-control form-control-sm"
                                                                                        placeholder="Qty">
                                                                                </td>
                                                                                <td class="unit">
                                                                                    <input type="text"
                                                                                        class="form-control form-control-sm"
                                                                                        placeholder="unit">
                                                                                </td>
                                                                                <td class="text-right amount">
                                                                                    <span class="editable-amount"><span
                                                                                            class="currency"></span>
                                                                                        <span
                                                                                            class="amount">0.00</span></span>
                                                                                </td>
                                                                                <td class="rate">
                                                                                    <input type="text"
                                                                                        class="form-control form-control-sm"
                                                                                        placeholder="Rate">
                                                                                </td>
                                                                                <td class="text-right amount">
                                                                                    <span class="editable-amount"><span
                                                                                            class="currency"></span>
                                                                                        <span
                                                                                            class="amount">0.00</span></span>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <a class="btn btn-dark additem">Add
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
                                                            <div class="invoice-detail-total">

                                                                <div class="row">

                                                                    <div class="col-md-12">
                                                                        <div class="totals-row">
                                                                            <div
                                                                                class="invoice-totals-row invoice-summary-subtotal">

                                                                                <div class="invoice-summary-label">
                                                                                    Gross Bill
                                                                                </div>

                                                                                <div class="invoice-summary-value">
                                                                                    <div class="subtotal-amount">
                                                                                        <span
                                                                                            class="currency">$</span><span
                                                                                            class="amount">0.00</span>
                                                                                    </div>
                                                                                </div>

                                                                            </div>



                                                                            <div
                                                                                class="invoice-totals-row invoice-summary-total">

                                                                                <div class="invoice-summary-label">
                                                                                    Less: Freight</div>

                                                                                <div class="invoice-summary-value">
                                                                                    <div class="freight">
                                                                                        <input id="freight"
                                                                                            type="text"
                                                                                            name="freight"
                                                                                            value="{{ old('freight', !empty($sale->freight) ? $sale->freight : '') }}"
                                                                                            class="form-control">
                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                            <div
                                                                                class="invoice-totals-row invoice-summary-tax">

                                                                                <div class="invoice-summary-label">
                                                                                    Less: Scheme
                                                                                </div>

                                                                                <div class="scheme">
                                                                                    <input id="scheme"
                                                                                        type="text" name="scheme"
                                                                                        value="{{ old('scheme', !empty($sale->scheme) ? $sale->scheme : '') }}"
                                                                                        class="form-control">
                                                                                </div>

                                                                            </div>

                                                                            <div
                                                                                class="invoice-totals-row invoice-summary-tax">

                                                                                <div class="invoice-summary-label">
                                                                                    Less: Commission
                                                                                </div>

                                                                                <div class="commission">
                                                                                    <input id="commission"
                                                                                        type="text"
                                                                                        name="commission"
                                                                                        value="{{ old('commission', !empty($sale->commission) ? $sale->commission : '') }}"
                                                                                        class="form-control">
                                                                                </div>

                                                                            </div>

                                                                            <div
                                                                                class="invoice-totals-row invoice-summary-balance-due">

                                                                                <div class="invoice-summary-label">
                                                                                    Total</div>

                                                                                <div class="invoice-summary-value">
                                                                                    <div class="balance-due-amount">
                                                                                        <span
                                                                                            class="currency">$</span><span>0.00</span>
                                                                                    </div>
                                                                                </div>

                                                                            </div>

                                                                            {{-- <input id="deliverd_to" type="text"
                                                                                        name="deliverd_to"
                                                                                        value="{{ old('deliverd_to', !empty($sale->deliverd_to) ? $sale->deliverd_to : '') }}"
                                                                                        placeholder="Deliverd to.. "
                                                                                        class="form-control" required>
                                                                                </div> --}}
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                        </div>
                                                        <a href="{{ route('sale-return.sales-return') }}"
                                                            style="float: right;"
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
                                                        @endif
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

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
        <script src="{{ asset('plugins/invoice-add/invoice-add.js') }}"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])




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
                '<td class="product"><select id="product_id" type = "text" name = "product_id[]" placeholder = "Please Select the Product" class = "form-control select2 form-control-sm custom-select" required ><option value = "" > Select the Product </option> @foreach ($dropDownData['products'] as $key => $value)<option value = "{{ $key }}"{{ (old('product_id') == $key ? 'selected' : '') || (!empty($saleOrder->product_id) ? collect($saleOrder->product_id)->contains($key) : '') ? 'selected' : '' }}>{{ $value }} </option> @endforeach </select>' +
                '<td class="rate">' +
                '<input type="text" class="form-control  form-control-sm" placeholder="Qty">' +
                ' </td>' +
                '<td class="text-right qty"><input type="text" class="form-control  form-control-sm" placeholder="unit"></td>' +
                '<td class="text-right amount"><span class="editable-amount"><span class="currency"></span> <span class="amount">0.00</span></td>' +
                '<td class="text-right rate"><input type="text" class="form-control  form-control-sm" placeholder="Rate"></td>' +
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
