<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        @vite(['resources/scss/light/assets/elements/search.scss', 'resources/scss/dark/assets/elements/search.scss'])
        <link rel="stylesheet" href="{{ asset('plugins/sweetalerts2/sweetalerts2.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script src="{{ asset('js/jquery.min.js') }}"></script>
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
                <div class="col-md-1 mt-1" role="group">
                </div>
                <div class="col-lg-8 col-md-8 col-sm-9 filtered-list-search mx-auto">
                    <form class="form-inline my-2 my-lg-0 justify-content-center" method="get"
                        action="{{ route('store-issue-note.list') }}">
                        <div class="w-100">
                            <input type="text" value="{{$param}}" name="param" id="param"
                                class="w-100 form-control product-search br-30" id="input-search"
                                placeholder="Search Store Issue Note...">
                            <button class="btn btn-primary _effect--ripple waves-effect waves-light" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </button>

                        </div>
                    </form>
                </div>
                <div class="col-md-3 mt-1 " role="group">
                    <a href="{{ route('store-issue-note.list') }}"
                        class="btn btn-primary _effect--ripple waves-effect waves-light" id="Refresh Cw" type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-refresh-cw">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14">
                            </polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15">
                            </path>
                        </svg>

                    </a>
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
                                    <th scope="col" style="width: 30%"><b>Product </b></th>
                                    <th scope="col" style="width: 20%"><b>Issued To</b></th>
                                    <th scope="col" style="width: 20%"><b>Issued By</b></th>
                                    <th scope="col" style="width: 20%"><b>Remarks </b></th>
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
                                                    <h6 class="mb-0">{{ $note->product->name }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $note->issued_to }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $note->issued_by }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $note->remarks }}</h6>

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
                                                <a href="javascript:void(0);"
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
                                                </a>
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
    <x-slot:footerFiles>
        <script src="{{ asset('plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>
        <script src="{{ asset('js/common.js') }}"></script>
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
