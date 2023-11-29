$(document).on("click", ".delete , .approve", function() {

    var url = $(this).attr('data-url');
    var id = $(this).attr('data-id');
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


$(' #action-button').click(function(e) {

    $('#confirm-content').confirmModal({
        topOffset: 0,
        top: '25%',
        onOkBut: function(event, el) {
            $('#purchaseForm').submit()
        }
    });
    e.preventDefault();
});


$('.addPurchaseItem').click(function(e) {

    var url = $(this).attr("data-url");
    var id = $(this).attr("id");
    var quantity = $('#quantity-'+id).val();
    $.get(url, {quantity:quantity})
        .done(function( data ) {
            if(data === 'success'){
                location.reload();
            }
        });
});

$('.addPoItem').click(function(e) {

    var url = $(this).attr("data-url");
    var id = $(this).attr("id");
    var quantity = $('#quantity-'+id).val();
    var purchasePrice = $('#purchasePrice-'+id).val();
    if(quantity === '' && purchasePrice === ''){
        alert('Please confirm quantity & unit price');
        return false;
    }
    $.get(url, {quantity:quantity,purchasePrice:purchasePrice})
        .done(function( data ) {
            if(data == 'success'){
                location.reload();
            }
        });
});


var count = 0;

$('.addmore').click(function(){

    var $el = $(this);
    var $cloneBlock = $('#clone-block');
    var $clone = $cloneBlock.find('.clone:eq(0)').clone();
    $clone.find('[id]').each(function(){this.id+='someotherpart'});
    $clone.find(':text,textarea' ).val("");
    $clone.attr('id', "added"+(++count));
    $clone.find('.remove').removeClass('hidden');
    $cloneBlock.append($clone);
    $('.numeric').numeric();

});

$('#clone-block').on('click', '.remove', function(){

    $(this).closest('.clone').remove();

});

$('.trash').on("click", ".remove", function() {

    var url = $(this).attr('data-url');
    var id = $(this).attr("id");
    $.ajax({
        url: url,
        type: 'GET',
        success: function (response) {
            if( 'success' == response ) {
                location.reload();
            }
        },
    })

});

