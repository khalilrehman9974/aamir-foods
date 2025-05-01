<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>
    <x-slot:headerFiles>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @vite(['resources/scss/light/assets/components/timeline.scss'])
        @vite(['resources/scss/light/assets/components/accordions.scss'])
        @vite(['resources/scss/dark/assets/components/accordions.scss'])
        {{-- @vite(['resources/scss/light/assets/elements/alert.scss']) --}}
        {{-- @vite(['resources/scss/dark/assets/elements/alert.scss']) --}}
        <link rel="stylesheet" href="{{ asset('plugins/filepond/filepond.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/filepond/FilePondPluginImagePreview.min.css') }}">
        @vite(['resources/scss/light/plugins/filepond/custom-filepond.scss'])
        @vite(['resources/scss/dark/plugins/filepond/custom-filepond.scss'])
    </x-slot>
    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chart of Inventory</li>
                <li class="breadcrumb-item active" aria-current="page">Detail Account</li>
            </ol>
        </nav>
    </div>


    <div id="tabsSimple" class="col-xl-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <!-- <div class="widget-header">
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>Chart of Accounts</h4>
                            </div>
                        </div>
                    </div> -->
            <div class="widget-content widget-content-detailAccount">
                <div class="simple-pill">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab" tabindex="0">
                            <div id="basic" class="col-lg-12">
                                <div class="statbox widget box box-shadow">
                                    @if (session('error'))
                                        <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4"
                                            role="alert">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-x close" data-bs-dismiss="alert">
                                                    <line x1="18" y1="6" x2="6" y2="18">
                                                    </line>
                                                    <line x1="6" y1="6" x2="18" y2="18">
                                                    </line>
                                                </svg>
                                            </button>
                                            <strong>Error!</strong> {{ session('error') }}
                                        </div>
                                        {{--                                                    <div class="bg-red-500 text-white text-center text-xl m-4 p-4">{{ session('error') }}</div> --}}
                                    @endif
                                    <div class="widget-content widget-content-detailAccount">
                                        <div class="row">
                                            <div class="col-lg-10 col-12 ">
                                                <form
                                                    action="{{ !empty($detailAccount) ? route('co-inventory-detail-account.update') : route('co-inventory-detail-account.save') }}"
                                                    method="POST" class="row g-3 needs-validation"
                                                    enctype="multipart/form-data" autocomplete="off" novalidate>
                                                    @csrf
                                                    <input type="hidden" name="id" id="id"
                                                        value="{{ isset($detailAccount->id) ? $detailAccount->id : '' }}" />
                                                    <div class="form-group input-group ">
                                                        {{-- <div class="col-md-12 mt-2">
                                                            <label for="party" class="form-label">Coa Main
                                                                Head</label>
                                                            <select id="party" type="text" name="coa_main_head"
                                                                class=" form-select" required>
                                                                <option value="">Select Coa Main Head
                                                                </option>
                                                                @foreach ($coaMainHeadAccounts as $key => $value)
                                                                    <option value="{{ $key }}"
                                                                        {{ (old('coa_main_head') == $key ? 'selected' : '') || (!empty($detailAccount->coa_main_head) ? collect($detailAccount->coa_main_head)->contains($key) : '') ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('coa_main_head')
                                                                <span style="color:red". class="invalid-feedback">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <br> --}}

                                                        <div class="col-lg-0 col-12 form-group mb-4">
                                                            <label for="inputState" class="form-label">Main
                                                                Head</label>
                                                            <select id="main-head" name="coa_main_head"
                                                                class="form-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                required>
                                                                <option selected>Please select main head
                                                                </option>
                                                                @foreach ($mainHeads as $index => $value)
                                                                    <option value="{{ $index }}"
                                                                        {{ (old('coa_main_head') == $index ? 'selected' : '') || (!empty($detailAccount->coa_main_head) ? collect($detailAccount->coa_main_head)->contains($index) : '') ? 'selected' : '' }}>
                                                                        {{ $value }}</option>
                                                                @endforeach
                                                            </select>

                                                            @if ($errors->has('coa_main_head'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('coa_main_head') }}
                                                                </div>
                                                            @endif

                                                        </div>
                                                        <br>
                                                        <div class="col-lg-0 col-12 form-group mb-4">
                                                            <label for="inputState" class="form-label">Control
                                                                Head</label>
                                                            @if (!empty($detailAccount))
                                                                <select id="control-head" name="control_head"
                                                                    class="form-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                    required>

                                                                    @foreach ($controlHeads as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ !empty($detailAccount) && $detailAccount->control_head == $key ? 'selected' : '' }}
                                                                            {{ $key == old('control_head') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                {{-- <select id="control-head" name="control_head"
                                                                        class="form-select" required>
                                                                        @foreach ($controlHeads as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ $key == old('control_head') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select> --}}

                                                                <select id="control-head" name="control_head"
                                                                    class="form-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                    required>
                                                                    <option></option>
                                                                </select>

                                                            @endif
                                                            @if ($errors->has('control_head'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('control_head') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <br>
                                                        <div class="col-lg-0 col-12 form-group mb-4">
                                                            <label for="inputState" class="form-label">Sub
                                                                Head</label>
                                                            @if (!empty($detailAccount))
                                                                <select id="sub-head" name="sub_head"
                                                                    class="form-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                    required>

                                                                    @foreach ($subHeads as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ !empty($detailAccount) && $detailAccount->sub_head == $key ? 'selected' : '' }}
                                                                            {{ $key == old('sub') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                {{-- <select id="sub-head" name="sub_head"
                                                                        class="form-select" required>
                                                                        @foreach ($subHeads as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ $key == old('sub_head') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select> --}}
                                                                <select id="sub-head" name="sub_head"
                                                                    class="form-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                    required>
                                                                    <option></option>
                                                                </select>
                                                            @endif
                                                            @if ($errors->has('sub_head'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('sub_head') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <br>
                                                        <div class="col-lg-0 col-12 form-group mb-4">
                                                            <label for="inputState" class="form-label">Sub-Sub
                                                                Head</label>
                                                            @if (!empty($detailAccount))
                                                                <select id="sub-sub-head" name="sub_sub_head"
                                                                    class="form-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} sub-sub-head"
                                                                    required>

                                                                    @foreach ($subSubHeads as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ !empty($detailAccount) && $detailAccount->sub_sub_head == $key ? 'selected' : '' }}
                                                                            {{ $key == old('sub_sub_head') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                {{-- <select id="sub-sub-head" name="sub_sub_head"
                                                                        class="form-select" required>
                                                                        @foreach ($subSubHeads as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ $key == old('sub_sub_head') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select> --}}
                                                                <select id="sub-sub-head" name="sub_sub_head"
                                                                    class="form-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} sub-sub-head"
                                                                    required>
                                                                    <option></option>
                                                                </select>
                                                            @endif
                                                            @if ($errors->has('sub_sub_head'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('sub_sub_head') }}
                                                                </div>
                                                            @endif
                                                        </div>

                                                        {{-- <div class="col-lg-0 col-12 form-group mb-4">
                                                            <label for="inputState" class="form-label">Main
                                                                Head</label>
                                                            <select id="main-head" name="main_head"
                                                                class="form-select" required>
                                                                <option selected>Please select main head
                                                                </option>
                                                                @foreach ($mainHeads as $index => $value)
                                                                    <option value="{{ $index }}"
                                                                        {{ (old('main_head') == $index ? 'selected' : '') || (!empty($detailAccount->main_head) ? collect($detailAccount->main_head)->contains($index) : '') ? 'selected' : '' }}>
                                                                        {{ $value }}</option>
                                                                @endforeach
                                                            </select>

                                                            @if ($errors->has('main_head'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('main_head') }}
                                                                </div>
                                                            @endif

                                                        </div>

                                                        <br>
                                                        <div class="col-lg-0 col-12 form-group mb-4">
                                                            <label for="inputState" class="form-label">Sub
                                                                Head</label>
                                                            @if (!empty($detailAccount))
                                                                <select id="sub-head" name="sub_head"
                                                                    class="form-select" required>

                                                                    @foreach ($subHeads as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ !empty($detailAccount) && $detailAccount->sub_head == $key ? 'selected' : '' }}
                                                                            {{ $key == old('sub') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                <select id="sub-head" name="sub_head"
                                                                    class="form-select">
                                                                    @foreach ($subHeads as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ $key == old('sub_head') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                            @if ($errors->has('sub_head'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('sub_head') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <br>
                                                        <div class="col-lg-0 col-12 form-group mb-4">
                                                            <label for="inputState" class="form-label">Sub Sub
                                                                Head</label>
                                                            @if (!empty($detailAccount))
                                                                <select id="sub-sub-head" name="sub_sub_head"
                                                                    class="form-select sub-sub-head" required>

                                                                    @foreach ($subSubHeads as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ !empty($detailAccount) && $detailAccount->sub_sub_head == $key ? 'selected' : '' }}
                                                                            {{ $key == old('sub') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                <select id="sub-sub-head" name="sub_sub_head"
                                                                    class="form-select sub-sub-head">
                                                                    @foreach ($subHeads as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ $key == old('sub_sub_head') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                            @if ($errors->has('sub_sub_head'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('sub_sub_head') }}
                                                                </div>
                                                            @endif
                                                        </div>--}}


                                                        <div class="col-xl-12 col-lg-12">
                                                            <label class="form-label" for="product-title-input">Price
                                                                Tag</label>
                                                            @if (!empty($detailAccount))
                                                                <select id="priceTag-dropdown" name="priceTag_id"
                                                                    class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} priceTag-dropdown"
                                                                    required>
                                                                    @foreach ($priceTags as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ !empty($detailAccount) && $detailAccount->priceTag_id == $key ? 'selected' : '' }}
                                                                            {{ $key == old('priceTag_id') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                <select id="priceTag-dropdown" name="priceTag_id"
                                                                    class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} priceTag-dropdown"
                                                                    required>
                                                                    <option value="">-- Select Price Tags --
                                                                    </option>
                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- <div class="col-lg- 0 col-12 form-group mb-2">
                                                        <label for="code" class="form-label">
                                                            Account Code</label>
                                                        <input id="code" type="text" name="code"
                                                            style="color: black;"
                                                            value="{{ old('code', !empty($detailAccount->code) ? $detailAccount->code : '') }}"
                                                            class="form-control" readonly>
                                                    </div> --}}

                                                    <div class="col-lg- 0 col-12 form-group mb-2">
                                                        <label for="account_code" class="form-label">
                                                            Account Code</label>
                                                        <input id="account_code" type="text" name="code"
                                                            style="color: black"
                                                            value="{{ old('code', !empty($detailAccount->code) ? $detailAccount->code : '') }}"
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                            readonly>
                                                    </div>
                                                    <br>
                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <label for="name" class="form-label">
                                                            Account Name </label>
                                                        <input id="name" type="text" name="name"
                                                            value="{{ old('name', !empty($detailAccount->name) ? $detailAccount->name : '') }}"
                                                            placeholder="Please Enter Detail Account "
                                                            class="form-control" required>
                                                        @if ($errors->has('name'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('name') }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="col-lg-0 col-12 ">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="packing_type_id"
                                                                    class="form-label">Packing
                                                                    Type</label>
                                                                <select id="packing_type_id" type="text"
                                                                    name="packing_type_id"
                                                                    class="form-control select2 form-control mb-3 custom-select"
                                                                    required>
                                                                    <option value="">Select Packing Type
                                                                    </option>
                                                                    @foreach ($dropDownData['PackingType'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ (old('packing_type_id') == $key ? 'selected' : '') || (!empty($detailAccount->packing_type_id) ? collect($detailAccount->packing_type_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{-- <div class="invalid-feedback">
                                                                            Please Select the Sector.
                                                                        </div> --}}
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="measurement_type_id"
                                                                    class="form-label">Measurement Type</label>
                                                                <select id="measurement_type_id" type="text"
                                                                    name="measurement_type_id"
                                                                    class="form-control select2 form-control mb-3 custom-select"
                                                                    required>
                                                                    <option value="">Select Measurement Type
                                                                    </option>
                                                                    @foreach ($dropDownData['MeasurementTypes'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ (old('measurement_type_id') == $key ? 'selected' : '') || (!empty($detailAccount->measurement_type_id) ? collect($detailAccount->measurement_type_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{-- <div class="invalid-feedback">
                                                                            Please Select the Sector.
                                                                        </div> --}}
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <div class="col-lg-0 col-12 ">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="name" class="form-label">
                                                                    Size </label>
                                                                <input id="size" type="text" name="size"
                                                                    value="{{ old('size', !empty($detailAccount->size) ? $detailAccount->size : '') }}"
                                                                    placeholder="Please The Size "
                                                                    class="form-control">
                                                                @if ($errors->has('size'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('size') }}
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="min_limit" class="form-label">
                                                                    Minimum Limit</label>
                                                                <input id="min_limit" type="text" name="min_limit"
                                                                    value="{{ old('min_limit', !empty($detailAccount->min_limit) ? $detailAccount->min_limit : '') }}"
                                                                    placeholder="Please Enter Minimum limit"
                                                                    class="form-control" required>
                                                                @if ($errors->has('min_limit'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('min_limit') }}
                                                                    </div>
                                                                @endif
                                                            </div>

                                                        </div>

                                                    </div>
                                                    <div class="col-lg-0 col-12 ">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="max_limit" class="form-label">
                                                                    Maximum Limit</label>
                                                                <input id="max_limit" type="text" name="max_limit"
                                                                    value="{{ old('max_limit', !empty($detailAccount->max_limit) ? $detailAccount->max_limit : '') }}"
                                                                    placeholder="Please Enter Maximum limit"
                                                                    class="form-control" required>
                                                                @if ($errors->has('max_limit'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('max_limit') }}
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="danger_level" class="form-label">
                                                                    Danger Level</label>
                                                                <input id="danger_level" type="text"
                                                                    name="danger_level"
                                                                    value="{{ old('danger_level', !empty($detailAccount->danger_level) ? $detailAccount->danger_level : '') }}"
                                                                    placeholder="Please Enter Danger level"
                                                                    class="form-control" required>
                                                                @if ($errors->has('danger_level'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('danger_level') }}
                                                                    </div>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-0 col-12 ">
                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <label for="opening_stock" class="form-label">
                                                                    Opening Stock</label>
                                                                <input id="opening_stock" type="text"
                                                                    name="opening_stock"
                                                                    value="{{ old('opening_stock', !empty($detailAccount->opening_stock) ? $detailAccount->opening_stock : '') }}"
                                                                    placeholder="Opening Stock" class="form-control"
                                                                    required>
                                                                @if ($errors->has('opening_stock'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('opening_stock') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="stock_rate" class="form-label">
                                                                    Stock Rate</label>
                                                                <input id="stock_rate" type="int"
                                                                    name="stock_rate"
                                                                    value="{{ old('stock_rate', !empty($detailAccount->stock_rate) ? $detailAccount->stock_rate : '') }}"
                                                                    placeholder="Stock Rate" class="form-control"
                                                                    required>
                                                                @if ($errors->has('opening_stock'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('opening_stock') }}
                                                                    </div>
                                                                @endif
                                                            </div>


                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="use_in" class="form-label">
                                                                    Use In</label>
                                                                <input id="use_in" type="text" name="use_in"
                                                                    value="{{ old('use_in', !empty($detailAccount->use_in) ? $detailAccount->use_in : '') }}"
                                                                    placeholder="Use In" class="form-control" required>
                                                                @if ($errors->has('use_in'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('use_in') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="rate" class="form-label">
                                                                    Rate</label>
                                                                <input id="rate" type="text" name="rate"
                                                                    value="{{ old('rate', !empty($detailAccount->rate) ? $detailAccount->rate : '') }}"
                                                                    placeholder="Rate" class="form-control" required>
                                                                @if ($errors->has('rate'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('use_in') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6 mt-4">

                                                            <label for="rate" class="form-label">
                                                                <b>For Update</b></label>

                                                            <div class="custom-radio2">
                                                                <input type="checkbox" id="update"
                                                                    name="update" value="1">
                                                                <label for="update">
                                                                    <span class="radio-btn"></span>
                                                                    Update
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-lg-0 col-12 form-group mb-2">
                                                        <label for="account_name" class="form-label">
                                                            Remarks </label>
                                                        <textarea name="remarks" id="remarks" placeholder="Please Enter Remarks "
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" type="text" cols="30"
                                                            rows="5">{{ @$detailAccount->remarks }}</textarea>
                                                    </div>
                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <label for="name" class="form-label">
                                                            Upload Product Image </label>

                                                        <div style='height: 0px;width: 0px; overflow:;'>
                                                            <input id="image" name="image" type="file"
                                                                value="Upload" onchange="sub(this)" />
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <br>

                                                    @if (@$detailAccount)
                                                        <div class="col-lg-0 col-12 form-group mb-4">
                                                            <div class="media">
                                                                <div class="avatar me-2">

                                                                    <img alt="avatar"
                                                                        @if (
                                                                            $detailAccount->image == null ||
                                                                                !file_exists(base_path('public/resources/images/inventory/') . '/' . $detailAccount->image)) src="{{ asset('images/no-attachments.png') }}"

                                                                         @else
                                                                        src="{{ asset('resources/images/inventory/') . '/' . $detailAccount->image }}" @endif
                                                                        class="rounded-circle" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif





                                                    {{--                                                            <div class="control-group input-group"> --}}
                                                    {{--                                                                <input type="file" name="file[]" class="form-control">&nbsp;&nbsp; --}}
                                                    {{--                                                                <div class="input-group-btn"> --}}
                                                    {{--                                                                    <button class="btn btn-danger delete-attachment" type="button"><i --}}
                                                    {{--                                                                            class="glyphicon glyphicon-remove" disabled="disabled"></i>x --}}
                                                    {{--                                                                    </button> --}}
                                                    {{--                                                                </div> --}}
                                                    {{--                                                            </div> --}}
                                                    {{--                                                                <div class="col-md-6 mx-auto"> --}}

                                                    {{--                                                                    <div class="multiple-file-upload"> --}}

                                                    {{--                                                                        <input  onclick="document.getElementById('image').click()" --}}
                                                    {{--                                                                               class="file-upload-multiple" --}}
                                                    {{--                                                                               name="image1" --}}
                                                    {{--                                                                               id="image1"> --}}
                                                    {{--                                                                    </div> --}}
                                                    {{--                                                                </div> --}}

                                                    {{--                                                            <div class="col-md-6 mx-auto"> --}}


                                                    {{--                                                                    <input type="file" --}}
                                                    {{--                                                                           class="" --}}
                                                    {{--                                                                           name="image1" --}}
                                                    {{--                                                                           id="image1"  onclick="getFile()> --}}
                                                    {{--                                                            </div> --}}



                                                    {{--                                                        @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1) --}}
                                                    @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                        <div class="col-lg-0 col-12 form-group mb-4">

                                                            <button type="submit"
                                                                class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                data-bs-container="body" data-bs-placement="right"
                                                                data-bs-content="Tooltip on right">
                                                                @if (!isset($detailAccount))
                                                                    Save
                                                                @else
                                                                    Update
                                                                @endif
                                                            </button>
                                                    @endif
                                                    <a href="{{ route('co-inventory-detail-account.list') }}"
                                                        class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
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
        $('.sub-sub-head').on('change', function() {
            var idProduct = this.value;

            $('.priceTag-dropdown').html('');
            $.ajax({
                url: config.routes.getProductPriceTags,
                type: "GET",
                data: {
                    product_id: idProduct,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('.priceTag-dropdown').html(
                        '<option value="">-- Select Price Tag --</option>');
                    $.each(result.priceTags, function(key, data) {
                        $('.priceTag-dropdown').append(
                            '<option value="' +
                            data.id +
                            '">' + data.name + '</option>');
                    });

                }
            });
        });
    </script>


    <x-slot:footerFiles>

        <script src="{{ asset('plugins/filepond/filepond.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImagePreview.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageCrop.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageResize.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageTransform.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>
        {{-- <script src="{{ asset('js/inventory-detail-account.js') }}"></script> --}}
        <script src="{{ asset('js/detail-account.js') }}"></script>

        <script>
            {{-- singleFile.addFiles("{{Vite::asset('resources/images/drag-1.jpeg')}}"); --}}
            {{-- multifiles.addFiles("{{Vite::asset('resources/images/list-blog-style-2.jpeg')}}"); --}}

            var config = {
                routes: {
                    // getSubHeads: "{{ url('co-inv-detail-account/get-sub-head-accounts') }}",
                    // getSubSubHeads: "{{ url('co-inv-detail-account/get-sub-sub-head-accounts') }}",
                    // getDetailAccountCode: "{{ url('co-inv-detail-account/get-detail-account-code') }}",
                    getProductPriceTags: "{{ url('co-inv-detail-account/get-product-price-tags') }}",


                    getControlHeads: "{{ url('sub-head/get-control-head-account') }}",
                    getSubSubHeads: "{{ url('detail-account/get-sub-sub-account') }}",
                    getSubHeads: "{{ url('sub-sub-head/get-sub-heads') }}",
                    getDetailAccountCode: "{{ url('detail-account/get-detail-account-code') }}",
                    getSaleManDetail: "{{ url('detail-account/get-saleMan-detail') }}",
                    getSaleManAreaDetail: "{{ url('detail-account/get-saleMan-area-detail') }}",
                    getProductPrice: "{{ url('detail-account/get-product-price') }}",
                    // getProductPriceTags: "{{ url('detail-account/get-product-price-tags') }}",
                    getProducts: "{{ url('detail-account/get-products') }}"
                },
            }

            // function getFile() {
            //     document.getElementById("image").click();
            // }
            //
            // function sub(obj) {
            //     var file = obj.value;
            //     var fileName = file.split("\\");
            //     document.getElementById("image").innerHTML = fileName[fileName.length - 1];
            //     // document.myForm.submit();
            //     // event.preventDefault();
            // }
        </script>

    </x-slot>
</x-base-layout>
