<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-slot : scrollspyConfig>
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
                                <li class="breadcrumb-item"><a href="{{ route('jv.list') }}">List of Journal
                                        Vouchers</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-0 col-6 ">
                    <a href="{{ route('jv.create') }}" class="btn btn-primary mt-2 mb-2 me-8" style="float : right; "
                        style="">Create
                    </a>

                </div>
            </div>

        </div>

    </div>
    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-lg-12 col-12">
            <div class="row">
                <div class="col-lg-12" style="margin-right: 0px !important;">
                    <form class="" method="get" action="{{ route('jv.list') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" value="{{ $param }}" name="date" id="date"
                                            class="form-control-sm search" id="input-search" placeholder="Date"
                                            style="width: 100%;">
                                        <span class="input-group-prepend">
                                            {{-- <button type="submit" class="btn btn-primary" disabled><i
                                                    class="fa fa-search"></i></button> --}}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <span class="input-group-prepend" style="margin-top: 0px; ">
                                    <button type="submit" class="btn btn-primary" value="Search" id="search-button"
                                        style="width: 100%;"><i class="fa fa-search"></i>&nbsp;
                                        Search</button>

                                </span>
                            </div>
                            <div class="col-md-2 ">
                                <span class="input-group-prepend" style="margin-top: 0px;">
                                    <a href="{{ route('jv.list') }}" class="btn btn-primary" value="Search"
                                        id="clear-filter" style="margin-left: 10px">Clear
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
                            <h4>List Of Journal Vouchers</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">


                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:5%"><b>ID </b> </th>
                                    <th scope="col" style="width: 20%"> <b>Date </b> </th>
                                    <th scope="col" style="width: 20%"> <b>Total Credit </b> </th>
                                    <th scope="col" style="width: 20%"> <b>Total Debit </b> </th>
                                    <th class="text-center" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($vouchers)
                                    @foreach ($vouchers as $jv)
                                        <tr id="row_{{ $jv->id }}">
                                            <td>
                                                <div class="media">
                                                    <div class="media-body align-self-center">
                                                        <h6 class="mb-0">{{ $jv->id }}</h6>

                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="media">
                                                    <div class="media-body align-self-center">
                                                        <h6 class="mb-0">
                                                            {{ \Carbon\Carbon::parse($jv->date)->format('d-m-Y') }}
                                                        </h6>

                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="media">
                                                    <div class="media-body align-self-center">
                                                        <h6 class="mb-0">{{ $jv->credit_amount }}</h6>

                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="media">
                                                    <div class="media-body align-self-center">
                                                        <h6 class="mb-0">{{ $jv->debit_amount }}</h6>

                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-center">
                                                <div class="action-btns">
                                                    @if ((!empty($permission->edit_access) && $permission->edit_access == 1) || Auth::user()->is_admin == 1)
                                                        <a href="{{ route('jv.edit', ['id' => $jv->id]) }}"
                                                            class="action-btn btn-edit bs-tooltip me-2"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="Edit">
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
                                @else
                                    <h4>No data found</h4>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot:footerFiles>
        <script src="{{ asset('plugins/bootstrap/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])

    </x-slot>
</x-base-layout>
