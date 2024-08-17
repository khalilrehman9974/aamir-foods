<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
        @vite(['resources/scss/light/assets/components/tabs.scss'])
        @vite(['resources/scss/dark/assets/components/tabs.scss'])
        <link rel="stylesheet" href="{{ asset('plugins/flatpickr/flatpickr.css') }}">
        @vite(['resources/scss/light/plugins/flatpickr/custom-flatpickr.scss'])
        @vite(['resources/scss/dark/plugins/flatpickr/custom-flatpickr.scss'])
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>


    {{--                <!-- BREADCRUMB --> --}}
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sale Mans</li>
                <li class="breadcrumb-item"><a href="{{ route('saleMan.list') }}">List</a></li>
                <li class="breadcrumb-item"><a href="{{ route('saleMan.create') }}">Create</a></li>
            </ol>
        </nav>
    </div>



    <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Add Sale Man</h4>
                        </div>
                    </div>
                </div>
                <div class="container">

                    <div class="widget-content widget-content-area">
                        <form method="POST"
                            action="{{ !empty($saleMan) ? route('saleMan.update') : route('saleMan.save') }}"
                            class="row g-3 needs-validation" autocomplete="off" novalidate>
                            @csrf
                            <input type="hidden" name="id" id="id"
                                value="{{ isset($saleMan->id) ? $saleMan->id : '' }}" />
                            <div class="form-group">
                                <div class="row ">
                                    <div class="col-md-6">
                                        <label for="validationCustom01" class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            value="{{ isset($saleMan->name) ? $saleMan->name : '' }}"
                                            placeholder="Enter Sale Man Name" required>
                                        @error('name')
                                            <span style="color:red" class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">

                                        <label class="form-label">Email</label>
                                        <input type="email" name="email"
                                            value="{{ isset($saleMan->email) ? $saleMan->email : '' }}"
                                            placeholder="Enter Sale Man Email" class="form-control">
                                        @error('email')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>

                                <div class="row ">
                                    <div class="col-md-6 mt-3">
                                        <label for="validationCustom01" class="form-label">Mobile
                                            Number</label>
                                        <input type="text" name="mobile_no" class="form-control" id="mobile_no"
                                            value="{{ isset($saleMan->mobile_no) ? $saleMan->mobile_no : '' }}"
                                            placeholder="Enter The Mobile Number" required>
                                        @error('mobile_no')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="validationCustom01" class="form-label">WhatsApp
                                            Number</label>
                                        <input type="text" name="whatsapp_no" class="form-control" id="whatsapp_no"
                                            value="{{ isset($saleMan->whatsapp_no) ? $saleMan->whatsapp_no : '' }}"
                                            placeholder="Enter The WhatsApp Number">
                                        @error('whatsapp_no')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-0 col-lg-12">
                                    <div class="row">
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Reference</label>
                                            <input type="reference" name="reference"
                                                value="{{ isset($saleMan->reference) ? $saleMan->reference : '' }}"
                                                placeholder="Reference..." class="form-control">
                                            @error('reference')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Designation</label>
                                            <input type="designation" name="designation"
                                                value="{{ isset($saleMan->designation) ? $saleMan->designation : '' }}"
                                                placeholder="Designation..." class="form-control">
                                            @error('designation')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-0 col-lg-12">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 mt-3">
                                            <label class="form-label" for="product-title-input">Country</label>
                                            <select id="country-dropdown" class="form-control" name="country_id">

                                                <option value="">-- Select Country --</option>
                                                {{-- @foreach ($countries as $data)
                                                    <option value="{{ $data->id }}" >
                                                        {{ $data->name }}
                                                    </option>
                                                @endforeach --}}
                                                @foreach ($countries as $index => $value)
                                                    <option value="{{ $index }}"
                                                        {{ (old('country_id') == $index ? 'selected' : '') || (!empty($saleMan->country_id) ? collect($saleMan->country_id)->contains($index) : '') ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>



                                        </div>

                                        <div class="col-xl-6 col-lg-6 mt-3">
                                            <label class="form-label" for="product-title-input">Zones</label>
                                            @if (!empty($saleMan))
                                                <select id="zone-dropdown" name="zone_id" class="form-control"
                                                    required>
                                                    @foreach ($zones as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ !empty($saleMan) && $saleMan->zone_id == $key ? 'selected' : '' }}
                                                            {{ $key == old('zone_id') ? 'selected' : '' }}>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                {{-- <select id="zone-dropdown" name="zone_id" class="form-select"
                                                    required>
                                                    @foreach ($zones as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ $key == old('zone_id') ? 'selected' : '' }}>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select> --}}
                                                <select id="zone-dropdown" name="zone_id" class="form-control">
                                                    <option value="">-- Select Zones --</option>
                                                </select>

                                            @endif
                                            {{-- <select id="zone-dropdown" name="zone_id" class="form-control">
                                                <option value="">-- Select Zones --</option>
                                            </select> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-0 col-lg-12">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 mt-3">
                                            <label class="form-label" for="product-title-input">Belt</label>
                                            @if (!empty($saleMan))
                                                <select id="sector-dropdown" name="sector_id" class="form-select"
                                                    required>

                                                    @foreach ($sectors as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ !empty($saleMan) && $saleMan->sector_id == $key ? 'selected' : '' }}
                                                            {{ $key == old('sector_id') ? 'selected' : '' }}>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                {{-- <select id="sector-dropdown" name="sector_id" class="form-select"
                                                    required>
                                                    @foreach ($sectors as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ $key == old('sector_id') ? 'selected' : '' }}>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select> --}}

                                                <select id="sector-dropdown" name="sector_id" class="form-control">
                                                    <option value="">-- Select Belt --</option>
                                                </select>

                                            @endif
                                            {{-- <select id="sector-dropdown" name="sector_id" class="form-control">
                                                <option value="">-- Select Belt --</option>
                                            </select> --}}
                                        </div>

                                        <div class="col-xl-6 col-lg-6 mt-3">
                                            <label class="form-label" for="product-title-input">Area</label>
                                            @if (!empty($saleMan))
                                                <select id="area-dropdown" name="area_id" class="form-select"
                                                    required>

                                                    @foreach ($areas as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ !empty($saleMan) && $saleMan->area_id == $key ? 'selected' : '' }}
                                                            {{ $key == old('area_id') ? 'selected' : '' }}>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                {{-- <select id="area-dropdown" name="area_id" class="form-select"
                                                    required>
                                                    @foreach ($areas as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ $key == old('area_id') ? 'selected' : '' }}>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                </select> --}}

                                                <select id="area-dropdown" name="area_id" class="form-control">
                                                    <option value="">-- Select Area --</option>
                                                </select>

                                            @endif
                                            {{-- <select id="area-dropdown" name="area_id" class="form-control">
                                                <option value="">-- Select Area --</option>
                                            </select> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-0 col-12 mt-3">

                                    <label for="mailing_address" class="form-label">Mailling
                                        Address</label>
                                    <textarea id="mailing_address" type="textarea" name="mailing_address" rows="3"
                                        placeholder="Please Enter Mailing Address" class="form-control">{{ @$saleMan->mailing_address }}</textarea>
                                    <div class="invalid-feedback">
                                        Please provide Mailing Address.
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mt-3">
                                        <label class="form-label">Address</label>
                                        <textarea type="textarea" id="address" name="address" placeholder="Enter The SaleMan Address..."
                                            value="{{ isset($saleMan->address) ? $saleMan->address : '' }}" class="form-control" required>{{ @$saleMan->address }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg- 0 col-12 mt-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea id="remarks" type="textarea" name="remarks" rows="3"
                                        value="{{ old('remarks', !empty($saleMan->remarks) ? $saleMan->remarks : '') }}"
                                        placeholder="Please Enter Remarks" class="form-control">{{ @$saleMan->remarks }}</textarea>
                                    <div class="invalid-feedback">
                                        Please provide Remarks.
                                    </div>
                                </div>

                                <br>
                                <a href="{{ route('saleMan.list') }}" style="float: right;"
                                    class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                    <button type="submit" style="float: right"
                                        class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                        data-bs-container="body" data-bs-placement="right"
                                        data-bs-content="Tooltip on right">
                                        @if (!isset($saleMan))
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);

        $('#country-dropdown').on('change', function() {
            var idCountry = this.value;
            $("#zone-dropdown").html('');
            $.ajax({
                url: "{{ url('api/fetch-zones') }}",
                type: "POST",
                data: {
                    country_id: idCountry,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(result) {
                    $('#zone-dropdown').html(
                        '<option value="">-- Select zone --</option>');
                    $.each(result.zones, function(key, data) {
                        $("#zone-dropdown").append('<option value="' + data
                            .id + '">' + data.name + '</option>');
                    });
                    $('#sector-dropdown').html(
                        '<option value="">-- Select Belt --</option>');
                    $('#area-dropdown').html('<option value="">-- Select Area --</option>');

                }
            });
        });

        /*------------------------------------------
        --------------------------------------------
        zone Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#zone-dropdown').on('change', function() {
            var idZone = this.value;
            $("#sector-dropdown").html('');
            $.ajax({
                url: "{{ url('api/fetch-sectors') }}",
                type: "POST",
                data: {
                    zone_id: idZone,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(res) {
                    $('#sector-dropdown').html(
                        '<option value="">-- Select Belt --</option>');
                    $.each(res.sectors, function(key, value) {
                        $("#sector-dropdown").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                    $('#area-dropdown').html('<option value="">-- Select Area --</option>');
                }
            });
        });

        $('#sector-dropdown').on('change', function() {
            var sectorId = this.value;
            $("#area-dropdown").html('');
            $.ajax({
                url: "{{ url('api/fetch-areas') }}",
                type: "POST",
                data: {
                    sector_id: sectorId,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(resul) {
                    $('#area-dropdown').html('<option value="">-- Select Area --</option>');
                    $.each(resul.areas, function(key, value) {
                        $("#area-dropdown").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                }
            });
        });
    </script>

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
        <script src="{{ asset('js/saleMan.js') }}"></script>

    </x-slot>

</x-base-layout>
