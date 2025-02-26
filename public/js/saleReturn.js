

//Runtime calculation


$(document).ready(function () {

    $('.dozen, .qty').on("input", function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
        let dzns = $(this).closest("tr").find(".dozen_" + row_id).val() ? $(".dozen_" + row_id).val() : 0;;
        if (parseInt(quantity) > 0) {
            $(this).closest("tr").find(".totDzn_" + row_id).val(quantity * dzns);
        } else {
            $(this).closest("tr").find(".totDzn_" + row_id).val('');
        }
        // let totalDzn = $(this).closest("tr").find(".totDzn_" + row_id).val();
        // let price = $(this).closest("tr").find(".rate_" + row_id).val();
        // if (parseInt(totalDzn) > 0) {
        //     $(this).closest("tr").find(".amount_" + row_id).val(totalDzn * price);
        // } else {
        //     $(this).closest("tr").find(".amount_" + row_id).val('');
        // }
        doAmountTotal();
    });


    $('.qty').on("input", function () {

        let totalBorayAmount = 0;
        let totalCartonAmount = 0;

        // Loop through all rows to calculate total Boray and Carton quantities
        $(".item-table tbody tr").each(function () {
            let packingType = $(this).find('.packing').val().toLowerCase();
            let quantity = parseFloat($(this).find('.qty').val()) || 0;

            // Check the packing type and sum the quantities
            if (packingType === 'boray') {
                totalBorayAmount += quantity;
            } else if (packingType === 'carton') {
                totalCartonAmount += quantity;
            }
        });

        // Update the totals in the respective fields
        $('#boray-amount').val(totalBorayAmount.toFixed(2));
        $('#carton-amount').val(totalCartonAmount.toFixed(2));
    });

    // Function to update the amount
    let getTableElement = document.querySelector('.item-table');
    let currentIndex = getTableElement.rows.length;

    function updateAmount(row_id) {
        let quantity = $(".totDzn_" + row_id).val();
        let price = $(".rate_" + row_id).val();

        if (parseFloat(quantity) > 0 && parseFloat(price) > 0) {
            $(".amount_" + row_id).val((quantity * price).toFixed(2)); // Format to 2 decimal places
        } else {
            $(".amount_" + row_id).val('');
        }
        doAmountTotal();
        NetAmountTotal();
    }

    // Event listener for dozen input (Calculate totDzn)
    // $(document).on("focusout", ".dozen", function () {
    //     var row_id = $(this).closest("tr").find(".row_id").val();
    //     let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
    //     let dzns = $(this).closest("tr").find(".dozen_" + row_id).val();

    //     if (parseInt(quantity) > 0) {
    //         $(this).closest("tr").find(".totDzn_" + row_id).val(quantity * dzns);
    //     } else {
    //         $(this).closest("tr").find(".totDzn_" + row_id).val('');
    //     }
    //     updateAmount(row_id); // Update amount when dozen changes
    // });

    // Event listener for rate input (when manually changed)
    $(document).on("input", ".rate, .dozen, .qty", function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        updateAmount(row_id);
    });

    // Run on page load to handle controller-filled rate values
    $(".rate").each(function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        updateAmount(row_id); // Ensure amount is calculated on page load
    });

    // Observer to detect when the rate is set automatically by the controller
    $(".rate").each(function () {
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
    //     let rate = $(this).closest("tr").find(".rate_" + row_id).val();
    //     if (parseInt(totalDzns) > 0) {
    //         $(this).closest("tr").find(".amount_" + row_id).val(totalDzns * rate);
    //     } else {
    //         $(this).closest("tr").find(".amount_" + row_id).val('');
    //     }
    //     doAmountTotal();
    // });




    $('.delete-item').on("click", function () {
        doAmountTotal();
        // discountTotal();
        NetAmountTotal();
    });

    $('.discount_amount').on("input", function () {
        NetAmountTotal();
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

    // function discountTotal() {
    //     $('#discount-amount').text("");
    //     var totalDiscount = 0;
    //     $(".discount").each(function () {
    //         if (!isNaN(this.value) && this.value.length != 0) {
    //             totalDiscount += parseFloat(this.value);
    //         }
    //     });
    //     $('#discount-amount').val(totalDiscount.toFixed(2));
    // }

    function NetAmountTotal() {
        var totalAmount = 0;

        let discount = $(".discount_amount").val() ? $(".discount_amount").val() : 0;
        let grossAmount = $("#gross-amount").val() ? $("#gross-amount").val() : 0;
        let commission = $(".commission_amount").val() ? $(".commission_amount").val() : 0;
        var totalLessAmount = parseInt(commission) + parseInt(discount);

        // $('#net-amount').val(totalLessAmount ? totalAmount.toFixed(2) - totalLessAmount : totalAmount.toFixed(2));
        var netAmount = parseFloat(grossAmount) + totalLessAmount;

        // Update the #net-amount field with the calculated net amount
        $('#net-amount').val(netAmount.toFixed(2));

    }



});

$(document).ready(function () {
    $('.select2').select2();
});

$("#dispatch_note").on("keypress", function (event) {
    if ($("#dispatch_note").val() !== "") {
        if (event.which == 13) {
            alert(event.which);
            event.preventDefault()
        }
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
        discountTotal();
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
    $('.discount').on("input", function () {
        discountTotal();
    });


    function discountTotal() {
        $('#discount-amount').text("");
        var totalDiscount = 0;
        $(".discount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalDiscount += parseFloat(this.value);
            }
        });
        $('#discount-amount').val(totalDiscount.toFixed(2));
    }
});
