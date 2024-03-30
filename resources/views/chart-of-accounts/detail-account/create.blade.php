<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
        </x-slot>
        <x-slot:headerFiles>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
                    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
                    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            @vite(['resources/scss/light/assets/components/timeline.scss'])

            <link rel="stylesheet" href="{{asset('plugins/sweetalerts2/sweetalerts2.css')}}">
            @vite(['resources/scss/light/plugins/sweetalerts2/custom-sweetalert.scss'])
            @vite(['resources/scss/dark/plugins/sweetalerts2/custom-sweetalert.scss'])
            @vite(['resources/scss/light/assets/components/accordions.scss'])
            @vite(['resources/scss/dark/assets/components/accordions.scss'])
            @vite(['resources/scss/light/assets/elements/alert.scss'])
            @vite(['resources/scss/dark/assets/elements/alert.scss'])
            </x-slot>
            <x-slot:scrollspyConfig>
                data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
                </x-slot>

                <!-- BREADCRUMB -->
                <div class="page-meta">
                    <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chart of Accounts</li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Account</li>
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
                                                @if(session('error'))
                                                    <div
                                                        class="alert alert-light-danger alert-dismissible fade show border-0 mb-4"
                                                        role="alert">
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                                aria-label="Close">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24" viewBox="0 0 24 24" fill="none"
                                                                 stroke="currentColor" stroke-width="2"
                                                                 stroke-linecap="round" stroke-linejoin="round"
                                                                 class="feather feather-x close"
                                                                 data-bs-dismiss="alert">
                                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                                            </svg>
                                                        </button>
                                                        <strong>Error!</strong> {{ session('error') }} </div>
                                                    {{--                                                    <div class="bg-red-500 text-white text-center text-xl m-4 p-4">{{ session('error') }}</div>--}}
                                                @endif
                                                <div class="widget-content widget-content-area">
                                                    <div class="row">
                                                        <div class="col-lg-10 col-12 ">
                                                            <form
                                                                action="{{ !empty($detailAccount) ? route('detail-account.update') : route('detail-account.save') }}"
                                                                method="POST" class="row g-3 needs-validation"
                                                                novalidate>
                                                                @csrf
                                                                <input type="hidden" name="id" id="id"
                                                                       value="{{ isset($detailAccount->id) ? $detailAccount->id : '' }}"/>
                                                                <div class="form-group input-group ">
                                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                                        <label for="inputState" class="form-label">Main
                                                                            Head</label>
                                                                        <select id="main-head" name="main_head"
                                                                                class="form-select" required>
                                                                            <option selected>Please select main head
                                                                            </option>
                                                                            @foreach($mainHeads as $index=>$value)
                                                                                <option
                                                                                    value="{{ $index }}" {{ (old("main_head") == $index ? "selected":"") || (!empty($detailAccount->main_head) ? collect($detailAccount->main_head)->contains($index) : '') ? 'selected':'' }}>{{ $value }}</option>
                                                                            @endforeach
                                                                        </select>

                                                                        @if($errors->has('main_head'))
                                                                            <div class="invalid-feedback">
                                                                                {{ $errors->first('main_head') }}
                                                                            </div>
                                                                        @endif

                                                                    </div>
                                                                    <br>
                                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                                        <label for="inputState" class="form-label">Control
                                                                            Head</label>
                                                                        @if(!empty($detailAccount))
                                                                            <select id="control-head"
                                                                                    name="control_head"
                                                                                    class="form-select" required>

                                                                                @foreach($controlHeads as $key=>$value)
                                                                                    <option
                                                                                        value="{{ $key }}" {{!empty($detailAccount) && ($detailAccount->control_head == $key) ? "selected" : ''}} {{($key == old('control_head')) ? 'selected' : ''}}>{{ $value }}</option>
                                                                                @endforeach
                                                                            </select>

                                                                        @else
                                                                            <select id="control-head"
                                                                                    name="control_head"
                                                                                    class="form-select" required>
                                                                                @foreach($controlHeads as $key=>$value)
                                                                                    <option
                                                                                        value="{{ $key }}" {{($key == old('control_head')) ? 'selected' : ''}}>{{ $value }}</option>
                                                                                @endforeach
                                                                            </select>

                                                                        @endif
                                                                        @if($errors->has('control_head'))
                                                                            <div class="invalid-feedback">
                                                                                {{ $errors->first('control_head') }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                                        <label for="inputState" class="form-label">Sub
                                                                            Head</label>
                                                                        @if(!empty($detailAccount))
                                                                            <select id="sub-head"
                                                                                    name="sub_head"
                                                                                    class="form-select" required>

                                                                                @foreach($subHeads as $key=>$value)
                                                                                    <option
                                                                                        value="{{ $key }}" {{!empty($detailAccount) && ($detailAccount->sub_head == $key) ? "selected" : ''}} {{($key == old('sub')) ? 'selected' : ''}}>{{ $value }}</option>
                                                                                @endforeach
                                                                            </select>

                                                                        @else
                                                                            <select id="sub-head"
                                                                                    name="sub_head"
                                                                                    class="form-select" required>
                                                                                @foreach($subHeads as $key=>$value)
                                                                                    <option
                                                                                        value="{{ $key }}" {{($key == old('sub_head')) ? 'selected' : ''}}>{{ $value }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        @endif
                                                                        @if($errors->has('sub_head'))
                                                                            <div class="invalid-feedback">
                                                                                {{ $errors->first('sub_head') }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                                        <label for="inputState" class="form-label">Sub-Sub
                                                                            Head</label>
                                                                        @if(!empty($detailAccount))
                                                                            <select id="sub-sub-head"
                                                                                    name="sub_sub_head"
                                                                                    class="form-select" required>

                                                                                @foreach($subSubHeads as $key=>$value)
                                                                                    <option
                                                                                        value="{{ $key }}" {{!empty($detailAccount) && ($detailAccount->sub_sub_head == $key) ? "selected" : ''}} {{($key == old('sub_sub_head')) ? 'selected' : ''}}>{{ $value }}</option>
                                                                                @endforeach
                                                                            </select>

                                                                        @else
                                                                            <select id="sub-sub-head"
                                                                                    name="sub_sub_head"
                                                                                    class="form-select" required>
                                                                                @foreach($subSubHeads as $key=>$value)
                                                                                    <option
                                                                                        value="{{ $key }}" {{($key == old('sub_sub_head')) ? 'selected' : ''}}>{{ $value }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        @endif
                                                                        @if($errors->has('sub_sub_head'))
                                                                            <div class="invalid-feedback">
                                                                                {{ $errors->first('sub_sub_head') }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-lg- 0 col-12 form-group mb-2">
                                                                        <label for="account_code" class="form-label">
                                                                            Account Code</label>
                                                                        <input id="account_code" type="text"
                                                                               name="account_code"
                                                                               value="{{ old('account_code', !empty($detailAccount->account_code) ? $detailAccount->account_code : '') }}"
                                                                               class="form-control" readonly>
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-lg-0 col-12 form-group mb-4">
                                                                        <label for="account_name" class="form-label">
                                                                            Account Name </label>
                                                                        <input id="account_name" type="text"
                                                                               name="account_name"
                                                                               value="{{ old('account_name', !empty($detailAccount->account_name) ? $detailAccount->account_name : '') }}"
                                                                               placeholder="Please Enter Detail Account "
                                                                               class="form-control" required>
                                                                        @if($errors->has('account_name'))
                                                                            <div class="invalid-feedback">
                                                                                {{ $errors->first('account_name') }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <br>
                                                                    <br>
                                                                    <div class="col-lg-0 col-12 form-group mb-2">
                                                                        <div id="toggleAccordion"
                                                                             class="accordion layout-spacing">
                                                                            <div class="card">
                                                                                <div class="card-header"
                                                                                     id="headingOne1">
                                                                                    <section class="mb-0 mt-0">
                                                                                        <div role="menu"
                                                                                             class="collapsed"
                                                                                             data-bs-toggle="collapse"
                                                                                             data-bs-target="#defaultAccordionOne"
                                                                                             aria-expanded="false"
                                                                                             aria-controls="defaultAccordionOne">
                                                                                            Additional Information
                                                                                            <div class="icons">
                                                                                                <svg
                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                    width="24"
                                                                                                    height="24"
                                                                                                    viewBox="0 0 24 24"
                                                                                                    fill="none"
                                                                                                    stroke="currentColor"
                                                                                                    stroke-width="2"
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round"
                                                                                                    class="feather feather-chevron-down">
                                                                                                    <polyline
                                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                                </svg>
                                                                                            </div>
                                                                                        </div>
                                                                                    </section>
                                                                                </div>

                                                                                <div id="defaultAccordionOne"
                                                                                     class="collapse"
                                                                                     aria-labelledby="headingOne1"
                                                                                     data-bs-parent="#toggleAccordion">
                                                                                    <div class="card-body">
                                                                                        <div
                                                                                            class="col-lg-0 col-12 form-group mb-2">
                                                                                            <label for="account_name"
                                                                                                   class="form-label">
                                                                                                Address </label>
                                                                                            <input id="address"
                                                                                                   type="text"
                                                                                                   name="address"
                                                                                                   value="{{ old('address', !empty($detailAccount->address) ? $detailAccount->address : '') }}"
                                                                                                   placeholder="Please Enter Address "
                                                                                                   class="form-control">
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-lg-0 col-12 form-group mb-2">
                                                                                            <label for="account_name"
                                                                                                   class="form-label">
                                                                                                Contact No 1 </label>
                                                                                            <input id="contact_no_1"
                                                                                                   type="text"
                                                                                                   name="contact_no_1"
                                                                                                   value="{{ old('contact_no_1', !empty($detailAccount->contact_no_1) ? $detailAccount->contact_no_1 : '') }}"
                                                                                                   placeholder="Please Enter Contact No 1"
                                                                                                   class="form-control">
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-lg-0 col-12 form-group mb-2">
                                                                                            <label for="contact_no_2"
                                                                                                   class="form-label">
                                                                                                Contact No 2 /
                                                                                                WhatsApp</label>
                                                                                            <input id="contact_no_2"
                                                                                                   type="text"
                                                                                                   name="contact_no_2"
                                                                                                   value="{{ old('contact_no_2', !empty($detailAccount->contact_no_2) ? $detailAccount->contact_no_2 : '') }}"
                                                                                                   placeholder="Please Enter Contact No 2 "
                                                                                                   class="form-control form-control-sm">
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-lg-0 col-12 form-group mb-2">
                                                                                            <label for="cnic"
                                                                                                   class="form-label">
                                                                                                Email </label>
                                                                                            <input id="email"
                                                                                                   type="text"
                                                                                                   name="email"
                                                                                                   value="{{ old('email', !empty($detailAccount->email) ? $detailAccount->email : '') }}"
                                                                                                   placeholder="Please Enter the email "
                                                                                                   class="form-control">
                                                                                        </div>
                                                                                        <div
                                                                                            class="col-lg-0 col-12 form-group mb-2">
                                                                                            <label for="cnic"
                                                                                                   class="form-label">
                                                                                                CNIC </label>
                                                                                            <input id="cnic" type="text"
                                                                                                   name="cnic"
                                                                                                   value="{{ old('cnic', !empty($detailAccount->cnic) ? $detailAccount->cnic : '') }}"
                                                                                                   placeholder="Please Enter the CNIC "
                                                                                                   class="form-control">
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="col col-md-6 form-group mb-2">
                                                                                                <label
                                                                                                    for="credit_limit"
                                                                                                    class="form-label">
                                                                                                    Credit
                                                                                                    Limit </label>
                                                                                                <input id="credit-limit"
                                                                                                       type="text"
                                                                                                       name="credit_limit"
                                                                                                       value="{{ old('credit_limit', !empty($detailAccount->credit_limit) ? $detailAccount->credit_limit : '') }}"
                                                                                                       placeholder="Please Enter Detail Account "
                                                                                                       class="form-control">
                                                                                            </div>
                                                                                            <div
                                                                                                class="col col-md-6 form-group mb-2">
                                                                                                <label
                                                                                                    for="opening_balance"
                                                                                                    class="form-label">
                                                                                                    Opening
                                                                                                    Balance </label>
                                                                                                <input
                                                                                                    id="opening-balance"
                                                                                                    type="text"
                                                                                                    name="opening_balance"
                                                                                                    value="{{ old('opening_balance', !empty($detailAccount->opening_balance) ? $detailAccount->opening_balance : '') }}"
                                                                                                    placeholder="Please Enter Opening Balance "
                                                                                                    class="form-control">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <br/>
                                                                        {{--                                                        @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)--}}
                                                                        @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                                            <button type="submit"
                                                                                    class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                                    data-bs-container="body"
                                                                                    data-bs-placement="right"
                                                                                    data-bs-content="Tooltip on right">
                                                                                @if (!isset($detailAccount))
                                                                                    Save
                                                                                @else
                                                                                    Update
                                                                                @endif
                                                                            </button>
                                                                        @endif
                                                                        <a href="{{ route('detail-account.list') }}"
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
                <x-slot:footerFiles>
                    <script>
                        var config = {
                            routes: {
                                getControlHeads: "{{ url('sub-head/get-control-head-account') }}",
                                getSubSubHeads: "{{ url('detail-account/get-sub-sub-account') }}",
                                getSubHeads: "{{ url('sub-sub-head/get-sub-heads') }}",
                                getDetailAccountCode: "{{ url('detail-account/get-detail-account-code') }}",
                            },
                        }
                    </script>
                    <script src="{{asset('js/common.js')}}"></script>
                    <script src="{{asset('js/detail-account.js')}}"></script>
                    <script src="{{asset('plugins/sweetalerts2/sweetalerts2.min.js')}}"></script>
                    <script src="{{asset('plugins/sweetalerts2/custom-sweetalert.js')}}"></script>
                    {{--                    <script src="{{asset('js/sub-sub-head.js')}}"></script>--}}
                    </x-slot>
</x-base-layout>
