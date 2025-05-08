<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>
    <x-slot:headerFiles>

        <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

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
                                <li class="breadcrumb-item"><a
                                        href="{{ route('detail-account.pricelist') }}">{{ $pageTitle }}</a></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                {{-- <div class="col-lg-0 col-6 ">
                    <a href="{{ route('detail-account.setPrice') }}" class="btn btn-primary mt-2 mb-2 me-8"
                        style="float : right; " style="">Create
                    </a>

                </div> --}}
            </div>
        </div>
    </div>
    <div class="row layout-top-spacing col-md-12">
        <div id="tableCustomBasic" class="col-lg-12 col-12 layout-spacing">
            <div class="row">
                <div class="col-lg-12" style="margin-right: 0px !important; ">

                    <form class="form-inline my-2 my-lg-0 justify-content-center" method="get"
                        action="{{ route('detail-account.pricelist') }}" autocomplete="off">

                        <div class="row" style="margin-bottom: 5px !important;">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="detail_account_id" class="form-label">
                                            Party</label>
                                        <select class="form-control-sm mb-3 select2 custom-select" name="detail_account_id"
                                            id="detail_account_id" style="width: 100%;">
                                            <option value="">Select</option>
                                            @foreach ($dropDownData['parties'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('detail_account_id') == $key ? 'selected' : '') || (!empty($saleOrder->detail_account_id) ? collect($saleOrder->detail_account_id)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="account_name" class="form-label">
                                            Inv 4th Head</label>
                                        <select class="form-control-sm mb-3 select2 custom-select" name="master_third_level"
                                            id="master_third_level" style="width: 100%;">
                                            <option value="">Select</option>
                                            @foreach ($dropDownData['subSubHeads'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('master_third_level') == $key ? 'selected' : '') || (!empty($saleOrder->master_third_level) ? collect($saleOrder->master_third_level)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="account_name" class="form-label">
                                            Price Tag</label>
                                        <select class="form-control-sm mb-3 select2 custom-select" name="master_price_tag"
                                            id="master_price_tag" style="width: 100%;">
                                            <option value="">Select</option>
                                            @foreach ($dropDownData['priceTags'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('master_price_tag') == $key ? 'selected' : '') || (!empty($saleOrder->master_price_tag) ? collect($saleOrder->master_price_tag)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="account_name" class="form-label">
                                            Products</label>
                                        <select class="form-control-sm mb-3 select2 custom-select" name="product_id"
                                            id="product_id" style="width: 100%;">
                                            <option value="">Select</option>
                                            @foreach ($dropDownData['products'] as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ (old('product_id') == $key ? 'selected' : '') || (!empty($saleOrder->product_id) ? collect($saleOrder->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="row" style="margin-bottom: 10px !important;">
                            <div class="col-lg-0 col-8 form-group mb-4">
                                {{-- <label for="account_name" class="form-label">
                                    Account Name </label>
                                <input id="account_name" type="text" name="account_name"
                                    value="{{ old('account_name', !empty($detailAccount->account_name) ? $detailAccount->account_name : '') }}"
                                    placeholder="Please Enter Detail Account "
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}">
                                @if ($errors->has('account_name'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('account_name') }}
                                    </div>
                                @endif --}}
                            </div>
                            <div class="col-md-2">
                                <label for="account_name" class="form-label">
                                </label>
                                <span class="input-group-prepend" style="margin-top: 0px; ">
                                    <button type="submit" class="btn btn-primary" value="Search" id="search-button"
                                        style="width: 100%;"><i class="fa fa-search"></i>&nbsp;
                                        Search</button>

                                </span>
                            </div>
                            <div class="col-md-2">
                                <label for="clear-filter" class="form-label">
                                </label>
                                <span class="input-group-prepend" style="margin-top: 0px ! important;">
                                    <a href="{{ route('detail-account.pricelist') }}" class="btn btn-primary"
                                        value="Search" id="clear-filter" style="width: 100%; margin-left: 6px">Clear
                                        Filter</a>

                                </span>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>{{ $pageTitle }}</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">


                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10%"> <b>Party</b> </th>
                                    <th scope="col" style="width: 20%"> <b>Coa Sub Sub Head</b> </th>
                                    <th scope="col" style="width: 10%"> <b>Price Tag</b> </th>
                                    <th scope="col" style="width: 20%"> <b>Product</b> </th>
                                    <th scope="col" style="width: 15%"> <b>Price</b> </th>
                                    <th scope="col" style="width: 15%"> <b>Discount</b> </th>
                                    <th scope="col" style="width: 10%"> <b>Scheme</b> </th>
                                    {{-- <th scope="col" style="width: 35%"> <b>Sale Man </b> </th> --}}
                                    <th class="text-center" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                {{--                                        @if (!$detailAccounts->empty()) --}}
                                @foreach ($detailAccounts as $account)
                                    <tr id="row_{{ $account->id }}">
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $account->detailAccountCode->account_name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $account->getCoaFourthLevel->account_name }}
                                                    </h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $account->priceTag->name }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $account->getProducts->name }}
                                                    </h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $account->price }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $account->discount }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media">
                                                <div class="media-body align-self-center">
                                                    <h6 class="mb-0">{{ $account->scheme }}</h6>

                                                </div>
                                            </div>
                                        </td>
                                        {{-- <td>
                                                    <div class="media">
                                                        <div class="media-body align-self-center">
                                                            <h6 class="mb-0">{{ @$account->SaleMan->name}}</h6>

                                                        </div>
                                                    </div>
                                                </td> --}}

                                        <td class="text-center">
                                            <div class="action-btns">
                                                @if ((!empty($permission->edit_access) && $permission->edit_access == 1) || Auth::user()->is_admin == 1)
                                                    <a href="{{ route('detail-account.editPrice', ['id' => $account->id]) }}"
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
                                                {{-- @if ((!empty($permission->delete_access) && $permission->delete_access == 1) || Auth::user()->is_admin == 1)
                                                            <a href="javascript:void(0);"
                                                               class="action-btn btn-delete bs-tooltip delete" data-toggle="tooltip" data-id="{{ $account->id  }}"
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
                                                        @endif --}}

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                {{--                                        @endif --}}
                            </tbody>
                        </table>
                    </div>

                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            {!! $detailAccounts->appends(request()->query())->links() !!}
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
                    deleteMainHead: "{{ url('sale-order/delete') }}",
                },
            }
        </script>
    </x-slot>
</x-base-layout>
