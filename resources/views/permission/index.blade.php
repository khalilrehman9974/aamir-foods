<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{-- {{ $pageTitle }} --}}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        @vite(['resources/scss/light/assets/elements/search.scss', 'resources/scss/dark/assets/elements/search.scss'])
        <link rel="stylesheet" href="{{ asset('plugins/sweetalerts2/sweetalerts2.css') }}">
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>
    <!-- Begin page -->
    <div id="wrapper">
        <div class="content-page">
            <div class="container-fluid">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <h4 class="page-title">User Management Permission</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title end breadcrumb -->

                <div class="row">
                    <div class="col-lg-12">
                        @if (session()->has('message'))
                            <div class="alert" style="background-color: #a9e8a8">
                                {{ session('message') }}
                            </div>
                        @endif
                        <div class="card m-b-30">
                            <div class="card-body">
                                <form method="get" action="{{ route('user.permission') }}" id="user-permission-form">
                                    <div class="input-group">
                                        <h6 class="light-dark w-100">Select User<span style="color: red">*</span></h6>

                                        <select class="select2 form-control mb-3 custom-select" name="userid"
                                            id="userid" style="width: 100%; height:46px;">
                                            <option value="">Select</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" <?php if (isset($_GET['userid']) && $_GET['userid'] == $user->id) {
                                                    echo ' selected';
                                                } ?>>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>

                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('permission.save') }}" method="post">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id" value="{{ $user_id ? $user_id : '' }}" />
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="card m-b-30">
                                <div class="card-body">
                                    <table class="table table-hover subject-table">
                                        <thead>
                                            <tr>
                                                <th>Menu Name</th>
                                                <th>Menu Access</th>
                                                <th>Select Access</th>
                                                <th>Entry Access</th>
                                                <th>Edit Access</th>
                                                <th>Delete Access</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @if ($menus)
                                                @php $count = 1; @endphp
                                                @foreach ($menus as $menu)
                                                    <tr>
                                                        <td>{{ $menu->name }}</td>
                                                        <td>
                                                            <div class="form-group">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" value="2"
                                                                        {{ isset(\App\Http\Controllers\PermissionController::getPermissionByUserAndMenu($user_id, $menu->id, 'menu_access')->menu_access) && \App\Http\Controllers\PermissionController::getPermissionByUserAndMenu($user_id, $menu->id, 'menu_access')->menu_access == 1 ? 'checked' : '' }}
                                                                        name="menu_access[{{ $menu->id }}]"
                                                                        class="custom-control-input"
                                                                        id="customCheck{{ $menu->id }}{{ $count + 1 }}">
                                                                    <span class="custom-control-label"
                                                                        for="chbxTerms"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" value="2"
                                                                        {{ isset(\App\Http\Controllers\PermissionController::getPermissionByUserAndMenu($user_id, $menu->id, 'select_access')->select_access) && \App\Http\Controllers\PermissionController::getPermissionByUserAndMenu($user_id, $menu->id, 'select_access')->select_access == 1 ? 'checked' : '' }}
                                                                        name="select_access[{{ $menu->id }}]"
                                                                        class="custom-control-input"
                                                                        id="customCheck{{ $menu->id }}{{ $count + 2 }}">
                                                                    <span class="custom-control-label"
                                                                        for="chbxTerms"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" value="2"
                                                                        {{ isset(\App\Http\Controllers\PermissionController::getPermissionByUserAndMenu($user_id, $menu->id, 'insert_access')->insert_access) && \App\Http\Controllers\PermissionController::getPermissionByUserAndMenu($user_id, $menu->id, 'insert_access')->insert_access == 1 ? 'checked' : '' }}
                                                                        name="insert_access[{{ $menu->id }}]"
                                                                        class="custom-control-input"
                                                                        id="customCheck{{ $menu->id }}{{ $count + 3 }}">
                                                                    <span class="custom-control-label"
                                                                        for="chbxTerms"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox"
                                                                        name="edit_access[{{ $menu->id }}]"
                                                                        value="2"
                                                                        {{ isset(\App\Http\Controllers\PermissionController::getPermissionByUserAndMenu($user_id, $menu->id, 'edit_access')->edit_access) && \App\Http\Controllers\PermissionController::getPermissionByUserAndMenu($user_id, $menu->id, 'edit_access')->edit_access == 1 ? 'checked' : '' }}
                                                                        class="custom-control-input"
                                                                        id="customCheck{{ $menu->id }}{{ $count + 4 }}">
                                                                    <span class="custom-control-label"
                                                                        for="chbxTerms"></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="form-group">
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox"
                                                                        name="delete_access[{{ $menu->id }}]"
                                                                        {{ isset(\App\Http\Controllers\PermissionController::getPermissionByUserAndMenu($user_id, $menu->id, 'delete_access')->delete_access) && \App\Http\Controllers\PermissionController::getPermissionByUserAndMenu($user_id, $menu->id, 'delete_access')->delete_access == 1 ? 'checked' : '' }}
                                                                        class="custom-control-input"
                                                                        id="customCheck{{ $menu->id }}{{ $count + 5 }}">
                                                                    <span class="custom-control-label"
                                                                        for="chbxTerms"></span>
                                                                </label>
                                                        </td>
                                                    </tr>
                                                    @php $count++; @endphp
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="button-items mb-0 text-right">
                                    <button type="button"
                                        class="btn btn-outline-danger waves-effect waves-light">Cancel</button>
                                    <button type="submit"
                                        class="btn btn-primary waves-effect waves-light">Save</button>
                                </div>

                            </div>
                        </div>
                    </div> <!-- end col -->
            </div>
            </form>
        </div>
    </div>
    </div>



    </div><!-- container -->

    <script>
        $("#userid").change(function() {
            $("#user-permission-form").submit();
            $("#user_id").val($(this).val());
        })
    </script>

    <x-slot:footerFiles>
        <script src="{{ asset('js/common.js') }}"></script>
        <script src="{{ asset('plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
        <script>
            var config = {
                routes: {
                    deleteMainHead: "{{ url('sale-order/delete') }}",
                },
            }
        </script>
    </x-slot>

</x-base-layout>
