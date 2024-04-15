<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        @vite(['resources/scss/light/assets/components/timeline.scss'])
        @vite(['resources/scss/light/assets/components/accordions.scss'])
        @vite(['resources/scss/dark/assets/components/accordions.scss'])
        <link rel="stylesheet" href="{{ asset('plugins/filepond/filepond.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/filepond/FilePondPluginImagePreview.min.css') }}">
        @vite(['resources/scss/light/plugins/filepond/custom-filepond.scss'])
        @vite(['resources/scss/dark/plugins/filepond/custom-filepond.scss'])

        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chart of Inventory</li>
            </ol>
        </nav>
    </div>


    <div id="tabsSimple" class="col-xl-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            {{-- <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>Chart Of Inventory Sub Sub Head</h4>
                    </div>
                </div>
            </div> --}}
            <div class="widget-content widget-content-area">
                <div class="simple-pill">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                            aria-labelledby="pills-home-tab" tabindex="0">
                            <div id="basic" class="col-lg-12">
                                <div class="statbox widget box box-shadow">
                                    <div class="widget-content widget-content-area">
                                        <div class="row">
                                            <div class="col-lg-6 col-12 ">
                                                <form
                                                    action="{{ !empty($subSubHead) ? route('co-inventory-sub-sub-head.update') : route('co-inventory-sub-sub-head.save') }}"
                                                    method="POST" class="row g-3 needs-validation" novalidate>
                                                    @csrf
                                                    <input type="hidden" name="id" id="id"
                                                        value="{{ isset($subSubHead->id) ? $subSubHead->id : '' }}" />
                                                    <div class="form-group">
                                                        <div class="col-lg-0 col-12 form-group mb-4">
                                                            <label for="inputState" class="form-label">Main
                                                                Head</label>
                                                            <select id="main-head" name="main_head" class="form-select"
                                                                required>
                                                                <option selected>Please select main head
                                                                </option>
                                                                @foreach ($mainHeads as $index => $value)
                                                                    <option value="{{ $index }}"
                                                                        {{ (old('main_head') == $index ? 'selected' : '') || (!empty($subSubHead->main_head) ? collect($subSubHead->main_head)->contains($index) : '') ? 'selected' : '' }}>
                                                                        {{ $value }}</option>
                                                                @endforeach
                                                            </select>

                                                            @if ($errors->has('main_head'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('main_head') }}
                                                                </div>
                                                            @endif

                                                        </div>


                                                        {{-- <div class="col-lg-0 col-12">
                                                            <label for="inputState" class="form-label">Main Account Head</label>
                                                            <select id="main-head" name="main_head" class="form-select" required>
                                                                <option selected=""></option>
                                                                @foreach ($mainHeads as $index => $value)
                                                                    <option value="{{ $index }}" {{ (old("main_head") == $index ? "selected":"") || (!empty($subSubHead->main_head) ? collect($subSubHead->main_head)->contains($index) : '') ? 'selected':'' }}>{{ $value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div> --}}
                                                        <br>
                                                        {{-- <div class="col-lg-0 col-12">
                                                            <label for="inputState" class="form-label">Sub Account Head</label>
                                                            <select id="sub-head" name="sub_head" class="form-select" required>
                                                                <option selected=""></option>
                                                                @foreach ($subHeads as $index => $value)
                                                                    <option value="{{ $index }}" {{ (old("sub_head") == $index ? "selected":"") || (!empty($subSubHead->sub_head) ? collect($subSubHead->sub_head)->contains($index) : '') ? 'selected':'' }}>{{ $value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div> --}}

                                                        <div class="col-lg-0 col-12 form-group mb-4">
                                                            <label for="inputState" class="form-label">Sub Head</label>
                                                            @if (!empty($subSubHead))
                                                                <select id="sub-head" name="sub_head"
                                                                    class="form-select" required>

                                                                    @foreach ($subHeads as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ !empty($subSubHead) && $subSubHead->sub_head == $key ? 'selected' : '' }}
                                                                            {{ $key == old('sub') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else

                                                                <select id="sub-head" name="sub_head"
                                                                    class="form-select">
                                                                    <option selected>
                                                                    </option>
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
                                                        <div class="col-lg- 0 col-12 ">
                                                            <label for="code" class="form-label">Sub Sub Account
                                                                Code</label>
                                                            <input id="code" type="text" name="code"
                                                                value="{{ old('code', !empty($subSubHead->code) ? $subSubHead->code : '') }}"
                                                                class="form-control" readonly>
                                                            <div class="invalid-feedback">

                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="col-lg-0 col-12 ">
                                                            <label for="name" class="form-label">Sub Sub Account
                                                                Name </label>
                                                            <input id="name" type="text" name="name"
                                                                value="{{ old('name', !empty($subSubHead->name) ? $subSubHead->name : '') }}"
                                                                placeholder="Please Enter Sub Sub Account "
                                                                class="form-control" required>
                                                        </div>

                                                        @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                            <button type="submit"
                                                                class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                data-bs-container="body" data-bs-placement="right"
                                                                data-bs-content="Tooltip on right">
                                                                @if (!isset($subSubHead))
                                                                    Save
                                                                @else
                                                                    Update
                                                                @endif
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('co-inventory-sub-sub-head.list') }}"
                                                            class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                        {{-- <input type="submit" value="Save"
                                                            class="mt-4 btn btn-primary"> --}}
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
    <x-slot:footerFiles>
        <script src="{{ asset('js/inventory-sub-sub-head.js') }}"></script>
        <script src="{{ asset('plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>
        <script src="{{ asset('plugins/sweetalerts2/custom-sweetalert.js') }}"></script>
        <script>
            $('#main-head').on('change', function() {
                var mainCode = $('#main-head :selected').val();
                $("#sub-head").empty();
                $("#code").val('');
                let url = config.routes.getSubHeads + '/' + mainCode;
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $("#sub-head").empty();
                        $("#sub-head").append($("<option />").val("").text("Please select the sub head"));
                        $.each(response.data, function(key, value) {
                            $("#sub-head").append($("<option />").val(key).text(value));
                        });
                    },
                    complete: function() {
                        $('#loading').css('display', 'none');
                    },
                    error: function(errorThrown) {
                        $('#code').val('');
                        $("#sub-head").empty();
                        var errors = errorThrown.responseJSON.errors;
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong',
                        })
                    }
                })
            })

            $('#sub-head').on('change', function() {
                var subCode = $('#sub-head :selected').val();
                $("#code").val('');
                let url = config.routes.getDetailAccountCode + '/' + subCode;
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $("#code").val(response.code);
                    },
                    complete: function() {
                        $('#loading').css('display', 'none');
                    },
                    error: function(errorThrown) {
                        $('#code').val('');
                        var errors = errorThrown.responseJSON.errors;
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong',
                        })
                    }
                })
            })
        </script>
        <script>
            var config = {
                routes: {
                    getSubHeads: "{{ url('co-inventory-sub-sub-head/get-sub-head-accounts') }}",
                    // getSubSubHeads: "{{ url('co-inv-detail-account/get-sub-sub-head-accounts') }}",
                    getsubSubHeadCode: "{{ url('co-inventory-sub-sub-head/get-detail-account-code') }}",
                },
            }
        </script>
    </x-slot>
</x-base-layout>
