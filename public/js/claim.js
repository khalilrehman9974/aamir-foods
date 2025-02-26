$(document).ready(function() {
    $('.select2').select2();
});

$(document).ready(function() {
    $('.select2').select2();

    $('.party').on('change', function() {
        var name = this.value;
        let url = config.routes.getPartySaleManDetail;

        $('.saleMan').html('');
        $.ajax({
            url: url,
            type: "GET",
            data: {
                party_id: name,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {

                $.each(result.saleManName, function(key, data) {
                    $('.saleMan').append(
                        '<option value="' +
                        data.id +
                        '"selected>' + data.name +
                        '</option>');
                });

            }
        });
    });
    $('.party').on('change', function() {
        var idParty = this.value;
        $(".sector-dropdown").html('');
        $(".area-dropdown").html('');
        $.ajax({
            url: config.routes.getPartySectorDetail,
            type: "GET",
            data: {
                party_id: idParty,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                $('.sector-dropdown').html(
                    '<option value="">-- Select Sector --</option>'
                );

                $.each(result.sectors, function(key, data) {
                    $('.sector-dropdown').append(
                        '<option value="' +
                        data.id +
                        '">' + data.name +
                        '</option>');
                });

            }
        });

    });
    $('.party').on('change', function() {
        var idParty = this.value;
        $(".sector-dropdown").html('');
        $(".area-dropdown").html('');
        $(".delivered-to-dropdown").html('');
        $.ajax({
            url: config.routes.getDeliveredToParty,
            type: "GET",
            data: {
                party_id: idParty,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                $('.delivered-to-dropdown').html(
                    '<option value="">-- Select Delivered To Party --</option>'
                );

                $.each(result.parties, function(key, data) {
                    $('.delivered-to-dropdown').append(
                        '<option value="' +
                        data.id +
                        '">' + data.party_name +
                        '</option>');
                });

            }
        });

    });

    $('.sector-dropdown').on('change', function() {
        var idSector = this.value;
        var idParty = $('.party').val();
        $(".area-dropdown").html('');
        $.ajax({
            url: config.routes.getPartyAreaDetail,
            type: "GET",
            data: {
                party_id: idParty,
                sector_id: idSector,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(result) {
                $('.area-dropdown').html(
                    '<option value="">-- Select Area --</option>'
                );

                $.each(result.areas, function(key, data) {
                    $('.area-dropdown').append(
                        '<option value="' +
                        data.id +
                        '">' + data.name +
                        '</option>');
                });

            }
        });
    });

});


$(document).ready(function() {
    $(".product_" + currentIndex).on('change', function() {
        var row_id = $(this).closest("tr").find(".row_id").val();
        var productSelected = '.product_' + row_id;
        var name = $(productSelected + ' :selected').text();
        let url = config.routes.getProductMeasurementTypeDetail + '/' + name;
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $(".measurement_" + row_id).val(response.name.name);
            },
            complete: function() {
                $('#loading').css('display', 'none');
            },
            error: function(errorThrown) {
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
$(document).ready(function() {
    $(".product_" + currentIndex).on('change', function() {
        var row_id = $(this).closest("tr").find(".row_id").val();
        var productSelected = '.product_' + row_id;
        var name = this.value;
        let url = config.routes.getProductMeasurementTypeDetail + '/' + name;
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(response) {
                $(".measurement_" + row_id).val(response.name.name);
            },
            complete: function() {
                $('#loading').css('display', 'none');
            },
            error: function(errorThrown) {
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

$(document).ready(function() {
    $('.product_' + currentIndex).select2();
});

$(document).ready(function() {
    $(".product_" + currentIndex).on('change', function() {
        var row_id = $(this).closest("tr").find(".row_id").val();
        var name = this.value;
        let url = config.routes.getProductPackingTypeDetail + '/' + name;
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(response) {
                $(".packing_" + row_id).val(response.name.name);
            },
            complete: function() {
                $('#loading').css('display', 'none');
            },
            error: function(errorThrown) {
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


$(document).on('click', 'body *', function() {
    $('.dozen').on("input", function() {
        var row_id = $(this).closest("tr").find(".row_id").val();
        let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
        let dzns = $(this).closest("tr").find(".dozen_" + row_id).val();
        if (parseInt(quantity) > 0) {
            $(this).closest("tr").find(".totDzn_" + row_id).val(quantity * dzns);
        } else {
            $(this).closest("tr").find(".totDzn_" + row_id).val('');
        }
        let totalDzn = $(this).closest("tr").find(".totDzn_" + row_id).val();
        let price = $(this).closest("tr").find(".rate_" + row_id).val();
        if (parseInt(totalDzn) > 0) {
            $(this).closest("tr").find(".amount_" + row_id).val(totalDzn * price);
        } else {
            $(this).closest("tr").find(".amount_" + row_id).val('');
        }
        doAmountTotal();
    });
    $('.rate').on("input", function() {

        var row_id = $(this).closest("tr").find(".row_id").val();
        let totalDzn = $(this).closest("tr").find(".totDzn_" + row_id).val();
        let price = $(this).closest("tr").find(".rate_" + row_id).val();
        if (parseInt(totalDzn) > 0) {
            $(this).closest("tr").find(".amount_" + row_id).val(totalDzn * price);
        } else {
            $(this).closest("tr").find(".amount_" + row_id).val('');
        }
        doAmountTotal();
    });


    $('.amount').on("focusout", function() {
        doAmountTotal();
    });

    $('.delete-item').on("click", function() {
        doAmountTotal();
    });

    function doAmountTotal() {
        $('#total-amount').text("");
        var totalAmount = 0;
        $(".amount").each(function() {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
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
        var totalAmount = 0;
        $(".amount").each(function() {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
    }
});

$(document).on('click', 'body *', function() {
    $('.qty').on("input", function() {
        doAmountTotal2();
    });

    $('.delete-item').on("click", function() {
        doAmountTotal2();
    });

    function doAmountTotal2() {

        let totalBorayAmount = 0;
        let totalCartonAmount = 0;

        // Loop through all rows to calculate total Boray and Carton quantities
        $(".item-table tbody tr").each(function() {
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
    }
});
