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
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('grn.list') }}">List of Goods Received Notes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Goods Received Note</li>
            </ol>
        </nav>
    </div>


    <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Goods Received Notes</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <div class="widget-content widget-content-area">
                        <form method="POST" action="{{ !empty($note) ? route('grn.update') : route('grn.save') }}"
                            class="row g-3 needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="id" id="id"
                                value="{{ isset($note->id) ? $note->id : '' }}" />
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">

                                        <label for="purchase_order_no">PO Number</label>
                                        <input type="text"
                                            value="{{ old('purchase_order_no', @$note->purchase_order_no) }}"
                                            name="purchase_order_no" class="form-control" id="purchase_order_no"
                                            placeholder="PO Number..." required>
                                        @error('purchase_order_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                    <div class="col-md-6 ">
                                        <label for="date">
                                            Date</label>
                                        <input type="text" class="form-control " id="date"
                                            placeholder="Select The Date">
                                    </div>
                                </div>
                                <div class="row">


                                    <div class="col-md-6">

                                        <label for="supplier_name">Supplier Name</label>
                                        <input type="text"
                                            value="{{ old('supplier_name', @$note->supplier_name) }}"
                                            name="supplier_name" class="form-control" id="supplier_name"
                                            placeholder="Name..." required>
                                        @error('supplier_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>

                                    <div class="col-md-6">

                                        <label for="supplier_bill_no">Supplier Bill No</label>
                                        <input type="text"
                                            value="{{ old('supplier_bill_no', @$note->supplier_bill_no) }}"
                                            name="supplier_bill_no" class="form-control" id="supplier_bill_no"
                                            placeholder="Bill No..." required>
                                        @error('supplier_bill_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>


                                <div class="row mb-5">
                                    <div class="col-md-6">

                                        <label for="inputState" class="form-label">Transporter</label>
                                        <select id="transporter_id" name="transporter_id" class="form-select">
                                            <option selected="">Please select the
                                                transporter</option>
                                            @foreach ($dropDownData['transporters'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('transporter_id') == $key ? 'selected' : '') || (!empty($note->transporter_id) ? collect($note->transporter_id)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6 ">
                                        <label for="fare">Fare</label>
                                        <input type="text" name="fare"
                                            value="{{ old('fare', @$note->fare) }}" class="form-control"
                                            id="fare" placeholder="fare" >
                                        @error('fare')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="invoice-detail-items">

                                    <div class="table-responsive">
                                        <table class="table item-table">
                                            <thead>
                                                <tr>
                                                    <th class="">
                                                    </th>
                                                    <th>Item Name</th>
                                                    <th class="">
                                                        Quantity</th>
                                                    <th class="">
                                                        Remarks</th>

                                                </tr>
                                                <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="delete-item-row">
                                                        <ul class="table-controls">
                                                            <li><a href="javascript:void(0);" class="delete-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="" data-original-title="Delete"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24" height="24"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-x-circle">
                                                                        <circle cx="12" cy="12" r="10">
                                                                        </circle>
                                                                        <line x1="15" y1="9"
                                                                            x2="9" y2="15">
                                                                        </line>
                                                                        <line x1="9" y1="9"
                                                                            x2="15" y2="15">
                                                                        </line>
                                                                    </svg></a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td class="product_id">
                                                        <select id="product_id" name="product_id[]" class="form-select form-select-sm">
                                                            <option selected="">Please select the
                                                                Item</option>
                                                            @foreach ($dropDownData['products'] as $key => $value)
                                                                <option value="{{ $key }}"
                                                                    {{ (old('product_id') == $key ? 'selected' : '') || (!empty($note->product_id) ? collect($note->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                    {{ $value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="quantity">
                                                        <input id="quantity" type="text" name="quantity[]"
                                                            value="{{ old('quantity', !empty($note->quantity) ? $note->quantity : '') }}"
                                                            placeholder="Quantity "
                                                            class="form-control form-control-sm">
                                                    </td>
                                                    <td class="remarks">
                                                        <textarea id="unit" type="text" name="remarks[]"
                                                            value="{{ old('remarks', !empty($note->remarks) ? $note->remarks : '') }}"
                                                            placeholder="Please Enter Remarks " class="form-control form-control-sm"></textarea>
                                                    </td>



                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <a class="btn btn-dark additem">Add
                                        Item</a>
                                </div>
                                <div class="form-group mt-5 mb-4">
                                    <label for="exampleFormControlTextarea1">Remarks</label>
                                    <textarea class="form-control" value="{{ old('remarks', @$note->remarks) }}" name="remarks" id="remarks"
                                        rows="3"></textarea>
                                </div>
                                <a href="{{ route('grn.list') }}" style="float: right;"
                                    class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                    <button type="submit" style="float: right"
                                        class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                        data-bs-container="body" data-bs-placement="right"
                                        data-bs-content="Tooltip on right">
                                        @if (!isset($issue_noteid))
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
        document.getElementsByClassName('additem')[0].addEventListener('click', function() {

            let getTableElement = document.querySelector('.item-table');
            let currentIndex = getTableElement.rows.length;

            let $html = '<tr>' +
                '<td class="delete-item-row">' +
                '<ul class="table-controls">' +
                '<li><a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>' +
                '</ul>' +
                '</td>' +
                '<td class="product_id"><select id="product_id" name="product_id[]" class="form-select form-select-sm"><option selected="">Please select the Item</option> @foreach ($dropDownData['products'] as $key => $value) <option value="{{ $key }}"{{ (old('product_id') == $key ? 'selected' : '') || (!empty($note->product_id) ? collect($note->product_id)->contains($key) : '') ? 'selected' : '' }}>{{ $value }}</option>@endforeach</select> </td>' +
                '<td class="quantity"><input type="text" name="quantity[]" class="form-control  form-control-sm" placeholder="Quantity "></td>' +
                '<td class="remarks" >' +
                '<textarea type="text" name="remarks[]" class="form-control  form-control-sm" placeholder="Please Enter remarks "></textarea>' +
                ' </td>' +

                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
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
    </Script>


    <x-slot:footerFiles>
        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])

    </x-slot>
</x-base-layout>
