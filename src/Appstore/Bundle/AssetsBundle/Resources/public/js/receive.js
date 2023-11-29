/**
 * Created by rbs on 5/1/17.
 */
$( ".date-picker" ).datepicker({
    dateFormat: "dd-mm-yy"
});

$( ".dateCalendar" ).datepicker({
    dateFormat: "dd-mm-yy",
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+0",
});


$('#invoiceParticulars').on("click", ".item-delete", function(e) {

    var url = $(this).attr("data-url");
    var id = $(this).attr("id");
    $('#confirm-content').confirmModal({
        topOffset: 0,
        top: '25%',
        onOkBut: function(event, el) {
            $('#remove-'+id).hide();
            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    obj = JSON.parse(response);
                    $('#invoiceParticulars').html(obj['invoiceParticulars']);
                }
            })
        }
    });
    e.preventDefault();
});

$(document).on('change', '.vatPercent', function() {


    var vat = parseFloat($(this).val());
    var purchase = parseInt($('#purchaseId').val());

    $.ajax({
        url: Routing.generate('assets_itemreceive_vat_update'),
        type: 'POST',
        data:'vat=' + vat+'&purchase='+purchase,
        success: function(response) {
            obj = JSON.parse(response);
            $('#invoiceParticulars').html(obj['invoiceParticulars']);
        }
    })

});

$(document).on('change', '.quantity', function() {
    var url = $(this).attr('data-action');
    var id = $(this).attr('data-id');
    var quantity = parseFloat($(this).val());
    $.ajax({
        url: url,
        type: 'POST',
        data:'quantity=' + quantity,
        success: function(response) {
            obj = JSON.parse(response);
            $('#invoiceParticulars').html(obj['invoiceParticulars']);
        }
    })

});


$(document).on('click', '#purchase_payment,#purchase_discount', function() {
    $(this).val('');
});

$(document).on("click", "#receiveBtn", function() {

    $(this).removeClass('approve');
    var id = $(this).attr("data-id");
    var url = $(this).attr("data-url");
    $('#confirm-content').confirmModal({
        topOffset: 0,
        top: '25%',
        onOkBut: function(event, el) {
            $('#purchase').submit();
        }
    });

});

