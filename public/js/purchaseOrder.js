$(document).ready(function () {


    $(".product").on('change', function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        console.log(row_id);
        var name = this.value;
        let url = config.routes.getProductSizeDetail + '/' + name;
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $(".size_" + row_id).val(response.size);
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


    $(".product").on('change', function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        var name = this.value;
        let url = config.routes.getProductPackingTypeDetail + '/' + name;
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $(".packing_" + row_id).val(response.name.name);
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



    $(".product").on('change', function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        var name = this.value;
        let url = config.routes.getProductMeasurementTypeDetail + '/' + name;
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                $(".measurement_" + row_id).val(response.name.name);
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
});

$(document).ready(function () {
    $('.rate').on("input", function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
        let price = $(this).closest("tr").find(".rate_" + row_id).val();
        // console.log(row_id + ", " + quantity + ", " + price);
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
        // $('#net-amount').val(totalAmount.toFixed(2));
    }

    $("#gross-amount, #tax, #shipping, #otherAmount").on("input", function () {
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        let tax = $("#tax").val() ? $("#tax").val() : 0;
        let shipping = $("#shipping").val() ? $("#shipping").val() : 0;
        let otherAmount = $("#otherAmount").val() ? $("#otherAmount").val() : 0;

        var totalLessAmount = parseInt(tax) + parseInt(shipping) + parseInt(otherAmount);

        $('#net-amount').val((totalAmount + (totalLessAmount || 0)).toFixed(2));
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

