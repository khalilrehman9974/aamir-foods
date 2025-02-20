$(document).on('click', 'body *', function () {
    $('.dzn').on("focusout", function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        // console.log("row_id");
        let quantity = $(this).closest("tr").find(".qty_" + row_id).val();
        let dzns = $(this).closest("tr").find(".dzn_" + row_id).val();
        if (parseInt(quantity) > 0) {
            $(this).closest("tr").find(".totalDzn_" + row_id).val(quantity * dzns);
        } else {
            $(this).closest("tr").find(".totalDzn_" + row_id).val('');
        }
    });

});


$(document).ready(function () {
    $('.select2').select2();
});
