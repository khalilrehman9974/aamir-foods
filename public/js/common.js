$(".delete").on('click', function () {
    var id = $(this).attr('data-id');
    var ajax_url = config.routes.deleteMainHead;
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "DELETE",
                url: ajax_url + '/' + id,
                data: {"id": id},
                beforeSend: function () {
                    $('#loading').css('display', 'block');
                },
                success: function (data) {
                    if (data.status == 'success') {
                        // toastr.success(data.message);
                        Swal.fire(
                            'Deleted!',
                            'Record has been deleted.',
                            'success'
                        )
                        $("#row_" + id).remove();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong',
                        })
                    }
                },
                complete: function () {
                    $('#loading').css('display', 'none');
                },
                error: function (errorThrown) {
                    var errors = errorThrown.responseJSON.errors;
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                    })
                }
            });

        }
    });
});

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

//Runtime calculation
$(document).on('click', 'body *', function () {
    $('.rate').on("focusout", function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
        let price = $(this).closest("tr").find(".rate_" + row_id).val();
        console.log(row_id + ", " + quantity + ", " + price);
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
        console.log('in do amount total');
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        $('#gross-amount').val(totalAmount.toFixed(2));
        $('#net-amount').val(totalAmount.toFixed(2));
    }

    $("#freight, #scheme, #commission").on("focusout", function (){
        var totalAmount = 0;
        $(".amount").each(function () {
            if (!isNaN(this.value) && this.value.length != 0) {
                totalAmount += parseFloat(this.value);
            }
        });
        let freight = $("#freight").val() ? $("#freight").val() : 0;
        let scheme = $("#scheme").val() ? $("#scheme").val() : 0;
        let commission = $("#commission").val() ? $("#commission").val() : 0;

        var totalLessAmount = parseInt(freight) + parseInt(scheme) + parseInt(commission);
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


