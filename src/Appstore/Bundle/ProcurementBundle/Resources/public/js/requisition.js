/**
 * Created by rbs on 5/1/17.
 */

$(document).on("click", ".approve", function() {
    var url = $(this).attr('data-url');
    $('#confirm-content').confirmModal({
        topOffset: 0,
        top: '25%',
        onOkBut: function(event, el) {
            $.get(url, function( data ) {
                location.reload();
            });
        }
    });
});

document.onkeyup = function(e) {
    var key = e.which || e.charCode || e.keyCode
    if(e.ctrlKey && key === 87) {
        $('.select2StockMedicinePurchase').select2('open');
    } else if (e.ctrlKey && key === 83) {
        $('.confirmSubmit').trigger('click');
    }
};


$(document).on('change', '.transactionMethod', function() {

    var paymentMethod = $(this).val();
    if( paymentMethod === 2){
        $('#cartMethod').show();
        $('#bkashMethod').hide();
    }else if( paymentMethod === 3){
        $('#bkashMethod').show();
        $('#cartMethod').hide();
    }else{
        $('#cartMethod').hide();
        $('#bkashMethod').hide();
    }

});


$('#purchaseItem_stockName').on("select2-selecting", function (e) {
    setTimeout(function () {
        $('#purchaseItem_stockName').focus();
    }, 500)
});


$('form#purchaseItemForm').on('keypress', '.input', function (e) {
    if (e.which === 13) {
        var inputs = $(this).parents("form").eq(0).find("input,select");
        var idx = inputs.index(this);
        if (idx == inputs.length - 1) {
            inputs[0].select()
        } else {
            inputs[idx + 1].focus(); //  handles submit buttons
        }
        switch (this.id) {
            case 'purchaseitem_item':
                $('#purchaseItem_name').focus();
                break;

            case 'purchaseItem_name':
                $('#purchaseItem_quantity').focus();
                $('#addPurchaseForm').click();
                $('#purchaseItem_item').select2('open');
                break;

        }
        return false;
    }
});

var form = $("#purchaseItemForm").validate({
    rules: {
        "purchaseItem[item]": {required: true},
        "purchaseItem[name]": {required: false},
        "purchaseItem[quantity]": {required: true},
    },

    messages: {
        "purchaseItem[item]":"Enter item name",
        "purchaseItem[quantity]":"Enter quantity"
    },
    tooltip_options: {
        "purchaseItem[item]": {placement:'top',html:true},
        "purchaseItem[quantity]": {placement:'top',html:true}
    },

    submitHandler: function(form) {
        $.ajax({
            url         : $('form#purchaseItemForm').attr( 'action' ),
            type        : $('form#purchaseItemForm').attr( 'method' ),
            data        : new FormData($('form#purchaseItemForm')[0]),
            processData : false,
            contentType : false,
            success: function(response){
                $('#purchaseItem').html(response);
                $("#purchaseItem_item").select2("val", "");
                $('#purchaseItemForm')[0].reset();

            }
        });
    }
});


$('#invoiceParticulars').on("click", ".deleteParticular", function() {

    var url = $(this).attr("data-url");
    $.get(url, function( response ) {
        $('#purchaseItem').html(response);
    });
});


$(document).on('change', '.updateItem', function() {

    var id = $(this).attr('data-id');
    var action = $(this).attr('data-action');
    var quantity     = parseFloat($('#quantity-'+id).val()  != '' ? $('#quantity-'+id).val() : 1 );
    var price     = parseFloat($('#price-'+id).val()  != '' ? $('#price-'+id).val() : 0 );
    var description     = $('#description-'+id).val();
    var subTotal  = (quantity * price);
    $("#subTotal-"+id).html(subTotal);
    $.ajax({
        url: action,
        type: 'POST',
        data:'purchaseItemId='+ id +'&quantity='+ quantity+'&price='+ price+'&description='+ description,
        success: function(response) {
            $('#purchaseItem').html(response);
        },

    })
});


