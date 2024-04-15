FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginImageExifOrientation,
    FilePondPluginFileValidateSize,
    // FilePondPluginImageEdit
);

FilePond.create(document.querySelector('.file-upload-multiple'));

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
    $("#code").val('');
    let url = config.routes.getDetailAccountCode + '/' + subCode;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#code").val(response.code);
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
