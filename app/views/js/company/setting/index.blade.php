<script>
$(document).ready(function() {
    $('#js-div-color').colorpicker();
    $('#js-div-background').colorpicker();

    $('#start_time, #end_time').timepicker({
        minuteStep: 5,
        showInputs: false,
        disableFocus: true,
        showSeconds: true,
        showMeridian: false
    });         
});
</script>