$(document).on('change','.inputs', function() {
    var purchase = parseInt($('#purchaseId').val());
    var medicinepurchase_memo = $('#medicinepurchase_memo').val();
    var medicinepurchase_transactionMethod = parseInt($('#medicinepurchase_transactionMethod').val());
    var medicinepurchase_payment =parseFloat($('#medicinepurchase_payment').val());
    var invoiceDue = parseFloat($('#invoiceDue').val());
    $.ajax({
        url: Routing.generate('medicine_purchase_discount_update'),
        type: 'POST',
        data:'purchase='+purchase+'&medicinepurchase_memo='+medicinepurchase_memo+'&medicinepurchase_transactionMethod='+medicinepurchase_transactionMethod+'&medicinepurchase_payment='+medicinepurchase_payment+'&invoiceDue='+invoiceDue,
        success: function(response) {
            console.log(response);
        }

    })

});


$(document).on("click", "#barcodePurchase", function() {
    $('.purchaseToggle').toggle();
});

$(document).on("change", ".barcode", function() {
    var barcode = $(this).val();
    $.ajax({
        url: Routing.generate('medicine_purchase_barcode_search',{'id':barcode}),
        type: 'GET',
        success: function (response) {
            obj = JSON.parse(response);
            $('#barcodePurchaseItem_name').val(obj['name']).focus();
            $('#barcodeUnit').html(obj['unit']);
        }
    })
});

$('form#barcodePurchaseItemForm').on('keypress', '.barcodeInput', function (e) {

    if (e.which === 13) {
        var inputs = $(this).parents("form#barcodePurchaseItemForm").eq(0).find("input,select");
        var idx = inputs.index(this);
        if (idx == inputs.length - 1) {
            inputs[0].select()
        } else {
            inputs[idx + 1].focus(); //  handles submit buttons
        }
        switch (this.id) {
            case 'barcodePurchaseItem_name':
                $('#barcodePurchaseItem_quantity').focus();
                break;

            case 'barcodePurchaseItem_quantity':
                $('#addPurchaseBarcodeItemForm').click();
                $('#barcodePurchaseItem_barcode').focus();
                break;
        }
        return false;
    }
});

var barcodePurchaseItem = $("#barcodePurchaseItemForm").validate({

    rules: {
        "barcodePurchaseItem[barcode]": {required: true},
        "barcodePurchaseItem[name]": {required: true},
        "barcodePurchaseItem[salesPrice]": {required: false},
        "barcodePurchaseItem[quantity]": {required: false},
        "barcodePurchaseItem[bonusQuantity]": {required: false}
    },

    messages: {
        "barcodePurchaseItem[barcode]":"Enter item barcode",
        "barcodePurchaseItem[name]":"Enter medicine/item name",
    },
    tooltip_options: {
        "barcodePurchaseItem[barcode]": {placement:'top',html:true},
        "barcodePurchaseItem[name]": {placement:'top',html:true},
    },
    submitHandler: function(barcodePurchaseItem) {
        $.ajax({
            url         :  barcodePurchaseItem.action,
            type        :  barcodePurchaseItem.method,
            data        : new FormData($('form#barcodePurchaseItemForm')[0]),
            processData : false,
            contentType : false,
            success: function(response){
                obj = JSON.parse(response);
                $('#invoiceParticulars').html(obj['invoiceParticulars']);
                $('#subTotal').html(obj['subTotal']);
                $('#vat').val(obj['vat']);
                $('.grandTotal').html(obj['netTotal']);
                $('#paymentTotal').val(obj['subTotal']);
                $('#due').val(obj['due']);
                $('.dueAmount').html(obj['due']);
                $('#discount').html(obj['discount']);
                $('#msg').html(obj['msg']);
                $('form#barcodePurchaseItemForm')[0].reset();
                $('#addPurchaseItem').html('<i class="icon-save"></i> Add').attr("disabled", false);
                $(".editableText").editable();
                $(".expirationDate").editable();
            }
        });
    }
});



$('.remove-value').click(function() {
    $(this).attr('value', '');
});

$('.invoice-mode').change(function() {
    var mode = $(this).val();
    if(mode === 'invoice'){
        $('.invoiceMode').hide();
        $('#due-input').toggle();
    }else{
        $(".invoiceMode").toggle();
        $('#due-input').hide();
    }
});

