/**
 * Created by rbs on 2/9/16.
 */

$(".select2Customer").select2({

    ajax: {

        url: Routing.generate('domain_customer_search'),
        dataType: 'json',
        delay: 250,
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
    formatResult: function (item) {
        return item.text
    }, // omitted for brevity, see the source of this page
    formatSelection: function (item) {
        return item.text
    }, // omitted for brevity, see the source of this page
    initSelection: function (element, callback) {
        var customer = $(element).val();
        $.ajax(Routing.generate('domain_customer_name', { customer : customer}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 2
});

$('form#salesForm').on('keypress', '.inputs', function (e) {

    if (e.which === 13) {
        var inputs = $(this).parents("form").eq(0).find("input,select");
        var idx = inputs.index(this);

        if (idx == inputs.length - 1) {
            inputs[0].select()
        } else {
            inputs[idx + 1].focus(); //  handles submit buttons
        }
        switch (this.id) {
            case 'sales_discount':
                $('#sales_payment').focus();
                break;
            case 'mobile':
                $('#onlineSalesPos').focus();
                break;
        }
        return false;
    }
});


var InventorySales = function(sales) {

    $('#item').select2('open');

    $(".addCustomer").click(function(){
        $( ".customer" ).slideToggle( "slow" );
    }).toggle( function() {
        $(this).removeClass("blue").addClass("red").html('<i class="icon-remove"></i>');
    }, function() {
        $(this).removeClass("red").addClass("blue").html('<i class="icon-user"></i>');
    });


    $( "#searchToggole" ).click(function() {
        $( "#search-product" ).slideToggle( "slow" );
    });

    $('form.horizontal-form').on('keypress', 'input', function (e) {

        if (e.which == 13) {
            e.preventDefault();

            switch (this.id) {

                case 'discount':
                    $('#paymentAmount').focus();
                    break;

                case 'paymentAmount':
                    $('#mobile').select2('open');
                    $('#sales_mobile').focus();
                    $(".paymentBtn").attr("disabled", false);
                    break;
            }
        }
    });

    $('#sales_general_salesBy').on("select2-selecting", function (e) {
        setTimeout(function () {
            $('#discount').focus();
        }, 2000)
    });

    $(document).on('change', '#barcode', function() {

        var barcode = $('#barcode').val();
        if(barcode === ''){
            $('#wrongBarcode').html('<strong>Error!: </strong>Invalid barcode, Please try again.');
            return false;
        }
        $.ajax({
            url: Routing.generate('tally_sales_barcode_item_add'),
            type: 'POST',
            data:'barcode='+barcode+'&sales='+sales,
            success: function(response){
                $('#barcode').focus().val('');
                obj = JSON.parse(response);
                $('#salesItem').html(obj['salesItems']);
                $('.subTotal').html(obj['subTotal']);
                $('.total').html(obj['total']);
                $('.netTotal').html(obj['netTotal']);
                $('.due').html(obj['due']);
                $('.vat').html(obj['tti']);
                $('.discount').html(obj['discount']);
                $('.discountCalculation').html(obj['discountCalculation']);
                $('.paymentTotal').html(obj['netTotal']);
                $('#wrongBarcode').html(obj['msg']);
                FormComponents.init();
            },

        })
    });

    $("#barcodeNo").select2({

        placeholder: "Enter specific barcode",
        allowClear: true,
        ajax: {

            url: Routing.generate('tally_stcok_barcode_autocomplete_search'),
            dataType: 'json',
            delay: 250,
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
        formatResult: function(item){ return item.text}, // omitted for brevity, see the source of this page
        formatSelection: function(item){return item.text }, // omitted for brevity, see the source of this page
        initSelection: function(element, callback) {
            var id = $(element).val();
        },
        minimumInputLength:1
    });

    $(document).on('change', '#barcodeNo', function() {

        var barcode = $('#barcodeNo').val();
        alert(barcode);
        if(barcode === ''){
            $('#wrongBarcode').html('Using wrong barcode, Please try again correct barcode.');
            return false;
        }
        $.ajax({
            url: Routing.generate('tally_sales_barcode_item_add'),
            type: 'POST',
            data:'barcode='+barcode+'&sales='+sales,
            success: function(response) {
                $('#barcode').focus().val('');
                obj = JSON.parse(response);
                $('#salesItem').html(obj['salesItems']);
                $('.subTotal').html(obj['subTotal']);
                $('.total').html(obj['total']);
                $('.netTotal').html(obj['netTotal']);
                $('.due').html(obj['due']);
                $('.vat').html(obj['tti']);
                $('.discount').html(obj['discount']);
                $('.discountCalculation').html(obj['discountCalculation']);
                $('.paymentTotal').html(obj['netTotal']);
                $('#wrongBarcode').html(obj['msg']);
                FormComponents.init();
            },
        })
    });

    $(".select2Item").select2({

        placeholder: "Search item name",
        ajax: {
            url: Routing.generate('tally_stcok_item_autocomplete_search'),
            dataType: 'json',
            delay: 250,
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
        formatResult: function(item){

            //return item.name +' => '+ (item.remainingQuantity)
            return item.name

        }, // omitted for brevity, see the source of this page
        formatSelection: function(item){return item.name}, // omitted for brevity, see the source of this page
        initSelection: function(element, callback) {
            var id = $(element).val();
        },
        allowClear: true,
        minimumInputLength:1
    });



    $(document).on('change', '.quantity , .salesPrice', function() {

        var rel = $(this).attr('rel');

        var quantity = parseFloat($('#salesQuantity-'+rel).val());
        var price = parseFloat($('#salesPrice-'+rel).val());
        var subTotal  = (quantity * price);
        $("#itemSubTotal-"+rel).html(subTotal);

        $.ajax({
            url: Routing.generate('tally_sales_sales_item_update'),
            type: 'POST',
            data:'salesItemId='+ rel +'&quantity='+ quantity +'&salesPrice='+ price,
            success: function(response) {
                location.reload();
            },

        })
    });

    $(document).on('click', '.serialSave', function() {
        var id =$(this).attr('id');
        var url =$(this).attr('data-url');
        var serial = $('#serialNo-'+id).val();
        if(serial ==='undefined') {
            alert('Please select serial no.');
            return false;
        }else{
            $.get(url,{'serial':serial});
            location.reload();
        }

    });

    $(document).on("change", ".customerProcess", function(e) {
        var formData = new FormData($('form#salesForm')[0]); // Create an arbitrary FormData instance
        var url = Routing.generate('tally_customer_update'); // Create an arbitrary FormData instance
        $.ajax(url,{
            processData: false,
            contentType: false,
            type: 'POST',
            data: formData,
            success: function (response){}
        });

    })

    $(document).on("click", ".delete", function() {

        var url = $(this).attr("rel");
        var id = $(this).attr("id");
        $('#confirm-content').confirmModal({
            topOffset: 0,
            top: '25%',
            onOkBut: function(event, el) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (response) {
                        obj = JSON.parse(response);
                        if ('success' == obj['success']) {
                            $('#remove-' + id).hide();
                            $('.subTotal').html(obj['subTotal']);
                            $('.total').html(obj['total']);
                            $('.netTotal').html(obj['netTotal']);
                            $('.due').html(obj['due']);
                            $('.vat').html(obj['tti']);
                            $('.discount').html(obj['discount']);
                            $('.discountCalculation').html(obj['discountCalculation']);
                            $('.paymentTotal').html(obj['netTotal']);
                            $('#wrongBarcode').html(obj['msg']);
                        }


                    },
                })
            }
        });

    });

    var delay = (function(){
        var timer = 0;
        return function(callback, ms){
            clearTimeout (timer);
            timer = setTimeout(callback, ms);
        };
    })();

    $(document).on('keyup', '#sales_discountCalculation', function() {

        var discountType = $('#sales_discountType').val();
        var discount = parseFloat($('#sales_discountCalculation').val());
        var invoice = $('#salesId').val();
        var total =  parseFloat($('#paymentTotal').val());
        if( discount >= total ){
            $('#sales_discount').val(0);
            alert ('Discount amount less then total amount');
            return false;
        }
        $.ajax({
            url: Routing.generate('tally_sales_discount_update'),
            type: 'POST',
            data:'discount=' + discount+'&discountType='+discountType+'&sales='+invoice,
            success: function(response) {
                obj = JSON.parse(response);
                $('.subTotal').html(obj['subTotal']);
                $('.total').html(obj['total']);
                $('.netTotal').html(obj['netTotal']);
                $('.due').html(obj['due']);
                $('.vat').html(obj['tti']);
                $('.discount').html(obj['discount']);
                $('.discountCalculation').html(obj['discountCalculation']);
                $('.paymentTotal').html(obj['netTotal']);
                $('#wrongBarcode').html(obj['msg']);
            }

        })

    });

    $(document).on('change', '#sales_discountType', function() {

        var discountType = $('#sales_discountType').val();
        var discount = parseFloat($('#sales_discountCalculation').val());
        var invoice = $('#salesId').val();
        var total =  parseFloat($('#paymentTotal').val());
        if( discount >= total ){
            $('#sales_discount').val(0);
            alert ('Discount amount less then total amount');
            return false;
        }
        $.ajax({
            url: Routing.generate('tally_sales_discount_update'),
            type: 'POST',
            data:'discount=' + discount+'&discountType='+discountType+'&sales='+invoice,
            success: function(response) {
                obj = JSON.parse(response);
                $('.subTotal').html(obj['subTotal']);
                $('.total').html(obj['total']);
                $('.netTotal').html(obj['netTotal']);
                $('.due').html(obj['due']);
                $('.vat').html(obj['tti']);
                $('.discount').html(obj['discount']);
                $('.discountCalculation').html(obj['discountCalculation']);
                $('.paymentTotal').html(obj['netTotal']);
                $('#wrongBarcode').html(obj['msg']);
            }

        })

    });

    $(document).on('keyup', '#sales_payment', function() {

        var payment     = parseInt($('#sales_payment').val()  != '' ? $('#sales_payment').val() : 0 );
        var total =  parseInt($('#paymentTotal').val());
        var dueAmount = (total-payment);
        if(dueAmount > 0){
            $('#balance').html('Due Tk.');
            $('.due').html(dueAmount);
        }else{
            var balance =  payment - total ;
            $('#balance').html('Return TK.');
            $('.due').html(balance);
       }

    });

    $(document).on("click", ".receiveBtn", function() {

        if ($('#paymentTotal').val().length == 0){
            alert('Please add sales item');
            $('input[name=barcode]').focus();
            return false;
        }

        $('#confirm-content').confirmModal({
            topOffset: 0,
            top: '25%',
            onOkBut: function(event, el) {
                $('form#salesForm').submit();
            }
        });

    });

    $(document).on('change', '#sales_transactionMethod', function() {

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




    $(".select2Location").select2({

        placeholder: "Search location name",
        ajax: {
            url: Routing.generate('tally_customer_location_search'),
            dataType: 'json',
            delay: 250,
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
        formatResult: function(item){
            return item.text

        }, // omitted for brevity, see the source of this page
        formatSelection: function(item){return item.text}, // omitted for brevity, see the source of this page
        initSelection: function(element, callback) {
            var id = $(element).val();
        },
        allowClear: true,
        minimumInputLength:1
    });



}

