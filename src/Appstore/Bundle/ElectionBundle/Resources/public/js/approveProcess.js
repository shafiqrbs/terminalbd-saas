/*
var doc = new jsPDF();

$("#pdfDownloader").click(function() {

    var doc = new jsPDF('p', 'pt', 'a4', true);
    doc.fromHTML($('#card-content').get(0), 15, 15, {
        'width': 500
    }, function (dispose) {
        doc.save('thisMotion.pdf');
    });
});
*/


$( ".date-picker" ).datepicker({
    dateFormat: "dd-mm-yy"
});

$( ".dateCalendar" ).datepicker({
    dateFormat: "dd-mm-yy",
    changeMonth: true,
    changeYear: true,
});

$( ".dob" ).datepicker({
    dateFormat: "dd-mm-yy",
    changeMonth: true,
    changeYear: true,
    yearRange: "-100:+0",
});

var itemArr = $.cookie('memberIds') ? $.cookie('memberIds').split(',') : [];

$('body').on('change', 'input.memberId', function(el) {
    console.log(itemArr);
    var val = $(this).val();
    if($(this).prop('checked')) {
        itemArr.push(val);
    }else{
        var index = itemArr.indexOf(val);
        if (index > -1) {
            itemArr.splice(index, 1);
        }
    }
    $.cookie('memberIds', itemArr, {path: '/'});
});


$(document).on( "click", ".toggle-btn", function(e){
    $('#hide').slideToggle(2000);
    $("i", this).toggleClass("fa fa-angle-double-up fa fa-angle-double-down");
});

$(document).on('change', '#dashboardMember', function() {

    var id  = $(this).val();
    var url = $('form#memberForm').attr('action');
    $.get(url,{'id':id}, function(response) {
        $('#search-content').html(response).show();
    });
});

$(document).on('change', '#dashboardVoter', function() {

    var id  = $(this).val();
    var url = $('form#voterForm').attr('action');
    $.get(url,{'id':id}, function(response) {
        $('#search-content').html(response).show();
    });
});

$(document).on('change', '#dashboardVoteCenter', function() {

    var id  = $(this).val();
    var url = $('form#voteCenterForm').attr('action');
    $.get(url,{'id':id}, function(response) {
        $('#search-content').html(response).show();
    });
});

$(document).on('change', '#dashboardCommittee', function() {

    var id  = $(this).val();
    var url = $('form#committeeForm').attr('action');
    $.get(url,{'id':id}, function(response) {
        $('#search-content').html(response).show();
    });
});

$(document).on('change', '#dashboardCampaign', function() {

    var id  = $(this).val();
    var url = $('form#campaignForm').attr('action');
    $.get(url,{'id':id}, function(response) {
        $('#search-content').html(response).show();
    });
});

$(document).on('change', '#dashboardAnalysis', function() {

    var id  = $(this).val();
    var url = $('form#analysisForm').attr('action');
    $.get(url,{'id':id}, function(response) {
        $('#search-content').html(response).show();
    });
});

$(document).on('click', '.tabLi', function() {
    $('#search-content').html('').hide();
});

$(document).on('click', '.view', function() {

    var url = $(this).attr("data-url");
    var title = $(this).attr("data-title");
    $('.dialogModal_header').html(title);
    $('.dialog_content').dialogModal({
        topOffset: 0,
        top: 0,
        type: '',
        onOkBut: function(event, el, current) {},
        onCancelBut: function(event, el, current) {},
        onLoad: function(el, current) {
            $.ajax({
                url:url,
                async: true,
                success: function (response) {
                    el.find('.dialogModal_content').html(response);
                }
            });
        },
        onClose: function(el, current) {},
        onChange: function(el, current) {}
    });

});


$(document).on("click", ".delete , .remove", function() {

    var url = $(this).attr('data-url');
    var id = $(this).attr('data-id');
    $('#confirm-content').confirmModal({
        topOffset: 0,
        top: '25%',
        onOkBut: function(event, el) {
            $.get(url, function( data ) {
                if(data === 'success'){
                    $('#remove-'+id).remove();
                }
            });
        }
    });
});


$(document).on("click", ".approve", function() {
    var url = $(this).attr('data-url');
    var id = $(this).attr('data-id');
    $('#confirm-content').confirmModal({
        topOffset: 0,
        top: '25%',
        onOkBut: function(event, el) {
            $.get(url, function( data ) {
                //location.reload();
            });
        }
    });
});

$('#addMasterItem').click(function(e) {

    var url =  $('#masterProduct').attr("action");
    $('#confirm-content').confirmModal({
        topOffset: 0,
        top: '25%',
        onOkBut: function(event, el) {
            $.ajax({
                url: url,
                type: 'POST',
                data : $('#masterProduct').serialize(),
                success: function (response) {
                    location.reload();
                },
            });
        }
    });
    e.preventDefault();
});


$( ".sms" ).click(function() {
    var url = $(this).attr('data-url');
    var id = $(this).attr('data-id');
    var title = $(this).attr('data-title');
    $.MessageBox({
        input    : true,
        message  : "Send SMS To "+title
    }).done(function(data){
        if ($.trim(data)) {
            $.get(url,{sms : data});
        }
    });
});


$(".select2Location").select2({

    ajax: {
        url: Routing.generate('election_location_all_search'),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_location_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 2
});

$(".select2Thana").select2({

    ajax: {
        url: Routing.generate('election_location_search',{'type':'thana'}),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_location_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 1
});

$(".select2Union").select2({

    ajax: {
        url: Routing.generate('election_location_search',{'type':'union'}),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_location_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 1
});

$(".select2Ward").select2({

    ajax: {
        url: Routing.generate('election_location_search',{'type':'ward'}),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_location_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 1
});

$(".select2Village").select2({

    ajax: {

        url: Routing.generate('election_location_search',{'type':'village'}),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_location_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 1
});

$(".select2VoteCenter").select2({

    ajax: {

        url: Routing.generate('election_location_search',{'type':'vote-center'}),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_location_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 1
});

$(".select2ElectionVoteCenter").select2({

    ajax: {

        url: Routing.generate('election_votecenter_search'),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_location_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 1
});

$(".select2Member").select2({

    ajax: {

        url: Routing.generate('election_member_search'),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_member_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 1
});

$(".select2Campaign").select2({

    ajax: {

        url: Routing.generate('election_event_search'),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_location_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 1
});

$(".select2Committee").select2({

    ajax: {
        url: Routing.generate('election_committee_search'),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_location_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 1
});

$(".select2Analysis").select2({

    ajax: {

        url: Routing.generate('election_campaign_search'),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_location_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 1
});

/*$(".select2Mobile").select2({

    ajax: {

        url: Routing.generate('election_member_mobile_search'),
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
        var name = $(element).val();
        $.ajax(Routing.generate('election_member_name', { name : name}), {
            dataType: "json"
        }).done(function (data) {
            return  callback(data);
        });
    },
    allowClear: true,
    minimumInputLength: 2
});*/

