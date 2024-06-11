<input type="hidden" name="id" id="id" value="{{ isset($bpv->id) ? $bpv->id : '' }}" />
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
                        <input type="text" class="form-control form-control-sm" name="date" id="date"
                        data-date-format="d-m-Y"
                        {{-- value="{{ empty($bpvTemp->date) ? null : \Illuminate\Support\Carbon::parse($bpvTemp->date)->format('d-m-Y') }}" --}}
                        value="{{ old('date', !empty($bpvTemp->date) ? $bpvTemp->date : '') }}"
                        placeholder="Select The Date">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@foreach ($bpvDetailsTemp as $bpvDetail)
    <tr class="tr_clone validator_0">
        <td class="delete-item-row">
            <ul class="table-controls">
                <li>
                    <a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top"
                        title="" data-original-title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-x-circle">
                            <circle cx="12" cy="12" r="10">
                            </circle>
                            <line x1="15" y1="9" x2="9" y2="15">
                            </line>
                            <line x1="9" y1="9" x2="15" y2="15">
                            </line>
                        </svg>
                    </a>
                </li>
            </ul>
        </td>
        <td hidden>
            <input type="text" name="row_id[]" class="row_id" value="0" hidden>
        </td>

        <td class="code">
            <input type="text" id="code" class="form-control form-control-sm" {{-- value="{{ @$bpv ? '' : $detailAccount  }} {{ old('code', !empty($bpv->code) ? $bpv->code : '') }}" --}}
                placeholder="Code">
        </td>

        <td class="description">
            <select id="party" name="account_id[]"
             class="form-control select2 custom-select mr-0 mb-0 form-control-sm">

                <option selected="">
                    Please select the
                    Party</option>
                @foreach ($dropDownData['accounts'] as $key => $value)
                    <option value="{{ $key }}"
                        {{ (old('account_id') == $key ? 'selected' : '') || (!empty($bpvDetail->account_id) ? collect($bpvDetail->account_id)->contains($key) : '') ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            <textarea id="description" type="text" name="description[]"
                value="{{ old('description', !empty($bpvDetail->description) ? $bpvDetail->description : '') }}"
                placeholder="Please Enter Description" class="form-control form-control-sm mt-3">{{@$bpvDetail->description}}</textarea>
        </td>
        <td class="title">
            <select id="account_title" name="bank_id[]"
                class="form-control select2 custom-select mr-0 mb-0 form-control-sm">
                <option selected="">
                    Please select the
                    Party</option>
                @foreach ($dropDownData['accounts'] as $key => $value)
                    <option value="{{ $key }}"
                        {{ (old('bank_id') == $key ? 'selected' : '') || (!empty($bpvDetail->bank_id) ? collect($bpvDetail->bank_id)->contains($key) : '') ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            <input type="text" id="amount" class="form-control form-control-sm mt-4 amount"
                value="{{ old('amount', !empty($bpvDetail->amount) ? $bpvDetail->amount : '') }}" name="amount[]"
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
@endforeach
