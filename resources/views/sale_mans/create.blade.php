<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
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
        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link href="../src/plugins/src/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../src/plugins/src/filepond/filepond.min.css">
        <link rel="stylesheet" href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css">

        <link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
        <link href="../src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
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
                                        <input type="text" name="name"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                            id="name" value="{{ isset($saleMan->name) ? $saleMan->name : '' }}"
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
                                            placeholder="Enter Sale Man Email"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
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
                                        <input type="text" name="mobile_no"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mobile_no"
                                            id="mobile_no" maxlength="12"
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
                                        <input type="text" name="whatsapp_no"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} whatsapp_no"
                                            id="whatsapp_no" maxlength="12"
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
                                                placeholder="Reference..."
                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                            @error('reference')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label">Designation</label>

                                            <select id="designation" type="text" name="designation"
                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mb-3 select2 custom-select"
                                                required>
                                                <option value="Order Booker">Order Booker
                                                </option>
                                                <option value="SO">
                                                    SO
                                                </option>
                                                <option value="TSO">TSO
                                                </option>
                                                <option value="ASM">ASM
                                                </option>
                                                <option value="ZSM">ZSM
                                                </option>
                                                <option value="NSM">NSM
                                                </option>
                                            </select>

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
                                            <select id="country-dropdown"
                                                class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}"
                                                name="country_id">

                                                <option value="">-- Select Country --</option>
                                                @foreach ($countries as $index => $value)
                                                    <option value="{{ $index }}"
                                                        {{ (old('country_id') == $index ? 'selected' : '') || (!empty($saleMan->country_id) ? collect($saleMan->country_id)->contains($index) : '') ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>



                                        </div>

                                        <div class="col-xl-6 col-lg-6 mt-3">
                                            <label class="form-label" for="product-title-input">Zones</label>
                                            <select id="zone-dropdown" name="zone_id[]"
                                                class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} zone-dropdown"
                                                multiple>
                                                <option value="select-all">Select All</option>

                                            </select>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-0 col-lg-12">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 mt-3">
                                            <label class="form-label" for="product-title-input">Belt</label>
                                            <select id="sector-dropdown" name="sector_id[]"
                                                class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} sector-dropdown"
                                                multiple>
                                                <option value="select-all">Select All</option>
                                            </select>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 mt-3">
                                            <label class="form-label" for="product-title-input">Area</label>

                                            <select id="area-dropdown" name="area_id[]"
                                                class="select2 custom-select form-control mb-3 {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} area-dropdown"
                                                multiple>
                                                <option value="select-all">Select All</option>

                                            </select>

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
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.getElementById('mobile_no');

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
            const input = document.getElementById('whatsapp_no');

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

        $(document).ready(function() {
            // Initialize select2
            $('.select2').select2();

            // Fetch and populate zones based on country
            $('#country-dropdown').on('change', function() {
                var idCountry = this.value;
                $("#zone-dropdown").html(
                    '<option value="select-all">Select All</option>'); // Add Select All option
                $.ajax({
                    url: "{{ url('api/fetch-zones') }}",
                    type: "POST",
                    data: {
                        country_id: idCountry,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $.each(result.zones, function(key, data) {
                            $("#zone-dropdown").append('<option value="' + data.id +
                                '">' + data.name + '</option>');
                        });
                    }
                });
            });

            $('#zone-dropdown').on('change', function() {
                var $this = $(this);
                var selectedValues = Array.from(this.selectedOptions).map(option => option.value);

                // If "Select All" is selected
                if (selectedValues.includes("select-all")) {
                    // Deselect "Select All" option
                    $this.find('option[value="select-all"]').prop('selected', false);

                    // Select all other options
                    $this.find('option').not('[value="select-all"]').prop('selected', true);

                    // Update Select2 to reflect changes
                    $this.trigger('change.select2');

                    // Refresh selected values
                    selectedValues = Array.from($this[0].selectedOptions).map(option => option.value);
                }

                // Filter out "select-all" from selected values
                var zoneIds = selectedValues.filter(value => value !== "select-all");

                if (zoneIds.length > 0) {
                    // Proceed with fetching sectors based on selected zones
                    $.ajax({
                        url: "{{ url('api/fetch-sectors') }}",
                        type: "POST",
                        data: {
                            zone_id: zoneIds,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('#sector-dropdown').html(
                                '<option value="select-all">Select All</option>');
                            $.each(res.sectors, function(key, value) {
                                $("#sector-dropdown").append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });


            // Sector Dropdown Change Event
            $('#sector-dropdown').on('change', function() {
                var $this = $(this);
                var selectedValues = Array.from(this.selectedOptions).map(option => option.value);

                // If "Select All" is selected
                if (selectedValues.includes("select-all")) {
                    // Deselect "Select All" option
                    $this.find('option[value="select-all"]').prop('selected', false);

                    // Select all other options
                    $this.find('option').not('[value="select-all"]').prop('selected', true);

                    // Update Select2 to reflect changes
                    $this.trigger('change.select2');

                    // Refresh selected values
                    selectedValues = Array.from($this[0].selectedOptions).map(option => option.value);
                }

                // Filter out "select-all" from the actual request
                var sectorIds = selectedValues.filter(value => value !== "select-all");

                if (sectorIds.length > 0) {
                    // Fetch areas based on selected sectors
                    $.ajax({
                        url: "{{ url('api/fetch-areas') }}",
                        type: "POST",
                        data: {
                            sector_id: sectorIds,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(resul) {
                            $('#area-dropdown').html(
                                '<option value="select-all">Select All</option>');
                            $.each(resul.areas, function(key, value) {
                                $("#area-dropdown").append('<option value="' + value
                                    .id + '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });


            $('#area-dropdown').on('change', function() {
                var $this = $(this);
                var selectedValues = Array.from(this.selectedOptions).map(option => option.value);

                // If "Select All" is selected
                if (selectedValues.includes("select-all")) {
                    // Deselect "Select All" itself
                    $this.find('option[value="select-all"]').prop('selected', false);

                    // Select all other options
                    $this.find('option').not('[value="select-all"]').prop('selected', true);

                    // Update Select2 UI
                    $this.trigger('change.select2');

                    // Refresh selected values after selecting all
                    selectedValues = Array.from($this[0].selectedOptions).map(option => option.value);
                }

                // If nothing is selected, deselect all
                if (selectedValues.length === 0) {
                    $this.find('option').prop('selected', false);
                    $this.trigger('change.select2');
                }
            });


        });
    </script>

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>

        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
        {{-- <script src="{{ asset('plugins/invoice-add/invoice-add.js') }}"></script> --}}
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        {{-- <script src="{{ asset('js/common.js') }}"></script> --}}

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
        {{-- <script src="{{ asset('js/saleMan.js') }}"></script> --}}

    </x-slot>

</x-base-layout>
