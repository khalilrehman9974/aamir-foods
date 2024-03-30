$('#control-head').on('change', function () {
    var controlCode = $('#control-head :selected').val();
    $("#sub-head").val('');
    $("#account_code").val('');
    let url = config.routes.getSubHeads + '/' + controlCode;
    $.ajax({
        url : url,
        type : 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#sub").empty();
            // $("#selectVersion").append("<option selected disabled> Please select the sub head </option>");
            $.each(response.data,function(i,obj)
            {
                $("#sub-head").empty();
                $("#sub-head").append($("<option />").val("").text("Please select the control head"));
                $.each(response.data, function (key, value) {
                    $("#sub-head").append($("<option />").val(key).text(value));
                });
            });
        },
        complete: function () {
            $('#loading').css('display', 'none');
        },
        error: function (errorThrown) {
            $('#account_code').val('');
            var errors = errorThrown.responseJSON.errors;
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
            })
        }
    })
})

$('#sub-head').on('change', function () {
    var subCode = $('#sub-head :selected').val();
    $("#account_code").val('');
    let url = config.routes.getSubSubHeads + '/' + subCode;
    $.ajax({
        url : url,
        type : 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#sub-sub-head").empty();
            $.each(response.data,function(i,obj)
            {
                $("#sub-sub-head").append($("<option />").val("").text("Please select the sub-sub head"));
                $.each(response.data, function (key, value) {
                    $("#sub-sub-head").append($("<option />").val(key).text(value));
                });
            });
        },
        complete: function () {
            $('#loading').css('display', 'none');
        },
        error: function (errorThrown) {
            $('#account_code').val('');
            var errors = errorThrown.responseJSON.errors;
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
            })
        }
    })
})

$('#sub-sub-head').on('change', function () {
    var subSubCode = $('#sub-sub-head :selected').val();
    $("#account_code").val('');
    let url = config.routes.getDetailAccountCode + '/' + subSubCode;
    $.ajax({
        url : url,
        type : 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#account_code").val(response.account_code);
        },
        complete: function () {
            $('#loading').css('display', 'none');
        },
        error: function (errorThrown) {
            $('#account_code').val('');
            var errors = errorThrown.responseJSON.errors;
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
            })
        }
    })
})
