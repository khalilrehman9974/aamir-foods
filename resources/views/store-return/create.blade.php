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
                <li class="breadcrumb-item active" aria-current="page">Store Return</li>
            </ol>
        </nav>
    </div>


    <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Store Return</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <div class="widget-content widget-content-area">
                        <form method="POST"
                            action="{{ !empty($storeReturn) ? route('storeReturn.update') : route('storeReturn.save') }}"
                            class="row g-3 needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="id" id="id"
                                value="{{ isset($storeReturn->id) ? $storeReturn->id : '' }}" />
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="inputState" class="form-label">Product</label>
                                        <select id="product_id" name="product_id"
                                            class="form-select select2 mb-3 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                            <option selected="">Please select the
                                                Product</option>
                                            @foreach ($dropDownData['products'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('product_id') == $key ? 'selected' : '') || (!empty($storeReturn->product_id) ? collect($storeReturn->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="return_to">Return To</label>
                                        <input type="text" value="{{ old('return_to', @$storeReturn->return_to) }}"
                                            name="return_to"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            id="return-to" placeholder="Return to..." required>
                                        @error('return_to')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>

                                    <div class="form-group col-md-6 ">
                                        <label for="return_by">Return By</label>
                                        <input type="text" name="return_by"
                                            value="{{ old('return_by', @$storeReturn->return_by) }}"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            id="return-by" placeholder="return By" required>
                                        @error('return_by')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="form-group mt-3 mb-4">
                                    <label for="exampleFormControlTextarea1">Remarks</label>
                                    <textarea class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" name="remarks" id="remarks"
                                        rows="3">{{ @$storeReturn->remarks }}</textarea>
                                </div>
                                <div class="invoice-detail-items">

                                    <div class="table-responsive">
                                        <table class="table item-table">
                                            <thead>
                                                <tr>
                                                    <th class="">
                                                    </th>
                                                    <th>Date</th>
                                                    <th class="">
                                                        Description</th>
                                                    <th class="">
                                                        Quantity</th>

                                                </tr>
                                                <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($storeReturns as $storeReturn)
                                                    <tr>
                                                        <td class="delete-item-row">
                                                            <ul class="table-controls">
                                                                <li><a href="javascript:void(0);" class="delete-item"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title=""
                                                                        data-original-title="Delete"><svg
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            class="feather feather-x-circle">
                                                                            <circle cx="12" cy="12"
                                                                                r="10">
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
                                                        <td class="date">
                                                            <input id="date" name="date[]"
                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} date flatpickr flatpickr-input"
                                                                type="text" data-date-format="Y-m-d"
                                                                value="{{ empty( $storeReturn->date) ? null :  \Illuminate\Support\Carbon::parse(  $storeReturn->date)->format('Y-m-d')}}"
                                                                {{-- value="{{ old('date', !empty($storeReturn->date) ? $storeReturn->date : '') }}" --}}
                                                                placeholder="Select Date.." readonly="readonly">
                                                        </td>
                                                        <td class="description ">
                                                            <textarea id="unit" type="text" name="description[]"
                                                                placeholder="Please Enter Description "
                                                                class="form-control mt-0 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">{{@$storeReturn->description}}</textarea>
                                                        </td>
                                                        <td class="quantity">
                                                            <input id="quantity" type="text" name="quantity[]"
                                                                value="{{ old('quantity', !empty($storeReturn->quantity) ? $storeReturn->quantity : '') }}"
                                                                placeholder="Please Enter Quantity "
                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <a class="btn btn-dark additem">Add
                                        Item</a>
                                </div>
                                <a href="{{ route('storeReturn.list') }}" style="float: right;"
                                    class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                    <button type="submit" style="float: right"
                                        class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                        data-bs-container="body" data-bs-placement="right"
                                        data-bs-content="Tooltip on right">
                                        @if (!isset($storeReturn))
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
                '<td class="date"><input id="date" name="date[]" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} date_' +
                currentIndex +
                ' flatpickr flatpickr-input" type="text" value="{{ old('date', !empty($storeReturn->date) ? $storeReturn->date : '') }}" placeholder="Select Date.." readonly="readonly"> ' +
                '<td class="description" >' +
                '<textarea type="text" name="description[]" class="form-control mt-0 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" placeholder="Please Enter Description "></textarea>' +
                ' </td>' +
                '<td class="quantity"><input type="text" name="quantity[]" class="form-control  {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" placeholder="Please Enter Quantity "></td>' +
                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);

            $(".date_" + currentIndex).flatpickr({
                dateFormat: "d-m-Y",
                minDate: "today",
                time_24hr: true,
            });
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
