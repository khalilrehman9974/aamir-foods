<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!--  BEGIN CUSTOM STYLE FILE  -->
        @vite(['resources/scss/light/assets/components/timeline.scss'])

        <link rel="stylesheet" href="{{ asset('plugins/sweetalerts2/sweetalerts2.css') }}">
        @vite(['resources/scss/light/plugins/sweetalerts2/custom-sweetalert.scss'])
        @vite(['resources/scss/dark/plugins/sweetalerts2/custom-sweetalert.scss'])







        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


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
                <li class="breadcrumb-item active" aria-current="page">Chart of Accounts</li>
                <li class="breadcrumb-item active" aria-current="page">Sub-Sub Head Account</li>
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
                                    <div class="widget-content widget-content-area">
                                        <div class="row">
                                            <div class="col-lg-6 col-12 ">
                                                <form
                                                    action="{{ !empty($subSubHead) ? route('sub-sub-head.update') : route('sub-sub-head.save') }}"
                                                    method="POST" class="row g-3 needs-validation" autocomplete="off"
                                                    novalidate>
                                                    @csrf
                                                    <input type="hidden" name="id" id="id"
                                                        value="{{ isset($subSubHead->id) ? $subSubHead->id : '' }}" />
                                                    <div class="form-group input-group">
                                                        <div class="col-lg-0 col-12">
                                                            <label for="inputState" class="form-label">Main Head</label>
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
                                                        <br>
                                                        <div class="col-lg-0 col-12">
                                                            <label for="inputState" class="form-label">Control
                                                                Head</label>
                                                            @if (!empty($subSubHead))
                                                                <select id="control-head" name="control_head"
                                                                    class="form-select" required>

                                                                    @foreach ($controlHead as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ !empty($subSubHead) && $subSubHead->control_head == $key ? 'selected' : '' }}
                                                                            {{ $key == old('control_head') ? 'selected' : '' }}>
                                                                            {{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                <select id="control-head" name="control_head"
                                                                    class="form-select" required>
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
                                                        <div class="col-lg-0 col-12">
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
                                                                    class="form-select" required>
                                                                    <option></option>
                                                                </select>
                                                            @endif
                                                            @if ($errors->has('sub_head'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('sub_head') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-0 col-12 mt-3 ">
                                                            <label for="price" class="form-label">Price Tag
                                                            </label>

                                                            @if (!empty($subSubHead))

                                                                <select id="price" name="priceTag[]"
                                                                    class="mb-3 form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price select2 custom-select"
                                                                    multiple>
                                                                    <option value="select-all">Select All</option>

                                                                    @foreach ($dropDownData['priceTag'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            @php
$isSelected = old('priceTag') == $key || $priceTags->pluck('priceTag')->contains($key); @endphp
                                                                            {{ $isSelected ? 'selected' : '' }}>
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach

                                                                </select>
                                                            @else
                                                                <select id="price" name="priceTag[]"
                                                                    class="mb-3 form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price select2 custom-select"
                                                                    multiple>
                                                                    <option value="select-all">Select All</option>
                                                                    @foreach ($dropDownData['priceTag'] as $key => $value)
                                                                        <option value="{{ $key }}"
                                                                            {{ (old('priceTag') == $key ? 'selected' : '') || (!empty($priceTags->priceTag) ? collect($priceTags->priceTag)->contains($key) : '') ? 'selected' : '' }}>
                                                                            {{ $value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            @endif

                                                        </div>
                                                        <br>
                                                        <div class="col-lg- 0 col-12 ">
                                                            <label for="account_code" class="form-label">Sub
                                                                Account Code</label>
                                                            <input id="account_code" type="text" name="account_code"
                                                                value="{{ old('account_code', !empty($subSubHead->account_code) ? $subSubHead->account_code : '') }}"
                                                                class="form-control" readonly>
                                                        </div>
                                                        <br>
                                                        <div class="col-lg-0 col-12 ">
                                                            <label for="account_name" class="form-label">Sub
                                                                Account Name </label>
                                                            <input id="account_name" type="text"
                                                                name="account_name"
                                                                value="{{ old('account_name', !empty($subSubHead->account_name) ? $subSubHead->account_name : '') }}"
                                                                placeholder="Please Enter Sub Account "
                                                                class="form-control" required>
                                                            @if ($errors->has('account_name'))
                                                                <div class="invalid-feedback">
                                                                    {{ $errors->first('account_name') }}
                                                                </div>
                                                            @endif
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
                                                        <a href="{{ route('sub-sub-head.list') }}"
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
        // $(document).ready(function() {
        //     // Initialize select2
        //     $('.select2').select2();
        //     $('.price').on('change', function() {
        //         var selectedValues = Array.from(this.selectedOptions).map(option => option.value);

        //         // If "Select All" is selected, select all other options except "Select All"
        //         if (selectedValues.includes("select-all")) {
        //             // Select all options except "Select All"
        //             $(this).find('option').not('[value="select-all"]').prop('selected', true);
        //         }

        //         // If "Select All" is deselected, deselect all options
        //         if (selectedValues.length === 0) {
        //             $(this).find('option').prop('selected', false);
        //         }

        //         // Make sure to update select2
        //         $(this).trigger('change.select2');
        //     });
        // });

        $(document).ready(function() {
            // Initialize select2
            $('.select2').select2();

            $('.price').on('change', function() {
                var selectedValues = Array.from(this.selectedOptions).map(option => option.value);

                if (selectedValues.includes("select-all")) {
                    // Deselect "Select All"
                    $(this).find('option[value="select-all"]').prop('selected', false);

                    // Select all other options
                    $(this).find('option').not('[value="select-all"]').prop('selected', true);

                    // Update select2 with new selections
                    $(this).trigger('change.select2');
                }
            });
        });
    </script>
    <x-slot:footerFiles>
        <script>
            var config = {
                routes: {
                    getControlHeads: "{{ url('sub-head/get-control-head-account') }}",
                    getSubHeads: "{{ url('sub-sub-head/get-sub-heads') }}",
                    getSubHeadCode: "{{ url('sub-sub-head/get-sub-sub-head-account') }}",
                },
            }
        </script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('js/common.js') }}"></script>
        <script src="{{ asset('plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>
        <script src="{{ asset('plugins/sweetalerts2/custom-sweetalert.js') }}"></script>
        <script src="{{ asset('js/sub-sub-head.js') }}"></script>




        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])

    </x-slot>
</x-base-layout>
