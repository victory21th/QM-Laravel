<script>
var objSlider;
$(document).ready(function() {
    $('#js-slider-waiting-time').slider({});
    
    objSlider = $('#js-slider-waiting-time').on('slide', function(obj) {
        $("div#js-div-range-waiting-min").text(obj.value[0] + " : " + obj.value[1] + " min");
        $("#js-waiting-time-min").val(obj.value[0]);
        $("#js-waiting-time-max").val(obj.value[1]);
        $("#is_all").prop('checked', false);
    });
        
    $("button#js-btn-apply").click(function() {
        var storeId = $(this).attr('data-id');
        $.ajax({
            url: "{{ URL::route('user.async.queue.apply') }}",
            dataType : "json",
            type : "POST",
            data : {storeId : storeId},
            success : function(data) {
               if (data.result == 'success') {
                   bootbox.alert(data.msg);
               } else {
                   bootbox.alert(data.msg, function() {
                       if (data.code == 'CD01') {
                           window.location.href = "{{ URL::route('user.auth.login') }}";
                       }
                   });
               }
            }
        });
    });
});
</script>