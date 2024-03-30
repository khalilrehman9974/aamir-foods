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
                                <li class="breadcrumb-item active" aria-current="page">Purchase Returns</li>
                                <li class="breadcrumb-item"><a href="{{ route('purchase-return.list') }}">List</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-0 col-6 ">
                    <a href="{{ route('purchase-return.create') }}" class="btn btn-primary mt-2 mb-2 me-8"
                        style="float : right; " style="">Create
                    </a>

                </div>
            </div>

        </div>

    </div>


    <div class="row layout-top-spacing">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-9 filtered-list-search mx-auto">
                <form class="form-inline my-2 my-lg-0 justify-content-center" method="get"
                    action="{{ route('purchase-return.list') }}">
                    <div class="w-100">
                        <input type="text" value="{{ @$request['param'] }}" name="param" id="param"
                            class="w-100 form-control product-search br-30" id="input-search"
                            placeholder="Search Sales...">
                        <button class="btn btn-primary _effect--ripple waves-effect waves-light"
                            type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-search">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>

                    </div>
                </form>
            </div>
        </div>
        <div id="tableCustomBasic" class="col-lg-12 col-12">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>List Of Purchase Returns</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-purchase">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col"> <b>Id </b> </th>
                                    <th scope="col" > <b>GRN No</b> </th>
                                    <th scope="col" style="width: 30%"> <b>Party </b> </th>
                                    <th scope="col" style="width: 20%"> <b>Date</b> </th>
                                    <th scope="col" style="width: 10%"> <b>Bill Number </b> </th>
                                    <th scope="col" style="width: 10%"> <b>Fare </b> </th>
                                    <th scope="col" style="width: 20%"> <b>Transporter </b> </th>
                                    <th scope="col" style="width: 20%"> <b>Carriage Inward </b> </th>
                                    <th scope="col" style="width: 20%"> <b>Total Amount</b> </th>
                                    {{-- <th scope="col" style="width: 80%"> <b>Remarks </b> </th> --}}
                                    <th class="text-center" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchaseReturns as $purchaseReturn)
                                    <tr id="row_{{ $purchaseReturn->id }}">
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $purchaseReturn->id }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $purchaseReturn->grn_no }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $purchaseReturn->party_id }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $purchaseReturn->date }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $purchaseReturn->bill_no }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $purchaseReturn->fare }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $purchaseReturn->Transporter_id }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $purchaseReturn->carriage_inward }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $purchaseReturn->total_amount }}</h6>

                                                </div>
                                            </div>
                                        </td>


                                        <td class="text-center">
                                            <div class="action-btns">
                                                @if ((!empty($permission->edit_access) && $permission->edit_access == 1) || Auth::user()->is_admin == 1)
                                                    <a href="{{ route('purchase-return.edit', ['id' => $purchaseReturn->id]) }}"
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
                                                    <a href="{{ route('purchase.delete') }}"
                                                        class="action-btn btn-delete bs-tooltip" data-toggle="tooltip"
                                                        data-placement="top" title="Delete">
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
                </div>
                <div class="inv-list-bottom-section d-sm-flex justify-content-sm-between text-center">
                    <div class="inv-list-pages-count  mb-sm-0 mb-3">
                        <div class="dataTables_info" id="invoice-list_info" role="status"
                            aria-live="polite">Showing page 1 of 2
                        </div>
                    </div>
                    <div class="inv-list-pagination">
                        <div class="dataTables_paginate paging_simple_numbers"
                            id="invoice-list_paginate">
                            <ul class="pagination">
                                <li class="paginate_button page-item previous disabled"
                                    id="invoice-list_previous">
                                    <a href="#" aria-controls="invoice-list" data-dt-idx="0"
                                        tabindex="0" class="page-link">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-arrow-left">
                                            <line x1="19" y1="12" x2="5"
                                                y2="12"></line>
                                            <polyline points="12 19 5 12 12 5">
                                            </polyline>
                                        </svg></a>
                                </li>
                                <li class="paginate_button page-item active">
                                    <a href="#" aria-controls="invoice-list" data-dt-idx="1"
                                        tabindex="0" class="page-link">1</a>
                                </li>
                                <li class="paginate_button page-item ">
                                    <a href="#" aria-controls="invoice-list" data-dt-idx="2"
                                        tabindex="0" class="page-link">2</a>
                                </li>
                                <li class="paginate_button page-item next" id="invoice-list_next"><a
                                        href="#" aria-controls="invoice-list" data-dt-idx="3"
                                        tabindex="0" class="page-link">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-arrow-right">
                                            <line x1="5" y1="12" x2="19"
                                                y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot:footerFiles>
        <script src="{{ asset('js/common.js') }}"></script>
        <script src="{{ asset('plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])
        <script>
            var config = {
                routes: {
                    deleteMainHead: "{{ url('control-head/delete') }}",
                },
            }
        </script>
    </x-slot>

</x-base-layout>
