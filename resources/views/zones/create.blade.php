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

    <!-- BREADCRUMB -->
    <div class="row layout-top-spacing">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><svg> ...
                    </svg></button>
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Zones</li>
                        <li class="breadcrumb-item"><a href="{{ route('zone.list') }}">List</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('zone.create') }}">Create</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Add Zone</h4>
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
                                                        action="{{ !empty($zone) ? route('zone.update') : route('zone.save') }}"
                                                        method="POST" class="row g-3 needs-validation" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($zone->id) ? $zone->id : '' }}" />
                                                        <div class="form-group">
                                                            <div class="col-lg-0 col-12 ">
                                                                <label for="country_id"
                                                                    class="form-label">Country</label>
                                                                <select id="country_id" type="text" name="country_id"
                                                                    {{-- value="{{ old('country_id', !empty($zone->country_id) ? $zone->country_id : '') }}" --}}
                                                                    placeholder="Please Select Country "
                                                                    class="form-control select2 custom-select country-dropdown"
                                                                    required>
                                                                    <option value="">Select</option>
                                                                    @foreach ($dropDownData['countries'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ (old('country_id') == $key ? 'selected' : '') || (!empty($zone->country_id) ? collect($zone->country_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                                {{-- <div class="invalid-feedback">
                                                                    Please Select the Sector.
                                                                </div> --}}
                                                            </div>
                                                            <div class="col-lg-0 col-12 ">
                                                                <label for="name" class="form-label">Zone</label>
                                                                <input id="name" type="text" name="name"
                                                                    value="{{ old('name', !empty($zone->name) ? $zone->name : '') }}"
                                                                    placeholder="Please Enter Zone Name "
                                                                    class="form-control" required>
                                                                {{-- <div class="invalid-feedback">
                                                                        Please Enter the zone Name.
                                                                    </div> --}}
                                                            </div>

                                                            <a href="{{ route('zone.list') }}" style="float: right;"
                                                                class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                            {{-- @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)

                                                            @endif --}}
                                                            <button type="submit" style="float: right"
                                                                class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                data-bs-container="body" data-bs-placement="right"
                                                                data-bs-content="Tooltip on right">
                                                                @if (!isset($zone))
                                                                    Save
                                                                @else
                                                                    Update
                                                                @endif
                                                            </button>
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
    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-lg-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>List Of Zones</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10%"> <b>ID </b> </th>
                                    <th scope="col" style="width: 40%"> <b>Zone </b> </th>
                                </tr>
                            </thead>
                            <tbody>

                                    <tr id="data">
                                        <td>
                                            <div class="media">
                                                <div id="data-container" style="color: black; font-size: 15px;" class="media-body align-self-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div id="name-container" style="color: black; font-size: 15px;" class="media-body align-self-center">
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                            </tbody>
                        </table>
                    </div>
                    {{-- <nav aria-label=" ListPagination">
                        <ul class="pagination justify-content-end">
                            {!! $zones->appends(request()->query())->links() !!}
                        </ul>
                    </nav> --}}
                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2();

            $('.country-dropdown').on('change', function() {
                var idCountry = this.value;

                $.ajax({
                    url: "{{ url('api/fetchZones') }}",
                    type: "POST",
                    data: {
                        country_id: idCountry,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',

                    success: function(response) {
                        var htmlContent = '<ul>';
                        $.each(response.zones, function(key, item) {
                            htmlContent += '<li>'+ item.id + '</li>';
                        });
                        htmlContent += '</ul>';
                        $('#data-container').html(htmlContent);

                        var htmlContent2 = '<ul>';
                        $.each(response.zones, function(key, item) {
                            htmlContent2 += '<li>'+  item.name + '</li>';
                        });
                        htmlContent += '</ul>';

                        $('#name-container').html(htmlContent2);
                    }

                });
            });
        });
    </script>
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
            },
            false);
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
        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </x-slot>
</x-base-layout>
