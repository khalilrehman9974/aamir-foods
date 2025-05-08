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

        <link href="../src/plugins/src/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
        <link href="../src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">

    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>

    <div class="row layout-top-spacing">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><svg> ...
                    </svg></button>
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
            <div class="row">
                <div class="col-lg-0 col-6 ">
                    <div class="page-meta">
                        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Order Sheet</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-0 col-6 ">
                    <a href="{{ route('order-sheet.productOrderSheetlist') }}" class="btn btn-primary mt-2 mb-2 me-8"
                        style="float : right; " style="">Product Order Sheet
                    </a>

                </div>
            </div>

        </div>

    </div>
    <div class="row layout-top-spacing col-md-12">
        <div id="tableCustomBasic" class="col-lg-12 col-12 layout-spacing">
            <div class="row">
                <div class="col-lg-12" style="margin-right: 0px !important;">
                    <form action="{{ route('order-sheet.orderSheetPrint') }}" method="get" id="form-search"
                        target="_blank">
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="inputState" class="form-label">From Date</label>
                                        <div class="input-daterange input-group" id="contract-date">

                                            <input name="from_date" style="color: black; "
                                                class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} "
                                                type="text" value="{{ @$request['from_date'] }}"
                                                placeholder="From Date..">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="inputState" class="form-label">To Date</label>
                                        <div class="input-daterange input-group" id="contract-date">

                                            <input type="text" name="to_date" class="form-control form-control-sm"
                                                value="{{ @$request['to_date'] }}" placeholder="To Date" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="inputState" class="form-label">Party</label>
                                        <select class="select2 form-control mb-3 custom-select" name="party_id"
                                            id="party_id" style="width: 100%; height:36px;">
                                            <option value="">Select</option>
                                            @foreach ($dropDownData['parties'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('party_id') == $key ? 'selected' : '') || (!empty($contract->party_id) ? collect($contract->party_id)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="inputState" class="form-label">Status</label>
                                        <select id="status" name="status"
                                            class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} mb-3 select2 custom-select"
                                            required>

                                            <option value="Pending">
                                                Pending
                                            </option>
                                            <option value="Approved">
                                                Approved
                                            </option>
                                            <option value="Dispatched">
                                                Dispatched
                                            </option>
                                            <option value="Cancelled">
                                                Cancelled
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="inputState" class="form-label">Sale Man</label>
                                            <select class="select2 form-control mb-3 custom-select" name="saleMan_id"
                                                id="saleMan_id" style="width: 100%; height:36px;">
                                                <option value="">Select</option>
                                                @foreach ($dropDownData['saleMans'] as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ (old('saleMan_id') == $key ? 'selected' : '') || (!empty($contract->saleMan_id) ? collect($contract->saleMan_id)->contains($key) : '') ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="inputState" class="form-label">Sectors</label>
                                            <select class="select2 form-control mb-3 custom-select" name="belt"
                                                id="belt" style="width: 100%; height:36px;">
                                                <option value="">Select</option>
                                                @foreach ($dropDownData['belts'] as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ (old('belt') == $key ? 'selected' : '') || (!empty($contract->belt) ? collect($contract->belt)->contains($key) : '') ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="inputState" class="form-label">Area</label>
                                            <select class="select2 form-control mb-3 custom-select" name="area"
                                                id="area" style="width: 100%; height:36px;">
                                                <option value="">Select</option>
                                                @foreach ($dropDownData['areas'] as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ (old('area') == $key ? 'selected' : '') || (!empty($contract->area) ? collect($contract->area)->contains($key) : '') ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="inputState" class="form-label"></label>
                                        <div class="input-group">
                                            <span class="input-group-prepend" style="margin-top: 0px;">
                                                <button type="submit" class="btn btn-primary" value="Search"
                                                    id="search-button"><i class="fa fa-search"></i>&nbsp;
                                                    Search</button>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
    </x-slot>

</x-base-layout>
