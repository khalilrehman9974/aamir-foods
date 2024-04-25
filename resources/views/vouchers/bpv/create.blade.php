<x-base-layout :scrollspy="false">
    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
            integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link rel="stylesheet" href="{{ asset('plugins/flatpickr/flatpickr.css') }}">
        <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />
        @vite(['resources/scss/light/plugins/flatpickr/custom-flatpickr.scss'])
        @vite(['resources/scss/dark/plugins/flatpickr/custom-flatpickr.scss'])


        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link href="../src/plugins/src/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../src/plugins/src/filepond/filepond.min.css">
        <link rel="stylesheet" href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css">

        <link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />
        <link href="../src/plugins/css/light/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">

        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>


    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Vouchers</li>
                <li class="breadcrumb-item"><a href="{{ route('bpv.list') }}">List of Bank Payment Vouchers</a></li>
                <li class="breadcrumb-item"><a href="{{ route('bpv.create') }}">Create Voucher</a></li>

            </ol>
        </nav>
    </div>

    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Create Bank Payment Voucher</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <div class="simple-pill">

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
                                <div id="basic" class="col-lg-12">
                                    <div class="statbox widget box box-shadow">
                                        <div class="widget-content widget-content-area">
                                            <div class="row">
                                                <div class="col-lg-12 col-12 ">
                                                    <form
                                                        action="{{ !empty($bpv) ? route('bpv.update') : route('bpv.save') }}"
                                                        method="POST" class="row g-3 needs-validation" novalidate>
                                                        @csrf
                                                        <input type="hidden" name="id" id="id"
                                                            value="{{ isset($bpv->id) ? $bpv->id : '' }}" />
                                                        <div class="invoice-detail-terms">
                                                            <div class="row justify-content-between">
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-lg-0 col-7 mt-4">
                                                                            <label for="">
                                                                                <h4>Voucher #:{{ @$maxid }}
                                                                                    {{ @$currentid }}</h4>
                                                                            </label>

                                                                        </div>

                                                                        <div class="col-md-5">

                                                                            <div class="form-group mb-4">

                                                                                <label for="date">
                                                                                    Date</label>
                                                                                <input type="text"
                                                                                    class="form-control form-control-sm"
                                                                                    name="date" id="date"
                                                                                    placeholder="Select The Date">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="tab-content" id="pills-tabContent">
                                                            <div class="invoice-detail-items mt-0">
                                                                <div class="table-responsive">
                                                                    <table class="table item-table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th class="" hidden>
                                                                                </th>
                                                                                <th></th>
                                                                                <th>Code</th>
                                                                                <th class="">Account Title
                                                                                </th>
                                                                                <th class="">Description</th>
                                                                                {{-- <th class="">
                                                                                </th> --}}


                                                                            </tr>
                                                                            <tr aria-hidden="true"
                                                                                class="mt-3 d-block table-row-hidden">
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr class="tr_clone validator_0">
                                                                                <td class="delete-item-row">
                                                                                    <ul class="table-controls">
                                                                                        <li>
                                                                                            <a href="javascript:void(0);"
                                                                                                class="delete-item"
                                                                                                data-toggle="tooltip"
                                                                                                data-placement="top"
                                                                                                title=""
                                                                                                data-original-title="Delete">
                                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                    width="24"
                                                                                                    height="24"
                                                                                                    viewBox="0 0 24 24"
                                                                                                    fill="none"
                                                                                                    stroke="currentColor"
                                                                                                    stroke-width="2"
                                                                                                    stroke-linecap="round"
                                                                                                    stroke-linejoin="round"
                                                                                                    class="feather feather-x-circle">
                                                                                                    <circle
                                                                                                        cx="12"
                                                                                                        cy="12"
                                                                                                        r="10">
                                                                                                    </circle>
                                                                                                    <line
                                                                                                        x1="15"
                                                                                                        y1="9"
                                                                                                        x2="9"
                                                                                                        y2="15">
                                                                                                    </line>
                                                                                                    <line
                                                                                                        x1="9"
                                                                                                        y1="9"
                                                                                                        x2="15"
                                                                                                        y2="15">
                                                                                                    </line>
                                                                                                </svg>
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </td>
                                                                                <td hidden>
                                                                                    <input type="text"
                                                                                        name="row_id[]" class="row_id"
                                                                                        value="0" hidden>
                                                                                </td>


                                                                                <td class="code">
                                                                                    <input type="text" id="code"
                                                                                        class="form-control form-control-sm code"
                                                                                        name="code[]"
                                                                                        {{-- value="{{ @$bpv ? '' : $detailAccount  }} {{ old('code', !empty($bpv->code) ? $bpv->code : '') }}" --}}
                                                                                        placeholder="Code">
                                                                                </td>

                                                                                <td class="description">
                                                                                    <select id="party"
                                                                                        class="form-control select2 custom-select mr-0 mb-0 form-control-sm">
                                                                                        <option selected="">
                                                                                            Please select the
                                                                                            Party</option>
                                                                                        @foreach ($dropDownData['accounts'] as $key => $value)
                                                                                            <option
                                                                                                value="{{ $key }}"
                                                                                                {{ (old('account_title_id') == $key ? 'selected' : '') || (!empty($bpv->account_title_id) ? collect($bpv->account_title_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                {{ $value }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <textarea id="description" type="text" name="description[]"
                                                                                        value="{{ old('description', !empty($bpv->description) ? $bpv->description : '') }}"
                                                                                        placeholder="Please Enter Description" class="form-control form-control-sm mt-3"></textarea>
                                                                                </td>
                                                                                <td class="title">
                                                                                    <select id="account_title"
                                                                                        class="form-control select2 custom-select mr-0 mb-0 form-control-sm">
                                                                                        <option selected="">
                                                                                            Please select the
                                                                                            Party</option>
                                                                                        @foreach ($dropDownData['accounts'] as $key => $value)
                                                                                            <option
                                                                                                value="{{ $key }}"
                                                                                                {{ (old('account_title_id') == $key ? 'selected' : '') || (!empty($bpv->account_title_id) ? collect($bpv->account_title_id)->contains($key) : '') ? 'selected' : '' }}>
                                                                                                {{ $value }}
                                                                                            </option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                    <input type="text"
                                                                                        id="amount"
                                                                                        class="form-control form-control-sm mt-4 amount"
                                                                                        value="{{ old('debit', !empty($bpv->debit) ? $bpv->debit : '') }}"
                                                                                        name="amount[]"
                                                                                        placeholder="Amount">

                                                                                </td>
                                                                                <br>
                                                                                {{-- <td class="title">
                                                                                    <textarea id="description" type="text" name="description[]"
                                                                                        value="{{ old('description', !empty($bpv->description) ? $bpv->description : '') }}"
                                                                                        placeholder="Please Enter Description" class="form-control form-control-sm mt-0"></textarea>
                                                                                </td> --}}
                                                                                {{--
                                                                                <td class="text-right qty">

                                                                                </td> --}}


                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <a href="javascript:void(0);"
                                                                    class="btn btn-dark additem mt-3"
                                                                    id="add-item">Add
                                                                    Item</a>

                                                            </div>


                                                            <div class="col-xl-6 invoice-address-client invoice-detail-total mt-3"
                                                                style="float:right">
                                                                <div class="invoice-address-client-fields">
                                                                    <div class="form-group row">
                                                                        <label for="gross-amount"
                                                                            class="col-sm-4 col-form-label col-form-label-sm ">Tot.
                                                                            Amount
                                                                        </label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" id="gross-amount"
                                                                                class="form-control form-control-sm gross-amount "
                                                                                name="total_amount" id="gross-amount"
                                                                                placeholder="Total Amount" readonly>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>






                                                        </div>


                                                        <div class="invoice-detail-terms">

                                                            <div class="row">
                                                                <div class="col-xl-12 ">
                                                                    <a href="{{ route('bpv.list') }}"
                                                                        style="float: right;"
                                                                        class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                                    <button type="submit" style="float: right"
                                                                        class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 mr-5"
                                                                        data-bs-container="body"
                                                                        data-bs-placement="right"
                                                                        data-bs-content="Tooltip on right">
                                                                        @if (!isset($bpv))
                                                                            Save
                                                                        @else
                                                                            Update
                                                                        @endif
                                                                    </button>
                                                                    {{-- @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)

                                                                        @endif --}}
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementsByClassName('additem')[0].addEventListener('click', function() {

            let getTableElement = document.querySelector('.item-table');
            let currentIndex = getTableElement.rows.length;

            let $html = '<tr>' +
                '<td class="delete-item-row">' +
                '<ul class="table-controls">' +
                '<li><a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>' +
                '</ul>' +
                '</td>' +
                '<td hidden><input type="text" name="row_id[]" class="row_id" value="' + currentIndex +
                '" hidden></td>' +
                '<td class="rate"><input type="text" class = "form-control form-control-sm" name = "code[]" placeholder = "Code" ></td> ' +
                '<td class="description"><select id="account_title" class="form-control select2 custom-select mr-0 mb-0 form-control-sm"> <option selected=""> Please select the Party</option> @foreach ($dropDownData['accounts'] as $key => $value) <option value="{{ $key }}" {{ (old('account_title_id') == $key ? 'selected' : '') || (!empty($bpv->account_title_id) ? collect($bpv->account_title_id)->contains($key) : '') ? 'selected' : '' }}> {{ $value }} </option> @endforeach </select><textarea id="description" type="text" name="description[]" value="{{ old('description', !empty($bpv->description) ? $bpv->description : '') }}" placeholder="Please Enter Description" class="form-control form-control-sm mt-3"></textarea> </td>' +
                '<td class="title"> <select id="account_title" class="form-control select2 custom-select mr-0 mb-0 form-control-sm"> <option selected=""> Please select the Party</option> @foreach ($dropDownData['accounts'] as $key => $value) <option value="{{ $key }}" {{ (old('account_title_id') == $key ? 'selected' : '') || (!empty($bpv->account_title_id) ? collect($bpv->account_title_id)->contains($key) : '') ? 'selected' : '' }}> {{ $value }} </option> @endforeach </select> <input type="text" id="amount" class="form-control form-control-sm mt-4 amount" value="{{ old('debit', !empty($bpv->debit) ? $bpv->debit : '') }}" name="amount[]" placeholder="Amount"></td>' +
                // '<td class="text-right qty"> <input id="amount" type="text" name="amount[]" value="{{ old('debit', !empty($bpv->debit) ? $bpv->debit : '') }}" placeholder="Amount " class="form-control form-control-sm amount"></td>' +
                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);
            deleteItemRow();

        })

        deleteItemRow();
        selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
        selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
        selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);

        var f2 = flatpickr(document.getElementById('due'), {
            defaultDate: currentDate.setDate(currentDate.getDate() + 5),
        });

        function deleteItemRow() {
            let deleteItem = document.querySelectorAll('.delete-item');
            for (var i = 0; i < deleteItem.length; i++) {
                deleteItem[i].addEventListener('click', function() {
                    this.parentElement.parentNode.parentNode.parentNode.remove();
                })
            }
        }
    </script>

    <x-slot:footerFiles>
        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>
        <script src="{{ asset('plugins/invoice-add/invoice-add.js') }}"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])

        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('js/common.js') }}"></script>
        <script src="{{ asset('js/BankPaymentVoucher.js') }}"></script>


        <script>
             var config = {
                routes: {
                    getPartyCode: "{{ url('bpv/get-party-code') }}",
                    getParty: "{{ url('bpv/get-party') }}",
                },
            }
        </script>

    </x-slot>


</x-base-layout>
