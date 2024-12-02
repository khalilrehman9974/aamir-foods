$('#party').on('change', function () {
    var name = $('#party :selected').text();
    let url = config.routes.getPartySaleManDetail + '/' + name;
    $.ajax({
        url: url,
        type: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $("#saleMan").val(response.name.name);

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
});


$('#party').on('change', function () {
    var name = $('#party :selected').text();
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
});


//Runtime calculation
$(document).on('click', 'body *', function () {
    $('.dozen').on("focusout", function () {

        var row_id = $(this).closest("tr").find(".row_id").val();
        let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
        let dzns = $(this).closest("tr").find(".dozen_" + row_id).val();
        if (parseInt(quantity) > 0) {
            $(this).closest("tr").find(".totDzn_" + row_id).val(quantity * dzns);
        } else {
            $(this).closest("tr").find(".totDzn_" + row_id).val('');
        }
    });
    $('.rate').on("focusout", function () {

        var row_id = $(this).closest("tr").find(".row_id").val();
        let quantity = $(this).closest("tr").find(".totDzn_" + row_id).val();
        let price = $(this).closest("tr").find(".rate_" + row_id).val();
        if (parseInt(quantity) > 0) {
            $(this).closest("tr").find(".amount_" + row_id).val(quantity * price);
        } else {
            $(this).closest("tr").find(".amount_" + row_id).val('');
        }
        doAmountTotal();
    });



    $('.delete-item').on("click", function () {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
        $('#net-amount').val(totalAmount.toFixed(2));
    }

    $("#freight, #scheme, #commission").on("focusout", function () {
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        let freight = $("#freight").val() ? $("#freight").val() : 0;
        let scheme = $("#scheme").val() ? $("#scheme").val() : 0;
        let commission = $("#commission").val() ? $("#commission").val() : 0;

        var totalLessAmount = parseInt(freight) + parseInt(scheme) + parseInt(
            commission);
        $('#net-amount').val(totalLessAmount ? totalAmount.toFixed(2) -
            totalLessAmount : totalAmount.toFixed(2));
    })
});

$(document).on('click', 'body *', function () {
    $('.amount').on("focusout", function () {
        doAmountTotal();
    });

    $('.delete-item').on("click", function () {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
    }
});

$(document).on('click', 'body *', function () {
    $('.amount').on("focusout", function () {
        doAmountTotal();
    });

    $('.delete-item').on("click", function () {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
    }
});

// $(document).on('click', 'body *', function () {
//     $('.qty').on("focusout", function () {
//         doAmountTotal();
//     });

//     $('.delete-item').on("click", function () {
//         doAmountTotal();
//     });

//     function doAmountTotal() {
//         $('#boray-amount').text("");
//         $('#carton-amount').text("");
//         // console.log('in do amount total');
//         var totalAmount = 0;
//         $(".qty").each(function () {
//             if (!isNaN(this.value) && this.value.length != 0) {
//                 totalAmount += parseFloat(this.value);
//             }
//         });

//         // if (document.getElementById('packing').value == "Carton") {
//         //     $('#carton-amount').val(totalAmount.toFixed(2));
//         // }

//         // if (document.getElementById('packing').value == "Boray") {
//         //     $('#boray-amount').val(totalAmount.toFixed(2));
//         // }
//         if (document.getElementById('packing').value == "Carton") {
//             $('#carton-amount').val(totalAmount.toFixed(2));
//         } else {
//             $('#boray-amount').val(totalAmount.toFixed(2));
//         }
//         // $('#quantity-amount').val(totalAmount.toFixed(2));
//     }
// });
