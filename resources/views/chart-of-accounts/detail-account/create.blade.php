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
        @vite(['resources/scss/dark/plugins/sweetalerts2/custom-sweetalert.scss']) --}}
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



        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        @vite(['resources/scss/light/plugins/filepond/custom-filepond.scss'])
        @vite(['resources/scss/dark/plugins/filepond/custom-filepond.scss'])



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
                                                    method="POST" class="row g-3 needs-validation"
                                                    enctype="multipart/form-data" autocomplete="off" novalidate>
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
                                                        <label for="account_name" class="form-label">
                                                            Remarks </label>
                                                        <textarea name="remarks" id="remarks" placeholder="Please Enter Remarks "
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" type="text" cols="30"
                                                            rows="5">{{ @$detailAccountDetails->remarks }}</textarea>
                                                    </div>

                                                    <div class="col-lg-6 col-12 form-group mb-4">
                                                        <label for="image" class="form-label">Upload Account
                                                            Image</label>

                                                        <input id="image" name="image" type="file"
                                                            class="form-control" accept="image/*">

                                                        {{-- Show existing image if editing --}}
                                                        @if (isset($detailAccount) && !empty($detailAccount->image))
                                                            <div class="mt-3">
                                                                <p><strong>Current Image:</strong></p>
                                                                <img src="{{ asset('resources/images/detailAccount/' . $detailAccount->image) }}"
                                                                    alt="Account Image" class="img-thumbnail"
                                                                    style="width: 150px; height: auto;">
                                                            </div>
                                                        @endif
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
                                                    </div>
                                                    <br>
                                                    <br>
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
                                                                                rows="5">{{ @$detailAccountDetails->address }}</textarea>
                                                                        </div>

                                                                        <div class="col-lg-0 col-12 form-group mb-2">
                                                                            <div class="row">
                                                                                {{-- <div
                                                                                    class="col col-md-6 form-group mb-2">
                                                                                    <label for="account_name"
                                                                                        class="form-label">
                                                                                        Contact No 1 </label>
                                                                                    <input id="contact_no_1"
                                                                                        type="text"
                                                                                        name="contact_no_1"
                                                                                        value="{{ old('contact_no_1', !empty($detailAccountDetails->contact_no_1) ? $detailAccountDetails->contact_no_1 : '') }}"
                                                                                        placeholder="Please Enter Contact No 1"
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                                </div> --}}

                                                                                <div
                                                                                    class="col col-md-6 form-group mb-2">
                                                                                    <label for="contact_no_1"
                                                                                        class="form-label">
                                                                                        Contact No 1
                                                                                    </label>
                                                                                    <input id="contact_no_1"
                                                                                        type="text"
                                                                                        name="contact_no_1"
                                                                                        value="{{ old('contact_no_1', !empty($detailAccountDetails->contact_no_1) ? $detailAccountDetails->contact_no_1 : '') }}"
                                                                                        placeholder="0300-1234567"
                                                                                        maxlength="12"
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                                </div>

                                                                                <div
                                                                                    class="col col-md-6 form-group mb-2">
                                                                                    <label for="contact_no_2"
                                                                                        class="form-label">
                                                                                        Contact No 2 /
                                                                                        WhatsApp</label>
                                                                                    <input id="contact_no_2"
                                                                                        type="text"
                                                                                        name="contact_no_2"
                                                                                        value="{{ old('contact_no_2', !empty($detailAccountDetails->contact_no_2) ? $detailAccountDetails->contact_no_2 : '') }}"
                                                                                        placeholder="Please Enter Contact No 2 "
                                                                                        placeholder="0300-1234567"
                                                                                        maxlength="12"
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
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
                                                                                        value="{{ old('email', !empty($detailAccountDetails->email) ? $detailAccountDetails->email : '') }}"
                                                                                        placeholder="Please Enter the email "
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                                                </div>
                                                                                <div
                                                                                    class="col col-md-6 form-group mb-2">
                                                                                    <label for="cnic"
                                                                                        class="form-label">
                                                                                        CNIC
                                                                                    </label>
                                                                                    <input id="cnic"
                                                                                        type="text" name="cnic"
                                                                                        value="{{ old('cnic', !empty($detailAccountDetails->cnic) ? $detailAccountDetails->cnic : '') }}"
                                                                                        placeholder="12345-1234567-1"
                                                                                        maxlength="15"
                                                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
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
                                                                                    value="{{ old('credit_limit', !empty($detailAccountDetails->credit_limit) ? $detailAccountDetails->credit_limit : '') }}"
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
                                                                                    value="{{ old('opening_balance', !empty($detailAccountDetails->opening_balance) ? $detailAccountDetails->opening_balance : '') }}"
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
                                                                                <input id="credit-days" type="text"
                                                                                    name="credit_days"
                                                                                    value="{{ old('credit_days', !empty($detailAccountDetails->credit_days) ? $detailAccountDetails->credit_days : '') }}"
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
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('contact_no_1');

            input.addEventListener('input', function() {
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

        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('contact_no_2');

            input.addEventListener('input', function() {
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

        document.addEventListener("DOMContentLoaded", function() {
            const cnicInput = document.getElementById('cnic');

            cnicInput.addEventListener('input', function() {
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

    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
            }
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

        <script src="{{ asset('plugins/filepond/filepond.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageExifOrientation.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImagePreview.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageCrop.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageResize.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/FilePondPluginImageTransform.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        {{-- <script src="{{ asset('js/common.js') }}"></script> --}}

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>



        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>

        {{-- <script src="{{ asset('plugins/invoice-add/invoice-add.js') }}"></script> --}}

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
    </x-slot>
</x-base-layout>
