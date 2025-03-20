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
        <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />



        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link rel="stylesheet" href="../src/plugins/src/filepond/filepond.min.css">
        <link rel="stylesheet" href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css">

        <link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />


    </x-slot>

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>


    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Defective Items</li>
                <li class="breadcrumb-item"><a href="{{ route('defective.list') }}">List of Defective Items</a></li>
                <li class="breadcrumb-item"><a href="{{ route('defective.create') }}">Create</a></li>

            </ol>
        </nav>
    </div>

    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Update Defective Items</h4>
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
                                                        action="{{ !empty($defectiveItems) ? route('defective.update') : route('defective.save') }}"
                                                        method="POST" class="row g-3 needs-validation" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($defectiveItems->id) ? $defectiveItems->id : '' }}" />
                                                        <div class="form-group">

                                                            <div class="row">
                                                                <div class="col-lg-0 col-6 ">
                                                                    <label for="date">
                                                                        Date</label>
                                                                    <input type="text" value="{{ $date }}"
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                        id="date" name="date"
                                                                        placeholder="Select The Date">
                                                                </div>
                                                                <div class="col-lg-0 col-6 ">

                                                                        <label for="entered_by" class="form-label">Entered By:
                                                                        </label>
                                                                        <input id="entered_by" type="text" name="entered_by" style="color: black"
                                                                            placeholder="Entered By... "
                                                                            value="{{ old('entered_by', !empty($defectiveItems->entered_by) ? $defectiveItems->entered_by : '') }}"
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            required>
                                                                </div>
                                                            </div>
                                                            <br>

                                                        </div>

                                                        <div class="tab-content" id="pills-tabContent">
                                                            <div class="invoice-detail-items"
                                                                style="padding: 0px 0px 0px 0px;">

                                                                <div class="table-responsive">
                                                                    <table class="table item-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="">
                                                                                </th>
                                                                                <th>
                                                                                </th>
                                                                                <th style="width: 15%;">From Depart</th>
                                                                                <th style="width: 15%;">Product</th>
                                                                                <th style="width: 8%;">P.T</th>
                                                                                <th style="width: 8%;">M.T</th>
                                                                                <th style="width: 8%;">Size</th>
                                                                                <th class="" style="width: 10%;">
                                                                                    Bags/<br>
                                                                                    Units</th>
                                                                                <th class=""style="width: 10%;">
                                                                                    Avg<br>
                                                                                    Weight</th>
                                                                                <th class="" style="width: 10%;">
                                                                                    Tot.Qty</th>
                                                                                <th style="width: 15%;">Remarks</th>

                                                                            </tr>
                                                                            <tr aria-hidden="true"
                                                                                class="mt-3 d-block table-row-hidden">
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @if (!empty($defectiveItemsDetails))
                                                                                @foreach ($defectiveItemsDetails as $defectiveItemsDetail)
                                                                                    @php
                                                                                        $index = $loop->index + 2; // Starts from 2
                                                                                    @endphp
                                                                                    <tr
                                                                                        class="tr_clone validator_{{ $index }}">
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
                                                                                        <td>
                                                                                            <input type="text"
                                                                                                name="row_id[]"
                                                                                                class="row_id"
                                                                                                value="{{ $index }}"
                                                                                                hidden>
                                                                                        </td>

                                                                                        <td class="department">
                                                                                            <select
                                                                                                id="from_department"
                                                                                                name="from_department[]"
                                                                                                class="form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} custom-select from_department_{{ $index }}">
                                                                                                <option selected="">
                                                                                                    Please select the
                                                                                                    Department</option>
                                                                                                @foreach ($dropDownData['departments'] as $key => $value)
                                                                                                    <option
                                                                                                        value="{{ $key }}"{{ (old('from_department') == $key ? 'selected' : '') || (!empty($defectiveItemsDetail->from_department) ? collect($defectiveItemsDetail->from_department)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                        {{ $value }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </td>

                                                                                        <td class="product">
                                                                                            <select id="product_id"
                                                                                                name="product_id[]"
                                                                                                class="form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} product product_{{ $index }}">
                                                                                                <option selected="">
                                                                                                    Please
                                                                                                    select the
                                                                                                    Items</option>
                                                                                                @foreach ($dropDownData['products'] as $key => $value)
                                                                                                    <option
                                                                                                        value="{{ $key }}"
                                                                                                        {{ (old('product_id') == $key ? 'selected' : '') || (!empty($defectiveItemsDetail->product_id) ? collect($defectiveItemsDetail->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                        {{ $value }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </td>
                                                                                        <br>

                                                                                        <td class="quantity">
                                                                                            <input type="text"
                                                                                                style="color: black;"
                                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_{{ $index }}"
                                                                                                id="packing"
                                                                                                name="packing_type[]"
                                                                                                placeholder="P.T"
                                                                                                value="{{ old('packing_type', !empty($defectiveItemsDetail->packing_type) ? $defectiveItemsDetail->packing_type : '') }}"
                                                                                                readonly>
                                                                                        </td>
                                                                                        <td class="quantity">
                                                                                            <input type="text"
                                                                                                style="color: black; "
                                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_{{ $index }}"
                                                                                                placeholder="M.T"
                                                                                                name="measurement_type[]"
                                                                                                value="{{ old('measurement_type', !empty($defectiveItemsDetail->measurement_type) ? $defectiveItemsDetail->measurement_type : '') }}"
                                                                                                id="measurement"
                                                                                                readonly>
                                                                                        </td>
                                                                                        <td class="quantity">
                                                                                            <input type="text"
                                                                                                style="color: black;"
                                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} size_{{ $index }}"
                                                                                                id="size"
                                                                                                name="size[]"
                                                                                                placeholder="Size"
                                                                                                value="{{ old('size', !empty(@$defectiveItemsDetail->size) ? @$defectiveItemsDetail->size : '') }}"
                                                                                                readonly>
                                                                                        </td>

                                                                                        <td class="bagsQuantity">
                                                                                            <input type="number"
                                                                                                id="bags"
                                                                                                name="bags[]"
                                                                                                value="{{ old('bags', !empty(@$defectiveItemsDetail->bags) ? @$defectiveItemsDetail->bags : '') }}"
                                                                                                style="color: black;"
                                                                                                class="bags form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} bags bags_{{ $index }}"
                                                                                                placeholder="Bags">
                                                                                        </td>
                                                                                        <td
                                                                                            class="measurementQuantity">
                                                                                            <input type="number"
                                                                                                id="avg_weight"
                                                                                                style="color: black;"
                                                                                                name="avg_weight[]"
                                                                                                value="{{ old('measurementType', !empty(@$defectiveItemsDetail->avg_weight) ? @$defectiveItemsDetail->avg_weight : '') }}"
                                                                                                class="avgWeight form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} avgWeight avgWeight_{{ $index }}"
                                                                                                placeholder="Avg Weight">
                                                                                        </td>

                                                                                        <td class="quantity">
                                                                                            <input type="text"
                                                                                                style="color: black;"
                                                                                                id="totalQuantity"
                                                                                                name="total_quantity[]"
                                                                                                class="totalQuantity form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} totalQuantity_{{ $index }}"
                                                                                                value="{{ old('total_quantity', !empty($defectiveItemsDetail->total_quantity) ? $defectiveItemsDetail->total_quantity : '') }}"
                                                                                                placeholder="Tot.Qty">
                                                                                        </td>


                                                                                        <td class="remarks">
                                                                                            <textarea style="margin-top: 0px;" name="remarks[]"
                                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} remarks_{{ $index }}"
                                                                                                id="remarks" cols="30" placeholder="Remarks">{{@$defectiveItemsDetail->remarks }}</textarea>
                                                                                        </td>
                                                                                    </tr>
                                                                                @endforeach

                                                                            @endif

                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <a class="btn btn-dark additem">Add
                                                                    Item</a>
                                                            </div>

                                                        </div>

                                                        <div class="form-group">
                                                            <a href="{{ route('defective.list') }}"
                                                                style="float: right;"
                                                                class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                            {{-- @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                            @endif --}}
                                                            <button type="submit" style="float: right"
                                                                class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                data-bs-container="body" data-bs-placement="right"
                                                                data-bs-content="Tooltip on right">
                                                                @if (!isset($defectiveItems))
                                                                    Save
                                                                @else
                                                                    Update
                                                                @endif
                                                            </button>
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
        $(document).on('click', 'body *', function() {
            $('.avgWeight, .bags ').on("input", function() {
                var row_id = $(this).closest("tr").find(".row_id").val();
                let weight = $(this).closest("tr").find(".avgWeight_" + row_id).val() || 0;
                let bags = $(this).closest("tr").find(".bags_" + row_id).val() || 0;
                if (parseInt(bags) > 0) {
                    $(this).closest("tr").find(".totalQuantity_" + row_id).val(bags * weight);
                } else {
                    $(this).closest("tr").find(".totalQuantity_" + row_id).val('0');
                }
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
                '<td class="department"> <select id="from_department" name="from_department[]" class="form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} custom-select from_department_' +
                currentIndex +
                '"> <option selected="">Please select the Departments</option>@foreach ($dropDownData['departments'] as $key => $value) <option value="{{ $key }}"{{ (old('from_department') == $key ? 'selected' : '') || (!empty($purchaseOrder->from_department) ? collect($purchaseOrder->from_department)->contains($key) : '') ? 'selected' : '' }}>{{ $value }}</option>@endforeach</select> </td>' +
                '<td class="product"> <select id="product_id" name="product_id[]" class="form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} custom-select product_' +
                currentIndex +
                '"> <option selected="">Please select the Items</option>@foreach ($dropDownData['products'] as $key => $value) <option value="{{ $key }}"{{ (old('product_id') == $key ? 'selected' : '') || (!empty($purchaseOrder->product_id) ? collect($purchaseOrder->product_id)->contains($key) : '') ? 'selected' : '' }}>{{ $value }}</option>@endforeach</select> </td>' +
                '<td class="quantity"><input type="text" style="color: black;" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_' +
                currentIndex +
                '"id="packing"  name="packing_type[]" placeholder="P.T" readonly></td>' +
                '<td class="quantity">' +
                ' <input type="text" style="color: black; "  class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_' +
                currentIndex +
                '" placeholder="M.T" name="measurement_type[]" id="measurement" readonly></td>' +

                '<td class="quantity"><input type="text" style="color: black;" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} size_' +
                currentIndex +
                '"id="size" name="size[]" placeholder="Size" readonly></td>' +
                '<td class="quantity"> <input type="text"  class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} bags bags_' +
                currentIndex +
                '"name="bags[]" placeholder="bags"></td>' +
                '<td class="quantity"><input type="text" id="avgWeight" name="avg_weight[]"  class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} avgWeight avgWeight_' +
                currentIndex +
                '"placeholder="Avg Weight" required></td>' +
                '<td class="quantity"><input type="text" style="color: black;" id="totalQuantity" name="total_quantity[]"  class="totalQuantity form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} totalQuantity_' +
                currentIndex +
                '"placeholder="Tot.Qty"></td>' +
                '<td class="remarks"><textarea style="margin-top: 0px;" name="remarks[]" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} remarks_' +
                currentIndex +
                '"id="remarks" cols="30" placeholder="Remarks"></textarea></td>' +
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
                            $(".packing_" + row_id).val(response.name.name);
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
                $('.avgWeight, .bags ').on("input", function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    let weight = $(this).closest("tr").find(".avgWeight_" + row_id).val() || 0;
                    let bags = $(this).closest("tr").find(".bags_" + row_id).val() || 0;
                    // console.log(row_id + ", " + quantity + ", " + price);
                    if (parseInt(bags) > 0) {
                        $(this).closest("tr").find(".totalQuantity_" + row_id).val(bags * weight);
                    } else {
                        $(this).closest("tr").find(".totalQuantity_" + row_id).val('0');
                    }
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
        $('.select2').select2();

        function deleteItemRow() {
            let deleteItem = document.querySelectorAll('.delete-item');
            for (var i = 0; i < deleteItem.length; i++) {
                deleteItem[i].addEventListener('click', function() {
                    this.parentElement.parentNode.parentNode.parentNode.remove();
                })
            }
        }
    </script>
    <script>
        var config = {
            routes: {
                getPartySaleManDetail: "{{ url('sale-order/get-party-sale-man') }}",
                getPartySectorDetail: "{{ url('sale-order/get-party-sale-man-sector') }}",
                getPartyAreaDetail: "{{ url('sale-order/get-party-sale-man-area') }}",
                getProductPackingTypeDetail: "{{ url('purchase-order/get-product-packing-type') }}",
                getProductMeasurementTypeDetail: "{{ url('purchase-order/get-product-measurement-type') }}",
                getProductSizeDetail: "{{ url('purchase-order/get-product-size') }}",
            },
        }
    </script>

    <script src="{{ asset('js/purchaseInvoice.js') }}"></script>

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    </x-slot>

</x-base-layout>
