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
                            <h2>Update Price</h2>
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
                                        <div class="row">
                                            <div class="col-lg-12 col-12 ">
                                                <form
                                                    action="{{ !empty($detailAccountPrices) ? route('detail-account.savePrice') : route('detail-account.savePrice') }}"
                                                    method="POST" autocomplete="off" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="id" id="id"
                                                        value="{{ isset($detailAccountPrices->id) ? $detailAccountPrices->id : '' }}" />

                                                    <div class="row justify-content-between">
                                                        <div class="form-group">

                                                            <div class="col-lg-0 col-12 ">
                                                                <div class="row">


                                                                    <div class="col-md-6 mt-2">
                                                                        <label for="party"
                                                                            class="form-label">Party</label>
                                                                        {{-- <select id="party" type="text"
                                                                                name="detail_account_id"
                                                                                placeholder="Please Select the Party Name"
                                                                                class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} party"
                                                                                required>
                                                                                <option value="">Select
                                                                                </option>
                                                                                @foreach ($dropDownData['parties'] as $key => $value)
                                                                                    <option value="{{ $key }}"
                                                                                        {{ (old('detail_account_id') == $key ? 'selected' : '') || (!empty($detailAccountPrices->detail_account_id) ? collect($detailAccountPrices->detail_account_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                        {{ $value }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select> --}}

                                                                        <select
                                                                            class="mb-3 form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            style="color: black;" disabled>
                                                                            <option selected>
                                                                                {{ $dropDownData['parties'][$detailAccountPrices->detail_account_id] ?? 'Party Not Found' }}
                                                                            </option>
                                                                        </select>
                                                                        <input type="hidden" name="detail_account_id"
                                                                            value="{{ $detailAccountPrices->detail_account_id }}">
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
                                                                        {{-- <select id="inventoryThirdLevel"
                                                                                type = "text"
                                                                                name = "master_third_level[]"
                                                                                class ="form-control select2 custom-select form-control-sm  master_third_level"
                                                                                placeholder = "Please Select the Inv Third Level">
                                                                                <option value = "">Please Select the
                                                                                    Inv Third Level </option>
                                                                                @foreach ($dropDownData['invetoryThirdLevel'] as $key => $value)
                                                                                    <option
                                                                                        value = "{{ $key }}"
                                                                                        {{ (old('master_third_level') == $key ? 'selected' : '') || (!empty($detailAccountPrices->master_third_level) ? collect($detailAccountPrices->master_third_level)->contains($key) : '') ? 'selected' : '' }}>
                                                                                        {{ $value }} </option>
                                                                                @endforeach
                                                                            </select> --}}

                                                                        <select
                                                                            class="mb-3 form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            style="color: black;" disabled>
                                                                            <option selected>
                                                                                {{ $dropDownData['invetoryThirdLevel'][$detailAccountPrices->master_third_level] ?? 'Inventory Not Found' }}
                                                                            </option>
                                                                        </select>
                                                                        <input type="hidden" name="master_third_level"
                                                                            value="{{ $detailAccountPrices->master_third_level }}">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-0 col-12 ">
                                                                <div class="row">
                                                                    <div class="col-md-6 mt-2">
                                                                        <label for="party" class="form-label">Price
                                                                            Tag</label>
                                                                        {{-- <select id="priceTag_dropdown"
                                                                                type = "text"
                                                                                name = "master_price_tag[]"
                                                                                class ="form-control select2 custom-select form-control-sm priceTag_dropdown"
                                                                                placeholder = "Please Select the Price Tag">
                                                                                <option value = "">Select the Price
                                                                                    Tag </option>
                                                                                @foreach ($dropDownData['priceTags'] as $key => $value)
                                                                                    <option
                                                                                        value = "{{ $key }}"
                                                                                        {{ (old('master_price_tag') == $key ? 'selected' : '') || (!empty($detailAccountPrices->master_price_tag) ? collect($detailAccountPrices->master_price_tag)->contains($key) : '') ? 'selected' : '' }}>
                                                                                        {{ $value }} </option>
                                                                                @endforeach
                                                                            </select> --}}

                                                                        <select
                                                                            class="mb-3 form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            style="color: black;" disabled>
                                                                            <option selected>
                                                                                {{ $dropDownData['priceTags'][$detailAccountPrices->master_price_tag] ?? 'Price Tag Not Found' }}
                                                                            </option>
                                                                        </select>
                                                                        <input type="hidden" name="master_price_tag"
                                                                            value="{{ $detailAccountPrices->master_price_tag }}">

                                                                    </div>

                                                                    <div class="col-md-6 mt-2">
                                                                        <label for="party"
                                                                            class="form-label">Product</label>
                                                                        {{-- <select id="product_id" type = "text"
                                                                                name = "product_id[]"
                                                                                class ="form-control select2 custom-select form-control-sm product"
                                                                                placeholder = "Please Select the Product">
                                                                                <option value = "">Select the
                                                                                    Product </option>
                                                                                @foreach ($dropDownData['products'] as $key => $value)
                                                                                    <option
                                                                                        value = "{{ $key }}"
                                                                                        {{ (old('product_id') == $key ? 'selected' : '') || (!empty($detailAccountPrices->product_id) ? collect($detailAccountPrices->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                        {{ $value }} </option>
                                                                                @endforeach
                                                                            </select> --}}

                                                                        <select
                                                                            class="mb-3 form-control select2 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                            style="color: black;" disabled>
                                                                            <option selected>
                                                                                {{ $dropDownData['products'][$detailAccountPrices->product_id] ?? 'Product Not Found' }}
                                                                            </option>
                                                                        </select>
                                                                        <input type="hidden" name="product_id"
                                                                            value="{{ $detailAccountPrices->product_id }}">

                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="col-lg-0 col-12 ">
                                                                <div class="row">
                                                                    <div class="col-md-6 mt-2">
                                                                        <label for="party"
                                                                            class="form-label">Price</label>
                                                                        <input id="price" type="text"
                                                                            name="price"
                                                                            value="{{ old('price', !empty($detailAccountPrices->price) ? $detailAccountPrices->price : '') }}"
                                                                            placeholder="price... "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                    </div>

                                                                    <div class="col-md-6 mt-2">
                                                                        <label for="party"
                                                                            class="form-label">Discount</label>
                                                                        <input id="discount" type="text"
                                                                            name="discount"
                                                                            value="{{ old('discount', !empty($detailAccountPrices->discount) ? $detailAccountPrices->discount : '') }}"
                                                                            placeholder="Discount... "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-0 col-12 ">
                                                                <div class="row">
                                                                    <div class="col-md-6 mt-2">
                                                                        <label for="party"
                                                                            class="form-label">Scheme</label>
                                                                        <input id="scheme" type="text"
                                                                            name="scheme"
                                                                            value="{{ old('scheme', !empty($detailAccountPrices->scheme) ? $detailAccountPrices->scheme : '') }}"
                                                                            placeholder="Scheme... "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
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
                                                                @if (!isset($detailAccountPrices))
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
        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
    </x-slot>
</x-base-layout>
