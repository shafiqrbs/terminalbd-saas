/**
 * Created by rbs on 2/9/16.
 */

oTable = $('#list-data').DataTable( {
    "paging":   false,
    "ordering": false,
    "info":     false
});

$(document).on('change', '.vote', function() {
    var maleVoter       = parseInt($('#votecenter_maleVoter').val()  != '' ? $('#votecenter_maleVoter').val() : 0 );
    var femaleVoter     = parseInt($('#votecenter_femaleVoter').val()  != '' ? $('#votecenter_femaleVoter').val() : 0 );
    var otherVoter      = parseInt($('#votecenter_otherVoter').val()  != '' ? $('#votecenter_otherVoter').val() : 0 );
    var totalVote       = (maleVoter + femaleVoter + otherVoter);
    $('#totalVote').val(totalVote);
});

$(document).on('change', '.voteCount', function() {
    var id = $(this).attr("data-id");
    var maleVoter       = parseInt($('#maleVoter-' + id).val()  != '' ? $('#maleVoter-' + id).val() : 0 );
    var femaleVoter     = parseInt($('#femaleVoter-' + id).val()  != '' ? $('#femaleVoter-' + id).val() : 0 );
    var otherVoter      = parseInt($('#otherVoter-' + id).val()  != '' ? $('#otherVoter-' + id).val() : 0 );
    var totalVoter = (maleVoter + femaleVoter + otherVoter);
    $('#totalVoter-' + id).val(totalVoter);
    if (totalVoter > 0){
        var url = Routing.generate('election_matrix_update',{'matrixId':id,'maleVoter':maleVoter,'femaleVoter':femaleVoter,'otherVoter':otherVoter});
        setTimeout(submitCountVote(id,url),3000)
    }
});

function submitCountVote(id,url) {

    $.ajax({
        url: url,
        type: 'POST',
        success: function (response) {
            obj = JSON.parse(response);
            $('.totalMaleVote-'+ obj['candidateId']).html(obj['maleVote']);
            $('.totalFemaleVote-'+ obj['candidateId']).html(obj['femaleVote']);
            $('.totalOtherVote-'+ obj['candidateId']).html(obj['otherVote']);
            $('.totalCandidateVote-'+ obj['candidateId']).html(obj['totalVote']);
            if(obj['msg'] !== 'success'){
                alert(obj['msg']);
                $('#totalVoter-'+id).val(obj['centerVote']).focus();
            }
        }
    });
}

$(document).on('change', '.centerCandidateTotalVote', function() {

    var id = $(this).attr("data-id");
    var centerCandidateVote   = parseInt($('#totalVoter-' + id).val()  != '' ? $('#totalVoter-' + id).val() : 0 );
    var url = Routing.generate('election_matrix_centercandidatetotalvoteupdate', {
        'matrixId': id,
        'centerCandidateVote': centerCandidateVote
    });
    setTimeout(submitCountVote(id,url),1000)
});




$(document).on('change', '.resultTotalVote , .resultInvalidVote', function() {

    var id = $(this).attr("data-id");
    var resultTotalVote     = parseInt($('#resultTotalVote-' + id).val()  != '' ? $('#resultTotalVote-' + id).val() : 0 );
    var resultInvalidVote   = parseInt($('#resultInvalidVote-' + id).val()  != '' ? $('#resultInvalidVote-' + id).val() : 0 );
    var process             = $('#process-' + id).val()  != '' ? $('#process-' + id).val() : '' ;
    var url = Routing.generate('election_matrix_cenetrvoteupdate', {
        'centerId': id,
        'resultTotalVote': resultTotalVote,
        'resultInvalidVote': resultInvalidVote,
        'process': process
    });
    $.ajax({
        url: url,
        type: 'POST',
        success: function (response) {
            obj = JSON.parse(response);
            $('.totalCastVote').html(obj['resultTotalVote']);
            $('.totalInvalidVote').html(obj['resultInvalidVote']);
            $('.activeVoteCenter').html(obj['activeVoteCenter']);
            $('.holdVoteCenter').html(obj['holdVoteCenter']);
            $('.rejectedVoteCenter').html(obj['rejectedVoteCenter']);
            if(obj['msg'] !== 'success'){
                alert(obj['msg']);
                $('#resultTotalVote-'+id).val(obj['centerTotalVote']).addClass('input-error');
                $('#resultInvalidVote-'+id).val(obj['centerInvalidVote']).addClass('input-error');
            }
        }
    });
});

