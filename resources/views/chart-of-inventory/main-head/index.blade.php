<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
        </x-slot>
        <x-slot:headerFiles>
            <meta name="csrf-token" content="{{ csrf_token() }}" />
            <script src="{{asset('js/jquery.min.js')}}"></script>
            <link rel="stylesheet" href="{{asset('plugins/sweetalerts2/sweetalerts2.css')}}">
            @vite(['resources/scss/light/plugins/sweetalerts2/custom-sweetalert.scss'])
            @vite(['resources/scss/dark/plugins/sweetalerts2/custom-sweetalert.scss'])
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
                                            <li class="breadcrumb-item active" aria-current="page">Chart of Inventory
                                            </li>
                                            <li class="breadcrumb-item"><a href="{{ route('co-inventory-main-head.list') }}">List</a>
                                            </li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-lg-0 col-6 ">
                                <a href="{{ route('co-inventory-main-head.create') }}" class="btn btn-primary mt-2 mb-2 me-8"
                                   style="float : right; " style="">Create
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row layout-top-spacing">
                    <div id="tableCustomBasic" class="col-lg-12 col-12 layout-spacing">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4>List Of Main Heads</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th scope="col" style="width: 20%"><b>Code </b></th>
                                            <th scope="col" style="width: 80%"><b>Name </b></th>
                                            <th class="text-center" scope="col"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($mainHeads as $mainHead)
                                            <tr id="row_{{ $mainHead->id }}">
                                                <td>
                                                    <div class="media">
                                                        <div class="media-body align-self-center">
                                                            <h6 class="mb-0">{{ $mainHead->code }}</h6>

                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="media">
                                                        <div class="media-body align-self-center">
                                                            <h6 class="mb-0">{{ $mainHead->name }}</h6>

                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="action-btns">
                                                        @if ((!empty($permission->edit_access) && $permission->edit_access == 1) || Auth::user()->is_admin == 1)
                                                            <a href="{{ route('co-inventory-main-head.edit', ['id' => $mainHead->id]) }}"
                                                               class="action-btn btn-edit bs-tooltip me-2"
                                                               data-toggle="tooltip" data-placement="top" title="Edit">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                     height="24" viewBox="0 0 24 24" fill="none"
                                                                     stroke="currentColor" stroke-width="2"
                                                                     stroke-linecap="round" stroke-linejoin="round"
                                                                     class="feather feather-edit-2">
                                                                    <path
                                                                        d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                                    </path>
                                                                </svg>
                                                            </a>
                                                        @endif
                                                        @if ((!empty($permission->delete_access) && $permission->delete_access == 1) || Auth::user()->is_admin == 1)
                                                            <a href="javascript:void(0);"
                                                               class="action-btn btn-delete bs-tooltip delete"
                                                               data-toggle="tooltip"
                                                               data-placement="top" title="Delete"
                                                               data-id="{{$mainHead->id}}"
                                                               data-name="{{$mainHead->account_name}}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                     height="24" viewBox="0 0 24 24" fill="none"
                                                                     stroke="currentColor" stroke-width="2"
                                                                     stroke-linecap="round" stroke-linejoin="round"
                                                                     class="feather feather-trash-2">
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
                                                        @endif

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end">
                                        {!! $mainHeads->appends(request()->query())->links() !!}
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <x-slot:footerFiles>
                    <script src="{{asset('js/common.js')}}"></script>
                    @vite(['resources/assets/js/elements/custom-search.js']);
                    <script src="{{asset('plugins/sweetalerts2/sweetalerts2.min.js')}}"></script>
                    <script src="{{asset('plugins/sweetalerts2/custom-sweetalert.js')}}"></script>
                    <script>
                        var config = {
                            routes: {
                                deleteMainHead: "{{ url('co-inv-main-head/delete') }}",
                            },
                        }
                    </script>
                    </x-slot>
</x-base-layout>
