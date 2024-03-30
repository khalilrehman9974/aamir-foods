@extends('layouts.app')
@section('content')
    <style>
        .row .invoice-info {
            font-size: 25px;
        }

        #sortable1, #sortable2 {
            border: 1px solid #eee;
            width: 142px;
            min-height: 20px;
            list-style-type: none;
            margin: 0;
            padding: 5px 0 0 0;
            float: left;
            margin-right: 10px;
        }

        #sortable1 li, #sortable2 li {
            margin: 0 5px 5px 5px;
            padding: 5px;
            font-size: 1.2em;
            width: 120px;
        }

        .scrollit {
            overflow: scroll;
            height: 400px;
            overflow-x: hidden;
            padding: .5px !important;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 920px !important;">
        @if(Session::has('error'))
            <p class="alert alert-danger">{{ Session::get('error') }}</p>
    @endif
    <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1>User Detail</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('users.list') }}">List of Users</a>
                            </li>
                            <li class="breadcrumb-item active">User Detail</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header" style="background-color: #869099">
                            <h3 class="card-title">User Detail</h3>
                        </div>
                    </div>
                    <table id="example1" class="table table-bordered"
                           style="overflow-scrolling: auto; max-height: 5px">
                        <tbody>
                        <tr>
                            <td><b>User Name</b></td>
                            <td class="project-actions text-left">
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <td><b>TID</b></td>
                            <td class="project-actions text-left">
                                {{ $user->tid }}
                            </td>
                        </tr>
                        <tr>
                            <td><b>Email</b></td>
                            <td class="project-actions text-left">
                                {{ $user->email }}
                            </td>
                        </tr>
                        <tr>
                            <td><b>Department</b></td>
                            <td class="project-actions text-left">
                                {{ $user->department }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <!-- /.card-body -->
                    <!-- /.card -->
                </div>
            </div>

            <form method="post" action="{{ route('user.update', $user->id) }}">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="roles">Roles</label><span style="color:red">*</span>
                                    <select class="select2" multiple="multiple" id="roles" name="roles[]"
                                            data-placeholder="Select Permission" style="width: 100%;">
                                        <option></option>
                                        @foreach($roles as $role)
                                            <option
                                                value="{{ $role['id'] }}" {{ !empty($role['user_id'])?'selected=selected':'' }}>{{ $role['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if(isset($errors->all()[1]) && $errors->all()[1] == 1)
                                        <div class="error-message">{{ $errors->all()[0] }}</div>
                                    @endif
                                    @error('roles')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="permissions">Permissions</label><span style="color:red">*</span>
                                    <select class="select2" multiple="multiple" id="permissions" name="permissions[]"
                                            data-placeholder="Select Permission" style="width: 100%;">
                                        @foreach($permissions as $permission)
                                            <option
                                                value="{{ $permission['id'] }}" {{ !empty($permission['user_id'])?'selected=selected':'' }}>{{ $permission['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @if(isset($errors->all()[1]) && $errors->all()[1] == 1)
                                        <div class="error-message">{{ $errors->all()[0] }}</div>
                                    @endif
                                    @error('permissions')
                                    <div class="error-message">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header" style="background-color: #869099">
                                <h3 class="card-title">Assign Content Group</h3>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <b>Available Content Groups</b>
                                </h3>
                                <div style="float: right">
                                    <input type="text" class="form-control" name="search_account_group"
                                           id="search_available_group"
                                           onkeypress="searchRecord('search_available_group', 'tbl_available_group')"
                                           placeholder="Search Content Group">
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="card-body scrollit">
                                    <table id="tbl_available_group" class="table table-bordered table-striped sortable"
                                           style="overflow-scrolling: auto; max-height: 30px">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($contentGroups as $group)
                                            <tr>
                                                <td><input type="checkbox" name="content_group_id[]"
                                                           value="{{ $group->id }}"
                                                           hidden/>
                                                    {{ $group->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="2" class="drop-row" style="display: none;"></td>
                                        </tr>
                                        </tfoot>
                                        {{--                        </tfoot>--}}
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- ./col -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <b>Assigned Content Groups</b>
                                </h3>
                                <div style="float: right">
                                    <input type="text" class="form-control" name="search_assigned_group"
                                           id="search_assigned_group"
                                           onkeypress="searchRecord('search_assigned_group', 'tbl_assigned_group')"
                                           placeholder="Search Content Group">
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body clearfix">
                                <div class="card-body scrollit">
                                    <table id="tbl_assigned_group" class="table table-bordered table-striped sortable"
                                           style="overflow-scrolling: auto; max-height: 30px">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($user->getContentGroup() as $cGroup)
                                            <tr id="assigned_group_tr">
                                                <td aria-placeholder="Place here">
                                                    <input type="checkbox" name="content_group_id[]"
                                                           value="{{ $cGroup->id }}"
                                                           hidden/>
                                                    {{ $cGroup->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr style="height: 300px;">
                                            <td colspan="2" class="drop-row" style="display: none;"></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- ./col -->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header" style="background-color: #869099">
                                <h3 class="card-title">Assign BU</h3>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><b>Available BU</b></h3>
                                <div style="float: right">
                                    <input type="text" class="form-control" name="search_account_group"
                                           id="search_available_bu"
                                           onkeypress="searchRecord('search_available_bu', 'tbl_available_bu')"
                                           placeholder="Search BU">
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body clearfix">
                                <div class="card-body scrollit">
                                    <table id="tbl_available_bu" class="table table-bordered table-striped sortable"
                                           style="overflow-scrolling: auto; max-height: 10px">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($bu as $b)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="bu_id[]" id="t_default"
                                                           value="{{ $b->id }}"
                                                           hidden/>
                                                    {{ $b->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr id="assigned_bu_tr">
                                            <td colspan="2" class="drop-row" style="display: none;"></td>
                                        </tr>
                                        </tfoot>
                                        {{--                        </tfoot>--}}
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- ./col -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><b>Assigned Bu</b></h3>
                                <div style="float: right">
                                    <input type="text" class="form-control" name="search_assigned_bu"
                                           id="search_assigned_bu"
                                           onkeypress="searchRecord('search_assigned_bu', 'tbl_assigned_bu')"
                                           placeholder="Search BU">
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body clearfix">
                                <div class="card-body scrollit">
                                    <table id="tbl_assigned_bu" class="table table-bordered table-striped sortable"
                                           style="overflow-scrolling: auto; max-height: 10px">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($user->getBu() as $bu)
                                            <tr id="assigned_bu_tr">
                                                <td aria-placeholder="Place here">
                                                    <input type="checkbox" name="bu_id[]" id="t_default"
                                                           value="{{ $bu->id }}"
                                                           hidden/>

                                                    {{ $bu->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr style="height: 300px;">
                                            <td colspan="2" class="drop-row" style="display: none;"></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- ./col -->
                </div>
                <form method="post" action="{{ route('user.update', $user->id) }}">
                    @csrf
                    <input type="hidden" name="selected_content_group_ids" id="selected_content_group_ids"
                           value="{{ old('selected_content_group_ids') }}"/>
                    <input type="hidden" name="selected_bu_ids" id="selected_bu_ids"
                           value="{{ old('selected_bu_ids') }}"/>
                    <div class="row clearfix float-right">
                        <div class="col-12 float-right">
                            <input type="submit" id="btn_save" value="Save"
                                   class="btn btn-success"
                                   style="background-color: #3c8dbc">
                            <a href="{{ route('users.list') }}" class="btn btn-secondary ">Cancel</a>
                        </div>
                    </div>
                </form>
        </section>
        <!-- /.content -->
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ asset('js/users.js') }}"></script>
    <!-- /.content-wrapper -->
@endsection
