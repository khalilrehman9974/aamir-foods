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
                                                        method="POST" class="row g-3 needs-validation" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="saleId" id="saleId"
                                                            value="{{ isset($saleOrder->id) ? $saleOrder->id : '' }}" />
                                                        <div class="form-group">
                                                            <div class="col-lg-0 col-12 ">
                                                                <div class="row">

                                                                    <div class="col-md-6">
                                                                        <label for="date">
                                                                            Date</label>
                                                                        <input type="text" class="form-control "
                                                                            id="date"
                                                                            placeholder="Select The Date">
                                                                    </div>
                                                                    <div class="col-md-6 ">
                                                                        <label for="party_id"
                                                                            class="form-label">Party</label>
                                                                        <select id="party_id" type="text"
                                                                            name="party_id"
                                                                            placeholder="Please Select the Party Name"
                                                                            class="form-control select2 form-control mb-3 custom-select"
                                                                            required>
                                                                            <option value="">Select</option>
                                                                            @foreach ($dropDownData['parties'] as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    {{ (old('party_id') == $key ? 'selected' : '') || (!empty($saleOrder->party_id) ? collect($saleOrder->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                    {{ $value }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="invalid-feedback">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-0 col-12 ">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="saleman_id" class="form-label">Sale
                                                                            Man</label>
                                                                        <select id="saleman_id" type="text"
                                                                            name="saleman_id"
                                                                            placeholder="Please Select the Sale Man"
                                                                            class="form-control select2 form-control mb-3 custom-select"
                                                                            required>
                                                                            <option value="">Select</option>
                                                                            @foreach ($dropDownData['saleMans'] as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    {{ (old('saleman_id') == $key ? 'selected' : '') || (!empty($saleOrder->saleman_id) ? collect($saleOrder->saleman_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                    {{ $value }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="invalid-feedback">

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="bilty_no" class="form-label">Bilty
                                                                            Number </label>
                                                                        <input id="bilty_no" type="bilty_no"
                                                                            name="bilty_no"
                                                                            value="{{ old('bilty_no', !empty($saleOrder->bilty_no) ? $saleOrder->bilty_no : '') }}"
                                                                            placeholder="Please Enter the Bilty No "
                                                                            class="form-control" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-0 col-12 ">
                                                                <div class="row">
                                                                    <div class="col-md-6 ">
                                                                        <label for="transporter_id"
                                                                            class="form-label">Transporter</label>
                                                                        <select id="transporter_id" type="text"
                                                                            name="transporter_id"
                                                                            placeholder="Please Select the Transporter"
                                                                            class="form-control select2 form-control mb-3 custom-select"
                                                                            required>
                                                                            <option value="">Select</option>
                                                                            @foreach ($dropDownData['transporters'] as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    {{ (old('transporter_id') == $key ? 'selected' : '') || (!empty($saleOrder->transporter_id) ? collect($saleOrder->transporter_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                    {{ $value }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="invalid-feedback">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 ">
                                                                        <label for="deliverd_to" class="form-label">Delivered
                                                                            To</label>
                                                                        <input id="deliverd_to" type="text"
                                                                            name="deliverd_to"
                                                                            value="{{ old('deliverd_to', !empty($saleOrder->deliverd_to) ? $saleOrder->deliverd_to : '') }}"
                                                                            placeholder="Deliverd to.. " class="form-control"
                                                                            >
                                                                    </div>
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
                                                                                    <th></th>
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
                                                                                <tr class="tr_clone validator_0">
                                                                                    <td class="delete-item-row">
                                                                                        <ul class="table-controls">
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
                                                                                    <td><input type="text"
                                                                                            name="row_id[]"
                                                                                            class="row_id"
                                                                                            value="0" hidden>
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

                                                                                    <td class="quantity">
                                                                                        <input type="text"
                                                                                            class="form-control form-control-sm qty_0"
                                                                                            value="{{ old('quantity', !empty($saleOrder->quantity) ? $saleOrder->quantity : '') }}"
                                                                                            name="quantity[]"
                                                                                            placeholder="Qty">
                                                                                    </td>
                                                                                    <td class="unit">
                                                                                        <input type="text"
                                                                                            class="form-control form-control-sm unit_0"
                                                                                            value="{{ old('unit', !empty($saleOrder->unit) ? $saleOrder->unit : '') }}"
                                                                                            name="unit[]"
                                                                                            placeholder="unit">
                                                                                    </td>
                                                                                    <td class="total_unit">
                                                                                        <input type="text"
                                                                                            class="form-control form-control-sm unit_0"
                                                                                            value="{{ old('total_unit', !empty($saleOrder->total_unit) ? $saleOrder->total_unit : '') }}"
                                                                                            name="total_unit[]"
                                                                                            placeholder="total_unit">
                                                                                    </td>
                                                                                    <td class="total_unit">
                                                                                        <input type="text"
                                                                                            class="form-control form-control-sm unit_0"
                                                                                            value="{{ old('rate', !empty($saleOrder->rate) ? $saleOrder->rate : '') }}"
                                                                                            name="rate[]"
                                                                                            placeholder="rate">
                                                                                    </td>
                                                                                    <td class="total_unit">
                                                                                        <input type="text"
                                                                                            id="amount"
                                                                                            name="amount[]"
                                                                                            value="{{ old('amount', !empty($saleOrder->amount) ? $saleOrder->amount : '') }}"
                                                                                            class="form-control form-control-sm amount_0 "
                                                                                            placeholder="Amount"
                                                                                            >
                                                                                    </td>
                                                                                </tr>
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
                                                                                    id="client-name" placeholder="">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="client-email"
                                                                                class="col-sm-4 col-form-label col-form-label-sm">-
                                                                                Freight</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="freight"
                                                                                    class="form-control form-control-sm" name="freight"
                                                                                    id="client-email" placeholder="">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="client-address"
                                                                                class="col-sm-4 col-form-label col-form-label-sm">-
                                                                                Scheme</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="scheme"
                                                                                    class="form-control form-control-sm" name="scheme"
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
                                                                                    class="form-control form-control-sm" name="commission"
                                                                                    id="client-phone" placeholder="">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group row">
                                                                            <label for="client-phone"
                                                                                class="col-sm-4 col-form-label col-form-label-sm">Net
                                                                                Amount</label>
                                                                            <div class="col-sm-8">
                                                                                <input type="text" id="net-amount" name="total_amount"
                                                                                    class="form-control form-control-sm"
                                                                                    id="client-phone" placeholder="">
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
                                            <a href="{{ route('sale-order.list') }}" style="float: right;"
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

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
        <script src="{{ asset('plugins/invoice-add/invoice-add.js') }}"></script>
        <script src="{{ asset('js/common.js') }}"></script>

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
                '<td><input type="checkbox" name="row_id[]" class="row_id" value="' + currentIndex +
                '" hidden></td>' +
                '<td class="product"> <select id="product_id" type = "text" name = "product_id[]" placeholder = "Please Select the Product" class = "form-control select2 form-control-sm custom-select" required ><option value = "" >Select the Product </option> @foreach ($dropDownData['products'] as $key => $value)<option value = "{{ $key }}" {{ (old('product_id') == $key ? 'selected' : '') || (!empty($saleOrder->product_id) ? collect($saleOrder->product_id)->contains($key) : '') ? 'selected' : '' }} >{{ $value }} </option> @endforeach </select> ' +
                '<td class="quantity">' +
                ' <input type="text" class = "form-control form-control-sm qty_0" value = "{{ old('quantity', !empty($saleOrder->quantity) ? $saleOrder->quantity : '') }}" name = "quantity[]"' +
                currentIndex +
                '" placeholder="Qty"></td>' +
                '<td class="unit"> <input type="text" class = "form-control form-control-sm unit_0" value = "{{ old('unit', !empty($saleOrder->unit) ? $saleOrder->unit : '') }}" name = "unit[]"' +
                currentIndex +
                '" placeholder="unit"></td>' +
                '<td class="total_unit"><input type="text" class = "form-control form-control-sm unit_0" value = "{{ old('total_unit', !empty($saleOrder->total_unit) ? $saleOrder->total_unit : '') }}" placeholder="total_unit" name = "total_unit[]" < /td>' +
                '<td class="total_unit"> <input type="text" name = "rate[]" id = "rate" class = "form-control form-control-sm rate_0 rate" value = "{{ old('rate', !empty($saleOrder->rate) ? $saleOrder->rate : '') }}"' +
                currentIndex +
                ' "  placeholder="Rate" data-id = "0" ></td>' +
                '<td class="total_unit"><input type="text" value="{{ old('amount', !empty($saleOrder->amount) ? $saleOrder->amount : '') }}" id="amount" name="amount[]" class="form-control form-control-sm amount_' +
                currentIndex + ' amount" placeholder="Amount" ></td>' +
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
    </script>
</x-base-layout>
