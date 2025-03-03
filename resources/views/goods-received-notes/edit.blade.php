<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->

        <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />

        <!--  BEGIN CUSTOM STYLE FILE  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
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
                                <div class="row mt-3">
                                    <div class="col-md-6">

                                        <label for="purchase_order_no">PO Number</label>
                                        <input type="text" value="{{ $note->purchase_order_no }}"
                                            name="purchase_order_no"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            style="color: black;" id="purchase_order_no" placeholder="PO Number..."
                                            required readonly>
                                        @error('purchase_order_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                    <div class="col-md-6">

                                        <label for="grn_no">GRN Number</label>
                                        <input type="text" value="{{ $maxid }}" name="grn_no"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            id="grn_no" placeholder="GRN Number..." style="color: black;" required
                                            readonly>
                                        @error('grn_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                                <br>
                                <div class="row mt-3">


                                    <div class="col-md-6 ">
                                        <label for="date">
                                            Date</label>
                                        <input type="text" value="{{$date}}"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} "
                                            id="date" name="date" placeholder="Select The Date">
                                    </div>


                                    {{-- <div class="col-md-6">
                                        <label for="supplier_name">Supplier Name</label>
                                        <input type="text" value="{{ old('supplier_name', @$note->supplier_name) }}"
                                            name="supplier_name"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            id="supplier_name" placeholder="Name..." required>
                                        @error('supplier_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div> --}}
                                    <div class="col-md-6">
                                        <label for="party" class="form-label">Party</label>
                                        <select id="party" type="text" name="party_id"
                                            placeholder="Please Select the Party Name"
                                            class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} "
                                            required>
                                            @foreach ($parties as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('party_id') == $key ? 'selected' : '') || (!empty($note->party_id) ? collect($note->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('party_id')
                                            <span style="color:red". class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <br>
                                <div class="row mt-3">

                                    <div class="col-md-6">

                                        <label for="supplier_bill_no">Supplier Bill No</label>
                                        <input type="text"
                                            value="{{ old('supplier_bill_no', @$note->supplier_bill_no) }}"
                                            name="supplier_bill_no"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            id="supplier_bill_no" placeholder="Bill No..." required>
                                        @error('supplier_bill_no')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>

                                    <div class="col-md-6">

                                        <label for="inputState" class="form-label">Transporter</label>
                                        <select id="transporter_id" name="transporter_id"
                                            class="form-select select2 mb-3 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                            <option selected="">Please select the
                                                transporter</option>
                                            @foreach ($dropDownData['transporters'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('transporter_id') == $key ? 'selected' : '') || (!empty($note->transporter_id) ? collect($note->transporter_id)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>


                                <div class="row mb-5">

                                    <div class="form-group col-md-6 ">
                                        <label for="fare">Fare</label>
                                        <input type="number" name="fare" value="{{ old('fare', @$note->fare) }}"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            id="fare" placeholder="fare">
                                        @error('fare')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6 ">
                                        <label for="unloaded_by">Unloaded By:</label>
                                        <input type="text" name="unloaded_by"
                                            value="{{ old('unloaded_by', @$note->unloaded_by) }}"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} unloaded_by"
                                            id="unloaded_by" placeholder="Unloaded By">
                                        @error('unloaded_by')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="invoice-detail-items" style="padding: 0px 0px 0px 0px;">

                                        <div class="table-responsive">
                                            <table class="table item-table">
                                                <thead>
                                                    <tr>
                                                        <th class="">
                                                        </th>
                                                        <th>
                                                        </th>
                                                        <th style="width: 15%;">Product</th>
                                                        <th style="width: 7%;">P.T</th>
                                                        <th style="width: 7%;">M.T</th>
                                                        <th>Size</th>
                                                        <th class="">
                                                            Bags/
                                                            Units</th>
                                                        <th class="">
                                                            Measurement Type</th>

                                                        <th class="">
                                                            Received Qty</th>
                                                        <th class="">
                                                            PO Qty</th>
                                                        <th class="">
                                                            Balance</th>
                                                        <th class="" style="width: 13%;">
                                                            Remarks</th>

                                                    </tr>
                                                    <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if (!empty($note_details))
                                                        @foreach ($note_details as $note_detail)
                                                            @php
                                                                $index = $loop->index + 2; // Starts from 2
                                                            @endphp
                                                            <tr class="tr_clone validator_{{ $index }}">
                                                                <td class="delete-item-row">
                                                                    <ul class="table-controls">
                                                                        <li>
                                                                            <a href="javascript:void(0);"
                                                                                class="delete-item"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top" title=""
                                                                                data-original-title="Delete">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor"
                                                                                    stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    class="feather feather-x-circle">
                                                                                    <circle cx="12"
                                                                                        cy="12" r="10">
                                                                                    </circle>
                                                                                    <line x1="15"
                                                                                        y1="9" x2="9"
                                                                                        y2="15">
                                                                                    </line>
                                                                                    <line x1="9"
                                                                                        y1="9" x2="15"
                                                                                        y2="15">
                                                                                    </line>
                                                                                </svg>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="row_id[]"
                                                                        class="row_id" value="{{ $index }}"
                                                                        hidden>
                                                                </td>

                                                                <td class="product">
                                                                    <select id="product_id" name="product_id[]"
                                                                        class="form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} product product_{{ $index }}">
                                                                        <option selected="">Please
                                                                            select the
                                                                            Items</option>
                                                                        @foreach ($dropDownData['products'] as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ (old('product_id') == $key ? 'selected' : '') || (!empty($note_detail->product_id) ? collect($note_detail->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <br>
                                                                <td class="quantity">
                                                                    <input type="text" style="color: black; "
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_{{ $index }}"
                                                                        placeholder="M.T" name="measurement_type[]"
                                                                        value="{{ old('measurement_type', !empty($note_detail->measurement_type) ? $note_detail->measurement_type : '') }}"
                                                                        id="measurement" readonly>
                                                                </td>
                                                                <td class="quantity">
                                                                    <input type="text" style="color: black;"
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_{{ $index }}"
                                                                        id="packing" name="packing_type[]"
                                                                        placeholder="P.T"
                                                                        value="{{ old('packing_type', !empty($note_detail->packing_type) ? $note_detail->packing_type : '') }}"
                                                                        readonly>
                                                                </td>
                                                                <td class="quantity">
                                                                    <input type="text" style="color: black;"
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} size_{{ $index }}"
                                                                        id="size" name="size[]"
                                                                        placeholder="Size"
                                                                        value="{{ old('size', !empty(@$note_detail->size) ? @$note_detail->size : '') }}"
                                                                        readonly>
                                                                </td>

                                                                <td class="bagsQuantity">
                                                                    <input type="number" id="bags"
                                                                        name="bags[]"
                                                                        class="bags form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} bags bags_{{ $index }}"
                                                                        placeholder="Bags" >
                                                                </td>
                                                                <td class="measurementQuantity">
                                                                    <input type="number" id="measurementType"
                                                                        name="measurementType[]"
                                                                        class="measurementType form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurementType measurementType_{{ $index }}"
                                                                        placeholder="M.T" >
                                                                </td>


                                                                <td class="receivedQuantity">
                                                                    <input type="number" id="received_qty"
                                                                        name="received_qty[]"
                                                                        value="{{ old('received_qty', !empty($note_detail->received_qty) ? $note_detail->received_qty : '') }}"
                                                                        class="received_qty form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} received_qty received_qty_{{ $index }}"
                                                                        placeholder="R.Qty" required>
                                                                </td>
                                                                <td class="po_quantity">
                                                                    <input type="text" style="color: black;" id="po_quantity"
                                                                        value="{{ old('po_quantity', !empty($note_detail->po_quantity) ? $note_detail->po_quantity : '') }}"
                                                                        class="po_quantity form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} po_quantity_{{ $index }}"
                                                                        name="po_quantity[]" placeholder="PO.Qty"
                                                                        readonly>
                                                                </td>

                                                                <td class="quantity">
                                                                    <input type="text" style="color: black;"
                                                                        id="balance" name="balance[]"
                                                                        value="{{ old('balance', !empty($note_detail->balance) ? $note_detail->balance : 0) }}"
                                                                        class="balance form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} balance_{{ $index }}"
                                                                        placeholder="balance" readonly>
                                                                </td>
                                                                <td class="remarks">
                                                                    <textarea style="margin-top: 0px;" name="detail_remarks[]"
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} detail_remarks_{{ $index }}"
                                                                        id="detail_remarks" cols="30" placeholder="Remarks">{{ @$note_detail->detail_remarks }}</textarea>
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

                                    {{-- <div class="col-md-12"> --}}

                                    <div class="col-md-6" style="float: right; padding-left: 15%;">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="client-phone">Tot Qty:</label>
                                            </div>
                                            <div class="col-md-4" style="width: 60%;">

                                                <input type="text" style="color: black;"
                                                    value="{{ old('total_quantity', !empty($note->total_quantity) ? $note->total_quantity : '') }}"
                                                    id="total_quantity" name="total_quantity"
                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                    id="client-phone" placeholder="Tot.Qty" readonly>
                                            </div>
                                        </div>

                                    </div>


                                    {{-- </div> --}}
                                </div>
                                <div class="form-group mt-5 mb-4">
                                    <label for="exampleFormControlTextarea1">Remarks</label>
                                    <textarea class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" name="remarks"
                                        id="remarks" rows="3">{{ @$note->remarks }}</textarea>
                                </div>
                                <a href="{{ route('grn.list') }}" style="float: right;"
                                    class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                <button type="submit" style="float: right"
                                    class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                    data-bs-container="body" data-bs-placement="right"
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
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).on('click', 'body *', function() {

            $('.bags, .measurementType').on("input", function() {
                var row_id = $(this).closest("tr").find(".row_id").val();
                let bagsQuantity = parseInt($(this).closest("tr").find(".bags_" + row_id).val(), 10) || 0;
                let measurementQty = parseInt($(this).closest("tr").find(".measurementType_" + row_id)
                    .val(), 10) || 0;
                // console.log(row_id + ", " + quantity + ", " + price);
                if (parseInt(measurementQty) > 0) {
                    $(this).closest("tr").find(".received_qty_" + row_id).val(bagsQuantity *
                        measurementQty);
                } else {
                    $(this).closest("tr").find(".received_qty_" + row_id).val('');
                }

                let poQuantity = $(this).closest("tr").find(".po_quantity_" + row_id).val();
                let receivedQty = $(this).closest("tr").find(".received_qty_" + row_id).val() || 0;
                // console.log(row_id + ", " + quantity + ", " + price);
                if (parseInt(poQuantity) > 0) {
                    $(this).closest("tr").find(".balance_" + row_id).val(receivedQty - poQuantity);
                } else {
                    $(this).closest("tr").find(".balance_" + row_id).val('');
                }
                doAmountTotal();
            });

            $('.received_qty, .po_quantity').on("input", function() {
                var row_id = $(this).closest("tr").find(".row_id").val();
                let poQuantity = $(this).closest("tr").find(".po_quantity_" + row_id).val();
                let receivedQty = $(this).closest("tr").find(".received_qty_" + row_id).val();
                // console.log(row_id + ", " + quantity + ", " + price);
                if (parseInt(poQuantity) > 0) {
                    $(this).closest("tr").find(".balance_" + row_id).val(receivedQty - poQuantity);
                } else {
                    $(this).closest("tr").find(".balance_" + row_id).val('');
                }
                doAmountTotal();
            });



            $('.delete-item').on("click", function() {
                doAmountTotal();
            });

            function doAmountTotal() {
                $('#received_qty').text("");
                var totalQuantity = 0;
                $(".received_qty").each(function() {
                    if (!isNaN(this.value) && this.value.length != 0) {
                        totalQuantity += parseFloat(this.value);
                    }
                });
                $('#total_quantity').val(totalQuantity.toFixed(2));
                // $('#net-amount').val(totalAmount.toFixed(2));
            }





        });

        $(document).on('click', 'body *', function() {
            $('.balance').on("input", function() {
                doAmountTotal();
            });

            $('.delete-item').on("click", function() {
                doAmountTotal();
            });

            function doAmountTotal() {
                $('#received_qty').text("");
                var totalQuantity = 0;
                $(".received_qty").each(function() {
                    if (!isNaN(this.value) && this.value.length != 0) {
                        totalQuantity += parseFloat(this.value);
                    }
                });
                $('#total_quantity').val(totalQuantity.toFixed(2));
                // $('#net-amount').val(totalAmount.toFixed(2));
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
                '<td><input type="checkbox" name="row_id[]" class="row_id" value="' + currentIndex +
                '" hidden></td>' +
                '<td class="product"> <select id="product_id" name="product_id[]" class="form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} custom-select product_' +
                currentIndex +
                '"> <option selected="">Please select the Items</option>@foreach ($dropDownData['products'] as $key => $value) <option value="{{ $key }}"{{ (old('product_id') == $key ? 'selected' : '') || (!empty($purchaseOrder->product_id) ? collect($purchaseOrder->product_id)->contains($key) : '') ? 'selected' : '' }}>{{ $value }}</option>@endforeach</select> ' +
                '<td class="quantity">' +
                ' <input type="text" style="color: black; "  class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurement_' +
                currentIndex +
                '" placeholder="M.T" name="measurement_type[]" id="measurement" readonly></td>' +
                '<td class="quantity"><input type="text" style="color: black;" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} packing_' +
                currentIndex +
                '"id="packing"  name="packing_type[]" placeholder="P.T" readonly></td>' +
                '<td class="quantity"><input type="text" style="color: black;" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} size_' +
                currentIndex +
                '"id="size" name="size[]" placeholder="Size" readonly></td>' +

                '<td class="bagsQuantity">' +
                '<input type="number" id="bags" name="bags[]" class="bags form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} bags bags_' +
                currentIndex +
                ' " placeholder="Bags" ></td>' +
                '<td class="measurementQuantity">' +
                '<input type="number" id="measurementType" name="measurementType[]" class="measurementType form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} measurementType measurementType_' +
                currentIndex +
                '"placeholder="M.T" ></td>' +

                '<td class="received_qty"><input type="number" id="received_qty" name="received_qty[]"  class="received_qty form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} received_qty_' +
                currentIndex +
                '"placeholder="R.Qty" required></td>' +

                '<td class="po_quantity"> <input type="text" id="po_quantity" class="po_quantity form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} po_quantity_' +
                currentIndex +
                '"name="po_quantity[]" placeholder="PO.Qty"></td>' +

                '<td class="quantity"><input type="text" style="color: black;" id="balance" name="balance[]"  class="balance form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} balance_' +
                currentIndex +
                '"placeholder="Balance" readonly></td>' +
                '<td class="remarks"><textarea style="margin-top: 0px;" name="detail_remarks[]" class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} detail_remarks_' +
                currentIndex +
                '"id="detail_remarks" cols="30" placeholder="Remarks"></textarea></td>' +
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

                    $(".size_" + row_id).html('');
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
                    $(".packing_" + row_id).html('');
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
                    $(".measurement_" + row_id).html('');
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

                $('.bags, .measurementType').on("input", function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    let bagsQuantity = parseInt($(this).closest("tr").find(".bags_" + row_id).val(),
                        10) || 0;
                    let measurementQty = parseInt($(this).closest("tr").find(".measurementType_" +
                            row_id)
                        .val(), 10) || 0;
                    // console.log(row_id + ", " + quantity + ", " + price);
                    if (parseInt(measurementQty) > 0) {
                        $(this).closest("tr").find(".received_qty_" + row_id).val(bagsQuantity *
                            measurementQty);
                    } else {
                        $(this).closest("tr").find(".received_qty_" + row_id).val('');
                    }

                    let poQuantity = $(this).closest("tr").find(".po_quantity_" + row_id).val();
                    let receivedQty = $(this).closest("tr").find(".received_qty_" + row_id).val() ||
                        0;
                    // console.log(row_id + ", " + quantity + ", " + price);
                    if (parseInt(poQuantity) > 0) {
                        $(this).closest("tr").find(".balance_" + row_id).val(receivedQty -
                            poQuantity);
                    } else {
                        $(this).closest("tr").find(".balance_" + row_id).val('');
                    }
                    doAmountTotal();
                });

                $('.received_qty, .po_quantity').on("input", function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    let poQuantity = $(this).closest("tr").find(".po_quantity_" + row_id).val();
                    let receivedQty = $(this).closest("tr").find(".received_qty_" + row_id).val();
                    // console.log(row_id + ", " + quantity + ", " + price);
                    if (parseInt(poQuantity) > 0) {
                        $(this).closest("tr").find(".balance_" + row_id).val(receivedQty -
                            poQuantity);
                    } else {
                        $(this).closest("tr").find(".balance_" + row_id).val('');
                    }
                    doAmountTotal();
                });



                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#received_qty').text("");
                    var totalQuantity = 0;
                    $(".received_qty").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalQuantity += parseFloat(this.value);
                        }
                    });
                    $('#total_quantity').val(totalQuantity.toFixed(2));
                    // $('#net-amount').val(totalAmount.toFixed(2));
                }





            });

            $(document).on('click', 'body *', function() {
                $('.balance').on("input", function() {
                    doAmountTotal();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#received_qty').text("");
                    var totalQuantity = 0;
                    $(".received_qty").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalQuantity += parseFloat(this.value);
                        }
                    });
                    $('#total_quantity').val(totalQuantity.toFixed(2));
                    // $('#net-amount').val(totalAmount.toFixed(2));
                }
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
                getProductPackingTypeDetail: "{{ url('purchase-order/get-product-packing-type') }}",
                getProductMeasurementTypeDetail: "{{ url('purchase-order/get-product-measurement-type') }}",
                getProductSizeDetail: "{{ url('purchase-order/get-product-size') }}",
            },
        }
    </script>


    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>

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
