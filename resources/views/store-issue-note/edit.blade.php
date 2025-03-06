<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

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
                <li class="breadcrumb-item active" aria-current="page">Store Issue Note</li>
            </ol>
        </nav>
    </div>


    <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Store Issue Note</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <div class="widget-content widget-content-area">
                        <form method="POST"
                            action="{{ !empty($issueNote) ? route('store-issue-note.update') : route('store-issue-note.save') }}"
                            class="row g-3 needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="id" id="id"
                                value="{{ isset($issueNote->id) ? $issueNote->id : '' }}" />
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="issued_to">Issue Note#</label>
                                        <input type="text" value="{{ $issueNote->id }}" style="color: black;"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            placeholder="Issue Note#..." readonly>
                                        @error('issued_to')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                    <div class="col-md-6">
                                        <label for="date">
                                            Date</label>
                                        <input type="text"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            id="date" name="date"
                                            value="{{$date}}"
                                            placeholder="Select The Date" required>
                                        @error('date')
                                            <span style="color:red" class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <label for="inputState" class="form-label">Product</label>
                                        <select id="product_id" name="product_id"
                                            class="form-select select2 mb-3 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                            <option selected="">Please select the
                                                Product</option>
                                            @foreach ($dropDownData['products'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('product_id') == $key ? 'selected' : '') || (!empty($issueNote->product_id) ? collect($issueNote->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="receiver_name">Receiver Name</label>
                                        <input type="text"
                                            value="{{ old('receiver_name', @$issueNote->receiver_name) }}"
                                            name="receiver_name"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            id="receiver-name" placeholder="Receiver Name..." required>
                                        @error('receiver_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>

                                    <div class="col-md-6">
                                        <label for="inputState" class="form-label">From Department</label>
                                        <select id="from_department" name="from_department"
                                            class="form-select select2 mb-3 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                            <option selected="">Please select the
                                                Department</option>
                                            @foreach ($dropDownData['departments'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('from_department') == $key ? 'selected' : '') || (!empty($issueNote->from_department) ? collect($issueNote->from_department)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="inputState" class="form-label">To Department</label>
                                        <select id="to_department" name="to_department"
                                            class="form-select select2 mb-3 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                            <option selected="">Please select the Department</option>
                                            @foreach ($dropDownData['departments'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('to_department') == $key ? 'selected' : '') || (!empty($issueNote->to_department) ? collect($issueNote->to_department)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="invoice-detail-items " style="padding: 0px 0px 0px 0px !important;">

                                        <div class="table-responsive">
                                            <table class="table item-table">
                                                <thead>
                                                    <tr>
                                                        <th class="">
                                                        </th>
                                                        <th class="">
                                                        </th>
                                                        <th style="width: 20%">Product</th>
                                                        <th class="" style="width: 8%">
                                                            P.T</th>
                                                        <th class="">
                                                            M.T</th>
                                                        <th class="">
                                                            Size</th>
                                                        <th class="">
                                                            Bags/Units</th>
                                                        <th class="">
                                                            Avg Weight</th>
                                                        <th class="">
                                                            Tot.Qty</th>
                                                        <th class="" style="width: 20%">
                                                            Remarks</th>

                                                    </tr>
                                                    <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($issueNoteDetails as $issueNote)
                                                        @php
                                                            $index = $loop->index + 2; // Starts from 2
                                                        @endphp
                                                        <tr class="tr_clone validator_{{ $index }}">
                                                            <td class="delete-item-row">
                                                                <ul class="table-controls">
                                                                    <li>
                                                                        <a href="javascript:void(0);"
                                                                            class="delete-item" data-toggle="tooltip"
                                                                            data-placement="top" title=""
                                                                            data-original-title="Delete">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
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
                                                                            </svg>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="row_id[]" class="row_id"
                                                                    value="{{ $index }}" hidden>
                                                            </td>

                                                            <td class="product">
                                                                <select id="product_id" name="product_id[]"
                                                                    class="form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} product product_{{ $index }}">
                                                                    <option selected="">Please
                                                                        select the
                                                                        Items</option>
                                                                    @foreach ($dropDownData['products'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ (old('product_id') == $key ? 'selected' : '') || (!empty($issueNote->product_id) ? collect($issueNote->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <br>
                                                            <td class="quantity">
                                                                <input type="text" style="color: black;"
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_{{ $index }}"
                                                                    id="packing" name="packing_type[]"
                                                                    placeholder="P.T"
                                                                    value="{{ old('packing_type', !empty($issueNote->packing_type) ? $issueNote->packing_type : '') }}"
                                                                    readonly>
                                                            </td>
                                                            <td class="quantity">
                                                                <input type="text" style="color: black; "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_{{ $index }}"
                                                                    placeholder="M.T" name="measurement_type[]"
                                                                    value="{{ old('measurement_type', !empty($issueNote->measurement_type) ? $issueNote->measurement_type : '') }}"
                                                                    id="measurement" readonly>
                                                            </td>

                                                            <td class="quantity">
                                                                <input type="text" style="color: black;"
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} size_{{ $index }}"
                                                                    id="size" name="size[]" placeholder="Size"
                                                                    value="{{ old('size', !empty(@$issueNote->size) ? @$issueNote->size : '') }}"
                                                                    readonly>
                                                            </td>

                                                            <td class="bagsQuantity">
                                                                <input type="number" id="bags" name="bags[]"
                                                                    value="{{ old('bags', !empty(@$issueNote->bags) ? @$issueNote->bags : '') }}"
                                                                    style="color: black;"
                                                                    class="bags form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} bags bags_{{ $index }}"
                                                                    placeholder="Bags">
                                                            </td>
                                                            <td class="avgWeight">
                                                                <input type="number" id="avgWeight"
                                                                    style="color: black;" name="avg_weight[]"
                                                                    value="{{ old('avg_weight', !empty(@$issueNote->avg_weight) ? @$issueNote->avg_weight : '') }}"
                                                                    class="avgWeight form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} avgWeight_{{ $index }}"
                                                                    placeholder="M.T">
                                                            </td>


                                                            <td class="totQuantity">
                                                                <input type="number" id="totalQty"
                                                                    name="total_qty[]"
                                                                    value="{{ old('total_qty', !empty(@$issueNote->total_qty) ? @$issueNote->total_qty : '') }}"
                                                                    class="totalQty form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} totalQty_{{ $index }}"
                                                                    placeholder="Tot Qty" required>
                                                            </td>

                                                            <td class="quantity">
                                                                <textarea id="unit" type="text" name="remarks[]" placeholder="Remarks...."
                                                                    class="mt-0 form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} remarks_{{ $index }}">{{ @$issueNote->remarks }}</textarea>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <a href="javascript:void(0)" class="btn btn-dark additem">Add
                                            Item</a>
                                        {{-- <a class="btn btn-dark additem">Add
                                        Item</a> --}}
                                    </div>
                                </div>
                                <a href="{{ route('store-issue-note.list') }}" style="float: right;"
                                    class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                    <button type="submit" style="float: right"
                                        class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                        data-bs-container="body" data-bs-placement="right"
                                        data-bs-content="Tooltip on right">
                                        @if (!isset($issueNote))
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
            console.log('FGFGF');
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
                '"><option selected =""> Please select the product </option> @foreach ($dropDownData['products'] as $key => $value) <option value = "{{ $key }}"{{ (old('product_id') == $key ? 'selected' : '') || (!empty($issueNote->product_id) ? collect($issueNote->product_id)->contains($key) : '') ? 'selected' : '' }}> {{ $value }}</option> @endforeach < /select> ' +
                '</td>' +
                '<td class="quantity">' +
                '<input type="text" style="color: black;" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_' +
                currentIndex +
                '"id="packing" name="packing_type[]" placeholder="P.T" readonly></td>' +
                '<td class="quantity">' +
                '<input type="text" style="color: black; " class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_' +
                currentIndex +
                '" placeholder="M.T" name="measurement_type[]" id="measurement" readonly></td>' +
                '<td class = "quantity" >' +
                '<input type = "text" style = "color: black;" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} size_' +
                currentIndex +
                '" id = "size" name ="size[]" placeholder = "Size" readonly ></td>' +
                '<td class = "bagsQuantity" >' +
                '<input type = "number" id = "bags" name = "bags[]" style = "color: black;" class = "bags form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} bags bags_' +
                currentIndex +
                '" placeholder = "Bags"  ></td>' +
                '<td class = "avgWeight" >' +
                '<input type = "number" id ="avgWeight" style = "color: black;" name = "avg_weight[]" class ="avgWeight form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} avgWeight_' +
                currentIndex +
                '" placeholder = "M.T"></td>' +
                '<td class = "totQuantity" >' +
                '<input type = "number" id = "totalQty" name = "total_qty[]" class ="totalQty form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} totalQty_' +
                currentIndex +
                '" placeholder = "Tot Qty" required> </td>' +
                '<td class = "quantity" >' +
                '<textarea id="unit" type="text" name="remarks[]" placeholder="Remarks...." class="mt-0 form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} remarks_' +
                currentIndex +
                '"></textarea> </td>' +
                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
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

            $(document).ready(function() {
                $(".product_" + currentIndex).on('change', function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    var name = this.value;
                    let url = config.routes.getProductSizeDetail + '/' + name;
                    $.ajax({
                        url: url,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $(".size_" + row_id).val(response.size);
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
        });
    </Script>

    <script>
        var config = {
            routes: {
                getProductPackingTypeDetail: "{{ url('store-issue-note/get-product-packing-type') }}",
                getProductMeasurementTypeDetail: "{{ url('store-issue-note/get-product-measurement-type') }}",
                getProductSizeDetail: "{{ url('store-issue-note/get-product-size') }}",
            },
        }
    </script>

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>
        {{-- <script src="{{ asset('plugins/invoice-add/invoice-add.js') }}"></script> --}}
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
