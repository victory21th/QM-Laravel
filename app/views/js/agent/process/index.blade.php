<script>
var ajaxNext = false;
$(document).ready(function() {
    $("button#js-btn-next").click(function() {
        $("#js-input-is-next").val(1);
        if ($("input[name='process_id']").val() == '') {
            asyncNext('', $("#js-input-is-next").val());
        } else {
            $("button#js-btn-ticket-type").removeClass('btn-info').addClass('btn-default');
            $("button#js-btn-ticket-type").last().addClass('btn-info').removeClass('btn-default');
            $("div#modal-ticket-types").modal();
        }
    });
    
    $("button#js-btn-stop").click(function() {
        $("button#js-btn-ticket-type").removeClass('btn-info').addClass('btn-default');
        $("button#js-btn-ticket-type").last().addClass('btn-info').removeClass('btn-default');
        $("div#modal-ticket-types").modal();
        $("#js-input-is-next").val(0);
    });

    $("button#js-btn-ticket-type").click(function() {
        $("button#js-btn-ticket-type").removeClass('btn-info').addClass('btn-default');
        $(this).addClass('btn-info');
    });

    $("button#js-btn-submit").click(function() {
        if ($("button#js-btn-ticket-type.btn-info").size() == 0) {
            bootbox.alert("Please select ticket type");
            return;
        }
        asyncNext($("button#js-btn-ticket-type.btn-info").attr('data-id'), $("#js-input-is-next").val());
    });

    $("a#js-a-signout").click(function(event) {
        if ($("#process_id").val() != '') {
            event.preventDefault();
            var url = $(this).attr('href');
            bootbox.confirm("You are processing queue now<br/> Are you sure to logout?", function(result) {
                if (result) {
                    window.location.href = url;
                }
            });            
        }
    });     
});

function asyncNext(ticketType, isNext) {
    if (ajaxNext) {
        return;
    }
    ajaxNext = true;
    $.ajax({
        url: "{{ URL::route('agent.async.next') }}",
        dataType : "json",
        type : "POST",
        data : {ticket_type : ticketType, is_next : isNext},
        success : function(data) {
            ajaxNext = false;
            if (data.result === 'success') {
                $("div#js-div-status").addClass('hidden');
                
                $("div#js-div-current-queue-no").removeClass('hidden');
                $("div#js-div-last-queue-no").removeClass('hidden');
                $("span#js-span-current-queue-no").text(data.currentQueueNo);
                $("span#js-span-last-queue-no").text(data.lastQueueNo);
                
                $("div#js-div-stop").removeClass('hidden');
                if (isNext == 1) {
                    $("#process_id").val(data.processId);
                } else {
                    $("#process_id").val('');
                }
            } else {
                $("div#js-div-status").removeClass('hidden');
                
                $("div#js-div-current-queue-no").addClass('hidden');
                $("div#js-div-last-queue-no").addClass('hidden');
                $("span#js-span-status").text(data.msg);
                
                $("div#js-div-stop").addClass('hidden');
                $("#process_id").val('');
            }
            $("div#modal-ticket-types").modal('hide');
        }
    });
}
</script>
