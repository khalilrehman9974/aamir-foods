$('#control-head').on('change', function () {
    var controlCode = $('#control-head :selected').val();
    $("#sub-head").val('');
    $("#account_code").val('');
    let url = config.routes.getSubHeads + '/' + controlCode;
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#sub").empty();
            // $("#selectVersion").append("<option selected disabled> Please select the sub head </option>");
            $.each(response.data, function (i, obj) {
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
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#sub-sub-head").empty();
            $.each(response.data, function (i, obj) {
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
        url: url,
        type: 'GET',
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

$(document).ready(function () {
    $('.select2').select2();
});

// $('#saleMan').on('change', function () {
//     var name = $('#saleMan :selected').text();
//     // console.log(name);
//     let url = config.routes.getSaleManAreaDetail + '/' + name;
//     $.ajax({
//         url: url,
//         type: 'GET',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (response) {
//             $("#area").val(response.name.name);
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

$('#main-head').on('change', function () {
    var mainCode = $('#main-head :selected').val();
    $("#control-head").val('');
    $("#account_code").val('');
    let url = config.routes.getControlHeads + '/' + mainCode;
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#control-head").html('');
            // $("#control-head").append('Please select the control head');
            $("#selectVersion").append("<option selected disabled> Please select the control head </option>");
            // var div_data="<option value=''>'Please select the control head'</option>";
            $.each(response.data, function (i, obj) {

                $("#control-head").empty();
                $("#control-head").append($("<option />").val("").text("Please select the control head"));
                $.each(response.data, function (key, value) {
                    $("#control-head").append($("<option />").val(key).text(value));
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

$('#code').on('keypress', function (event) {
    if (event.key === "Enter") {
        var code = $('#code :selected').val();
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

$('#product').on('change', function () {
    var name = $('#product :selected').text();
    // console.log(name);
    let url = config.routes.getProductPrice + '/' + name;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#price").val(response.price);
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




