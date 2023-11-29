/*
$( ".date-picker" ).datepicker({
    dateFormat: "dd-mm-yy"
});

$( ".dateCalendar" ).datepicker({
    dateFormat: "dd-mm-yy",
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+0"
});

*/


/*
$(document).on("click", ".editable-submit", function() {
    setTimeout(pageReload, 3000);
});
function pageReload() {
    location.reload();
}
*/


$(document).on('click', '#booking', function() {

    var title = $(this).attr('data-title');
    $('.dialogModal_header').html('');
    $('.overview-title').html(title);
    $('.dialog_content').dialogModal({
        topOffset: 0,
        top: 0,
        type: '',
        onOkBut: function(event, el, current) {},
        onCancelBut: function(event, el, current) {},
        onLoad: function(el, current) {
            $.ajax({
                url: Routing.generate('hotel_booking'),
                async: true,
                success: function (response) {
                    el.find('.dialogModal_content').html(response);
                    jqueryTemporaryLoad();
                }
            });
        },
        onClose: function(el, current) {},
        onChange: function(el, current) {}
    });

});

function jqueryTemporaryLoad() {

    $('#searchReservation').daterangepicker(
        {
            format: 'dd-MM-yyyy',
            numberOfMonths: 2,
            startDate: Date.today(),
            minDate: Date.today(),
            endDate: Date.today(),
            separator: ' To ',
        },
        function(start, end) {
            var startDate = start.toString('yyyy-MM-dd');
            var endDate = end.toString('yyyy-MM-dd');
            if(startDate !== '' && endDate  !== '' ){
                $('#bookingStartDate').val(startDate);
                $('#bookingEndDate').val(endDate);
            }
        }
    );

    $('#tempReservation').daterangepicker(
        {
            format: 'dd-MM-yyyy',
            numberOfMonths: 2,
            startDate: Date.today(),
            minDate: Date.today(),
            endDate: Date.today(),
            separator: ' To ',
        },
        function(start, end) {
            var startDate = start.toString('yyyy-MM-dd');
            var endDate = end.toString('yyyy-MM-dd');
            if(startDate !== '' && endDate  !== '' ){
                $('#tempBookingStartDate').val(startDate);
                $('#tempBookingEndDate').val(endDate);
                $.ajax({
                    url: Routing.generate('hotel_available_room_search',{'bookingStartDate':startDate,'bookingEndDate':endDate}),
                    type: 'POST',
                    success: function (response) {
                        obj = JSON.parse(response);
                        $('#room-load').html(obj['rooms']);
                        if(obj['msg'] === 'valid'){
                            $('#addRoom').prop('disabled', false);
                        }else{
                            $('#addRoom').prop('disabled', true);
                        }
                    }
                })
            }
        }
    );

    $(document).on('change', '#particular', function() {

        var particular = $('#particular').val();
        $.ajax({
            url: Routing.generate('hotel_particular_search',{'id':particular}),
            type: 'POST',
            success: function (response) {
                obj = JSON.parse(response);
                if(obj['msg'] === 'valid'){
                    $('#salesPrice').val(obj['salesPrice']);
                    $('#subTotal').html(obj['salesPrice']);
                }else{
                    $("#particular").select2().select2("val","");
                    alert(obj['msg']);
                }
           }
        })

    });

    $(".booking-roomx").click(function(){
        var id = $(this).attr('data-id');
        $('#room-'+id).html(data).slideToggle( "slow" );
    }).toggle( function() {
        $(this).removeClass("blue").addClass("red").html('<i class="icon-remove"></i>');
    }, function() {
        $(this).removeClass("red").addClass("blue").html('<i class="icon-user"></i>');
    });

    $(document).on("click", ".room-cancel", function(e) {
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        $.get(url, function( data ) {
            $('#remove-'+id).remove();
        });
    });

    $(document).on("click", "#bookingSearch", function(e) {
        var url = $(this).attr('data-url');
        var bookingStartDate = $('#tempBookingStartDate').val();
        var bookingEndDate = $('#tempBookingEndDate').val();
        var process = $('#processStatus').val();
        var category = $('#category').val();
        var type = $('#type').val();
        var floor = $('#floor').val();
        if(bookingStartDate === "" || bookingStartDate === "" ){
            return false;
        }
        $.get(url,{'bookingStartDate':bookingStartDate,'bookingEndDate':bookingEndDate,'process':process,'category':category,'type':type,'floor':floor}, function( response ) {
            obj = JSON.parse(response);
            $('#bookingLoad').html(obj['data']);
            $('#date').html(obj['date']);
        });
        e.stopImmediatePropagation();
        e.preventDefault();
    });


    $('form#stockInvoice').on('keypress', 'input,select,textarea', function (e) {

        if (e.which == 13) {

            e.preventDefault();
            switch (this.id) {

                case 'tempReservation':
                    $('#particular').focus();
                    break;

                case 'particular':
                    $('#guestName').focus();
                    break;

                case 'guestName':
                    $('#guestMobile').focus();
                    break;

                case 'guestMobile':
                    $('#adult').focus();
                    break;

                case 'adult':
                    $('#child').focus();
                    break;
                case 'child':
                    $('#addRoom').trigger('click');
                    break;

            }
        }
    });

    $(document).on('click', '.booking-submit', function(e) {

        $.ajax({
            url         : $('form#stockInvoice').attr( 'action' ),
            type        : $('form#stockInvoice').attr( 'method' ),
            data        : new FormData($('form#stockInvoice')[0]),
            processData : false,
            contentType : false,
            success: function(response){
                obj = JSON.parse(response);
                $('#invoiceParticulars').html(obj['invoiceParticulars']);
                $('.subTotal').html(obj['subTotal']);
            }
        });
    })

}


