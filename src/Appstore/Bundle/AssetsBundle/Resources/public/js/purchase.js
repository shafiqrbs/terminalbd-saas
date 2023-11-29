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

$(".addVendor").click(function(){
    $( ".customer" ).slideToggle( "slow" );
}).toggle( function() {
    $(this).removeClass("yellow").addClass("red").html('<i class="icon-remove"></i>');
}, function() {
    $(this).removeClass("red").addClass("yellow").html('<i class="icon-user"></i>');
});

$('form#purchaseItem').on('keypress', 'input', function (e) {

    if (e.which == 13) {
        e.preventDefault();

        switch (this.id) {

            case 'particular':
                $('#price').focus();
                break;

            case 'price':
                $('#quantity').focus();
                break;

            case 'quantity':
                $('#addParticular').submit();
                break;
        }
    }
});


var form = $("#purchaseItem").validate({

    rules: {

        "name": {required: false},
        "price": {required: true},
        "quantity": {required: true},
    },

    messages: {

        "name":"Enter item name",
        "price":"Enter purchase price",
        "quantity":"Enter purchase quantity",
    },
    tooltip_options: {

        "name": {placement:'top',html:true},
        "price": {placement:'top',html:true},
        "quantity": {placement:'top',html:true},
    },

    submitHandler: function(form) {

        $.ajax({
            url         : $('form#purchaseItem').attr( 'action' ),
            type        : $('form#purchaseItem').attr( 'method' ),
            data        : new FormData($('form#purchaseItem')[0]),
            processData : false,
            contentType : false,
            success: function(response){
                obj = JSON.parse(response);
                $('#invoiceParticulars').html(obj['invoiceParticulars']);
                $('.subTotal').html(obj['subTotal']);
                $('.netTotal').html(obj['netTotal']);
                $('.tti').html(obj['tti']);
                $('.rebate').html(obj['rebate']);
                $('.netTotal').val(obj['netTotal']);
                $('.due').html(obj['due']);
                $('.discount').html(obj['discount']);
                $('.discount').val(obj['discount']);
                $('#paymentTotal').val(obj['netTotal']);
                $('form#purchaseItem')[0].reset();
                $('#quantity').val('1');

            }
        });
    }
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
                    $('.subTotal').html(obj['subTotal']);
                    $('.netTotal').html(obj['netTotal']);
                    $('.tti').html(obj['tti']);
                    $('.rebate').html(obj['rebate']);
                    $('.netTotal').val(obj['netTotal']);
                    $('.due').html(obj['due']);
                    $('.discount').html(obj['discount']);
                    $('.discount').val(obj['discount']);
                    $('#paymentTotal').val(obj['netTotal']);

                }
            })
        }
    });
    e.preventDefault();
});

$(document).on('change', '.discount', function() {


    var discount = parseFloat($(this).val());
    var purchase = parseInt($('#purchaseId').val());

    $.ajax({
        url: Routing.generate('assets_purchase_discount_update'),
        type: 'POST',
        data:'discount=' + discount+'&purchase='+purchase,
        success: function(response) {
            obj =JSON.parse(response);
            $('.subTotal').html(obj['subTotal']);
            $('.netTotal').html(obj['netTotal']);
            $('.tti').html(obj['tti']);
            $('.rebate').html(obj['rebate']);
            $('.netTotal').val(obj['netTotal']);
            $('.due').html(obj['due']);
            $('.discount').html(obj['discount']);
            $('.discount').val(obj['discount']);
            $('#paymentTotal').val(obj['netTotal']);

        }

    })

});

$(document).on('keyup', '#purchase_payment', function() {

    var payment     = parseInt($('#purchase_payment').val()  != '' ? $('#purchase_payment').val() : 0 );
    var due =  parseInt($('#paymentTotal').val());
    var dueAmount = (due - payment);
    if(dueAmount > 0){
        $('#balance').html('Due Tk.');
        $('.due').html(dueAmount);
    }else{
        var balance =  payment - due ;
        $('#balance').html('Return Tk.');
        $('.due').html(balance);
    }
});

$('form#purchase').on('keypress', 'input', function (e) {

    if (e.which == 13) {
        e.preventDefault();

        switch (this.id) {

            case 'purchase_payment':
                $('#receiveBtn').click();


        }
    }
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

