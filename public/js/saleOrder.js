$('#party').on('change', function () {
    var name = $('#party :selected').text();
    // console.log("here");
    let url = config.routes.getPartySaleManDetail + '/' + name;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#saleMan").val(response.saleMan_id);
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
$('#party').on('change', function () {
    var name = $('#party :selected').text();
    // console.log(name);
    let url = config.routes.getPartySectorDetail + '/' + name;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#sector").val(response.sector);
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


$('#party').on('change', function () {
    var name = $('#party :selected').text();
    // console.log(name);
    let url = config.routes.getPartyAreaDetail + '/' + name;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#area").val(response.area);
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



// $('#product').on('change', function () {
//     var name = $('#product :selected').text();
//     // console.log(name);
//     let url = config.routes.getProductPackingTypeDetail + '/' + name;
//     $.ajax({
//         url: url,
//         type: 'GET',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (response) {
//             $("#packing").val(response.packing_type_id);
//         },
//         complete: function () {
//             $('#loading').css('display', 'none');
//         },
//         error: function (errorThrown) {
//             $('').val('');
//             var errors = errorThrown.responseJSON.errors;
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Something went wrong',
//             })
//         }
//     })
// })


