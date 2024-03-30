$('#control-head').on('change', function () {
    var controlCode = $('#control-head :selected').val();
    $("#account_code").val('');
    let url = config.routes.getSubHeadCode + '/' + controlCode;
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
