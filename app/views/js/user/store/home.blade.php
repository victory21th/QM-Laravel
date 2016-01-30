<script>
$(document).ready(function() {
    $('#layerslider').layerSlider({
        skinsPath : "/assets/plugin_slider/skins/",
        skin : 'fullwidth',
        thumbnailNavigation : 'show',
        navButtons : true,
        navStartStop : false,
        hoverPrevNext : true,
        responsive : true,
        responsiveUnder : 0,
        sublayerContainer : 960,
        showCircleTimer  : true,
        autoPauseSlideshow : false,
        autoStart : true
    });
    $('#layerslider').layerSlider('stop');

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