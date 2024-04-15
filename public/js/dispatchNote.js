$(document).on('click', 'body *', function() {
    $('.qty').on("focusout", function() {
        doAmountTotal();
    });

    $('.delete-item').on("click", function() {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        console.log('in do amount total');
        var totalAmount = 0;
        $(".qty").each(function() {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#quantity-amount').val(totalAmount.toFixed(2));
    }
});

$(document).on('click', 'body *', function() {
    $('.unit').on("focusout", function() {
        doAmountTotal();
    });

    $('.delete-item').on("click", function() {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        console.log('in do amount total');
        var totalAmount = 0;
        $(".unit").each(function() {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#unit-amount').val(totalAmount.toFixed(2));
    }
});

$(document).ready(function () {
    console.log("DOM is ready");
    $('.select2').select2();
    console.log("DOM is loaded");
});
