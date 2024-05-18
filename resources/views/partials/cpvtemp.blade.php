<input type="hidden" name="id" id="id" value="{{ @$cpvTemp->id }}" required />
<div class="form-inline">
    <div class="form-group">
        <label for="">
            <h2>CPV ID:{{ @$maxid }}{{@$currid}} </h2>
        </label>

    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4" style="">
            <label for="date">Date:</label>
            <input type="date" class="form-control" name="date" id="date"
                value="{{ old('date', @$cpvTemp->date) }}" style="width: 80%;" placeholder="Enter the date" required>
            @error('date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>


    </div>
</div>
<div class="row ex1">
    <div class="col-sm-8">
        <label for="sup_id">Supplier Name: </label>
        <select class="form-control select2" style="width: 100%;" name="sup_id" id="sup_id" required>
            <option value="">Select</option>
            @foreach ($tblcust_sups as $key => $value)
                <option value="{{ $key }}"
                    {{ (old('sup_id') == $key ? 'selected' : '') || (!empty($cpvTemp->sup_id) ? collect($cpvTemp->sup_id)->contains($key) : '') ? 'selected' : '' }}>
                    {{ $value }}</option>
            @endforeach
        </select>
        @error('sup_id')
            <div class="error-message">{{ $message }}</div>
        @enderror
    </div>


</div>
@foreach ($cpvDetailsTemp as $cpvDetail)
    <tr class="tr_clone validator_0">
        <td><input type="checkbox" name="row_id[]" class="row_id" value="0" hidden></td>

        <td><select class="form-control select2 account_code account_code" style="width: 100%;" name="account_code[]"
                data-uom="" data-item-number="" required>
                <option value="">Select</option>
                @foreach ($acc_codes as $key => $value)
                    <option value="{{ $key }}"
                        {{ (old('account_code') == $key ? 'selected' : '') || (!empty($cpvDetail->account_code) ? collect($cpvDetail->account_code)->contains($key) : '') ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            <div><span class="spinner-border spinner-border-sm" id="account_code-spinner"
                    style="margin-top: -26px; margin-left: 306px; display: none"></span>
            </div>
        </td>
        <td><input type="text" name="remarks[]" class="form-control remarks " style="width: 100%;"
                value="{{ $cpvDetail->remarks }}" style="width: 150px" id="remarks[]"></td>

        <td><input type="text" name="credit_amount[]" class="form-control credit_amount credit_amount_0"
                value="{{ $cpvDetail->credit_amount }}" onkeypress="return isNumber(event)" style="width: 190px">
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
