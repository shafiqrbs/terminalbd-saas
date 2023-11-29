$( ".date-picker" ).datepicker({
    dateFormat: "dd-mm-yy"
});

$( ".dateCalendar" ).datepicker({
    dateFormat: "dd-mm-yy",
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+0",
});




$(document).on('click', '.addPatient', function() {

    $('.dialogModal_header').html('Patient Information');
    $('.dialog_content').dialogModal({
        topOffset: 0,
        top: 0,
        type: '',
        onOkBut: function(event, el, current) {},
        onCancelBut: function(event, el, current) {},
        onLoad: function(el, current) {
            $.ajax({
                url: Routing.generate('dms_invoice_new'),
                async: true,
                success: function (response) {
                    el.find('.dialogModal_content').html(response);
                    formSubmit();
                }
            });
        },
        onClose: function(el, current) {},
        onChange: function(el, current) {}
    });

});

$(document).on("click", ".saveButton", function() {

    var formData = new FormData($('form#invoiceForm')[0]); // Create an arbitrary FormData instance
    var url = $('form#invoiceForm').attr('action'); // Create an arbitrary FormData instance
    $.ajax({
        url:url ,
        type: 'POST',
        processData: false,
        contentType: false,
        data:formData,
        success: function(response){

        }
    });

});



function formSubmit() {

    $("form#invoicePatientForm").validate({

        rules: {

            "patient[customer][name]": {required: true},
            "patient[customer][mobile]": {required: true},
            "patient[customer][age]": {required: true},
            "patient[customer][address]": {required: false},
            "patient[customer][weight]": {required: false},
        },

        messages: {

            "patient[customer][name]": "Enter patient name",
            "patient[customer][mobile]": "Enter patient mobile no",
            "patient[customer][age]": "Enter patient age",
        },
        tooltip_options: {
            "patient[customer][name]": {placement: 'top', html: true},
            "patient[customer][mobile]": {placement: 'top', html: true},
            "patient[customer][age]": {placement: 'top', html: true},
        }

    });
}

