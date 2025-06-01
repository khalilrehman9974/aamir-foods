<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
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

    <div class="row layout-top-spacing">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><svg> ...
                    </svg></button>
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
                                <li class="breadcrumb-item active" aria-current="page">Transporters</li>
                                <li class="breadcrumb-item"><a href="{{ route('transporter.list') }}">List</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-0 col-6 ">
                    <a href="{{ route('transporter.create') }}" class="btn btn-primary mt-2 mb-2 me-8"
                        style="float : right; " style="">Create
                    </a>

                </div>
            </div>

        </div>

    </div>


    <div class="row layout-top-spacing">
        <div class="row">
            <div class="col-md-1">

            </div>
            <div class="col-lg-8 col-md-8 col-sm-9 filtered-list-search mx-auto">
                <form class="form-inline my-2 my-lg-0 justify-content-center" method="get"
                    action="{{ route('transporter.list') }}" autocomplete="off">
                    <div class="w-100">
                        <input type="text" value="{{ request('param') }}" name="param" id="param"
                            class="w-100 form-control product-search br-30" id="input-search"
                            placeholder="Search Transporters...">
                        <button class="btn btn-primary _effect--ripple waves-effect waves-light" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-search">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>

                    </div>
                </form>
            </div>
            <div class="col-md-3 mt-1 " role="group">
                <a href="{{ route('transporter.list') }}"
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
        <div id="tableCustomBasic" class="col-lg-12 col-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>List Of Transporters</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-transporter">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col"> <b>Id </b> </th>
                                    <th scope="col" style="width: 20%"> <b>Name </b> </th>
                                    <th scope="col" style="width: 10%"> <b>City </b> </th>
                                    <th scope="col" style="width: 30%"> <b>Address </b> </th>
                                    <th scope="col" style="width: 10%"> <b>Contact Number </b> </th>
                                    <th scope="col" style="width: 10%"> <b>Contact Person</b> </th>
                                    <th scope="col" style="width: 80%"> <b>Remarks </b> </th>
                                    <th class="text-center" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transporters as $transporter)
                                    <tr id="row_{{ $transporter->id }}">
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $transporter->id }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $transporter->name }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $transporter->city }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $transporter->address }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $transporter->contact_number }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $transporter->contact_person }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $transporter->remarks }}</h6>

                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <div class="action-btns">
                                                @if ((!empty($permission->edit_access) && $permission->edit_access == 1) || Auth::user()->is_admin == 1)
                                                    <a href="{{ route('transporter.edit', ['id' => $transporter->id]) }}"
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
                                                
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            {!! $transporters->appends(request()->query())->links() !!}
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </div>
    <x-slot:footerFiles>

    </x-slot>

</x-base-layout>
