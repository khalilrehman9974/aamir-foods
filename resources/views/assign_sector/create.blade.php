<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
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
    <!-- BREADCRUMB -->
    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Assign Sectors</li>
                        <li class="breadcrumb-item"><a href="{{ route('assignSector.list') }}">List</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('assignSector.create') }}">Create</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Assign Sector</h4>
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
                                                        action="{{ !empty($assignSector) ? route('assignSector.update') : route('assignSector.save') }}"
                                                        method="post" class="row g-3 needs-validation" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($assignSector->id) ? $assignSector->id : '' }}" />
                                                        <div class="form-group">
                                                            <div class="col-lg-0 col-12 ">
                                                                <label for="sale_mans_id" class="form-label">Sale Man</label>
                                                                <select id="sale_mans_id"  name="sale_mans_id"

                                                                    placeholder="Please Select Sale Man "
                                                                    class="form-control select2 form-control mb-3 custom-select" required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($dropDownData['sale_mans'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ (old('sale_mans_id') == $key ? 'selected' : '') || (!empty($assignSector->sale_mans_id) ? collect($assignSector->sale_mans_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{-- <div class="invalid-feedback">
                                                                    Please Select the Sale Man.
                                                                </div> --}}
                                                            </div>
                                                            <div class="col-lg-0 col-12 ">
                                                                <label for="sector_id" class="form-label">Sector</label>
                                                                <select id="sector_id" type="text" name="sector_id"
                                                                    value="{{ old('sector_id', !empty($assignSector->sector_id) ? $assignSector->sector_id : '') }}"
                                                                    placeholder="Please Select Sector "
                                                                    class="form-control select2 form-control mb-3 custom-select" required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($dropDownData['sectors'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ (old('sector_id') == $key ? 'selected' : '') || (!empty($assignSector->sector_id) ? collect($assignSector->sector_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{-- <div class="invalid-feedback">
                                                                    Please Select the Sector.
                                                                </div> --}}
                                                            </div>

                                                            <a href="{{ route('assignSector.list') }}" style="float: right;"
                                                                class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                            @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                                <button type="submit" style="float: right"
                                                                    class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                    data-bs-container="body" data-bs-placement="right"
                                                                    data-bs-content="Tooltip on right">
                                                                    @if (!isset($assignSector))
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
    </div>
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
    <x-slot:footerFiles>
        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])

    </x-slot>
</x-base-layout>
