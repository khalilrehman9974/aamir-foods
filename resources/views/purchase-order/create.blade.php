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
                <li class="breadcrumb-item active" aria-current="page">purchaseOrder</li>
                <li class="breadcrumb-item"><a href="{{ route('purchase-order.list') }}">List of Purchase Orders</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('purchase-order.create') }}">Create</a></li>

            </ol>
        </nav>
    </div>
    <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Add Purchase Order</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <div class="widget-content widget-content-area">
                        <form
                            action="{{ !empty($purchaseOrder) ? route('purchase-order.update') : route('purchase-order.store') }}"
                            method="POST" class="row g-3 needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="id" id="id"
                                value="{{ isset($purchaseOrder->id) ? $purchaseOrder->id : '' }}" />
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="date">
                                            Date</label>
                                        <input id="date" name="date"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} date flatpickr flatpickr-input"
                                            type="text"
                                            value="{{ empty($purchaseOrder->date) ? null : \Illuminate\Support\Carbon::parse($purchaseOrder->date)->format('Y-m-d') }}"
                                            placeholder="Select Date.." readonly="readonly">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <label for="name" class="form-label">Name
                                        </label>
                                        <input id="name" type="name" name="name"
                                            value="{{ old('name', !empty($purchaseOrder->name) ? $purchaseOrder->name : '') }}"
                                            placeholder="Please Enter The Name "
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="company_name" class="form-label">Company
                                            Name
                                        </label>
                                        <input id="company_name" type="company_name" name="company_name"
                                            value="{{ old('company_name', !empty($purchaseOrder->company_name) ? $purchaseOrder->company_name : '') }}"
                                            placeholder="Please Enter The Company Name "
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-12 ">

                                    <label for="address" class="form-label ">Address</label>
                                    <textarea id="address" type="textarea" name="address" rows="3" placeholder="Please Enter Address"
                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">{{ @$purchaseOrder->address }} </textarea>
                                    <div class="invalid-feedback">
                                        Please provide Address.
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
                                                    <th>Item Description</th>
                                                    <th class="">Total Quantity</th>
                                                    <th class="">Schedule Date</th>
                                                    <th class="">Schedule Quantity
                                                    </th>
                                                    <th class="">Delivery Date</th>
                                                    <th class="">Deliver Quantity
                                                    </th>
                                                    <th class="text-right">
                                                        Price
                                                    </th>

                                                </tr>
                                                <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="tr_clone validator_0">
                                                    <td class="delete-item-row">
                                                        <ul class="table-controls">
                                                            <li>
                                                                <a href="javascript:void(0);" class="delete-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="" data-original-title="Delete">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
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
                                                                    </svg>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="row_id[]" class="row_id"
                                                            value="0" hidden>
                                                    </td>

                                                    <td class="product">
                                                        <select id="product_id" name="product_id[]"
                                                            class="form-control select2 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} custom-select">
                                                            <option selected="">Please
                                                                select the
                                                                Items</option>
                                                            @foreach ($dropDownData['products'] as $key => $value)
                                                                <option value="{{ $key }}"
                                                                    {{ (old('product_id') == $key ? 'selected' : '') || (!empty($purchaseOrder->product_id) ? collect($purchaseOrder->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                    {{ $value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <br>
                                                    <td class="quantity">
                                                        <input type="text"
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_0"
                                                            name="total_quantity[]" placeholder="Qty">
                                                    </td>
                                                    <td class="Schedule_date">

                                                        <input id="date" name="Schedule_date[]"
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} date flatpickr flatpickr-input"
                                                            type="text"
                                                            value="{{ empty($purchaseOrder->Schedule_date) ? null : \Illuminate\Support\Carbon::parse($purchaseOrder->Schedule_date)->format('Y-m-d') }}"
                                                            placeholder="Select Date.." readonly="readonly">


                                                        {{-- <input id="date" name="Schedule_date[]"
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} flatpickr flatpickr-input"
                                                            type="text"
                                                            value="{{ old('date', !empty($purchaseOrder->Schedule_date) ? $purchaseOrder->Schedule_date : '') }}"
                                                            placeholder="Select Schedule Date.." readonly="readonly">
                                                        <input id="date" name="date[]"
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} date flatpickr flatpickr-input"
                                                            type="text"
                                                            value="{{ old('date', !empty($purchaseOrder->date) ? $purchaseOrder->date : '') }}"
                                                            placeholder="Select Date.." readonly="readonly"> --}}
                                                    </td>
                                                    <td class="Schedule_quantity">
                                                        <input type="text"
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_0"
                                                            value="{{ old('Schedule_quantity', !empty($purchaseOrder->Schedule_quantity) ? $purchaseOrder->Schedule_quantity : '') }}"
                                                            name="Schedule_quantity[]" placeholder="Qty">
                                                    </td>
                                                    <td class="Delivery_date">
                                                        <input id="date" name="Delivery_date[]"
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} flatpickr flatpickr-input"
                                                            type="text"
                                                            value="{{ old('date', !empty($purchaseOrder->Delivery_date) ? $purchaseOrder->Delivery_date : '') }}"
                                                            placeholder="Select Delivery Date.." readonly="readonly">
                                                    </td>
                                                    <td class="Delivery_quantity">
                                                        <input type="text"
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} qty_0"
                                                            value="{{ old('Delivery_quantity', !empty($purchaseOrder->Delivery_quantity) ? $purchaseOrder->Delivery_quantity : '') }}"
                                                            name="Delivery_quantity[]" placeholder="Qty">
                                                    </td>
                                                    <td class="price">
                                                        <input type="text" id="price" name="price[]"
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount"
                                                            value="{{ old('price', !empty($purchaseOrder->price) ? $purchaseOrder->price : '') }}"
                                                            placeholder="Price">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <a href="javascript:void(0);" class="btn btn-dark additem mt-3"
                                        id="add-item">Add
                                        Item</a>

                                </div>
                                <div class="invoice-detail-note">

                                    <div class="row">

                                        <div class="col-md-12 align-self-center">

                                            <div class="form-group row invoice-note">
                                                <label for="invoice-detail-notes"
                                                    class="col-sm-12 col-form-label col-form-label-sm mt-3">Remarks</label>
                                                <div class="col-sm-12">
                                                    <textarea class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" id="remarks"
                                                        placeholder='Enter The Remarks' style="height: 88px;">{{ @$purchaseOrder->remarks }}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                                <div class="col-xl-7 invoice-address-client invoice-detail-total"
                                    style="float: right">
                                    <div class="invoice-address-client-fields">

                                        <div class="form-group row">
                                            <label for="client-phone"
                                                class="col-md-3 col-form-label col-form-label-sm ">Total
                                                Amount</label>
                                            <div class="col-md-9">
                                                <input type="text" id="gross-amount"
                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} gross-amount"
                                                    name="grand_total" id="gross-amount" placeholder="Total Amount"
                                                    readonly>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <a href="{{ route('purchase-order.list') }}" style="float: right;"
                                    class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                    <button type="submit" style="float: right"
                                        class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                        data-bs-container="body" data-bs-placement="right"
                                        data-bs-content="Tooltip on right">
                                        @if (!isset($purchaseOrder))
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
                '<td><input type="checkbox" name="row_id[]" class="row_id" value="' + currentIndex +
                '" hidden></td>' +
                '<td class="product"><select id="product_id" name="product_id[]" class="form-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"> <option selected = "" > Please select the Items </option> @foreach ($dropDownData['products'] as $key => $value)<option value = "{{ $key }}" {{ (old('product_id') == $key ? 'selected' : '') || (!empty($purchaseOrder->product_id) ? collect($purchaseOrder->product_id)->contains($key) : '') ? 'selected' : '' }}>{{ $value }} </option>@endforeach </select> ' +
                '<td class="quantity">' +
                '<input type="text" class ="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  qty_0" name = "total_quantity[]" ' +
                currentIndex +
                '" placeholder="Qty"></td>' +
                '<td class="Schedule_date"><input id="date" name = "Schedule_date[]" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  flatpickr flatpickr-input" type = "text" value = "{{ old('date', !empty($purchaseOrder->Schedule_date) ? $purchaseOrder->Schedule_date : '') }}"' +
                currentIndex +
                '"  placeholder = "Select Schedule Date.." readonly = "readonly" ></td>' +
                '<td class="Schedule_quantity"><input type="text" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  qty_0" value="{{ old('Schedule_quantity', !empty($purchaseOrder->Schedule_quantity) ? $purchaseOrder->Schedule_quantity : '') }}" name = "Schedule_quantity[]" placeholder = "Qty" > </td>' +
                '<td class="Delivery_date"> <input id="date" name = "Delivery_date[]" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  flatpickr flatpickr-input" type = "text" value = "{{ old('date', !empty($purchaseOrder->Delivery_date) ? $purchaseOrder->Delivery_date : '') }}"' +
                currentIndex +
                '" placeholder = "Select Delivery Date.." readonly = "readonly" ></td>' +
                '<td><input type = "text" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  qty_0" value ="{{ old('Delivery_quantity', !empty($purchaseOrder->Delivery_quantity) ? $purchaseOrder->Delivery_quantity : '') }}"  placeholder = "Qty" name = "Delivery_quantity[]"' +
                currentIndex +
                '"  placeholder = "Qty"></td>' +
                '<td><input type="text" id = "price" name= "price[]" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount" value = "{{ old('price', !empty($purchaseOrder->price) ? $purchaseOrder->price : '') }}"' +
                currentIndex +
                '" placeholder="Price"></td>' +
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
</x-base-layout>
