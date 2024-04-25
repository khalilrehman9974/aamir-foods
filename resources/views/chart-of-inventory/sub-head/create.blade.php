<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!--  BEGIN CUSTOM STYLE FILE  -->
        @vite(['resources/scss/light/assets/components/timeline.scss'])

        <link rel="stylesheet" href="{{asset('plugins/sweetalerts2/sweetalerts2.css')}}">
        @vite(['resources/scss/light/plugins/sweetalerts2/custom-sweetalert.scss'])
        @vite(['resources/scss/dark/plugins/sweetalerts2/custom-sweetalert.scss'])

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
                                                    action="{{ !empty($subHead) ? route('co-inventory-sub-head.update') : route('co-inventory-sub-head.save') }}"
                                                    method="POST" class="row g-3 needs-validation" novalidate>
                                                    @csrf
                                                    <input type="hidden" name="id" id="id"
                                                        value="{{ isset($subHead->id) ? $subHead->id : '' }}" />
                                                    <div class="form-group">
                                                        <div class="col-lg-0 col-12">
                                                            <label for="inputState" class="form-label">Main Account Head</label>
                                                            <select id="main-head" name="main_head" class="form-select" required>
                                                                <option selected=""></option>
                                                                @foreach($mainHeads as $index=>$value)
                                                                    <option value="{{ $index }}" {{ (old("main_head") == $index ? "selected":"") || (!empty($subHead->main_head) ? collect($subHead->main_head)->contains($index) : '') ? 'selected':'' }}>{{ $value }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <br>
                                                        <div class="col-lg- 0 col-12 ">
                                                            <label for="code" class="form-label">Sub Account Code</label>
                                                            <input id="code" type="text" name="code"
                                                                   value="{{ old('code', !empty($subHead->code) ? $subHead->code : '') }}"
                                                                   class="form-control" readonly>
                                                            <div class="invalid-feedback">
                                                                Please provide a Main Head.
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="col-lg-0 col-12 ">
                                                            <label for="name" class="form-label">Sub Account Name </label>
                                                            <input id="name" type="text" name="name"
                                                                   value="{{ old('name', !empty($subHead->name) ? $subHead->name : '') }}"
                                                                   placeholder="Please Enter Sub Account "
                                                                   class="form-control" required>
                                                        </div>

                                                        @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                            <button type="submit"
                                                                    class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                    data-bs-container="body" data-bs-placement="right"
                                                                    data-bs-content="Tooltip on right">
                                                                @if (!isset($subHead))
                                                                    Save
                                                                @else
                                                                    Update
                                                                @endif
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('co-inventory-sub-head.list') }}"
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
{{--            <script src="{{asset('js/common.js')}}"></script>--}}
            <script src="{{asset('plugins/sweetalerts2/sweetalerts2.min.js')}}"></script>
            <script src="{{asset('plugins/sweetalerts2/custom-sweetalert.js')}}"></script>
            <script>
                $('#main-head').on('change', function () {
                    var mainCode = $('#main-head :selected').val();
                    let url = "{{ url('co-inv-sub-head/get-sub-head-account') }}" + '/' + mainCode;
                    $.ajax({
                        url : url,
                        type : 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            $("#code").val(response.account_code);
                        },
                        complete: function () {
                            $('#loading').css('display', 'none');
                        },
                        error: function (errorThrown) {
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
            </x-slot>
</x-base-layout>
