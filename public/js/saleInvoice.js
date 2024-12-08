

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
        let totalDzns = $(this).closest("tr").find(".totDzn_" + row_id).val();
        let rate = $(this).closest("tr").find(".rate_" + row_id).val();
        if (parseInt(totalDzns) > 0) {
            $(this).closest("tr").find(".amount_" + row_id).val(totalDzns * rate);
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
        console.log('in do amount total');
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
        // $('#net-amount').val(totalAmount.toFixed(2));
    }

    $(".carriage, .discount, .commission").on("focusout", function (){
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        let carriage = $(".carriage").val() ? $(".carriage").val() : 0;
        let discount = $(".discount").val() ? $(".discount").val() : 0;
        let commission = $(".commission").val() ? $(".commission").val() : 0;

        var totalLessAmount = parseInt(carriage) + parseInt(discount) + parseInt(commission);
        $('#net-amount').val(totalLessAmount ? totalAmount.toFixed(2) - totalLessAmount : totalAmount.toFixed(2));
    })



});

$(document).ready(function () {
    console.log("DOM is ready");
    $('.select2').select2();
    console.log("DOM is loaded");
});

$("#dispatch_note").on("keypress", function (event) {
    if ($("#dispatch_note").val() !== "" ) {
        if (event.which == 13) {
            alert(event.which);
            event.preventDefault()
        }
    }
});

$(document).on('click', 'body *', function() {
    $('.amount').on("focusout", function() {
        doAmountTotal();
    });

    $('.delete-item').on("click", function() {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        console.log('in do amount total');
        var totalAmount = 0;
        $(".amount").each(function() {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
    }
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
