$('.saleMan').on('change', function () {
    console.log("Check sale man");
    var name = $('.saleMan :selected').text();
    // console.log(name);
    let url = config.routes.getSaleManDetail + '/' + name;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $(".sector").val(response.name.name);
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
});
