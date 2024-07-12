$('#code').on('keypress', function (event) {
    if (event.key === "Enter") {
        var code = $('#code').val();
        // console.log(code);
        let url = config.routes.getParty + '/' + code;
        // let url = "{{ url('co-inv-party/get-party-account') }}" + '/' + subCode;
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $("#party").val(response.account_name);
            },
            complete: function () {
                $('#loading').css('display', 'none');
            },
            error: function (errorThrown) {
                $('#party').val('');
                var errors = errorThrown.responseJSON.errors;
                Swal.fire({
                    icon: 'error',
                    title: 'Something went wrong',
                })
            }
        })
    }
})



$('#party').on('change', function () {
    var name = $('#party :selected').text();
    // console.log(name);
    let url = config.routes.getPartyCode + '/' + name;
    // let url = "{{ url('co-inv-party/get-party-account') }}" + '/' + subCode;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#code").val(response.account_code);
        },
        complete: function () {
            $('#loading').css('display', 'none');
        },
        error: function (errorThrown) {
            $('#code').val('');
            var errors = errorThrown.responseJSON.errors;
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
            })
        }
    })
})


$('#TempData').on('keydown', function (event) {
    // if (event.key === "Enter") {
    var id = $('#TempData').val();
    // console.log(id);
    let url = config.routes.getDetailData + '/' + id;
    // let url = "{{ url('co-inv-party/get-party-account') }}" + '/' + subCode;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#detail").val(response.voucher_master_id);
            $.each(response, function(key , value){

                '<tr>' +
                    '<td class="delete-item-row">' +
                    '<ul class="table-controls">' +
                    '<li><a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>' +
                    '</ul>' +
                    '</td>' +
                    '<td hidden><input type="text" name="row_id[]" class="row_id" value="' + currentIndex +
                    '" hidden></td>' +
                    '<td class="code"><input type="text" id="code" class="form-control form-control-sm code" placeholder = "Code" ></td> ' +
                    '<td class="description"><select id="account_title" name="account_id[]" class="form-control select2 custom-select mr-0 mb-0 form-control-sm"> <option selected=""> Please select the Party</option> @foreach ($dropDownData["accounts"] as '+key+' => $value) <option value="{{ $key }}" {{ (old('account_id') == $key ? 'selected' : '') || (!empty($bpv->account_id) ? collect($bpv->account_id)->contains($key) : '') ? 'selected' : '' }}> {{ $value }} </option> @endforeach </select><textarea id="description" type="text" name="description[]" value="{{ old('description', !empty($bpv->description) ? $bpv->description : '') }}" placeholder="Please Enter Description" class="form-control form-control-sm mt-3"></textarea> </td>' +
                        '<td class="title"> <select name="bank_id[]" id="account_title" class="form-control select2 custom-select mr-0 mb-0 form-control-sm"> <option selected=""> Please select the Bank</option> @foreach ($dropDownData["accounts"] as $key => $value) <option value="{{ $key }}" {{ (old('bank_id') == $key ? 'selected' : '') || (!empty($bpv->bank_id) ? collect($bpv->bank_id)->contains($key) : '') ? 'selected' : '' }}> {{ $value }} </option> @endforeach </select> <input type="text" id="amount" class="form-control form-control-sm mt-4 amount" value="{{ old('debit', !empty($bpv->debit) ? $bpv->debit : '') }}" name="amount[]" placeholder="Amount"></td>' +
                            // '<td class="text-right qty"> <input id="amount" type="text" name="amount[]" value="{{ old('debit', !empty($bpv->debit) ? $bpv->debit : '') }}" placeholder="Amount " class="form-control form-control-sm amount"></td>' +
                            '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
                            '</div>' +
                            '</div>' +
                            '</td>' +
                            '</tr>';
            })

        },
        complete: function () {
            $('#loading').css('display', 'none');
        },
        error: function (errorThrown) {
            $('#detail').val('');
            var errors = errorThrown.responseJSON.errors;
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
            })
        }
    })
    // }
})



$('#detail').on('change', function () {
    var name = $('#detail :selected').text();
    // console.log(name);
    // let url = config.routes.getPartyCode + '/' + name;
    // let url = "{{ url('co-inv-party/get-party-account') }}" + '/' + subCode;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#TempData").val(response.account_code);
        },
        complete: function () {
            $('#loading').css('display', 'none');
        },
        error: function (errorThrown) {
            $('').val('');
            var errors = errorThrown.responseJSON.errors;
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
            })
        }
    })
})


$(document).on('click', 'body *', function () {
    $('.amount').on("focusout", function () {
        doAmountTotal();
    });

    $('.delete-item').on("click", function () {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        console.log('in do amount total');
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
    }
});
