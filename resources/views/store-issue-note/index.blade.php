<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>

    <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

</x-slot>

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>

    <div class="row layout-top-spacing">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <svg> ...
                    </svg>
                </button>
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
                                <li class="breadcrumb-item active" aria-current="page">Store Issue Note</li>
                                <li class="breadcrumb-item"><a href="{{ route('store-issue-note.list') }}">List</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-0 col-6 ">
                    <a href="{{ route('store-issue-note.create') }}" class="btn btn-primary mt-2 mb-2 me-8"
                        style="float : right; " style="">Create
                    </a>

                </div>
            </div>

        </div>

    </div>

    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-lg-12 col-12">
            <div class="row">
                <div class="col-lg-12" style="margin-right: 0px !important;">
                    <form class="form-inline my-2 my-lg-0 justify-content-center" method="get"
                        action="{{ route('store-issue-note.list') }}">

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="inputState" class="form-label">Date</label>
                                        <input type="text" value="{{ $param }}" name="date"
                                            id="date" class="form-control-sm search" id="input-search"
                                            placeholder="Date" style="width: 100%;">
                                        <span class="input-group-prepend">

                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="inputState" class="form-label">To Department</label>
                                        <select class="form-control-sm mb-3 select2 custom-select" name="to_department"
                                            id="to_department" style="width: 100%;">
                                            <option value="">Select</option>
                                            @foreach ($dropDownData['departments'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('to_department') == $key ? 'selected' : '') || (!empty($saleOrder->to_department) ? collect($saleOrder->to_department)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <span class="input-group-prepend" style="margin-top: 0px; ">
                                    <button type="submit" class="btn btn-primary" value="Search"
                                        id="search-button" style="width: 100%;"><i class="fa fa-search"></i>&nbsp;
                                        Search</button>

                                </span>
                            </div>
                            <div class="col-md-2 ">
                                <span class="input-group-prepend" style="margin-top: 0px;">
                                    <a href="{{ route('store-issue-note.list') }}" class="btn btn-primary"
                                        value="Search" id="clear-filter" style="margin-left: 10px">Clear
                                        Filter</a>

                                </span>
                            </div>
                    </form>
                </div>

            </div>
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>List Of Store Issue Notes</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-note">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10%"><b>Id </b></th>
                                    <th scope="col" style="width: 20%"><b>Date </b></th>
                                    <th scope="col" style="width: 30%"><b>Receiver Name </b></th>
                                    <th scope="col" style="width: 20%"><b>From Department</b></th>
                                    <th scope="col" style="width: 20%"><b>To Department</b></th>

                                    <th class="text-center" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($storeIssueNotes as $note)
                                    <tr id="row_{{ $note->id }}">
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $note->id }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ \Carbon\Carbon::parse($note->date)->format('d-m-Y') }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{$note->receiver_name}}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $note->fromDepartment->name }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $note->toDepartment->name }}</h6>

                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <div class="action-btns">
                                                {{-- @if ((!empty($permission->edit_access) && $permission->edit_access == 1) || Auth::user()->is_admin == 1)

                                                @endif --}}
                                                <a href="{{ route('store-issue-note.edit', ['id' => $note->id]) }}"
                                                    class="action-btn btn-edit bs-tooltip me-2" data-toggle="tooltip"
                                                    data-placement="top" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-edit-2">
                                                        <path
                                                            d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                {{-- <a href="javascript:void(0);"
                                                    class="action-btn btn-delete bs-tooltip delete"
                                                    data-id="{{ $note->id }}" data-toggle="tooltip"
                                                    data-placement="top" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" class="feather feather-trash-2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                        </path>
                                                        <line x1="10" y1="11" x2="10"
                                                            y2="17">
                                                        </line>
                                                        <line x1="14" y1="11" x2="14"
                                                            y2="17">
                                                        </line>
                                                    </svg>
                                                </a> --}}
                                                {{-- @if ((!empty($permission->delete_access) && $permission->delete_access == 1) || Auth::user()->is_admin == 1)

                                                @endif --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <nav aria-label=" ListPagination">
                        <ul class="pagination justify-content-end">
                            {!! $storeIssueNotes->appends(request()->query())->links() !!}
                        </ul>
                    </nav>
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
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
        <script>
            var config = {
                routes: {
                    deleteMainHead: "{{ url('store-issue-note/delete') }}",
                },
            }
        </script>

    </x-slot>
</x-base-layout>