$(document).on('change', '.process', function() {

    var id = $(this).attr("data-id");

    var resultTotalVote     = parseInt($('#resultTotalVote-' + id).val()  != '' ? $('#resultTotalVote-' + id).val() : 0 );
    var resultInvalidVote   = parseInt($('#resultInvalidVote-' + id).val()  != '' ? $('#resultInvalidVote-' + id).val() : 0 );
    var process             = $('#process-' + id).val()  != '' ? $('#process-' + id).val() : '' ;
    var url = Routing.generate('election_matrix_cenetrvoteupdate', {
        'centerId': id,
        'resultTotalVote': resultTotalVote,
        'resultInvalidVote': resultInvalidVote,
        'process': process
    });
    $.ajax({
        url: url,
        type: 'POST',
        success: function (response) {
            obj = JSON.parse(response);
            $('.totalCastVote').html(obj['resultTotalVote']);
            $('.totalInvalidVote').html(obj['resultInvalidVote']);
            $('.activeVoteCenter').html(obj['activeVoteCenter']);
            $('.holdVoteCenter').html(obj['holdVoteCenter']);
            $('.rejectedVoteCenter').html(obj['rejectedVoteCenter']);

        }
    });
});

$('form').on('keypress', '.inputs', function (e) {

    if (e.which === 13) {
        var inputs = $(this).parents("form").eq(0).find("input,select");
        var idx = inputs.index(this);
        if (idx == inputs.length - 1) {
            inputs[0].select()
        } else {
            inputs[idx + 1].focus(); //  handles submit buttons
        }
        return false;
    }
});



var form = $("#familyForm").validate({

    rules: {

        "#name": {required: true},
        "#mobile": {required: false},
        "#nid": {required: false},
        "#relation": {required: true}
    },

    messages: {

        "#name":"Enter name",
        "#mobile":"Enter mobile",
        "#nid":"Enter nid",
        "#relation":"Enter relation"
    },
    tooltip_options: {

        "#name": {placement:'top',html:true},
        "#mobile": {placement:'top',html:true},
        "#nid": {placement:'top',html:true},
        "#relation": {placement:'top',html:true}
    },

    submitHandler: function(form) {

        $.ajax({
            url         : $('form#familyForm').attr( 'action' ),
            type        : $('form#familyForm').attr( 'method' ),
            data        : new FormData($('form#familyForm')[0]),
            processData : false,
            contentType : false,
            success: function(response){
                $('form#familyForm')[0].reset();
                $('#familyMembers').html(response);
            }
        });
    }
});

var committees = $("#memberCommittee").validate({
     submitHandler: function(committees) {
        $.ajax({
            url         : $('form#memberCommittee').attr( 'action' ),
            type        : $('form#memberCommittee').attr( 'method' ),
            data        : new FormData($('form#memberCommittee')[0]),
            processData : false,
            contentType : false,
            success: function(response){
                if(response === 'invalid'){
                    alert('Already this member is added.');
                }else{
                    $("#committee_member").select2().select2("val","").select2('open');
                    $('#memberCommittees').html(response);
                    $('form#memberCommittee')[0].reset();
                    $('#addMember').html("<i class='icon-save'></i> Add");
                    $('.committeeButton').attr("disabled", false);
                }
            }
        });
    }
});

var event = $("#memberEvent").validate({
     submitHandler: function(event) {
        $.ajax({
            url         : $('form#memberEvent').attr( 'action' ),
            type        : $('form#memberEvent').attr( 'method' ),
            data        : new FormData($('form#memberEvent')[0]),
            processData : false,
            contentType : false,
            success: function(response){
                $('#memberEvents').html(response);
                $('form#memberEvent')[0].reset();
            }
        });
    }
});

var memberVotecenter = $("#memberVotecenter").validate({
     submitHandler: function(memberVotecenter) {
        $.ajax({
            url         : $('form#memberVotecenter').attr( 'action' ),
            type        : $('form#memberVotecenter').attr( 'method' ),
            data        : new FormData($('form#memberVotecenter')[0]),
            processData : false,
            contentType : false,
            success: function(response){
                $('#memberCommittees').html(response);
                $('form#memberVotecenter')[0].reset();
            }
        });
    }
});

var memberPooling = $("#memberPooling").validate({
     submitHandler: function(memberPooling) {
        $.ajax({
            url         : $('form#memberPooling').attr( 'action' ),
            type        : $('form#memberPooling').attr( 'method' ),
            data        : new FormData($('form#memberPooling')[0]),
            processData : false,
            contentType : false,
            success: function(response){
                $('#memberPoolings').html(response);
                $('form#memberPooling')[0].reset();
            }
        });
    }
});

