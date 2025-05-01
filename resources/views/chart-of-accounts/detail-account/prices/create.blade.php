<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>
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
        <link href="../src/plugins/src/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
        <link href="../src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">

    </x-slot>
    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>


    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Price Setting</li>
                <li class="breadcrumb-item"><a href="{{ route('detail-account.pricelist') }}">List</a></li>
                <li class="breadcrumb-item"><a href="{{ route('detail-account.setPrice') }}">Set Price</a></li>
            </ol>
        </nav>
    </div>

    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h2>Set Price</h2>
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
                                                        action="{{ !empty($detailAccountPrice) ? route('detail-account.savePrice') : route('detail-account.savePrice') }}"
                                                        method="POST" autocomplete="off" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($detailAccountPrice->id) ? $detailAccountPrice->id : '' }}" />

                                                        <div class="row justify-content-between">
                                                            <div class="form-group">

                                                                <div class="col-lg-0 col-12 ">
                                                                    <div class="row">
                                                                        <div class="col-md-6 mt-2">
                                                                            <label for="party"
                                                                                class="form-label">Party</label>
                                                                            <select id="party" type="text"
                                                                                name="detail_account_id"
                                                                                placeholder="Please Select the Party Name"
                                                                                class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} party"
                                                                                required>
                                                                                <option value="">Select
                                                                                </option>
                                                                                @foreach ($dropDownData['parties'] as $key => $value)
                                                                                    <option value="{{ $key }}"
                                                                                        {{ (old('detail_account_id') == $key ? 'selected' : '') || (!empty($detailAccountPrice->detail_account_id) ? collect($detailAccountPrice->detail_account_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                        {{ $value }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            @error('detail_account_id')
                                                                                <span style="color:red".
                                                                                    class="invalid-feedback">
                                                                                    <strong>{{ $message }}</strong>
                                                                                </span>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="col-md-6 mt-2">
                                                                            <label for="party" class="form-label">Coa
                                                                                Inventory Forth Level</label>
                                                                            <select id="inventoryThirdLevel"
                                                                                type = "text"
                                                                                name = "inventory_third_level[]"
                                                                                class ="form-control select2 custom-select form-control-sm  inventory_third_level"
                                                                                placeholder = "Please Select the Inv Third Level">
                                                                                <option value = "">Please Select the
                                                                                    Inv Third Level </option>
                                                                                @foreach ($dropDownData['invetoryThirdLevel'] as $key => $value)
                                                                                    <option
                                                                                        value = "{{ $key }}"
                                                                                        {{ (old('inventory_third_level') == $key ? 'selected' : '') || (!empty($detailAccountRecords->inventory_third_level) ? collect($detailAccountRecords->inventory_third_level)->contains($key) : '') ? 'selected' : '' }}>
                                                                                        {{ $value }} </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-0 col-12 ">
                                                                    <div class="row">
                                                                        <div class="col-md-6 mt-2">
                                                                            <label for="party"
                                                                                class="form-label">Party</label>
                                                                            <select id="priceTag_dropdown"
                                                                                type = "text" name = "price_tag_id[]"
                                                                                class ="form-control select2 custom-select form-control-sm priceTag_dropdown"
                                                                                placeholder = "Please Select the Price Tag">
                                                                                <option value = "">Select the Price
                                                                                    Tag </option>
                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="invoice-detail-terms"
                                                                    style="padding: 0px 0px 0px 0px !important;">
                                                                    <div class="tab-content mt-2"
                                                                        id="pills-tabContent">
                                                                        <div class="invoice-detail-items"
                                                                            style="padding:0px 0px 0px 0px !important;">

                                                                            <div class="table-responsive">
                                                                                <table class="table item-table">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>
                                                                                            </th>
                                                                                            <th></th>
                                                                                            {{-- <th scope="col"
                                                                                                style="width: 20%">
                                                                                                Inventory Fourth Lvl
                                                                                            </th>
                                                                                            <th class="">
                                                                                                Price Tag</th> --}}
                                                                                            <th class=""
                                                                                                style="width: 20%">
                                                                                                Product</th>
                                                                                            <th class="">
                                                                                                Price</th>
                                                                                            {{-- <th class="">
                                                                                                Packing Type</th> --}}
                                                                                            <th class="">
                                                                                                Discount
                                                                                            </th>
                                                                                            <th class="text-right"
                                                                                                style="width: 10%">
                                                                                                Scheme</th>


                                                                                        </tr>
                                                                                        <tr aria-hidden="true"
                                                                                            class="mt-3 d-block table-row-hidden">
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>

                                                                                        @if (!empty($detailAccountPriceDetails))
                                                                                            @foreach ($detailAccountPriceDetails as $detailAccountPriceDetail)
                                                                                                @php
                                                                                                    $index =
                                                                                                        $loop->index +
                                                                                                        2;
                                                                                                @endphp
                                                                                                <tr
                                                                                                    class="tr_clone validator_{{ $index }}">
                                                                                                    <td
                                                                                                        class="delete-item-row">
                                                                                                        <ul
                                                                                                            class="table-controls">
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
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            name="row_id[]"
                                                                                                            class="row_id"
                                                                                                            value="{{ $index }}"
                                                                                                            hidden>
                                                                                                    </td>
                                                                                                    {{-- <td
                                                                                                        class="inventoryThirdLevel">
                                                                                                        <select
                                                                                                            id="inventoryThirdLevel"
                                                                                                            type="text"
                                                                                                            name="inventory_third_level[]"
                                                                                                            placeholder="Please Select the Inv Third Level"
                                                                                                            class="{{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} form-control mb-3 inventory_third_level_{{ $rowIndex }} select2 custom-select">
                                                                                                            <option
                                                                                                                value="">
                                                                                                                Select
                                                                                                                the
                                                                                                                Inv
                                                                                                                Third
                                                                                                                Level
                                                                                                            </option>
                                                                                                            @foreach ($dropDownData['invetoryThirdLevel'] as $key => $value)
                                                                                                                <option
                                                                                                                    value="{{ $key }}"
                                                                                                                    {{ (old('inventory_third_level') == $key ? 'selected' : '') || (!empty($detailAccountRecord->inventory_third_level) ? collect($detailAccountRecord->inventory_third_level)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                                    {{ $value }}
                                                                                                                </option>
                                                                                                            @endforeach
                                                                                                        </select>
                                                                                                    </td> --}}
                                                                                                    {{-- <td
                                                                                                        class="price_tag">
                                                                                                        <select
                                                                                                            id="priceTag_dropdown"
                                                                                                            type="text"
                                                                                                            name="price_tag_id[]"
                                                                                                            placeholder="Please Select the Price Tag"
                                                                                                            class="{{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} form-control  mb-3 priceTag_dropdown priceTag_dropdown_{{ $rowIndex }} select2 custom-select">
                                                                                                            <option
                                                                                                                value="">
                                                                                                                Select
                                                                                                                the
                                                                                                                Price
                                                                                                                Tag
                                                                                                            </option>
                                                                                                            @foreach ($dropDownData['priceTags'] as $key => $value)
                                                                                                                <option
                                                                                                                    value="{{ $key }}"
                                                                                                                    {{ (old('price_tag_id') == $key ? 'selected' : '') || (!empty($detailAccountRecord->price_tag_id) ? collect($detailAccountRecord->price_tag_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                                    {{ $value }}
                                                                                                                </option>
                                                                                                            @endforeach
                                                                                                        </select>
                                                                                                    </td> --}}
                                                                                                    <td
                                                                                                        class="product">
                                                                                                        <select
                                                                                                            id="product"
                                                                                                            type="text"
                                                                                                            name="product_id[]"
                                                                                                            placeholder="Please Select the Product"
                                                                                                            class="{{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} form-control mb-3 product product_{{ $index }} select2"
                                                                                                            multiple>
                                                                                                            <option
                                                                                                                value="select-all">
                                                                                                                Select
                                                                                                                All
                                                                                                            </option>
                                                                                                            @foreach ($dropDownData['products'] as $key => $value)
                                                                                                                <option
                                                                                                                    value="{{ $key }}"
                                                                                                                    {{ (old('product_id') == $key ? 'selected' : '') || (!empty($detailAccountProduct->product_id) ? collect($detailAccountProduct->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                                    {{ $value }}
                                                                                                                </option>
                                                                                                            @endforeach
                                                                                                            {{-- @foreach ($products as $key => $value)
                                                                                                                <option
                                                                                                                    value="{{ $key }}"
                                                                                                                    {{ collect($detailAccountProduct->product_id)->contains($key) ? 'selected' : '' }}>
                                                                                                                    {{ $value }}
                                                                                                                </option>
                                                                                                            @endforeach --}}
                                                                                                        </select>
                                                                                                    </td>


                                                                                                    <td
                                                                                                        class="quantity">
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            id="price"
                                                                                                            class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price_{{ $index }}"
                                                                                                            name="price[]"
                                                                                                            value="{{ old('price', !empty($detailAccountProduct->price) ? $detailAccountProduct->price : '') }}"
                                                                                                            placeholder="Price">
                                                                                                    </td>

                                                                                                    <td
                                                                                                        class="quantity">
                                                                                                        <input
                                                                                                            id="scheme"
                                                                                                            type="text"
                                                                                                            name="scheme[]"
                                                                                                            value="{{ old('scheme', !empty($detailAccountProduct->scheme) ? $detailAccountProduct->scheme : '') }}"
                                                                                                            placeholder="Scheme... "
                                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} scheme_{{ $index }}">
                                                                                                    </td>

                                                                                                    <td
                                                                                                        class="quantity">
                                                                                                        <input
                                                                                                            id="discount"
                                                                                                            type="text"
                                                                                                            name="discount[]"
                                                                                                            value="{{ old('discount', !empty($detailAccountProduct->discount) ? $detailAccountProduct->discount : '') }}"
                                                                                                            placeholder="Discount... "
                                                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} discount_{{ $index }}">
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        @endif


                                                                                    </tbody>
                                                                                </table>
                                                                            </div>

                                                                            <a href="javascript:void(0);"
                                                                                class="btn btn-dark additem"
                                                                                id="add-item">Add
                                                                                Item</a>

                                                                        </div>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>



                                                        <div class="form-group">
                                                            <a href="{{ route('detail-account.pricelist') }}"
                                                                style="float: right;"
                                                                class="btn btn-dark rounded bs-popover ml-2 mt-2  mb-4">Cancel</a>
                                                            @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                                <button type="submit" style="float: right"
                                                                    class="btn btn-success  rounded bs-popover me-1 mt-2 mb-4 "
                                                                    data-bs-container="body" data-bs-placement="right"
                                                                    data-bs-content="Tooltip on right">
                                                                    @if (!isset($detailAccountPrice))
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
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.inventory_third_level').on('change', function() {
                var idProduct = this.value;

                var row_id = $(this).closest("tr").find(".row_id").val();

                $(".priceTag_dropdown").html('');
                $.ajax({
                    url: config.routes.getProductPriceTags,
                    type: "GET",
                    data: {
                        product_id: idProduct,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('.priceTag_dropdown').html(
                            '<option value="">-- Select Tag --</option>'
                        );

                        $.each(result.priceTags, function(key, data) {
                            $('.priceTag_dropdown').append(
                                '<option value="' +
                                data.id +
                                '">' + data.name +
                                '</option>');
                        });

                    }
                });
            });




            // $('.priceTag_dropdown').on('change', function() {
            //     var idProduct = $(".inventory_third_level").val();
            //     var idPriceTag = $(".priceTag_dropdown").val();

            //     $('.product_' + currentIndex).html('');
            //     $.ajax({
            //         url: config.routes.getProducts,
            //         type: "GET",
            //         data: {
            //             product_id: idProduct,
            //             priceTag_id: idPriceTag,
            //             _token: '{{ csrf_token() }}'
            //         },
            //         dataType: 'json',

            //         success: function(result) {
            //             $('.product_' + currentIndex).html(
            //                 '<option value="select-all">-- Select All --</option>'
            //             );
            //             $.each(result.products,
            //                 function(key,
            //                     data) {
            //                     $('.product_' +
            //                             currentIndex)
            //                         .append(
            //                             '<option value="' +
            //                             data
            //                             .id + '">' +
            //                             data.name +
            //                             '</option>'
            //                         );
            //                 });
            //         }
            //     });
            // });
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

                // '<td class="inventoryThirdLevel">' +
                // '<select id="inventoryThirdLevel" type = "text" name = "inventory_third_level[]" class ="form-control select2 custom-select form-control-sm  inventory_third_level_' +
                // currentIndex +
                // '" placeholder = "Please Select the Inv Third Level"  ><option value = "" >Please Select the Inv Third Level </option> @foreach ($dropDownData['invetoryThirdLevel'] as $key => $value)<option value = "{{ $key }}" {{ (old('inventory_third_level') == $key ? 'selected' : '') || (!empty($detailAccountRecords->inventory_third_level) ? collect($detailAccountRecords->inventory_third_level)->contains($key) : '') ? 'selected' : '' }} >{{ $value }} </option> @endforeach </select>' +
                // '</td>' +


                // '<td class="price_tag">' +
                // '<select id="priceTag_dropdown" type = "text" name = "price_tag_id[]" class ="form-control select2 custom-select form-control-sm priceTag_dropdown priceTag_dropdown_' +
                // currentIndex +
                // '" placeholder = "Please Select the Price Tag"  ><option value = "" >Select the Price Tag </option>  </select> ' +
                // '</td>' +

                '<td class="product">' +
                '<select id="product" type = "text" name="product_id[]" class ="form-control form-control-sm product product_' +
                currentIndex +
                ' select2 custom-select" placeholder = "Please Select the Product" multiple><option value="select-all" >Select All </option> </select> ' +
                '</td>' +


                '<td class="quantity">' +
                '<input type="text" id="price" class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price_' +
                currentIndex +
                '" name = "price[]" value = "{{ old('price', !empty($detailAccountProduct->price) ? $detailAccountProduct->price : '') }}" placeholder = "Price" > ' +
                '</td>' +

                '<td class="quantity">' +
                '<input id="scheme" type="text" name="scheme[]" value="{{ old('scheme', !empty($detailAccountProduct->scheme) ? $detailAccountProduct->scheme : '') }}" placeholder="Scheme... " class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} scheme_' +
                currentIndex +
                '">' +
                '</td>' +

                '<td class="quantity">' +
                '<input id="discount" type="text" name="discount[]" value="{{ old('discount', !empty($detailAccountProduct->discount) ? $detailAccountProduct->discount : '') }}" placeholder="Discount... " class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} discount_' +
                currentIndex + '">' +
                '</td>' +

                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';
            $(".item-table tbody").append($html);
            deleteItemRow();
            fetchProducts(currentIndex);
            $('.product_' + currentIndex).select2();
            $(document).ready(function() {
                $('.select2').select2();
            });
            $(document).ready(function() {
                $('.product_' + currentIndex).select2();
            });

            $(document).ready(function() {
                // $('.inventory_third_level').on('change', function() {
                //     var idProduct = this.value;

                //     var row_id = $(this).closest("tr").find(".row_id").val();

                //     $(".priceTag_dropdown").html('');
                //     $.ajax({
                //         url: config.routes.getProductPriceTags,
                //         type: "GET",
                //         data: {
                //             product_id: idProduct,
                //             _token: '{{ csrf_token() }}'
                //         },
                //         dataType: 'json',
                //         success: function(result) {
                //             $('.priceTag_dropdown').html(
                //                 '<option value="">-- Select Tag --</option>'
                //             );

                //             $.each(result.priceTags, function(key, data) {
                //                 $('.priceTag_dropdown').append(
                //                     '<option value="' +
                //                     data.id +
                //                     '">' + data.name +
                //                     '</option>');
                //             });

                //         }
                //     });
                // });




                // $('.priceTag_dropdown').on('change', function() {
                //     var idProduct = $(".inventory_third_level").val();
                //     var idPriceTag = $(".priceTag_dropdown").val();

                //     $('.product_' + currentIndex).html('');
                //     $.ajax({
                //         url: config.routes.getProducts,
                //         type: "GET",
                //         data: {
                //             product_id: idProduct,
                //             priceTag_id: idPriceTag,
                //             _token: '{{ csrf_token() }}'
                //         },
                //         dataType: 'json',

                //         success: function(result) {
                //             $('.product_' + currentIndex).html(
                //                 '<option value="select-all">-- Select All --</option>'
                //             );
                //             $.each(result.products,
                //                 function(key,
                //                     data) {
                //                     $('.product_' +
                //                             currentIndex)
                //                         .append(
                //                             '<option value="' +
                //                             data
                //                             .id + '">' +
                //                             data.name +
                //                             '</option>'
                //                         );
                //                 });
                //         }
                //     });
                // });
                $('.product_' + currentIndex).on('change', function() {
                    var $this = $(this);
                    var selectedValues = Array.from(this.selectedOptions).map(option => option
                        .value);

                    // If "Select All" is selected
                    if (selectedValues.includes("select-all")) {
                        // Deselect "Select All" itself
                        $this.find('option[value="select-all"]').prop('selected', false);

                        // Select all other options
                        $this.find('option').not('[value="select-all"]').prop('selected', true);

                        // Update Select2 UI
                        $this.trigger('change.select2');

                        // Refresh selected values after selecting all
                        selectedValues = Array.from($this[0].selectedOptions).map(option => option
                            .value);
                    }

                    // If nothing is selected, deselect all
                    if (selectedValues.length === 0) {
                        $this.find('option').prop('selected', false);
                        $this.trigger('change.select2');
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

        function deleteItemRow() {
            let deleteItem = document.querySelectorAll('.delete-item');
            for (var i = 0; i < deleteItem.length; i++) {
                deleteItem[i].addEventListener('click', function() {
                    this.parentElement.parentNode.parentNode.parentNode.remove();
                })
            }
        }


        // function fetchProducts(index) {
        //     var idProduct = $(".inventory_third_level").val();
        //     var idPriceTag = $(".priceTag_dropdown").val();

        //     $('.product_' + currentIndex).html('');
        //     $.ajax({
        //         url: config.routes.getProducts,
        //         type: "GET",
        //         data: {
        //             product_id: idProduct,
        //             priceTag_id: idPriceTag,
        //             _token: '{{ csrf_token() }}'
        //         },
        //         dataType: 'json',

        //         success: function(result) {
        //             $('.product_' + currentIndex).html(
        //                 '<option value="select-all">-- Select All --</option>'
        //             );
        //             $.each(result.products,
        //                 function(key,
        //                     data) {
        //                     $('.product_' +
        //                             currentIndex)
        //                         .append(
        //                             '<option value="' +
        //                             data
        //                             .id + '">' +
        //                             data.name +
        //                             '</option>'
        //                         );
        //                 });
        //         }
        //     });
        // }

        function fetchProducts($request) {
            var idProduct = $('.inventory_third_level').val();
            var idPriceTag = $('.priceTag_dropdown').val();

            // $('.product_' + currentIndex).html(''); // Clear current product options

            $.ajax({
                url: config.routes.getProducts,
                type: "GET",
                data: {
                    product_id: idProduct,
                    priceTag_id: idPriceTag,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('.product_' + $request).html('<option value="select-all">-- Select All --</option>');
                    $.each(result.products, function(key, data) {
                        $('.product_' + $request).append(
                            '<option value="' + data.id + '">' + data.name + '</option>'
                        );
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Fetch Products Error:", error);
                }
            });
        }


        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    <script>
        .invoice - detail - items {
            padding: 0 px!important;
            padding: 0 px 0 px!important;
        }
    </script>

    <script>
        var config = {
            routes: {
                getProductPrice: "{{ url('detail-account/get-product-price') }}",
                getProductPriceTags: "{{ url('detail-account/get-product-price-tags') }}",
                getProducts: "{{ url('detail-account/get-products') }}"
            },
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
    </x-slot>
</x-base-layout>
