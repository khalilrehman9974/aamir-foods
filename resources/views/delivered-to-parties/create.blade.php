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

        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}


        {{-- <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" /> --}}



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
                <li class="breadcrumb-item active"><a href="{{ route('delivered-to-parties.list') }}">List Of Delivered
                        To Parties</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('delivered-to-parties.create') }}">Create</a></li>
            </ol>
        </nav>
    </div>


    <div id="tabsSimple" class="col-xl-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 mt-3">
                        <h4 style="color: black;">Create Delivered To Party</h4>
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
                                                    action="{{ !empty($deliveredParties) ? route('delivered-to-parties.update') : route('delivered-to-parties.save') }}"
                                                    method="POST" class="row g-3 needs-validation" autocomplete="off">
                                                    @csrf
                                                    <input type="hidden" name="id" id="id"
                                                        value="{{ isset($deliveredParties->id) ? $deliveredParties->id : '' }}" />

                                                    <br>
                                                    <div class="col-lg-12 form-group mb-4">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="party_name" class="form-label">
                                                                    Party Id</label>
                                                                <input type="text" style="color: black;"
                                                                    value="{{ @$maxid }} {{ @$currentid }}"
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                    readonly>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12 form-group mb-4">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="inputState" class="form-label">Coa
                                                                    Party</label>
                                                                <select id="detail_account_id" name="detail_account_id"
                                                                    class="form-select select2 mb-3 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} detail_account_id {{ $errors->has('detail_account_id') ? 'is-invalid' : '' }}"
                                                                    required>
                                                                    <option value="">Select Party
                                                                    </option>
                                                                    @foreach ($dropDownData['parties'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ (old('detail_account_id') == $key ? 'selected' : '') || (!empty($deliveredParties->detail_account_id) ? collect($deliveredParties->detail_account_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('detail_account_id')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="party_name" class="form-label">
                                                                    Delivered To Party Name </label>
                                                                <input id="party_name" type="text"
                                                                    name="party_name"
                                                                    value="{{ old('party_name', !empty($deliveredParties->party_name) ? $deliveredParties->party_name : '') }}"
                                                                    placeholder="Please Enter Delivered To Party Name "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                                    required>
                                                                @if ($errors->has('party_name'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('party_name') }}
                                                                    </div>
                                                                @endif
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label for="inputState" class="form-label">Sales
                                                                    Man</label>
                                                                <select id="saleMan" name="saleMan_id"
                                                                    class="form-select select2 mb-3 custom-select {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} saleMan"
                                                                    required>
                                                                    <option value="">Select Sale Man
                                                                    </option>
                                                                    @foreach ($dropDownData['saleMans'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ (old('saleMan_id') == $key ? 'selected' : '') || (!empty($deliveredParties->saleMan_id) ? collect($deliveredParties->saleMan_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-xl-6 col-lg-6">
                                                                <label class="form-label"
                                                                    for="product-title-input">Belt</label>
                                                                @if (empty($deliveredParties))
                                                                    <select id="sector-dropdown" name="sector_id[]"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} sector-dropdown"
                                                                        required multiple>
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
                                                                        required>
                                                                        @foreach ($sectors as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                {{ $key == old('sector') ? 'selected' : '' }}>
                                                                                {{ $value }}</option>
                                                                        @endforeach
                                                                    </select> --}}
                                                                    <select id="sector-dropdown" name="sector_id[]"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} sector-dropdown"
                                                                        required multiple>
                                                                        @foreach ($sectors as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                @php
$isSelected = old('sector_id') == $key || $deliveredPartiesSectors->pluck('sector_id')->contains($key); @endphp
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

                                                        </div>
                                                    </div>
                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <div class="row">
                                                            <div class="col-xl-6 col-lg-6">
                                                                <label class="form-label"
                                                                    for="product-title-input">Area</label>
                                                                @if (empty($deliveredParties))
                                                                    <select id="area-dropdown" name="area_id[]"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} area-dropdown"
                                                                        required multiple>
                                                                        <option value="">-- Select Areas --
                                                                        </option>
                                                                    </select>
                                                                @else
                                                                    <select id="area-dropdown" name="area_id[]"
                                                                        class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} area-dropdown"
                                                                        required multiple>
                                                                        @foreach ($areas as $key => $value)
                                                                            <option value="{{ $key }}"
                                                                                @php
$isSelected = old('area_id') == $key || $deliveredPartiesAreas->pluck('area_id')->contains($key); @endphp
                                                                                {{ $isSelected ? 'selected' : '' }}>
                                                                                {{ $value }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="inputState" class="form-label">Commision
                                                                </label>
                                                                <input id="commision" type="text" name="commision"
                                                                    value="{{ old('commision', !empty($deliveredParties->commision) ? $deliveredParties->commision : '') }}"
                                                                    placeholder="Commision... "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <div class="row">
                                                            @if (!empty($deliveredParties))
                                                                <div class="col-md-6">
                                                                    <label class="form-label"
                                                                        for="product-title-input">Mode</label>
                                                                    <div class="custom-radio">
                                                                        <input type="radio" id="primary"
                                                                            name="mode" value="primary"
                                                                            {{ old('mode', $deliveredParties) == 'primary' ? 'checked' : '' }}>
                                                                        <label for="primary">
                                                                            <span class="radio-btn"></span>
                                                                            Primary
                                                                        </label>
                                                                    </div>

                                                                    <div class="custom-radio">

                                                                        <input type="radio" id="Secondary"
                                                                            name="mode" value="secondary"
                                                                            {{ old('mode', $deliveredParties) == 'secondary' ? 'checked' : '' }}>
                                                                        <label for="Secondary">
                                                                            <span class="radio-btn"></span>
                                                                            Secondary
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label"
                                                                        for="product-title-input"><b>Status</b></label>
                                                                    <div class="custom-radio2">
                                                                        <input type="radio" id="active"
                                                                            name="status" value="active"
                                                                            {{ old('status', $deliveredParties) == 'active' ? 'checked' : '' }}>
                                                                        <label for="active">
                                                                            <span class="radio-btn"></span>
                                                                            Active
                                                                        </label>
                                                                    </div>

                                                                    <div class="custom-radio2">
                                                                        <input type="radio" id="dead"
                                                                            name="status" value="dead"
                                                                            {{ old('status', $deliveredParties) == 'dead' ? 'checked' : '' }}>
                                                                        <label for="dead">
                                                                            <span class="radio-btn"></span>
                                                                            Dead
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="col-md-6">
                                                                    <label class="form-label"
                                                                        for="product-title-input"><b>Mode</b></label>
                                                                    <div class="custom-radio">
                                                                        <input type="radio" id="primary"
                                                                            name="mode" value="primary" checked>
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
                                                                    <label class="form-label"
                                                                        for="product-title-input"><b>Status</b></label>
                                                                    <div class="custom-radio2">
                                                                        <input type="radio" id="active"
                                                                            name="status" value="active" checked>
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

                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <div class="row">
                                                            <div class="col-lg-6 form-group mb-2">
                                                                <label for="party_name" class="form-label">
                                                                    Contact No 1 </label>
                                                                <input id="contact_no_1" type="text"
                                                                    name="contact_no_1"
                                                                    value="{{ old('contact_no_1', !empty($deliveredParties->contact_no_1) ? $deliveredParties->contact_no_1 : '') }}"
                                                                    placeholder="Please Enter Contact No 1"
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                            </div>
                                                            <div class="col-lg-6 form-group mb-2">
                                                                <label for="contact_no_2" class="form-label">
                                                                    Contact No 2 /
                                                                    WhatsApp</label>
                                                                <input id="contact_no_2" type="text"
                                                                    name="contact_no_2"
                                                                    value="{{ old('contact_no_2', !empty($deliveredParties->contact_no_2) ? $deliveredParties->contact_no_2 : '') }}"
                                                                    placeholder="Please Enter Contact No 2 "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <div class="row">
                                                            <div class="col-lg-6 form-group mb-2">
                                                                <label for="cnic" class="form-label">
                                                                    Email </label>
                                                                <input id="email" type="text" name="email"
                                                                    value="{{ old('email', !empty($deliveredParties->email) ? $deliveredParties->email : '') }}"
                                                                    placeholder="Please Enter the email "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                            </div>
                                                            <div class="col-lg-6 form-group mb-2">
                                                                <label for="cnic" class="form-label">
                                                                    CNIC </label>
                                                                <input id="cnic" type="text" name="cnic"
                                                                    value="{{ old('cnic', !empty($deliveredParties->cnic) ? $deliveredParties->cnic : '') }}"
                                                                    placeholder="Please Enter the CNIC "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                        <div class="row">
                                                            <div class="col col-md-6 form-group mb-2">
                                                                <label for="credit_limit" class="form-label">
                                                                    Credit
                                                                    Limit </label>
                                                                <input id="credit-limit" type="text"
                                                                    name="credit_limit"
                                                                    value="{{ old('credit_limit', !empty($deliveredParties->credit_limit) ? $deliveredParties->credit_limit : '') }}"
                                                                    placeholder="Please Enter Detail Account "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                            </div>
                                                            <div class="col col-md-6 form-group mb-2">
                                                                <label for="opening_balance" class="form-label">
                                                                    Opening
                                                                    Balance </label>
                                                                <input id="opening-balance" type="text"
                                                                    name="opening_balance"
                                                                    value="{{ old('opening_balance', !empty($deliveredParties->opening_balance) ? $deliveredParties->opening_balance : '') }}"
                                                                    placeholder="Please Enter Opening Balance "
                                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-0 col-12 form-group mb-2">
                                                        <label for="party_name" class="form-label">
                                                            Address </label>
                                                        <textarea name="address" id="address" placeholder="Please Enter Address "
                                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}" type="text" cols="30"
                                                            rows="10">{{ @$deliveredParties->address }}</textarea>
                                                    </div>

                                                    <br>
                                                    <div class="col-lg-0 col-12 form-group mb-2"
                                                        style="text-align: right;">

                                                        <br />
                                                        {{-- @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1) --}}
                                                        @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                            <button type="submit" id="save"
                                                                class="btn btn-success rounded bs-popover me-1 mt-5 mb-4 save"
                                                                data-bs-container="body" data-bs-placement="right"
                                                                data-bs-content="Tooltip on right">
                                                                @if (!isset($deliveredParties))
                                                                    Save
                                                                @else
                                                                    Update
                                                                @endif
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('delivered-to-parties.list') }}"
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
    <script src="{{ asset('js/delivered-to-parties.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('.saleMan').on('change', function() {
                var idSaleMan = this.value;

                $(".sector-dropdown").html('');
                $.ajax({
                    url: "{{ url('delivered-to-parties/get-saleMan-detail') }}",
                    type: "GET",
                    data: {
                        saleMan_id: idSaleMan,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        // console.log(result.sectors);
                        $('.sector-dropdown').html(
                            '<option value="">-- Select Belt --</option>');
                        $.each(result.sectors, function(key, data) {
                            // console.log(data.name);
                            $(".sector-dropdown").append('<option value="' + data.id +
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
                        url: "{{ url('delivered-to-parties/get-saleMan-area-detail') }}",
                        type: "GET",
                        data: {
                            sector: selectedValues,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(result) {
                            // console.log(result.sectors);
                            $('.area-dropdown').html(
                                '<option value="">-- Select Area --</option>');
                            $.each(result.areas, function(key, data) {
                                // console.log(data.name);
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
        $(document).ready(function() {
            $('.select2').select2();

            $('form').on('submit', function() {

                if ($('.detail_account_id').val() === '') {
                    $('.detail_account_id').addClass('is-invalid');
                    return false;
                }
            });

            $('.detail_account_id').on('change', function() {
                if ($(this).val() !== '') {
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>

    <x-slot:footerFiles>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        {{-- <script src="{{ asset('plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalerts2/custom-sweetalert.js') }}"></script> --}}
        <script src="{{ asset('js/common.js') }}"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])





        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>

        {{-- <script src="{{ asset('plugins/invoice-add/invoice-add.js') }}"></script> --}}
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
    </x-slot>
</x-base-layout>
