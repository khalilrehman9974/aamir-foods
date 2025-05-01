<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>
    <x-slot:headerFiles>

        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
        @vite(['resources/scss/light/assets/components/timeline.scss'])
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <link rel="stylesheet" href="{{ asset('plugins/sweetalerts2/sweetalerts2.css') }}">
        {{-- @vite(['resources/scss/light/plugins/sweetalerts2/custom-sweetalert.scss'])
        @vite(['resources/scss/dark/plugins/sweetalerts2/custom-sweetalert.scss'])--}}
        @vite(['resources/scss/light/assets/components/accordions.scss'])
        @vite(['resources/scss/dark/assets/components/accordions.scss'])
        @vite(['resources/scss/light/assets/elements/alert.scss'])
        @vite(['resources/scss/dark/assets/elements/alert.scss'])

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

        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}


        {{-- <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />--}}



        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


    </x-slot>

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chart of Accounts</li>
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
            <div class="widget-content widget-content-area">
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
                                        {{-- <div class="bg-red-500 text-white text-center text-xl m-4 p-4">{{ session('error') }}</div> --}}
                                    @endif
                                    <div class="widget-content widget-content-area">
                                        <div class="row">
                                            <div class="col-lg-12 col-12 ">
                                                <form
                                                    action="{{ !empty($detailAccount) ? route('detail-account.update') : route('detail-account.save') }}"
                                                    method="POST" class="row g-3 needs-validation" autocomplete="off"
                                                    novalidate>
                                                    @csrf
                                                    <input type="hidden" name="id" id="id"
                                                        value="{{ isset($detailAccount->id) ? $detailAccount->id : '' }}" />

                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <label for="inputState" class="form-label">Main
                                                            Head</label>
                                                        <select id="main-head" name="main_head"
                                                            class="form-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                            required>
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
                                                                class="form-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
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
                                                                class="form-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
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
                                                    <br>
                                                    <div class="col-lg- 0 col-12 form-group mb-2">
                                                        <label for="account_code" class="form-label">
                                                            Account Code</label>
                                                        <input id="account_code" type="text" name="account_code"
                                                            style="color: black"
                                                            value="{{ old('account_code', !empty($detailAccount->account_code) ? $detailAccount->account_code : '') }}"
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                            readonly>
                                                    </div>
                                                    <br>
                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <label for="account_name" class="form-label">
                                                            Account Name </label>
                                                        <input id="account_name" type="text" name="account_name"
                                                            value="{{ old('account_name', !empty($detailAccount->account_name) ? $detailAccount->account_name : '') }}"
                                                            placeholder="Please Enter Detail Account "
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                            required>
                                                        @if ($errors->has('account_name'))
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first('account_name') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <div class="row">


                                                            <div class="col-md-6">
                                                                <label for="inputState" class="form-label">Sales
                                                                    Man</label>
                                                                <select id="saleMan" name="saleMan_id"
                                                                    class="form-select select2 mb-3 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} saleMan">
                                                                    <option value="">Select Sale Man
                                                                    </option>
                                                                    @foreach ($dropDownData['saleMans'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ (old('saleMan_id') == $key ? 'selected' : '') || (!empty($detailAccount->saleMan_id) ? collect($detailAccount->saleMan_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-xl-6 col-lg-6">
                                                                <label class="form-label"
                                                                    for="product-title-input">Belt</label>
                                                                @if (empty($detailAccount))
                                                                    <select id="sector-dropdown" name="sector_id[]"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} sector-dropdown"
                                                                        multiple>
                                                                        {{-- <option value="select-all"
                                                                            class="select-all-option">Select All
                                                                        </option> --}}
                                                                    </select>

                                                                    {{-- <select id="sector-dropdown" name="zone_id[]" multiple="multiple">
                                                                        <option value="select-all" class="select-all-option">Select All</option>
                                                                        @foreach ($zones as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ (old('zone_id') && in_array($key, old('zone_id'))) || (!empty($saleManZones->pluck('zone_id')->toArray()) && in_array($key, $saleManZones->pluck('zone_id')->toArray())) ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select> --}}
                                                                @else
                                                                    {{-- <select id="sector-dropdown" name="sector" class="form-select"
                                                                        >
                                                                        @foreach ($sectors as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ $key == old('sector') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select> --}}
                                                                    <select id="sector-dropdown" name="sector_id[]"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} sector-dropdown"
                                                                        multiple>
                                                                        @foreach ($sectors as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                @php
$isSelected = old('sector_id') == $key || $detailAccountSectors->pluck('sector_id')->contains($key); @endphp
                                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                        {{-- @foreach ($zones as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ !empty($saleManZones) && $saleManZones->zone_id == $key ? 'selected' : '' }}
                                                                                {{ $key == old('zone_id') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach --}}
                                                                    </select>
                                                                @endif
                                                            </div>


                                                            {{-- <div class="col-md-6">
                                                                <label for="inputState" class="form-label">Sector/Belt
                                                                </label>
                                                                <input id="sector" type="text" name="sector"
                                                                    value="{{ old('sector', !empty($detailAccount->sector) ? $detailAccount->sector : '') }}"
                                                                    placeholder="Belt... "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                    >
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <div class="row">
                                                            <div class="col-xl-6 col-lg-6">
                                                                <label class="form-label"
                                                                    for="product-title-input">Area</label>
                                                                @if (empty($detailAccount))
                                                                    <select id="area-dropdown" name="area_id[]"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} area-dropdown"
                                                                        multiple>
                                                                        <option value="">-- Select Areas --
                                                                        </option>
                                                                    </select>
                                                                @else
                                                                    {{-- <select id="area-dropdown" name="area" class="form-select"
                                                                        >
                                                                        @foreach ($sectors as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ $key == old('sector') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select> --}}
                                                                    <select id="area-dropdown" name="area_id[]"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} area-dropdown"
                                                                        multiple>
                                                                        @foreach ($areas as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                @php
$isSelected = old('area_id') == $key || $detailAccountAreas->pluck('area_id')->contains($key); @endphp
                                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach

                                                                        {{-- @foreach ($zones as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ !empty($saleManZones) && $saleManZones->zone_id == $key ? 'selected' : '' }}
                                                                                {{ $key == old('zone_id') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach --}}
                                                                    </select>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="inputState" class="form-label">Commision
                                                                </label>
                                                                <input id="commision" type="text" name="commision"
                                                                    value="{{ old('commision', !empty($detailAccount->commision) ? $detailAccount->commision : '') }}"
                                                                    placeholder="Commision... "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-0 col-12 form-group mb-2">
                                                        <label for="account_name"
                                                            class="form-label">
                                                            Remarks </label>
                                                        <textarea name="remarks" id="remarks" placeholder="Please Enter Remarks "
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" type="text" cols="30"
                                                            rows="5">{{ optional($detailAccountDetails->first())->remarks }}</textarea>
                                                    </div>


                                                    {{-- <div class="invoice-detail-terms"
                                                        style="padding: 0px 0px 0px 0px !important;">
                                                        <div class="tab-content mt-5" id="pills-tabContent">
                                                            <div class="invoice-detail-items"
                                                                style="padding:0px 0px 0px 0px !important;">

                                                                <div class="table-responsive">
                                                                    <table class="table item-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>
                                                                                </th>
                                                                                <th></th>
                                                                                <th scope="col" style="width: 15%">
                                                                                    Inventory 3rd Level</th>
                                                                                <th scope="col" style="width: 15%">
                                                                                    Price Tag</th>
                                                                                <th class="col">
                                                                                    Product</th>
                                                                                <th class="">
                                                                                    Price</th>
                                                                                <th class="">
                                                                                    Scheme</th>
                                                                                <th class="">
                                                                                    Discount</th>
                                                                                <th></th>
                                                                            </tr>
                                                                            <tr aria-hidden="true"
                                                                                class="mt-3 d-block table-row-hidden">
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                            @if (!empty($detailAccountRecords))
                                                                                @foreach ($detailAccountRecords as $index => $detailAccountRecord)
                                                                                    @php
                                                                                        $selectedPriceTag =
                                                                                            $detailAccountRecord->price_tag_id;
                                                                                        $filteredProducts = $detailAccountProducts->where(
                                                                                            'master_price_tag',
                                                                                            $selectedPriceTag,
                                                                                        );
                                                                                        $rowIndex = $index + 1;
                                                                                    @endphp
                                                                                    <tr class="tr_clone validator_0 main_row main_row_{{ $rowIndex }}"
                                                                                        data-parent-id=parent_row_1>
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
                                                                                                style="width: 0%;"
                                                                                                name="row_id[]"
                                                                                                class="row_id"
                                                                                                value="{{ $rowIndex }}"
                                                                                                hidden>
                                                                                        </td>

                                                                                        <td
                                                                                            class="inventoryThirdLevel">
                                                                                            <select
                                                                                                id="inventoryThirdLevel"
                                                                                                type="text"
                                                                                                name="inventory_third_level[]"
                                                                                                placeholder="Please Select the Inv Third Level"
                                                                                                class="{{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} form-control mb-3 inventory_third_level_{{ $rowIndex }} select2 custom-select">
                                                                                                <option value="">
                                                                                                    Select
                                                                                                    the
                                                                                                    Inv Third Level
                                                                                                </option>
                                                                                                @foreach ($dropDownData['invetoryThirdLevel'] as $key => $value)
                                                                                                    <option
                                                                                                        value="{{ $key }}"
                                                                                                        {{ (old('inventory_third_level') == $key ? 'selected' : '') || (!empty($detailAccountRecord->inventory_third_level) ? collect($detailAccountRecord->inventory_third_level)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                        {{ $value }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </td>

                                                                                        <td class="price_tag">
                                                                                            <select
                                                                                                id="priceTag_dropdown"
                                                                                                type="text"
                                                                                                name="price_tag_id[]"
                                                                                                placeholder="Please Select the Price Tag"
                                                                                                class="{{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} form-control  mb-3 priceTag_dropdown priceTag_dropdown_{{ $rowIndex }} select2 custom-select">
                                                                                                <option value="">
                                                                                                    Select
                                                                                                    the
                                                                                                    Price Tag
                                                                                                </option>
                                                                                                @foreach ($dropDownData['priceTags'] as $key => $value)
                                                                                                    <option
                                                                                                        value="{{ $key }}"
                                                                                                        {{ (old('price_tag_id') == $key ? 'selected' : '') || (!empty($detailAccountRecord->price_tag_id) ? collect($detailAccountRecord->price_tag_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                        {{ $value }}
                                                                                                    </option>
                                                                                                @endforeach
                                                                                            </select>
                                                                                        </td>

                                                                                        <td><a
                                                                                                class="btn btn-dark plus-btn plus_btn_{{ $rowIndex }}">+</a>
                                                                                        </td>



                                                                                    </tr>
                                                                                    @foreach ($filteredProducts as $detailAccountProduct)
                                                                                        @php
                                                                                            $index = $loop->index + 1; // Starts from 2

                                                                                        @endphp
                                                                                        <tr class="child_row"
                                                                                            data-parent-id="parent_row_{{ $index }}">
                                                                                            <td>
                                                                                                <input type="text"
                                                                                                    name="sub_row_id[]"
                                                                                                    class="sub_row_id"
                                                                                                    value="{{ $index }}"
                                                                                                    hidden>
                                                                                            </td>
                                                                                            <td>
                                                                                            </td>

                                                                                            <td class="quantity">
                                                                                                <input
                                                                                                    id= "masterThirdLevel"
                                                                                                    type = "text"
                                                                                                    value="{{ $detailAccountProduct->master_third_level }}"
                                                                                                    name="master_third_level[]"
                                                                                                    class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} masterThirdLevel_{{ $index }}"
                                                                                                    hidden>
                                                                                            </td>
                                                                                            <td class="quantity">
                                                                                                <input
                                                                                                    id= "master_price_tag"
                                                                                                    type = "text"
                                                                                                    value="{{ $detailAccountProduct->master_price_tag }}"
                                                                                                    name="master_price_tag[]"
                                                                                                    class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} master_price_tag_{{ $index }}"
                                                                                                    hidden>
                                                                                            </td>
                                                                                            <td class="product">
                                                                                                <select id="product"
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
                                                                                                    {{-- @foreach ($dropDownData['products'] as $key => $value)
                                                                                                        <option
                                                                                                            value="{{ $key }}"
                                                                                                            {{ (old('product_id') == $key ? 'selected' : '') || (!empty($detailAccountProduct->product_id) ? collect($detailAccountProduct->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                            {{ $value }}
                                                                                                        </option>
                                                                                                    @endforeach /////
                                                                                                    @foreach ($products as $key => $value)
                                                                                                        <option
                                                                                                            value="{{ $key }}"
                                                                                                            {{ collect($detailAccountProduct->product_id)->contains($key) ? 'selected' : '' }}>
                                                                                                            {{ $value }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </td>


                                                                                            <td class="quantity">
                                                                                                <input type="text"
                                                                                                    id="price"
                                                                                                    class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price_{{ $index }}"
                                                                                                    name="price[]"
                                                                                                    value="{{ old('price', !empty($detailAccountProduct->price) ? $detailAccountProduct->price : '') }}"
                                                                                                    placeholder="Price">
                                                                                            </td>

                                                                                            <td class="quantity">
                                                                                                <input id="scheme"
                                                                                                    type="text"
                                                                                                    name="scheme[]"
                                                                                                    value="{{ old('scheme', !empty($detailAccountProduct->scheme) ? $detailAccountProduct->scheme : '') }}"
                                                                                                    placeholder="Scheme... "
                                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} scheme_{{ $index }}">
                                                                                            </td>

                                                                                            <td class="quantity">
                                                                                                <input id="discount"
                                                                                                    type="text"
                                                                                                    name="discount[]"
                                                                                                    value="{{ old('discount', !empty($detailAccountProduct->discount) ? $detailAccountProduct->discount : '') }}"
                                                                                                    placeholder="Discount... "
                                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} discount_{{ $index }}">
                                                                                            </td>

                                                                                            <td
                                                                                                class="delete-item-sub-row">
                                                                                                <ul
                                                                                                    class="table-controls">
                                                                                                    <li>
                                                                                                        <a href="javascript:void(0);"
                                                                                                            class="delete-sub-item"
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
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @endforeach

                                                                            @endif
                                                                        </tbody>
                                                                    </table>



                                                                </div>

                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-dark additem" id="add-item">Add
                                                                    Item</a>

                                                            </div>

                                                        </div>
                                                    </div> --}}


                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <div class="row">
                                                            {{-- <div class="col-md-6">
                                                                <label for="inputState" class="form-label">Scheme
                                                                </label>
                                                                <input id="scheme" type="text" name="scheme"
                                                                    value="{{ old('scheme', !empty($detailAccount->scheme) ? $detailAccount->scheme : '') }}"
                                                                    placeholder="Scheme... "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                    >
                                                            </div> --}}



                                                        </div>
                                                    </div>

                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <div class="row">
                                                            @if (!empty($detailAccount))
                                                                <div class="col-md-6">

                                                                    <div class="custom-radio">
                                                                        <input type="radio" id="primary"
                                                                            name="mode" value="primary"
                                                                            {{ old('mode', $detailAccount) == 'primary' ? 'checked' : '' }}>
                                                                        <label for="primary">
                                                                            <span class="radio-btn"></span>
                                                                            Primary
                                                                        </label>
                                                                    </div>

                                                                    <div class="custom-radio">
                                                                        <input type="radio" id="Secondary"
                                                                            name="mode" value="secondary"
                                                                            {{ old('mode', $detailAccount) == 'secondary' ? 'checked' : '' }}>
                                                                        <label for="Secondary">
                                                                            <span class="radio-btn"></span>
                                                                            Secondary
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">

                                                                    <div class="custom-radio2">
                                                                        <input type="radio" id="active"
                                                                            name="status" value="active"
                                                                            {{ old('status', $detailAccount) == 'active' ? 'checked' : '' }}>
                                                                        <label for="active">
                                                                            <span class="radio-btn"></span>
                                                                            Active
                                                                        </label>
                                                                    </div>

                                                                    <div class="custom-radio2">
                                                                        <input type="radio" id="dead"
                                                                            name="status" value="dead"
                                                                            {{ old('status', $detailAccount) == 'dead' ? 'checked' : '' }}>
                                                                        <label for="dead">
                                                                            <span class="radio-btn"></span>
                                                                            Dead
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="col-md-6">
                                                                    {{-- <input type="radio" id="primary"
                                                                        name="primary" value="primary">
                                                                    <label for="primary">Primary</label><br>
                                                                    <input type="radio" id="secondary"
                                                                        name="secondary" value="secondary">
                                                                    <label for="secondary">Secondary</label><br> --}}

                                                                    <div class="custom-radio">
                                                                        <input type="radio" id="primary"
                                                                            name="mode" value="primary">
                                                                        <label for="primary">
                                                                            <span class="radio-btn"></span>
                                                                            Primary
                                                                        </label>
                                                                    </div>

                                                                    <div class="custom-radio">
                                                                        <input type="radio" id="Secondary"
                                                                            name="mode" value="secondary">
                                                                        <label for="Secondary">
                                                                            <span class="radio-btn"></span>
                                                                            Secondary
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">

                                                                    <div class="custom-radio2">
                                                                        <input type="radio" id="active"
                                                                            name="status" value="active">
                                                                        <label for="active">
                                                                            <span class="radio-btn"></span>
                                                                            Active
                                                                        </label>
                                                                    </div>

                                                                    <div class="custom-radio2">
                                                                        <input type="radio" id="dead"
                                                                            name="status" value="dead">
                                                                        <label for="dead">
                                                                            <span class="radio-btn"></span>
                                                                            Dead
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>
                                                        <div class="row">
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
                                                    </div>

                                                    {{-- <div id="defaultAccordionOne" class="collapse"
                                                        aria-labelledby="headingOne1"
                                                        data-bs-parent="#toggleAccordion">
                                                        <div class="card-body">
                                                            <div class="col-lg-0 col-12 form-group mb-2">
                                                                <label for="account_name" class="form-label">
                                                                    Address </label>
                                                                <textarea name="address" id="address" placeholder="Please Enter Address "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" type="text" cols="30"
                                                                    rows="5">{{ @$detailAccountDetails->address }}</textarea>
                                                            </div>
                                                            <div class="col-lg-0 col-12 form-group mb-2">
                                                                <label for="account_name" class="form-label">
                                                                    Remarks </label>
                                                                <textarea name="remarks" id="remarks" placeholder="Please Enter Remarks "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" type="text" cols="30"
                                                                    rows="5">{{ @$detailAccountDetails->remarks }}</textarea>
                                                            </div>
                                                            <div class="col-lg-0 col-12 form-group mb-2">
                                                                <div class="row">
                                                                    <div class="col col-md-6 form-group mb-2">
                                                                        <label for="account_name" class="form-label">
                                                                            Contact No 1 </label>
                                                                        <input id="contact_no_1" type="text"
                                                                            name="contact_no_1"
                                                                            value="{{ old('contact_no_1', !empty($detailAccountDetails->contact_no_1) ? $detailAccountDetails->contact_no_1 : '') }}"
                                                                            placeholder="Please Enter Contact No 1"
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                    </div>

                                                                    <div class="col col-md-6 form-group mb-2">
                                                                        <label for="contact_no_2" class="form-label">
                                                                            Contact No 2 /
                                                                            WhatsApp</label>
                                                                        <input id="contact_no_2" type="text"
                                                                            name="contact_no_2"
                                                                            value="{{ old('contact_no_2', !empty($detailAccountDetails->contact_no_2) ? $detailAccountDetails->contact_no_2 : '') }}"
                                                                            placeholder="Please Enter Contact No 2 "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-0 col-12 form-group mb-2">
                                                                <div class="row">
                                                                    <div class="col col-md-6 form-group mb-2">
                                                                        <label for="cnic" class="form-label">
                                                                            Email </label>
                                                                        <input id="email" type="text"
                                                                            name="email"
                                                                            value="{{ old('email', !empty($detailAccountDetails->email) ? $detailAccountDetails->email : '') }}"
                                                                            placeholder="Please Enter the email "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                    </div>
                                                                    <div class="col col-md-6 form-group mb-2">
                                                                        <label for="cnic" class="form-label">
                                                                            CNIC </label>
                                                                        <input id="cnic" type="text"
                                                                            name="cnic"
                                                                            value="{{ old('cnic', !empty($detailAccountDetails->cnic) ? $detailAccountDetails->cnic : '') }}"
                                                                            placeholder="Please Enter the CNIC "
                                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col col-md-6 form-group mb-2">
                                                                    <label for="credit_limit" class="form-label">
                                                                        Credit
                                                                        Limit </label>
                                                                    <input id="credit-limit" type="text"
                                                                        name="credit_limit"
                                                                        value="{{ old('credit_limit', !empty($detailAccountDetails->credit_limit) ? $detailAccountDetails->credit_limit : '') }}"
                                                                        placeholder="Please Enter Detail Account "
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                </div>
                                                                <div class="col col-md-6 form-group mb-2">
                                                                    <label for="opening_balance" class="form-label">
                                                                        Opening
                                                                        Balance </label>
                                                                    <input id="opening-balance" type="text"
                                                                        name="opening_balance"
                                                                        value="{{ old('opening_balance', !empty($detailAccountDetails->opening_balance) ? $detailAccountDetails->opening_balance : '') }}"
                                                                        placeholder="Please Enter Opening Balance "
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col col-md-6 form-group mb-2">
                                                                    <label for="credit_days" class="form-label">
                                                                        Credit
                                                                        Days </label>
                                                                    <input id="credit-days" type="text"
                                                                        name="credit_days"
                                                                        value="{{ old('credit_days', !empty($detailAccountDetails->credit_days) ? $detailAccountDetails->credit_days : '') }}"
                                                                        placeholder="Please Enter credit Days "
                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div> --}}

                                                    <div class="col-lg-0 col-12 form-group mb-2">
                                                        <div id="toggleAccordion" class="accordion layout-spacing">
                                                            <div class="card">
                                                                <div class="card-header" id="headingOne1">
                                                                    <section class="mb-0 mt-0">
                                                                        <div role="menu" class="collapsed"
                                                                            data-bs-toggle="collapse"
                                                                            data-bs-target="#defaultAccordionOne"
                                                                            aria-expanded="false"
                                                                            aria-controls="defaultAccordionOne">
                                                                            Additional Information
                                                                            <div class="icons">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor"
                                                                                    stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    class="feather feather-chevron-down">
                                                                                    <polyline points="6 9 12 15 18 9">
                                                                                    </polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </div>
                                                                    </section>
                                                                </div>

                                                                <div id="defaultAccordionOne" class="collapse"
                                                                    aria-labelledby="headingOne1"
                                                                    data-bs-parent="#toggleAccordion">
                                                                    <div class="card-body">
                                                                        <div class="col-lg-0 col-12 form-group mb-2">
                                                                            <label for="account_name"
                                                                                class="form-label">
                                                                                Address </label>
                                                                            <textarea name="address" id="address" placeholder="Please Enter Address "
                                                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" type="text" cols="30"
                                                                                rows="5">{{ optional($detailAccountDetails->first())->address }}</textarea>
                                                                        </div>

                                                                        <div class="col-lg-0 col-12 form-group mb-2">
                                                                            <div class="row">
                                                                                <div
                                                                                    class="col col-md-6 form-group mb-2">
                                                                                    <label for="account_name"
                                                                                        class="form-label">
                                                                                        Contact No 1 </label>
                                                                                    <input id="contact_no_1"
                                                                                        type="text"
                                                                                        name="contact_no_1" maxlength="12"
                                                                                        {{-- value="{{ old('contact_no_1', !empty($detailAccountDetails->contact_no_1) ? $detailAccountDetails->contact_no_1 : '') }}" --}}
                                                                                        value="{{ old('contact_no_1', optional($detailAccountDetails->first())->contact_no_1) }}"
                                                                                        placeholder="Please Enter Contact No 1"
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} contact_no_1">
                                                                                </div>

                                                                                <div
                                                                                    class="col col-md-6 form-group mb-2">
                                                                                    <label for="contact_no_2"
                                                                                        class="form-label">
                                                                                        Contact No 2 /
                                                                                        WhatsApp</label>
                                                                                    <input id="contact_no_2"
                                                                                        type="text"
                                                                                        name="contact_no_2" maxlength="12"
                                                                                        {{-- value="{{ old('contact_no_2', !empty($detailAccountDetails->contact_no_2) ? $detailAccountDetails->contact_no_2 : '') }}" --}}
                                                                                        value="{{ old('contact_no_2', optional($detailAccountDetails->first())->contact_no_2) }}"
                                                                                        placeholder="Please Enter Contact No 2 "
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} contact_no_2">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-0 col-12 form-group mb-2">
                                                                            <div class="row">
                                                                                <div
                                                                                    class="col col-md-6 form-group mb-2">
                                                                                    <label for="cnic"
                                                                                        class="form-label">
                                                                                        Email </label>
                                                                                    <input id="email"
                                                                                        type="text" name="email"
                                                                                        {{-- value="{{ old('email', !empty($detailAccountDetails->email) ? $detailAccountDetails->email : '') }}" --}}
                                                                                        value="{{ old('email', optional($detailAccountDetails->first())->email) }}"
                                                                                        placeholder="Please Enter the email "
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                                </div>
                                                                                <div
                                                                                    class="col col-md-6 form-group mb-2">
                                                                                    <label for="cnic"
                                                                                        class="form-label">
                                                                                        CNIC </label>
                                                                                    <input id="cnic"
                                                                                        type="text" name="cnic"
                                                                                        {{-- value="{{ old('cnic', !empty($detailAccountDetails->cnic) ? $detailAccountDetails->cnic : '') }}" --}}
                                                                                        value="{{ old('cnic', optional($detailAccountDetails->first())->cnic) }}"
                                                                                        placeholder="Please Enter the CNIC " maxlength="15"
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} cnic">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col col-md-6 form-group mb-2">
                                                                                <label for="credit_limit"
                                                                                    class="form-label">
                                                                                    Credit
                                                                                    Limit </label>
                                                                                <input id="credit-limit"
                                                                                    type="text" name="credit_limit"
                                                                                    {{-- value="{{ old('credit_limit', !empty($detailAccountDetails->credit_limit) ? $detailAccountDetails->credit_limit : '') }}" --}}
                                                                                    value="{{ old('credit_limit', optional($detailAccountDetails->first())->credit_limit) }}"
                                                                                    placeholder="Please Enter Detail Account "
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                            </div>
                                                                            <div class="col col-md-6 form-group mb-2">
                                                                                <label for="opening_balance"
                                                                                    class="form-label">
                                                                                    Opening
                                                                                    Balance </label>
                                                                                <input id="opening-balance"
                                                                                    type="text"
                                                                                    name="opening_balance"
                                                                                    {{-- value="{{ old('opening_balance', !empty($detailAccountDetails->opening_balance) ? $detailAccountDetails->opening_balance : '') }}" --}}
                                                                                    value="{{ old('opening_balance', optional($detailAccountDetails->first())->opening_balance) }}"
                                                                                    placeholder="Please Enter Opening Balance "
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col col-md-6 form-group mb-2">
                                                                                <label for="credit_days"
                                                                                    class="form-label">
                                                                                    Credit
                                                                                    Days </label>
                                                                                <input id="credit-days"
                                                                                    type="text" name="credit_days"
                                                                                    {{-- value="{{ old('credit_days', !empty($detailAccountDetails->credit_days) ? $detailAccountDetails->credit_days : '') }}" --}}
                                                                                    value="{{ old('credit_days', optional($detailAccountDetails->first())->credit_days) }}"
                                                                                    placeholder="Please Enter credit Days "
                                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <br />
                                                        {{-- @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1) --}}
                                                        @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                            <button type="submit" id="save"
                                                                class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 save"
                                                                data-bs-container="body" data-bs-placement="right"
                                                                data-bs-content="Tooltip on right">
                                                                @if (!isset($detailAccount))
                                                                    Save
                                                                @else
                                                                    Update
                                                                @endif
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('detail-account.list') }}"
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
        document.addEventListener('keydown', function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            const input = document.getElementById('contact_no_1');

            input.addEventListener('input', function () {
                // Remove all non-digit characters
                let raw = this.value.replace(/\D/g, '');

                // Limit to 11 digits max
                if (raw.length > 11) raw = raw.slice(0, 11);

                // Auto-format: insert dash after 4 digits
                if (raw.length > 4) {
                    this.value = raw.slice(0, 4) + '-' + raw.slice(4);
                } else {
                    this.value = raw;
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const input = document.getElementById('contact_no_2');

            input.addEventListener('input', function () {
                // Remove all non-digit characters
                let raw = this.value.replace(/\D/g, '');

                // Limit to 11 digits max
                if (raw.length > 11) raw = raw.slice(0, 11);

                // Auto-format: insert dash after 4 digits
                if (raw.length > 4) {
                    this.value = raw.slice(0, 4) + '-' + raw.slice(4);
                } else {
                    this.value = raw;
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const cnicInput = document.getElementById('cnic');

            cnicInput.addEventListener('input', function () {
            let raw = this.value.replace(/\D/g, ''); // Only digits

            if (raw.length > 13) raw = raw.slice(0, 13); // Limit to 13 digits

            let formatted = raw;
            if (raw.length > 5 && raw.length <= 12) {
                formatted = raw.slice(0, 5) + '-' + raw.slice(5);
            }
            if (raw.length > 12) {
                formatted = raw.slice(0, 5) + '-' + raw.slice(5, 12) + '-' + raw.slice(12);
            }

            this.value = formatted;
            });
        });
    </script>

    <script src="{{ asset('js/detail-account.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('.saleMan').on('change', function() {
                var idSaleMan = this.value;

                $(".sector-dropdown").html('');
                $.ajax({
                    url: "{{ url('detail-account/get-saleMan-detail') }}",
                    type: "GET",
                    data: {
                        saleMan_id: idSaleMan,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $('.sector-dropdown').html(
                            '<option value="">-- Select Belt --</option>');
                        $.each(result.sectors, function(key, data) {
                            $("#sector-dropdown").append('<option value="' + data.id +
                                '">' + data.name + '</option>');
                        });

                        // $('.sector-dropdown').html(
                        //     '<option value="">-- Select Belt --</option>');
                    }
                });
            });

            /*------------------------------------------
            --------------------------------------------
            zone Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/


            $('.sector-dropdown').on('change', function() {
                // var idSaleMan = this.value;
                var sectors = document.querySelectorAll('.sector-dropdown');

                sectors.forEach(function(sector) {
                    // var selectedValue = sector.value; // Get the selected value of each dropdown
                    var selectedValues = Array.from(sector.selectedOptions).map(option => option
                        .value);

                    $(".area-dropdown").html('');
                    $.ajax({
                        url: "{{ url('detail-account/get-saleMan-area-detail') }}",
                        type: "GET",
                        data: {
                            sector: selectedValues,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            $('.area-dropdown').html(
                                '<option value="">-- Select Area --</option>');
                            $.each(result.areas, function(key, data) {
                                $("#area-dropdown").append('<option value="' +
                                    data.id +
                                    '">' + data.name + '</option>');
                            });
                        }
                    });
                });






            });
        });


        $(document).ready(function() {
            // let getTableElement = document.querySelectorAll('.main_row');
            // let currentIndex = getTableElement.length;
            // console.log(currentIndex);

            // document.addEventListener("DOMContentLoaded", function() {

            // Add event listener to the table for dynamic row handling
            $(".plus-btn").on('click', function(event) {
                var row_id = $(this).closest("tr").find(".row_id").val();

                const subRowIndexes = {};
                // console.log(currentIndexValue);
                // Ensure the "+" button was clicked
                if (event.target.classList.contains("plus-btn")) {

                    var parentRow = event.target.closest(".main_row");
                    // Ensure that parentRow is found correctly
                    if (parentRow) {
                        var parentId = parentRow.getAttribute("data-parent-id");
                        // var parentValue = parentRow.value();
                        var parentValue = $(".inventory_third_level_" +
                            row_id).val();
                        var parentPriceTagValue = $(".priceTag_dropdown_" +
                            row_id).val();


                        // Check if a sub-table already exists for the parent row
                        var subTable = parentRow.querySelectorAll(
                            ".item-sub-table");
                        // var subTable = event.target.closest(".child_row");

                        if (!subTable) {
                            subTable = document.createElement("table");
                            subTable.classList.add(".item-sub-table");
                            parentRow.insertAdjacentElement('afterend', subTable);
                        }

                        // Add a new child row to the sub-table
                        var childRowCount = subTable.length + 1;
                        var childRow = document.createElement("tr");
                        childRow.classList.add("child_row");
                        childRow.setAttribute("data-parent-id", parentId);

                        childRow.innerHTML =
                            `<td><input type="checkbox" name="row_id[]" class="row_id" value="${childRowCount}" hidden></td>
                            <td></td>
                            <td class="quantity"><input id= "masterThirdLevel" type = "text" name="master_third_level[]" value = "${parentValue}" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} masterThirdLevel_${parentId}" hidden></td>
                            <td class="quantity"><input id= "masterPriceTag" type = "text" name="master_price_tag[]" value = "${parentPriceTagValue}" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} masterPriceTag_${parentId}" hidden></td>
                            <td class="product" style="width: 20%;"> <select id="product" type = "text" name="product_id[]" class ="form-control form-control-sm product product_${parentId} select2" placeholder = "Please Select the Product"  ><option value="select-all" >Select All </option> </select></td>
                            <td class="quantity"> <input type="text" id="price" value="{{ old('price', !empty($detailAccountRecords->price) ? $detailAccountRecord->price : '') }}"name="price[]" placeholder="Price" class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price_${parentId}" ></td>
                            <td class="quantity"><input id = "scheme" type = "text" name = "scheme[]" value = "{{ old('scheme', !empty($detailAccount->scheme) ? $detailAccount->scheme : '') }}" placeholder = "Scheme... " class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} scheme_${parentId} "></td>
                            <td class="quantity"><input id = "discount" type = "text" name = "discount[]" value = "{{ old('discount', !empty($detailAccount->discount) ? $detailAccount->discount : '') }}" placeholder = "Discount... " class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} discount_${parentId}" ></td>
                            <td class="delete-item-sub-row">
                            <ul class="table-controls">
                            <li><a href="javascript:void(0);" class="delete-sub-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>
                            </ul>
                            </td>
                            </div>
                            </div>
                            </td>`;
                        // $(".item-sub-table tbody").append($childRow);




                        // Append the new child row to the sub-table
                        parentRow.insertAdjacentElement('afterend', childRow);
                        deleteItemSubRow();
                        $('.product_' + parentId).select2();


                    }

                    $('.product_' + parentId).on('click', function() {
                        var productSelected = '.product_' + parentId;
                        $(productSelected).select2();
                    });


                    $('.priceTag_dropdown_' + row_id).on('change', function() {
                        $('.product_' + parentId).html('');
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
                                $.each(result.products,
                                    function(key,
                                        data) {
                                        $('.product_' +
                                                parentId)
                                            .append(
                                                '<option value="' +
                                                data
                                                .id + '">' +
                                                data.name +
                                                '</option>'
                                            );
                                    });
                            }
                        });
                    });

                }




                var idProduct = $(".inventory_third_level_" +
                    row_id).val();
                var idPriceTag = $(".priceTag_dropdown_" +
                    row_id).val();
                // var idProduct = $(".inventory_third_level_" + currentIndex).find("option:selected").attr('id');
                var row_id = $(this).closest("tr").find(".row_id")
                    .val();

                // $('.product_' + parentId).html('');
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
                        // $('.product_' + row_id).html(
                        //     '<option value="">-- Select Product--</option>');


                        $.each(result.products, function(key, data) {
                            $('.product_' + parentId).append(
                                '<option value="' + data
                                .id + '">' +
                                data.name + '</option>');
                        });
                    }
                });
                // $(document).ready(function() {
                // $('.product_' + parentId).on('change', function() {
                //     var selectedValues = Array.from(this.selectedOptions)
                //         .map(option =>
                //             option
                //             .value);

                //     // If "Select All" is selected, select all other options except "Select All"
                //     if (selectedValues.includes("select-all")) {
                //         // Select all options except "Select All"
                //         $(this).find('option').not('[value="select-all"]')
                //             .prop(
                //                 'selected', true);
                //     }

                //     // If "Select All" is deselected, deselect all options
                //     if (selectedValues.length === 0) {
                //         $(this).find('option').prop('selected', false);
                //     }

                //     // Make sure to update select2
                //     $(this).trigger('change.select2');
                // });



            });


        });
    </script>

    <script>
        document.getElementsByClassName('additem')[0].addEventListener('click', function(event) {

            let getTableElement = document.querySelectorAll('.main_row');
            let currentIndex = getTableElement.length + 1;


            let $html = '<tr class="main_row" data-parent-id= "parent_row_' + currentIndex +
                '">' +
                '<td class="delete-item-row">' +
                '<ul class="table-controls">' +
                '<li><a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>' +
                '</ul>' +
                '</td>' +
                '<td><input type="checkbox" name="row_id[]" class="row_id" value="' + currentIndex +
                '" hidden></td>' +
                '<td class="inventoryThirdLevel"> <select id="inventoryThirdLevel" type = "text" name = "inventory_third_level[]" class ="form-control select2 custom-select form-control-sm  inventory_third_level_' +
                currentIndex +
                '" placeholder = "Please Select the Inv Third Level"  ><option value = "" >Please Select the Inv Third Level </option> @foreach ($dropDownData['invetoryThirdLevel'] as $key => $value)<option value = "{{ $key }}" {{ (old('inventory_third_level') == $key ? 'selected' : '') || (!empty($detailAccountRecords->inventory_third_level) ? collect($detailAccountRecords->inventory_third_level)->contains($key) : '') ? 'selected' : '' }} >{{ $value }} </option> @endforeach </select></td> ' +
                '<td class="price_tag"> <select id="priceTag_dropdown" type = "text" name = "price_tag_id[]" class ="form-control select2 custom-select form-control-sm priceTag_dropdown priceTag_dropdown_' +
                currentIndex +
                '" placeholder = "Please Select the Price Tag"  ><option value = "" >Select the Price Tag </option>  </select></td> ' +
                // '<td class="product"> <select id="product" type = "text" name = "product_id[]" class ="form-control select2 custom-select form-control-sm product product_' +
                // currentIndex +
                // '" placeholder = "Please Select the Product"  ><option value = "" >Select the Product </option> </select></td> ' +

                // '<td class="quantity"> <input type="text" id="price" value="{{ old('price', !empty($detailAccountRecords->price) ? $detailAccountRecord->price : '') }}"name="price[]" placeholder="Price" class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price_' +
                // currentIndex +
                // '" ></td>' +
                // '<td class="quantity"><input id = "scheme" type = "text" name = "scheme[]" value = "{{ old('scheme', !empty($detailAccount->scheme) ? $detailAccount->scheme : '') }}" placeholder = "Scheme... " class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} scheme_' +
                // currentIndex +
                // '" ></td>' +
                // '<td class="quantity"><input id = "discount" type = "text" name = "discount[]" value = "{{ old('discount', !empty($detailAccount->discount) ? $detailAccount->discount : '') }}" placeholder = "Discount... " class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} discount_' +
                // currentIndex +
                // '" ></td>' +
                '<td><a  class="btn btn-dark plus-btn plus_btn_' +
                currentIndex +
                '">+</a></td>' +

                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);
            deleteItemRow();
            // $('.product_' + currentIndex).select2();
            $('.inventory_third_level_' + currentIndex).select2();
            $('.priceTag_dropdown' + currentIndex).select2();

            $(document).ready(function() {
                $(".inventory_third_level_" + currentIndex).on('click', function() {
                    var inventorySelected = '.inventory_third_level_' + currentIndex;
                    $(inventorySelected).select2();
                });
            });
            $(document).ready(function() {
                $('.priceTag_dropdown_' + currentIndex).on('click', function() {
                    var priceTagSelected = '.priceTag_dropdown_' + currentIndex;
                    $(priceTagSelected).select2();
                });
            });


            $(document).ready(function() {
                $('.inventory_third_level_' + currentIndex).on('change', function() {
                    var idProduct = this.value;

                    var row_id = $(this).closest("tr").find(".row_id").val();

                    $(".priceTag_dropdown_" + currentIndex).html('');
                    $.ajax({
                        url: config.routes.getProductPriceTags,
                        type: "GET",
                        data: {
                            product_id: idProduct,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            $('.priceTag_dropdown_' + currentIndex).html(
                                '<option value="">-- Select Tag --</option>'
                            );

                            $.each(result.priceTags, function(key, data) {
                                $('.priceTag_dropdown_' +
                                    currentIndex).append(
                                    '<option value="' +
                                    data.id +
                                    '">' + data.name +
                                    '</option>');
                            });

                        }
                    });
                });


            });




            $(document).ready(function() {
                // document.addEventListener("DOMContentLoaded", function() {

                // Add event listener to the table for dynamic row handling
                $(".plus_btn_" + currentIndex).on('click', function(event) {



                    const subRowIndexes = {};
                    // Ensure the "+" button was clicked
                    if (event.target.classList.contains("plus-btn")) {

                        var parentRow = event.target.closest(".main_row");
                        // Ensure that parentRow is found correctly
                        if (parentRow) {
                            var parentId = parentRow.getAttribute("data-parent-id");
                            // var parentValue = parentRow.value();
                            var parentValue = $(".inventory_third_level_" +
                                currentIndex).val();
                            var parentPriceTagValue = $(".priceTag_dropdown_" +
                                currentIndex).val();


                            // Check if a sub-table already exists for the parent row
                            var subTable = parentRow.querySelectorAll(
                                ".item-sub-table");
                            // var subTable = event.target.closest(".child_row");

                            if (!subTable) {
                                subTable = document.createElement("table");
                                subTable.classList.add(".item-sub-table");
                                parentRow.insertAdjacentElement('afterend', subTable);
                            }

                            // Add a new child row to the sub-table
                            var childRowCount = subTable.length + 1;
                            var childRow = document.createElement("tr");
                            childRow.classList.add("child_row");
                            childRow.setAttribute("data-parent-id", parentId);

                            childRow.innerHTML =
                                `<td><input type="checkbox" name="row_id[]" class="row_id" value="${childRowCount}" hidden></td>
                            <td></td>
                            <td class="quantity"><input id= "masterThirdLevel" type = "text" name="master_third_level[]" value = "${parentValue}" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} masterThirdLevel_${parentId}" hidden></td>
                            <td class="quantity"><input id= "masterPriceTag" type = "text" name="master_price_tag[]" value = "${parentPriceTagValue}" class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} masterPriceTag_${parentId}" hidden></td>
                            <td class="product" style="width: 20%;"> <select id="product" type = "text" name="product_id[]" class ="form-control form-control-sm product product_${parentId} select2" placeholder = "Please Select the Product"  ><option value="select-all" >Select All </option> </select></td>
                            <td class="quantity"> <input type="text" id="price" value="{{ old('price', !empty($detailAccountRecords->price) ? $detailAccountRecord->price : '') }}"name="price[]" placeholder="Price" class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price_${parentId}" ></td>
                            <td class="quantity"><input id = "scheme" type = "text" name = "scheme[]" value = "{{ old('scheme', !empty($detailAccount->scheme) ? $detailAccount->scheme : '') }}" placeholder = "Scheme... " class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} scheme_${parentId} "></td>
                            <td class="quantity"><input id = "discount" type = "text" name = "discount[]" value = "{{ old('discount', !empty($detailAccount->discount) ? $detailAccount->discount : '') }}" placeholder = "Discount... " class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} discount_${parentId}" ></td>
                            <td class="delete-item-sub-row">
                            <ul class="table-controls">
                            <li><a href="javascript:void(0);" class="delete-sub-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>
                            </ul>
                            </td>
                            </div>
                            </div>
                            </td>`;
                            // $(".item-sub-table tbody").append($childRow);




                            // Append the new child row to the sub-table
                            parentRow.insertAdjacentElement('afterend', childRow);
                            deleteItemSubRow();
                            $('.product_' + parentId).select2();


                        }

                        $('.product_' + parentId).on('click', function() {
                            var productSelected = '.product_' + parentId;
                            $(productSelected).select2();
                        });


                        $('.priceTag_dropdown_' + currentIndex).on('change',
                            function() {
                                $('.product_' + parentId).html('');
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
                                        $.each(result.products,
                                            function(key,
                                                data) {
                                                $('.product_' +
                                                        parentId)
                                                    .append(
                                                        '<option value="' +
                                                        data
                                                        .id + '">' +
                                                        data.name +
                                                        '</option>'
                                                    );
                                            });
                                    }
                                });
                            });

                    }




                    var idProduct = $(".inventory_third_level_" +
                        currentIndex).val();
                    var idPriceTag = $(".priceTag_dropdown_" +
                        currentIndex).val();
                    // var idProduct = $(".inventory_third_level_" + currentIndex).find("option:selected").attr('id');
                    var row_id = $(this).closest("tr").find(".row_id")
                        .val();

                    // $('.product_' + parentId).html('');
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
                            // $('.product_' + row_id).html(
                            //     '<option value="">-- Select Product--</option>');


                            $.each(result.products, function(key, data) {
                                $('.product_' + parentId).append(
                                    '<option value="' + data
                                    .id + '">' +
                                    data.name + '</option>');
                            });
                        }
                    });
                    // $(document).ready(function() {
                    // $('.product_' + parentId).on('change', function() {
                    //     var selectedValues = Array.from(this.selectedOptions)
                    //         .map(option =>
                    //             option
                    //             .value);

                    //     // If "Select All" is selected, select all other options except "Select All"
                    //     if (selectedValues.includes("select-all")) {
                    //         // Select all options except "Select All"
                    //         $(this).find('option').not('[value="select-all"]')
                    //             .prop(
                    //                 'selected', true);
                    //     }

                    //     // If "Select All" is deselected, deselect all options
                    //     if (selectedValues.length === 0) {
                    //         $(this).find('option').prop('selected', false);
                    //     }

                    //     // Make sure to update select2
                    //     $(this).trigger('change.select2');
                    // });



                });


            });






            // $('.saleMan').on('change', function() {
            //     var idSaleMan = this.value;

            //     $(".sector-dropdown").html('');
            //     $.ajax({
            //         url: "{{ url('detail-account/get-saleMan-detail') }}",
            //         type: "GET",
            //         data: {
            //             saleMan_id: idSaleMan,
            //             _token: '{{ csrf_token() }}'
            //         },
            //         dataType: 'json',
            //         success: function(result) {
            //             $('.sector-dropdown').html(
            //                 '<option value="">-- Select Belt --</option>');
            //             $.each(result.sectors, function(key, data) {
            //                 $("#sector-dropdown").append('<option value="' +
            //                     data.id +
            //                     '">' + data.name + '</option>');
            //             });

            //             // $('.sector-dropdown').html(
            //             //     '<option value="">-- Select Belt --</option>');
            //         }
            //     });
            // });




        })

        deleteItemRow();
        // selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
        // selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
        // selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);

        function deleteItemRow() {
            let deleteItem = document.querySelectorAll('.delete-item');
            for (var i = 0; i < deleteItem.length; i++) {
                deleteItem[i].addEventListener('click', function() {
                    this.parentElement.parentNode.parentNode.parentNode.remove();
                })
            }
        }

        function deleteItemSubRow() {
            let deleteItem = document.querySelectorAll('.delete-sub-item');
            for (var i = 0; i < deleteItem.length; i++) {
                deleteItem[i].addEventListener('click', function() {
                    this.parentElement.parentNode.parentNode.parentNode.remove();
                })
            }
        }

        $(document).ready(function() {
            let getTableElement = document.querySelector('.item-table');
            let currentIndex = getTableElement.rows.length;
            $(".product_" + currentIndex).on('click', function() {
                var productSelected = '.product_' + currentIndex;
                $(productSelected).select2();
                // $(document.body).on("change", ".product", function() {
                //     $('.select2').select2();
            });
        });

        $(document).ready(function() {
            let getTableElement = document.querySelector('.item-table');
            let currentIndex = getTableElement.rows.length;
            $(".priceTag_dropdown_" + currentIndex).on('click', function() {
                var priceTagSelected = '.priceTag_dropdown_' + currentIndex;
                $(priceTagSelected).select2();
            });
        });
    </script>


    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     let currentIndex = 0;
        //     // Add event listener to the table for dynamic row handling
        //     document.getElementsByClassName("plus_btn_"+ currentIndex).addEventListener('click', function(event){

        //         // Ensure the "+" button was clicked
        //         if (event.target.classList.contains("plus-btn")) {
        //             var parentRow = event.target.closest(".main_row");

        //             // Ensure that parentRow is found correctly
        //             if (parentRow) {
        //                 var parentId = parentRow.getAttribute("data-parent-id");

        //                 // Check if a sub-table already exists for the parent row
        //                 var subTable = parentRow.querySelector(".sub-table");

        //                 // If no sub-table exists, create it
        //                 if (!subTable) {
        //                     subTable = document.createElement("table");
        //                     subTable.classList.add("sub-table");
        //                     parentRow.insertAdjacentElement('afterend', subTable);
        //                 }

        //                 // Add a new child row to the sub-table
        //                 var childRowCount = subTable.rows.length + 1;
        //                 var childRow = document.createElement("tr");
        //                 childRow.classList.add("child_row");
        //                 // let getTableElement = document.querySelector('.item-sub-table');
        //                 // let currentIndex = getTableElement.rows.length + 1;
        //                 currentIndex++;
        //                 // Set the content of the child row
        //                 // childRow.innerHTML = `
    //                 //     <td colspan="3">
    //                 //         <button class="minus-btn">-</button>
    //                 //     </td>
    //                 // `;
        //                 childRow.innerHTML =
        //                     `<td><input type="checkbox" name="row_id[]" class="row_id" value="${currentIndex}" hidden></td>
    //                     <td class="product" style="width: 20%;"> <select id="product" type = "text" name = "product_id[]" class ="form-control select2 custom-select form-control-sm  product_${currentIndex} "placeholder = "Please Select the Product"  ><option value = "" >Select the Product </option> </select></td>
    //                     <td class="quantity"> <input type="text" id="price" value="{{ old('price', !empty($detailAccountRecords->price) ? $detailAccountRecord->price : '') }}"name="price[]" placeholder="Price" class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price_${currentIndex}" ></td>
    //                     <td class="quantity"><input id = "scheme" type = "text" name = "scheme[]" value = "{{ old('scheme', !empty($detailAccount->scheme) ? $detailAccount->scheme : '') }}" placeholder = "Scheme... " class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} scheme_${currentIndex} "></td>
    //                     <td class="quantity"><input id = "discount" type = "text" name = "discount[]" value = "{{ old('discount', !empty($detailAccount->discount) ? $detailAccount->discount : '') }}" placeholder = "Discount... " class = "form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} discount_${currentIndex}" ></td>
    //                     <td class="delete-item-sub-row">
    //                     <ul class="table-controls">
    //                     <li><a href="javascript:void(0);" class="delete-sub-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>
    //                     </ul>
    //                     </td>
    //                     </div>
    //                     </div>
    //                     </td>`;
        //                 // $(".item-sub-table tbody").append($childRow);
        //                 deleteItemSubRow();



        //                 // Append the new child row to the sub-table
        //                 parentRow.insertAdjacentElement('afterend', childRow);
        //                 // subTable.appendChild(childRow);
        //             }
        //         }

        //         // Check if the "-" button was clicked to remove a child row
        //         if (event.target.classList.contains("minus-btn")) {
        //             var childRow = event.target.closest(".child_row");
        //             if (childRow) {
        //                 childRow.remove();
        //             }
        //         }
        //     });
        // });
    </script>

    <script>
        const saveRouteUrl = "{{ route('detail-account.save') }}";
        var config = {
            routes: {
                getControlHeads: "{{ url('sub-head/get-control-head-account') }}",
                getSubSubHeads: "{{ url('detail-account/get-sub-sub-account') }}",
                getSubHeads: "{{ url('sub-sub-head/get-sub-heads') }}",
                getDetailAccountCode: "{{ url('detail-account/get-detail-account-code') }}",
                getSaleManDetail: "{{ url('detail-account/get-saleMan-detail') }}",
                getSaleManAreaDetail: "{{ url('detail-account/get-saleMan-area-detail') }}",
                getProductPrice: "{{ url('detail-account/get-product-price') }}",
                getProductPriceTags: "{{ url('detail-account/get-product-price-tags') }}",
                getProducts: "{{ url('detail-account/get-products') }}"
            },
        }
    </script>


    <x-slot:footerFiles>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        {{-- <script src="{{ asset('plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>
        <script src="{{ asset('plugins/sweetalerts2/custom-sweetalert.js') }}"></script> --}}
        <script src="{{ asset('js/common.js') }}"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])

        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

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
