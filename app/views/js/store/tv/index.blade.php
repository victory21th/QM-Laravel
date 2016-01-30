<script>
$(document).ready(function() {
    asyncStatus();
    if ($("div#js-div-slider").size() > 0) {
        slider();
        sliding();
    }    
});

function slider() {
    if ($("div#js-div-slider").css("display") == "none") {
        $("div#js-div-tv").fadeOut(1000);
        $("div#js-div-slider").fadeIn(1000);
        
    } else {
        $("div#js-div-slider").fadeOut(1000);        
        $("div#js-div-tv").fadeIn(1000);
    }
    setTimeout(slider, 12000);
}

function sliding() {
    $("div#js-div-slider > div:gt(0)").hide();
    setInterval(function() { 
        $('div#js-div-slider > div:first').fadeOut(1000)
          .next().fadeIn(1000)
          .end().appendTo('div#js-div-slider');
      },  4000);
}

function asyncStatus() {
    $.ajax({
        url: "{{ URL::route('store.async.status') }}",
        dataType : "json",
        type : "POST",
        data : {token : $("#token").val()},
        success : function(data) {
           if (data.result == 'success') {
               $("div#js-div-failed").addClass('hide');
               $("div#js-div-success").removeClass('hide');               

               $("span#js-span-last").text(data.last);
               $("span#js-span-current").text(data.current);
               $("span#js-span-waiting-time").text(data.waitingTime);
               $("span#js-span-counter").text(data.counter);
           } else {
               $("div#js-div-failed").removeClass('hide');
               $("div#js-div-success").addClass('hide');
               $("div#js-div-failed").text(data.msg);
               $("span#js-span-last").text(data.last);
               $("span#js-span-waiting-time").text("---");
           }
        }
    });
    setTimeout(asyncStatus, 10000);
}
</script>
