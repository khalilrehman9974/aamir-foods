$(document).on('click', 'body *', function() {
    $('.debit').on("focusout", function() {
        doAmountTotal();
    });

    $('.delete-item').on("click", function() {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        console.log('in do amount total');
        var totalAmount = 0;
        $(".debit").each(function() {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
    }
});

$(document).on('click', 'body *', function() {
    $('.credit').on("focusout", function() {
        doAmountTotal();
    });

    $('.delete-item').on("click", function() {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        console.log('in do amount total');
        var totalAmount = 0;
        $(".credit").each(function() {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#credit-amount').val(totalAmount.toFixed(2));
    }
});
