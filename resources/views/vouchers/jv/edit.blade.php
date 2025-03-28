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
        <link href="{{ asset('plugins/invoice-add/invoice-add.css') }}" rel="stylesheet" type="text/css" />

        <!--  BEGIN CUSTOM STYLE FILE  -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="../src/plugins/src/filepond/filepond.min.css">
        <link rel="stylesheet" href="../src/plugins/src/filepond/FilePondPluginImagePreview.min.css">

        <link href="../src/plugins/css/light/filepond/custom-filepond.css" rel="stylesheet" type="text/css" />

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
                <li class="breadcrumb-item"><a href="{{ route('jv.list') }}">List of Journal Vouchers</a></li>
                <li class="breadcrumb-item"><a href="{{ route('jv.create') }}">Create Voucher</a></li>

            </ol>
        </nav>
    </div>

    <div class="row layout-top-spacing">
        <div id="tableCustomBasic" class="col-xl-12 col-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Create Journal Voucher</h4>
                        </div>
                    </div>
                </div>
                <div class="widget-content widget-content-area">

                    <div class="simple-pill">

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
                                <form id="jvForm" action="{{ !empty($jv) ? route('jv.update') : route('jv.save') }}"
                                    method="POST" class="row g-3">
                                    @csrf
                                    <input type="hidden" name="id" id="id"
                                        value="{{ isset($jv->id) ? $jv->id : '' }}" />
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
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="{{ $date }}" name="date" id="date"
                                                        placeholder="Select The Date" required>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="invoice-detail-items" style="padding: 0px 0px 0px 0px;">
                                            <div class="table-responsive">
                                                <table class="table item-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="" hidden>
                                                            </th>
                                                            <th></th>

                                                            <th class="" style="width: 50%"><b>Debit Account
                                                                    /Credit
                                                                    Account/Description</b>
                                                            </th>
                                                            {{-- <th class=""><b>Credit Account</b>
                                                            </th> --}}
                                                            <th class=""><b>Debit</b>
                                                            </th>
                                                            <th class=""><b>Credit</b>
                                                            </th>


                                                        </tr>
                                                        <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($jvDetails))
                                                            @foreach ($jvDetails as $jvDetail)
                                                                @php
                                                                    $index = $loop->index + 2; // Starts from 2
                                                                @endphp
                                                                <tr class="tr_clone validator_0">
                                                                    <td class="delete-item-row">
                                                                        <ul class="table-controls">
                                                                            <li>
                                                                                <a href="javascript:void(0);"
                                                                                    class="delete-item"
                                                                                    data-toggle="tooltip"
                                                                                    data-placement="top" title=""
                                                                                    data-original-title="Delete">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="24" height="24"
                                                                                        viewBox="0 0 24 24"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        stroke-width="2"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        class="feather feather-x-circle">
                                                                                        <circle cx="12"
                                                                                            cy="12" r="10">
                                                                                        </circle>
                                                                                        <line x1="15"
                                                                                            y1="9"
                                                                                            x2="9"
                                                                                            y2="15">
                                                                                        </line>
                                                                                        <line x1="9"
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
                                                                        <input type="text" name="row_id[]"
                                                                            class="row_id" value="2" hidden>
                                                                    </td>

                                                                    <td class="title">
                                                                        <select id="debit_account"
                                                                            name="debit_account[]"
                                                                            class="form-control select2 custom-select mr-0 mb-0 form-control-sm debit_account_{{ $index }}">
                                                                            <option selected="">
                                                                                Please select the
                                                                                Debit Account</option>
                                                                            @foreach ($dropDownData['accounts'] as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    {{ (old('debit_account') == $key ? 'selected' : '') || (!empty($jvDetail->debit_account) ? collect($jvDetail->debit_account)->contains($key) : '') ? 'selected' : '' }}>
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                    </td>
                                                                    <br>


                                                                    <td class="text-right qty">
                                                                        <input type="number" id="debit"
                                                                            class="form-control form-control-sm debit"
                                                                            value="{{ old('debit', !empty($jvDetail->debit) ? $jvDetail->debit : '') }}"
                                                                            name="debit[]" placeholder="Debit">
                                                                    </td>
                                                                <tr>
                                                                    <td></td>

                                                                    <td class="title">
                                                                        <select id="credit_account"
                                                                            name="credit_account[]"
                                                                            class="form-control select2 custom-select mr-0 mb-0 form-control-sm credit_account_{{ $index }}">
                                                                            <option selected="">
                                                                                Please select the
                                                                                Credit Account</option>
                                                                            @foreach ($dropDownData['accounts'] as $key => $value)
                                                                                <option value="{{ $key }}"
                                                                                    {{ (old('credit_account') == $key ? 'selected' : '') || (!empty($jvDetail->credit_account) ? collect($jvDetail->credit_account)->contains($key) : '') ? 'selected' : '' }}>
                                                                                    {{ $value }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <textarea id="description" type="text" name="description[]" placeholder="Please Enter Description"
                                                                            class="form-control form-control-sm mt-3">{{ $jvDetail->description }}</textarea>
                                                                    </td>
                                                                    <td></td>
                                                                    <td class="text-right qty">
                                                                        <input type="number" id="credit"
                                                                            class="form-control form-control-sm credit"
                                                                            value="{{ old('credit', !empty($jvDetail->credit) ? $jvDetail->credit : '') }}"
                                                                            name="credit[]" placeholder="Credit">
                                                                    </td>
                                                                </tr>

                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>

                                            <a href="javascript:void(0);" class="btn btn-dark additem mt-3"
                                                id="add-item">Add
                                                Item</a>

                                        </div>

                                        <div class="col-xl-6 invoice-address-client invoice-detail-total mt-3"
                                            style="float:right">
                                            <div class="invoice-address-client-fields">
                                                <div class="form-group row">
                                                    <label for="gross-amount"
                                                        class="col-sm-4 col-form-label col-form-label-sm ">Tot.
                                                        Debit
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <input type="text" id="gross-amount"
                                                            class="form-control form-control-sm gross-amount "
                                                            name="debit_amount" id="gross-amount"
                                                            value="{{ $jv->debit_amount }}" style="color: black"
                                                            placeholder="Total Debit Amount" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="credit-amount"
                                                        class="col-sm-4 col-form-label col-form-label-sm ">Tot.
                                                        Credit
                                                    </label>
                                                    <div class="col-sm-8">
                                                        <input type="text" id="credit-amount"
                                                            class="form-control form-control-sm credit-amount "
                                                            name="credit_amount" style="color: black"
                                                            value="{{ $jv->credit_amount }}" id="credit-amount"
                                                            placeholder="Total Credit Amount" readonly>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>


                                    <div class="invoice-detail-terms">

                                        <div class="row">
                                            <div class="col-xl-12 ">
                                                <a href="{{ route('jv.list') }}" style="float: right;"
                                                    class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                                <button type="submit" style="float: right"
                                                    class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 mr-5"
                                                    data-bs-container="body" data-bs-placement="right"
                                                    data-bs-content="Tooltip on right">
                                                    @if (!isset($jv))
                                                        Save
                                                    @else
                                                        Update
                                                    @endif
                                                </button>
                                                {{-- <input type="submit" style="float: right"
                                                                        value="{{ 'SaveAsDraft' }}"
                                                                        class="btn btn-primary save-as me-1 mt-5 mb-4 mr-5">
                                                                    <input type="hidden" name="save_type"
                                                                        id="save_type" /> --}}
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

    <script>
        document.getElementById('jvForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const debitAmount = parseFloat(document.getElementById('gross-amount').value) || 0;
            const creditAmount = parseFloat(document.getElementById('credit-amount').value) || 0;

            if (debitAmount !== creditAmount) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Debit Amount and Credit Amount must be equal before submitting the JV Voucher!',
                    confirmButtonText: 'OK'
                });
            } else {
                this.submit();
            }
        });
    </script>

    <script src="{{ asset('js/journalVoucher.js') }}"></script>
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
                '<td class="description"><select id="account_title" name="debit_account[]" class="form-control select2 custom-select mr-0 mb-0 form-control-sm debit_account_' +
                currentIndex +
                '"> <option selected=""> Please select the Debit Account</option> @foreach ($dropDownData['accounts'] as $key => $value) <option value="{{ $key }}" {{ (old('debit_account') == $key ? 'selected' : '') || (!empty($bpv->debit_account) ? collect($bpv->debit_account)->contains($key) : '') ? 'selected' : '' }}> {{ $value }} </option> @endforeach </select></td>' +

                '<td class="text-right qty"> <input id="debit" type="number" name="debit[]" value="{{ old('debit', !empty($jv->debit) ? $jv->debit : '') }}" placeholder="Debit " class="form-control form-control-sm debit"></td>' +

                '<tr>' +
                '<td>' +
                '</td>' +
                '<td class="title"> <select name="credit_account[]" id="bank" class="form-control select2 custom-select mr-0 mb-0 form-control-sm credit_account_' +
                currentIndex +
                '"> <option selected=""> Please select the Credit Account</option> @foreach ($dropDownData['accounts'] as $key => $value) <option value="{{ $key }}" {{ (old('credit_account') == $key ? 'selected' : '') || (!empty($bpv->credit_account) ? collect($bpv->credit_account)->contains($key) : '') ? 'selected' : '' }}> {{ $value }} </option> @endforeach </select> <textarea id="description" type="text" name="description[]" value="{{ old('description', !empty($bpv->description) ? $bpv->description : '') }}" placeholder="Please Enter Description" class="form-control form-control-sm mt-3"></textarea> </td>' +
                '<td>' +
                '</td>' +
                '<td class="text-right qty"> <input id="credit" type="number" name="credit[]" value="{{ old('credit', !empty($jv->credit) ? $jv->credit : '') }}" placeholder="Credit " class="form-control form-control-sm credit"></td>' +
                '</tr>' +
                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);
            deleteItemRow();
            $('.select2').select2();
            $('.debit_account_' + currentIndex).select2();
            $('.credit_account_' + currentIndex).select2();
            $(document).on('click', 'body *', function() {
                $('.debit').on("input", function() {
                    doAmountTotal();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#total-amount').text("");
                    console.log('in do amount total');
                    var totalAmount = 0;
                    $(".debit").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    $('#gross-amount').val(totalAmount.toFixed(2));
                }
            });

            $(document).on('click', 'body *', function() {
                $('.credit').on("input", function() {
                    doAmountTotal();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#total-amount').text("");
                    console.log('in do amount total');
                    var totalAmount = 0;
                    $(".credit").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    $('#credit-amount').val(totalAmount.toFixed(2));
                }
            });

        })

        deleteItemRow();
        selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
        selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
        selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);

        var f2 = flatpickr(document.getElementById('due'), {
            defaultDate: currentDate.setDate(currentDate.getDate() + 5),
        });

        $(document).ready(function() {
            $('.select2').select2();
            $('.debit_account_' + currentIndex).select2();
            $('.credit_account_' + currentIndex).select2();
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

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])

        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"
            integrity="sha512-RtZU3AyMVArmHLiW0suEZ9McadTdegwbgtiQl5Qqo9kunkVg1ofwueXD8/8wv3Af8jkME3DDe3yLfR8HSJfT2g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="{{ asset('js/common.js') }}"></script>


        <script>
            var input = document.getElementById("code");
            input.addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    document.getElementById("party").click();
                }
            });
        </script>

    </x-slot>
</x-base-layout>