$(document).on('change', '#medicinepurchase_payment , #medicinepurchase_discount', function() {
    var payment     = parseInt($('#medicinepurchase_payment').val()  != '' ? $('#medicinepurchase_payment').val() : 0 );
    var due =  parseInt($('#paymentTotal').val());
    var dueAmount = (due-payment);
    if(dueAmount > 0){
        $('#balance').html('Due Tk.');
        $('.dueAmount').html(dueAmount);
    }else{
        var balance =  payment - due ;
        $('#balance').html('Return Tk.');
        $('.dueAmount').html(balance);
    }
});



$(document).on('change', '#medicinepurchase_payment , #invoiceDue', function() {
    var invoiceMode = $('#medicinepurchase_invoiceMode').val();
    if(invoiceMode === "invoice") {
        var payment = parseInt($('#medicinepurchase_payment').val() != '' ? $('#medicinepurchase_payment').val() : 0);
        var invoiceDue = parseInt($('#invoiceDue').val() != '' ? $('#invoiceDue').val() : 0);
        var paymentTotal = parseInt($('#paymentTotal').val() != '' ? $('#paymentTotal').val() : 0);
        var discount = (paymentTotal - (payment + invoiceDue));
        var percentage = ((discount * 100) / paymentTotal).toFixed(2);
        $('#discountPercentage').html(percentage + '%');
    }

});


$(document).on("click", ".confirmSubmit", function() {
    $('#confirm-content').confirmModal({
        topOffset: 0,
        top: '25%',
        onOkBut: function(event, el) {
            $('form#purchaseForm').submit();
        }
    });

});

function brandMedicineSearch(purchase){

    var url = Routing.generate('medicine_purchase_stock_item_search',{'purchase':purchase});
    $(".select2StockMedicinePurchaseItem").select2({
        placeholder: "Search stock medicine name",
        ajax: {
            url: url,
            dataType: 'json',
            delay: 100,
            data: function (params, page) {
                return {
                    q: params,
                    page_limit: 100
                };
            },
            results: function (data, page) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (m) {
            return m;
        },
        formatResult: function (item) { return item.text}, // omitted for brevity, see the source of this page
        formatSelection: function (item) { return item.text }, // omitted for brevity, see the source of this page
        initSelection: function (element, callback) {
            var id = $(element).val();
            $.ajax(Routing.generate('medicine_stock_name', { stock : id}), {
                dataType: "json"
            }).done(function (data) {
                return  callback(data);
            });
        },
        allowClear: true,
        minimumInputLength: 1

    });

    $(document).on('change', '#purchaseItem_stockName', function() {

        var medicine = $('#purchaseItem_stockName').val();
        if(medicine === ""){ return false }
        $.ajax({
            url: Routing.generate('medicine_purchase_particular_search',{'id':medicine,'purchase':purchase}),
            type: 'GET',
            success: function (response) {
                obj = JSON.parse(response);
                $('#purchaseItem_quantity').focus();
                $('#pack').val(obj['pack']);
                $('#minQuantity').val(obj['minQuantity']);
                $('#purchaseItem_salesPrice').val(obj['salesPrice']);
                $('#purchaseItem_purchasePrice').val(obj['purchasePrice']);
                $('#stockSalesPrice').val(obj['salesPrice']);
                $('#stockPurchasePrice').val(obj['purchasePrice']);
                $('#tradePrice').html(obj['tp']);
                $('#unit').html(obj['unit']);
                if(obj['openingStatus'] === 'valid' ){
                    $('#opening-box').show();
                    $('#currentSalesQty').html(obj['salesQty']);
                    $('#salesQty').val(obj['salesQty']);
                    $('#openingQuantity').val(obj['salesQty']);
                    $('#totalQty').html(obj['salesQty']);
                }
                $(".editableText").editable();
                $(".expirationDate").editable();
            }
        })
    });

    $(document).on("change", "#medicineVendor", function() {
        var id = $(this).val();
        var purchase = $('#purchaseId').val();
        $.ajax({
            url: Routing.generate('medicine_purchase_update_brand',{'id':purchase,'filedName':'vendor','mode':id}),
            type: 'GET',
            success: function (response) {
                console.log('done');
            }
        })
    });

    $(document).on("change", "#brandName", function() {
        var id = $(this).val();
        var purchase = $('#purchaseId').val();
        $.ajax({
            url: Routing.generate('medicine_purchase_update_brand',{'id':purchase,'filedName':'brand','mode':id}),
            type: 'GET',
            success: function (response) {
                console.log('done');
            }
        })
    });

}

