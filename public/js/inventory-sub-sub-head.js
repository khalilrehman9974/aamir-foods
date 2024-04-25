$('#main-head').on('change', function () {
    var mainCode = $('#main-head :selected').val();
    $("#sub-head").empty();
    $("#code").val('');
    let url = config.routes.getSubHeads + '/' + mainCode;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#sub-head").empty();
            $("#sub-head").append($("<option />").val("").text("Please select the sub head"));
            $.each(response.data, function (key, value) {
                $("#sub-head").append($("<option />").val(key).text(value));
            });
        },
        complete: function () {
            $('#loading').css('display', 'none');
        },
        error: function (errorThrown) {
            $('#code').val('');
            $("#sub-head").empty();
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
    let url = config.routes.getsubSubHeadCode + '/' + subCode;
    // let url = "{{ url('co-inv-sub-head/get-sub-head-account') }}" + '/' + subCode;
    $.ajax({
        url : url,
        type : 'GET',
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
