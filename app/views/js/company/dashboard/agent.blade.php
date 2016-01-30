<script>
$(document).ready(function() {
    $('#startDate, #endDate').datepicker({format: 'yyyy-mm-dd'});
    
    var startDate = $("#startDate").val();
    var endDate = $("#endDate").val();
    
    $("#period").change(function() {
        var type = $(this).val();
        var startDate = new Date();
        var endDate = new Date();
        if (type == 0) {
            $("#startDate").val("");
            $("#endDate").val("");
        } else if (type == 3) {
            startDate.setDate(startDate.getDate() - 3 );
        } else if (type == 7) {
            startDate.setDate(startDate.getDate() - 7 );
        } else if (type == 30) {
            startDate.setMonth(startDate.getMonth() - 1 );
        } else if (type == 60) {
            startDate.setMonth(startDate.getMonth() - 2 );
        } else if (type == 90) {
            startDate.setMonth(startDate.getMonth() - 3 );
        } else if (type == 180) {
            startDate.setMonth(startDate.getMonth() - 6 );
        } else if (type == 365) {
            startDate.setYear(startDate.getFullYear() - 1 );
        }
        $("#startDate").val(getFormattedDate(startDate));
        $("#endDate").val(getFormattedDate(endDate));        
    });

    if (startDate == '' || endDate == '') {
        $("#period").val(7);
        $("#period").change();
    }
});

function getFormattedDate(date) {
    var year = date.getFullYear();
    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;
    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;
    return year + '-' + month + '-' + day;
}

function onValidate() {
    var startDate = $("#startDate").val();
    var endDate = $("#endDate").val();

    if (startDate == '' || endDate == '' || endDate < startDate) {
        alert('Please select Start Date & End Date Correctly.');
        return false;
    }
    return true;
}
</script>