
<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link rel="stylesheet" href="{{ asset('plugins/flatpickr/flatpickr.css') }}">
        @vite(['resources/scss/light/plugins/flatpickr/custom-flatpickr.scss'])
        @vite(['resources/scss/dark/plugins/flatpickr/custom-flatpickr.scss'])
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->


    {{--                <!-- BREADCRUMB --> --}}
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
                <li class="breadcrumb-item"><a href="{{ route('users.list') }}">List of Users</a></li>
                <li class="breadcrumb-item"><a href="{{ route('register') }}">Create</a></li>
            </ol>
        </nav>
    </div>



    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-content widget-content-area">
                    <div class="simple-pill">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
                                <div id="basic" class="col-lg-12">
                                    <div class="statbox widget box box-shadow">
                                        <div class="widget-header">
                                            <div class="row">
                                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                    <h4>Create User</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container">

                                            <div class="widget-content widget-content-area">
                                                <form method="POST"
                                                    action="{{ isset($user->id) ? route('user.update') : route('register') }}"
                                                    class="row g-3 needs-validation" novalidate>
                                                    @csrf
                                                    <input type="hidden" name="id" id="id"
                                                        value="{{ isset($user->id) ? $user->id : '' }}" />
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-8">

                                                                <label for="validationCustom01"
                                                                    class="form-label">Name</label>
                                                                <input type="text" name="name"
                                                                    class="form-control" id="name"
                                                                    value="{{ old('name', !empty($user->name) ? $user->name : '') }}"
                                                                    placeholder="Enter User Name" required>
                                                                @error('name')
                                                                    <span style="color:red" class="invalid-feedback">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-4">

                                                                <label class="form-label">Email</label>
                                                                <input type="email" name="email"
                                                                value="{{ old('email', !empty($user->email) ? $user->email : '') }}"
                                                                    class="form-control">
                                                                @error('email')
                                                                    <div class="invalid-feedback">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-8">

                                                                <label>Password<span
                                                                        style="color: red">*</span></label>
                                                                <input id="password" type="password"
                                                                    class="form-control @error('password') is-invalid @enderror"
                                                                    name="password" autocomplete="new-password">

                                                                @error('password')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror

                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Confirm Password<span
                                                                    style="color: red">*</span></label>
                                                                <input id="password-confirm" type="password"
                                                                    class="form-control @error('password') is-invalid @enderror"
                                                                    name="password_confirmation"
                                                                    autocomplete="new-password">
                                                                @error('password')
                                                                    <span style="color:red" class="invalid-feedback">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-2">
                                                                    <label>User Type<span
                                                                        style="color: red">*</span></label>
                                                                <div class="n-chk">

                                                                    <div class="form-check form-check-primary form-check-inline me-0 mb-0">

                                                                        <input
                                                                            class="form-check-input inbox-chkbox contact-chkbox"
                                                                            type="checkbox" name="is_admin"
                                                                            value="1"
                                                                            {{ old('is_admin') && $user->is_admin == old('is_admin') ? 'checked' : '' }}
                                                                            @if (@$user->is_admin == 1) checked @endif>
                                                                    </div>
                                                                </div>
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <br>
                                                        <a href="{{ route('users.list') }}" style="float: right;"
                                                            class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                        @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                                            <button type="submit" style="float: right"
                                                                class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                                                data-bs-container="body" data-bs-placement="right"
                                                                data-bs-content="Tooltip on right">
                                                                @if (!isset($user))
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

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])

    </x-slot>

</x-base-layout>
