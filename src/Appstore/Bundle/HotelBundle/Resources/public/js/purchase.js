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

$(document).on('change', '.transactionMethod', function() {

    var paymentMethod = $(this).val();

    if( paymentMethod == 2){
        $('#cartMethod').show();
        $('#bkashMethod').hide();
    }else if( paymentMethod == 3){
        $('#bkashMethod').show();
        $('#cartMethod').hide();
    }else{
        $('#cartMethod').hide();
        $('#bkashMethod').hide();
    }

});

$(document).on('change', '#particular', function() {

    var url = $('#particular').val();
    $.ajax({
        url: url,
        type: 'GET',
        success: function (response) {
            obj = JSON.parse(response);
            $('#particularId').val(obj['particularId']);
            $('#quantity').val(obj['quantity']);
            $('#price').val(obj['price']);
            $('#purchasePrice').val(obj['purchasePrice']);
            $('#unit').html(obj['unit']);
        }
    })
});

var form = $("#purchaseItem").validate({

    rules: {

        "particular": {required: true},
        "purchasePrice": {required: true},
        "quantity": {required: true},
    },

    messages: {

        "particular":"Enter particular name",
        "purchasePrice":"Enter purchase price",
        "quantity":"Enter purchase quantity",
    },
    tooltip_options: {
        "particular": {placement:'top',html:true},
        "purchasePrice": {placement:'top',html:true},
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
                $('#subTotal').html(obj['subTotal']);
                $('#netTotal').html(obj['netTotal']);
                $('#paymentTotal').val(obj['netTotal']);
                $('#discount').html(obj['discount']);
                $('#due').html(obj['due']);
                $('#purchasePrice').val('');
                $("#particular").select2().select2("val","");
                $('#price').val('');
                $('#unit').html('Unit');
                $('#quantity').val('1');
            }
        });
    }
});

/*$(document).on('click', '#addParticular', function() {

    var particularId = $('#particularId').val();
    var quantity = $('#quantity').val();
    var price = $('#purchasePrice').val();
    var url = $('#addParticular').attr('data-url');
    if(particularId == ''){
        $('.msg-hidden').show();
        $('input[name=particular]').focus();
        $('#msg').html('Please select medicine or accessories name');
        return false;
    }
    if(price == ''){
        $('.msg-hidden').show();
        $('#msg').html('Please enter purchase price');
        $('input[name=purchasePrice]').focus();
        return false;
    }
    $.ajax({
        url: url,
        type: 'POST',
        data: 'particularId='+particularId+'&quantity='+quantity+'&price='+price,
        success: function (response) {
            obj = JSON.parse(response);
            $('#invoiceParticulars').html(obj['invoiceParticulars']);
            $('#subTotal').html(obj['subTotal']);
            $('#netTotal').html(obj['netTotal']);
            $('#paymentTotal').val(obj['netTotal']);
            $('#discount').html(obj['discount']);
            $('#due').html(obj['due']);
            $('#purchasePrice').val('');
            $("#particular").select2().select2("val","");
            $('#price').val('');
            $('#unit').html('Unit');
            $('#quantity').val('1');
        }
    })
});*/

$(document).on('keyup', '#purchase_discountCalculation', function() {

    var discountType = $('#purchase_discountType').val();
    var discount = parseInt($('#purchase_discountCalculation').val());
    var invoice = $('#purchaseId').val();
    var total =  parseInt($('#purchase_payment').val());
    if( discount >= total ){
        $('#purchase_discount').val(0);
        return false;
    }
    $.ajax({
        url: Routing.generate('business_purchase_discount_update'),
        type: 'POST',
        data:'discount=' + discount+'&discountType='+discountType+'&invoice='+invoice,
        success: function(response) {
            obj = JSON.parse(response);
            $('#subTotal').html(obj['subTotal']);
            $('#netTotal').html(obj['netTotal']);
            $('#paymentTotal').val(obj['netTotal']);
            $('#discount').html(obj['discount']);
            $('#due').html(obj['due']);
        }

    })

});

$('#invoiceParticulars').on("click", ".particularDelete", function() {

    var url = $(this).attr("data-url");
    var id = $(this).attr("data-id");
    $('#confirm-content').confirmModal({
        topOffset: 0,
        top: '25%',
        onOkBut: function(event, el) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    $('#remove-'+id).hide();
                    obj = JSON.parse(response);
                    $('#subTotal').html(obj['subTotal']);
                    $('#netTotal').html(obj['netTotal']);
                    $('#paymentTotal').val(obj['netTotal']);
                    $('#discount').html(obj['discount']);
                    $('#due').html(obj['due']);

                }
            })
        }
    });

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

            case 'purchase_discountCalculation':
                $('#purchase_payment').focus();
                break;

            case 'purchase_payment':
                $('#receiveBtn').focus();
                break;
        }
    }
});
