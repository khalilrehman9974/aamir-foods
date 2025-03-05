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
                                <li class="breadcrumb-item"><a href="{{ route('grn.list') }}">List of GRN</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-0 col-6 ">
                    <a href="{{ route('grn.generate') }}" class="btn btn-primary mt-2 mb-2 me-8" style="float : right; "
                        style="">Create
                    </a>
                </div>
            </div>

        </div>

    </div>
    <div class="row layout-top-spacing col-md-12">
        <div id="tableCustomBasic" class="col-lg-12 col-12 layout-spacing">
            <div class="row">
                <div class="col-lg-12" style="margin-right: 0px !important;">
                    <form class="form-inline my-2 my-lg-0 justify-content-center" method="get"
                        action="{{ route('grn.list') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="text" value="{{ $param }}" name="date"
                                            id="date" class="form-control-sm search" id="input-search"
                                            placeholder="Date" style="width: 100%;">
                                        <span class="input-group-prepend">
                                            {{-- <button type="submit" class="btn btn-primary" disabled><i
                                                        class="fa fa-search"></i></button> --}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <select class="form-control-sm mb-3 select2 custom-select" value="{{ $param }}" name="party_id"
                                            id="party_id" style="width: 100%;">
                                            <option value="">Select</option>
                                            @foreach ($dropDownData['parties'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('party_id') == $key ? 'selected' : '') || (!empty($claim->party_id) ? collect($claim->party_id)->contains($key) : '') ? 'selected' : '' }}>
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
                                    <a href="{{ route('grn.list') }}" class="btn btn-primary"
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
                            <h4>List Of Goods Received Notes</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">


                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col"> <b>ID </b> </th>
                                    <th scope="col" style="width: 10%"> <b>PO#</th>
                                    <th scope="col" style="width: 15%"> <b>Date</b> </th>
                                    <th scope="col" style="width: 20%"> <b>Supplier Name</b> </th>
                                    <th scope="col" style="width: 30%"> <b>Transporter </b> </th>
                                    <th scope="col" style="width: 10%"> <b>Fare </b> </th>
                                    <th class="text-center" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($notes)
                                    @foreach ($notes as $note)
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
                                                        <h6 class="mb-0">{{ $note->purchase_order_no }}</h6>

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
                                                        <h6 class="mb-0">{{ $note->party->account_name }}</h6>

                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="media">
                                                    <div class="media-body align-self-center">
                                                        <h6 class="mb-0">{{ $note->transporter->name }}</h6>

                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="media">
                                                    <div class="media-body align-self-center">
                                                        <h6 class="mb-0">{{ $note->fare }}</h6>

                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="action-btns">
                                                    @if ((!empty($permission->edit_access) && $permission->edit_access == 1) || Auth::user()->is_admin == 1)
                                                        <a href="{{ route('grn.edit', ['id' => $note->id]) }}"
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

                                                    <a href="{{ route('purchase.create', ['id' => $note->id]) }}"

                                                        class="action-btn btn-edit bs-tooltip me-2"
                                                        data-toggle="tooltip" data-placement="top" title="Enter Purchase Invoice">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M288 256H96v64h192v-64zm89-151L279.1 7c-4.5-4.5-10.6-7-17-7H256v128h128v-6.1c0-6.3-2.5-12.4-7-16.9zm-153 31V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zM64 72c0-4.4 3.6-8 8-8h80c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H72c-4.4 0-8-3.6-8-8V72zm0 64c0-4.4 3.6-8 8-8h80c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H72c-4.4 0-8-3.6-8-8v-16zm256 304c0 4.4-3.6 8-8 8h-80c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h80c4.4 0 8 3.6 8 8v16zm0-200v96c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16v-96c0-8.8 7.2-16 16-16h224c8.8 0 16 7.2 16 16z"/></svg>
                                                    </a>
                                                    <a href="{{ route('grn.print', ['id' => $note->id]) }}" target="_blank" title="Print"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg></a>
                                                    {{-- @if ((!empty($permission->delete_access) && $permission->delete_access == 1) || Auth::user()->is_admin == 1)

                                                @endif --}}

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
                    <nav aria-label=" ListPagination">
                        <ul class="pagination justify-content-end">
                            {!! $notes->appends(request()->query())->links() !!}
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
                    deleteMainHead: "{{ url('grn/delete') }}",
                },
            }
        </script>
    </x-slot>
</x-base-layout>
