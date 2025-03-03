//Runtime calculation

$(document).ready(function () {


    // Function to update the amount
    function updateAmount(row_id) {
        let quantity = $(".qty_" + row_id).val();
        let price = $(".price_" + row_id).val();

        if (parseFloat(quantity) > 0 && parseFloat(price) > 0) {
            $(".amount_" + row_id).val((quantity * price).toFixed(2)); // Format to 2 decimal places
        } else {
            $(".amount_" + row_id).val('');
        }
        doAmountTotal();
        netAmountTotal();
        quantityTotal();
    }



    // Event listener for price input (when manually changed)
    $(document).on("input", ".price, .carriage, .tax", function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        updateAmount(row_id);
    });

    // Run on page load to handle controller-filled price values
    $(".price").each(function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        updateAmount(row_id); // Ensure amount is calculated on page load
    });

    // Observer to detect when the price is set automatically by the controller
    $(".price").each(function () {
        var targetNode = this;
        var observer = new MutationObserver(function (mutationsList) {
            mutationsList.forEach(function (mutation) {
                if (mutation.type === "attributes" && mutation.attributeName === "value") {
                    var row_id = $(targetNode).closest("tr").find(".row_id").val();
                    updateAmount(row_id);
                }
            });
        });

        observer.observe(targetNode, { attributes: true, attributeFilter: ["value"] });
    });

    // $('.rate').on("focusout", function () {

    //     var row_id = $(this).closest("tr").find(".row_id").val();
    //     let totalDzns = $(this).closest("tr").find(".totDzn_" + row_id).val();
    //     let rate = $(this).closest("tr").find(".price_" + row_id).val();
    //     if (parseInt(totalDzns) > 0) {
    //         $(this).closest("tr").find(".amount_" + row_id).val(totalDzns * rate);
    //     } else {
    //         $(this).closest("tr").find(".amount_" + row_id).val('');
    //     }
    //     doAmountTotal();
    // });




    $('.delete-item').on("click", function () {
        doAmountTotal();
        netAmountTotal();
        quantityTotal();
    });

    function doAmountTotal() {
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
        // $('#net-amount').val(totalAmount.toFixed(2));
    }

    function quantityTotal() {

        var totalQuantity = 0;
        $(".qty").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalQuantity += parseFloat(this.value);
            }
        });
        $('#total_quantity').val(totalQuantity.toFixed(2));
        // $('#net-amount').val(totalAmount.toFixed(2));
    }

    // $(".carriage, .tax , .price").on("input", function () {
    function netAmountTotal() {

        // Convert carriage and tax values to numbers
        let carriage = parseFloat($(".carriage").val()) || 0;
        let tax = parseFloat($(".tax").val()) || 0;
        let totalAmount = parseFloat($(".gross-amount").val()) || 0;
        // Calculate total less amount
        var totalAddAmount = carriage + tax;

        // Set the net amount by summing totalAmount and totalLessAmount
        $('#net-amount').val((totalAmount - totalAddAmount).toFixed(2));
    }



});


$(document).on('click', 'body *', function() {
    $('.bags, .measurementType').on("input", function() {
        var row_id = $(this).closest("tr").find(".row_id").val();
        let bagsQuantity = parseInt($(this).closest("tr").find(".bags_" + row_id).val(), 10) || 0;
        let measurementQty = parseInt($(this).closest("tr").find(".measurementType_" + row_id)
            .val(), 10) || 0;
        // console.log(row_id + ", " + quantity + ", " + price);
        if (parseInt(measurementQty) > 0) {
            $(this).closest("tr").find(".qty_" + row_id).val(bagsQuantity * measurementQty);
        } else {
            $(this).closest("tr").find(".qty_" + row_id).val('');
        }

        let quantity = $(".qty_" + row_id).val();
        let price = $(".price_" + row_id).val();

        if (parseFloat(quantity) > 0 && parseFloat(price) > 0) {
            $(".amount_" + row_id).val((quantity * price).toFixed(2)); // Format to 2 decimal places
        } else {
            $(".amount_" + row_id).val('');
        }
        doAmountTotal();
        netAmountTotal();
        quantityTotal();
    });




    $('.delete-item').on("click", function() {
        doAmountTotal();
        netAmountTotal();
        quantityTotal();
    });

    function doAmountTotal() {
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
    }

    function quantityTotal() {

        var totalQuantity = 0;
        $(".qty").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalQuantity += parseFloat(this.value);
            }
        });
        $('#total_quantity').val(totalQuantity.toFixed(2));
        // $('#net-amount').val(totalAmount.toFixed(2));
    }

    // $(".carriage, .tax , .price").on("input", function () {
    function netAmountTotal() {

        // Convert carriage and tax values to numbers
        let carriage = parseFloat($(".carriage").val()) || 0;
        let tax = parseFloat($(".tax").val()) || 0;
        let totalAmount = parseFloat($(".gross-amount").val()) || 0;
        // Calculate total less amount
        var totalAddAmount = carriage + tax;

        // Set the net amount by summing totalAmount and totalLessAmount
        $('#net-amount').val((totalAmount - totalAddAmount).toFixed(2));
    }





});

