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


    {{--                <!-- BREADCRUMB --> --}}
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Transporters</li>
                <li class="breadcrumb-item"><a href="{{ route('transporter.list') }}">List of Transporters</a></li>
                <li class="breadcrumb-item"><a href="{{ route('transporter.create') }}">Create</a></li>
            </ol>
        </nav>
    </div>



    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Add Transporter</h4>
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
                                                    <form method="POST"
                                                        action="{{ !empty($transporter) ? route('transporter.update') : route('transporter.save') }}"
                                                        class="row g-3 needs-validation" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                        value="{{ isset($transporter->id) ? $transporter->id : '' }}" />
                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="validationCustom01"
                                                                        class="form-label">Name</label>
                                                                    <input type="text" name="name"
                                                                        class="form-control" id="name"
                                                                        value="{{ isset($transporter->name) ? $transporter->name : '' }}"
                                                                        placeholder="Enter the Name" required>
                                                                    @error('name')
                                                                        <span style="color:red" class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">

                                                                    <label class="form-label">City</label>
                                                                    <input type="text" name="city" placeholder="Enter the City Name"
                                                                        value="{{ isset($transporter->city) ? $transporter->city : '' }}"
                                                                        class="form-control" required>
                                                                    @error('city')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror

                                                                </div>

                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label for="validationCustom01"
                                                                        class="form-label">Contact Number
                                                                        </label>
                                                                    <input type="text" name="contact_number"
                                                                        class="form-control" id="contact_number"
                                                                        value="{{ isset($transporter->contact_number) ? $transporter->contact_number : '' }}"
                                                                        placeholder="Enter The Contact Number" required>
                                                                    @error('contact_number')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="validationCustom01"
                                                                        class="form-label">Contact Person </label>
                                                                    <input type="text" name="contact_person"
                                                                        class="form-control" id="contact_person"
                                                                        value="{{ isset($transporter->contact_person) ? $transporter->contact_person : '' }}"
                                                                        placeholder="Enter the Contact Person Name">
                                                                    @error('contact_person')
                                                                        <span class="invalid-feedback">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Address</label>
                                                                    <textarea type="textarea" id="address" placeholder="Enter the Address" name="address"
                                                                        value="{{ isset($transporter->address) ? $transporter->address : '' }}" class="form-control" required>{{@$transporter->address}}</textarea>
                                                                    @error('address')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-lg- 0 col-12 ">
                                                                <label for="remarks"
                                                                    class="form-label">Remarks</label>
                                                                <textarea id="remarks" placeholder="Please Enter Remarks" type="textarea" name="remarks" rows="3"
                                                                    value="{{ old('remarks', !empty($transporter->remarks) ? $transporter->remarks : '') }}"
                                                                     class="form-control">{{@$transporter->remarks}}</textarea>
                                                                <div class="invalid-feedback">
                                                                    Please provide Remarks.
                                                                </div>
                                                            </div>

                                                            <br>
                                                            <a href="{{ route('transporter.list') }}"
                                                                style="float: right;"
                                                                class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                            @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                                <button type="submit" style="float: right"
                                                                    class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                    data-bs-container="body" data-bs-placement="right"
                                                                    data-bs-content="Tooltip on right">
                                                                    @if (!isset($transporter))
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
