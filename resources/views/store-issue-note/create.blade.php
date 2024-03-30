<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ $pageTitle }}
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
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
      <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->

    <x-slot:scrollspyConfig>
        data-bs-spy="scroll" data-bs-target="#navSection" data-bs-offset="100"
    </x-slot>

    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Store Issue Note</li>
            </ol>
        </nav>
    </div>


    <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                            <h4>Store Issue Note</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4">
                    <div class="widget-content widget-content-area">
                        <form method="POST"
                            action="{{ !empty($issue_noteid) ? route('store-issue-note.update') : route('store-issue-note.save') }}"
                            class="row g-3 needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="id" id="id"
                                value="{{ isset($issue_note->id) ? $issue_note->id : '' }}" />
                            <div class="form-group">
                                <div class="col">
                                    <label for="inputState" class="form-label">Product</label>
                                    <select id="product_id" name="product_id" class="form-select">
                                        <option selected="">Please select the
                                            Distributer</option>
                                        @foreach ($dropDownData['products'] as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ (old('product_id') == $key ? 'selected' : '') || (!empty($issue_note->product_id) ? collect($issue_note->product_id)->contains($key) : '') ? 'selected' : '' }}>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">

                                        <label for="issued_to">Issued To</label>
                                        <input type="text" value="{{ old('issued_to', @$issue_note->issued_to) }}"
                                            name="issued_to" class="form-control" id="issued-to"
                                            placeholder="Issued to..." required>
                                        @error('issued_to')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>

                                    <div class="form-group col-md-6 ">
                                        <label for="issued_by">Issued By</label>
                                        <input type="text" name="issued_by"
                                            value="{{ old('issued_by', @$issue_note->issued_by) }}"
                                            class="form-control" id="issued-by" placeholder="Issued By" required>
                                        @error('issued_by')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="form-group mb-4">
                                    <label for="exampleFormControlTextarea1">Remarks</label>
                                    <textarea class="form-control" value="{{ old('remarks', @$issue_note->remarks) }}" name="remarks" id="remarks"
                                        rows="3"></textarea>
                                </div>
                                <div class="invoice-detail-items">

                                    <div class="table-responsive">
                                        <table class="table item-table">
                                            <thead>
                                                <tr>
                                                    <th class="">
                                                    </th>
                                                    <th>Date</th>
                                                    <th class="">
                                                        Description</th>
                                                    <th class="">
                                                        Quantity</th>

                                                </tr>
                                                <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="delete-item-row">
                                                        <ul class="table-controls">
                                                            <li><a href="javascript:void(0);" class="delete-item"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    title="" data-original-title="Delete"><svg
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        width="24" height="24"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="feather feather-x-circle">
                                                                        <circle cx="12" cy="12" r="10">
                                                                        </circle>
                                                                        <line x1="15" y1="9"
                                                                            x2="9" y2="15">
                                                                        </line>
                                                                        <line x1="9" y1="9"
                                                                            x2="15" y2="15">
                                                                        </line>
                                                                    </svg></a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                    <td class="date">
                                                        <input id="date" name="date[]"
                                                            class="form-control form-control-sm date flatpickr flatpickr-input"
                                                            type="text"
                                                            value="{{ old('date', !empty($issue_note->date) ? $issue_note->date : '') }}"
                                                            placeholder="Select Date.." readonly="readonly">
                                                    </td>
                                                    <td class="">
                                                        <textarea id="unit" type="text" name="description[]"
                                                            value="{{ old('description', !empty($issue_note->description) ? $issue_note->description : '') }}"
                                                            placeholder="Please Enter Description " class="form-control form-control-sm"></textarea>
                                                    </td>
                                                    <td class="">
                                                        <input id="quantity" type="text" name="quantity[]"
                                                            value="{{ old('quantity', !empty($issue_note->quantity) ? $issue_note->quantity : '') }}"
                                                            placeholder="Please Enter Quantity "
                                                            class="form-control form-control-sm">
                                                    </td>


                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <a class="btn btn-dark additem">Add
                                        Item</a>
                                </div>
                                <a href="{{ route('store-issue-note.list') }}" style="float: right;"
                                    class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">Cancel</a>
                                @if ((!empty($permission) && $permission->insert_access == 1) || Auth::user()->is_admin == 1)
                                    <button type="submit" style="float: right"
                                        class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 "
                                        data-bs-container="body" data-bs-placement="right"
                                        data-bs-content="Tooltip on right">
                                        @if (!isset($issue_noteid))
                                            Save
                                        @else
                                            Update
                                        @endif
                                    </button>
                                @endif
                            </div>
                        </form>
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
                '<td class="date"><input id="date" name="date[]" class="form-control date_'+currentIndex+' flatpickr flatpickr-input" type="text" value="{{ old('date', !empty($issue_note->date) ? $issue_note->date : '') }}" placeholder="Select Date.." readonly="readonly"> ' +
                '<td class="description" >' +
                '<textarea type="text" name="description[]" class="form-control  form-control-sm" placeholder="Please Enter Description "></textarea>' +
                ' </td>' +
                '<td class=""><input type="text" name="quantity[]" class="form-control  form-control-sm" placeholder="Please Enter Quantity "></td>' +
                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);

            $( ".date_"+currentIndex ).flatpickr({
                dateFormat:"d-m-Y",
                minDate: "today",
                time_24hr: true,
            });
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
    </Script>


    <x-slot:footerFiles>
        <script src="{{ asset('plugins/filepond/FilePondPluginFileValidateType.min.js') }}"></script>
        <script src="{{ asset('plugins/filepond/filepondPluginFileValidateSize.min.js') }}"></script>

        <script type="module" src="{{ asset('plugins/flatpickr/flatpickr.js') }}"></script>
        <script type="module" src="{{ asset('plugins/flatpickr/custom-flatpickr.js') }}"></script>

        <script src="{{ asset('plugins/global/vendors.min.js') }}"></script>
        @vite(['resources/assets/js/elements/custom-search.js'])

    </x-slot>
</x-base-layout>
