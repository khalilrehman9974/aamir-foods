

//Runtime calculation


$(document).ready(function () {
    // Function to update the amount
    let getTableElement = document.querySelector('.item-table');
    let currentIndex = getTableElement.rows.length;

    function updateAmount(row_id) {
        let quantity = $(".quantity_" + row_id).val();
        let price = $(".price_" + row_id).val();

        if (parseFloat(quantity) > 0 && parseFloat(price) > 0) {
            $(".amount_" + row_id).val((quantity * price).toFixed(2)); // Format to 2 decimal places
        } else {
            $(".amount_" + row_id).val('');
        }
        doAmountTotal();
    }



    // Event listener for price input (when manually changed)
    $(document).on("input", ".price", function () {
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

    // $(".carriage, .tax").on("input", function () {
    //     var totalAmount = 0;
    //     $(".amount").each(function () {
    //         if (!isNaN(this.value) && this.value.length != 0) {
    //             totalAmount += parseFloat(this.value);
    //         }
    //     });
    //     let carriage = $(".carriage").val() ? $(".carriage").val() : 0;
    //     let tax = $(".tax").val() ? $(".tax").val() : 0;

    //     var totalLessAmount = parseInt(carriage) + parseInt(tax);
    //     $('#net-amount').val(totalLessAmount ? totalAmount.toFixed(2) + totalLessAmount : totalAmount.toFixed(2));
    // })

    $(".carriage, .tax , .price").on("input", function () {
        var totalAmount = 0;

        // Loop through each amount field and sum up values
        $(".amount").each(function () {
            let value = parseFloat($(this).val()) || 0; // Convert to number or default to 0
            totalAmount += value;
        });

        // Convert carriage and tax values to numbers
        let carriage = parseFloat($(".carriage").val()) || 0;
        let tax = parseFloat($(".tax").val()) || 0;

        // Calculate total less amount
        var totalAddAmount = carriage + tax;

        // Set the net amount by summing totalAmount and totalLessAmount
        $('#net-amount').val((totalAmount + totalAddAmount).toFixed(2));
    });



});

