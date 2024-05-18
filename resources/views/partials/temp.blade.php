<input type="hidden" name="id" id="id" value="{{ @$brvTemp->id }}" required />
<div class="form-inline">
    <div class="form-group">
        <label for="">
            <h2>BRV ID:{{ @$maxid }} {{@$currid}}</h2>
        </label>

    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4" style="">
            <label for="br_date">Date:</label>
            <input type="date" class="form-control" name="br_date" id="br_date"
                value="{{ old('br_date', @$brvTemp->br_date) }}" style="width: 80%;" placeholder="Enter the date" required>
            @error('br_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-sm-4" style="">
            <label for="">Bank:</label>
            <select class="form-control select2" style="width: 80%;" name="bank_id" id="bank_id" required>
                <option value="">Select</option>
                @foreach ($banks as $key => $value)
                    <option value="{{ $key }}"
                        {{ (old('bank_id') == $key ? 'selected' : '') || (!empty($brvTemp->bank_id) ? collect($brvTemp->bank_id)->contains($key) : '') ? 'selected' : '' }}>
                        {{ $value }}</option>
                @endforeach
            </select>
            @error('bank_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-4" style="">
            <label for="">Type:</label>
            <select name="type" id="type" class="form-control" value="{{ old('type', @$brvTemp->type) }}" required>

                <option value="Daily">Daily</option>
                <option value="Weekly">Weekly</option>
                <option value="Monthly">Monthly</option>
            </select>
        </div>

    </div>
</div>
<div class="row ex1">
    <div class="col-sm-8">
        <label for="cust_id">Customer Name: </label>
        <select class="form-control select2" style="width: 100%;" name="cust_id" id="cust_id" required>
            <option value="">Select</option>
            @foreach ($tblcust_sups as $key => $value)
                <option value="{{ $key }}"
                    {{ (old('cust_id') == $key ? 'selected' : '') || (!empty($brvTemp->cust_id) ? collect($brvTemp->cust_id)->contains($key) : '') ? 'selected' : '' }}>
                    {{ $value }}</option>
            @endforeach
        </select>
        @error('cust_id')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-sm-4">
        <label for=""> Reference No.</label>
        <input type="text" class="form-control" name="ref_no" id="ref_no"
            value="{{ old('ref_no', @$brvTemp->ref_no) }}" style="width: 100%;" placeholder="Enter Reference No." required>
        @error('ref_no')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

</div>
@foreach ($brvDetailsTemp as $brvDetail)
    <tr class="tr_clone validator_0">
        <td><input type="checkbox" name="row_id[]" class="row_id" value="0" hidden></td>

        <td><select class="form-control select2 account_code account_code" style="width: 100%;" name="account_code[]"
                data-uom="" data-item-number="" required>
                <option value="">Select</option>
                @foreach ($acc_codes as $key => $value)
                    <option value="{{ $key }}"
                        {{ (old('account_code') == $key ? 'selected' : '') || (!empty($brvDetail->account_code) ? collect($brvDetail->account_code)->contains($key) : '') ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            <div><span class="spinner-border spinner-border-sm" id="account_code-spinner"
                    style="margin-top: -26px; margin-left: 306px; display: none"></span>
            </div>
        </td>
        <td><input type="text" name="remarks[]" class="form-control remarks " style="width: 100%;"
                value="{{ $brvDetail->remarks }}" style="width: 150px" id="remarks[]"></td>

        <td><input type="text" name="debit_amount[]" class="form-control debit_amount debit_amount_0"
                value="{{ $brvDetail->debit_amount }}" onkeypress="return isNumber(event)" style="width: 190px">
        </td>
        <td class="project-actions float-right" style="width: 200px">
            <button class="btn btn-primary btn-sm btn-duplicate" title="Create">
                <i class="fas fa-plus"></i>
            </button>
            <button class="btn btn-primary btn-sm delete-row delete_row_0" title="Delete Row">
                <i class="fas fa-delete"></i>
            </button>
        </td>
    </tr>
@endforeach
