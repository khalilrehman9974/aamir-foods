$(document).on('click', 'body *', function () {
    $('.dzn, .qty').on("input", function () {
        var row_id = $(this).closest("tr").find(".row_id").val();
        // console.log("row_id");
        let quantity = parseFloat($(this).closest("tr").find(".qty_" + row_id).val().trim() ) || 0;
        // let dzns = $(this).closest("tr").find(".dzn_" + row_id).val() || "0";
        let dzns =parseFloat($(this).closest("tr").find(".dzn_" + row_id).val().trim()) || 0;


        if (parseInt(quantity) > 0) {
            let total = quantity * dzns || 0;
            $(this).closest("tr").find(".totalDzn_" + row_id).val(total);
        } else {
            $(this).closest("tr").find(".totalDzn_" + row_id).val('0');
        }
    });

});


$(document).ready(function () {
    $('.select2').select2();
});
