<script>
var data1 = [], data2 = [], data3 = [], reasons = [];

<?php 
$i = 0;
foreach ($hourly_statistics as $item) {?>
    var temp = [{{ $item->hr }}, {{ $item->cnt }}];
    data1[{{ $i++ }}] = temp;
<?php } ?>

<?php 
$i = 0;
foreach ($daily_statistics as $item) {?>
    var temp = [Date.UTC({{ $item->y }}, {{ $item->m }}, {{ $item->d }}), {{ $item->cnt }}];
    data2[{{ $i++ }}] = temp;
<?php } ?>

<?php 
    $i = 0;
    foreach ($reason_statistics as $item) {?>
    var temp = ['{{ $item->name }}', {{ $item->cnt }}];
    data3[{{ $i }}] = temp;
    reasons[{{ $i++ }}] = '{{ $item->name}}';
<?php } ?>

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

    $('#container1').highcharts({
        chart: { type: 'spline' },
        title: { text: 'Hourly Tickets' },
        xAxis: { title: { text: 'Hour' }, categories: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23] },
        yAxis: { title: {text: ' '}, min: 0 },
        series: [{name: 'Hourly Tickets', data: data1}]
    });

    $('#container2').highcharts({
        chart: { type: 'spline' },
        title: { text: 'Daily Tickets' },
        xAxis: { type: 'datetime', dateTimeLabelFormats: { month: '%e. %b', year: '%b' }, title: { text: 'Date' } },
        yAxis: { title: {text: ' '}, min: 0 },
        tooltip: { headerFormat: '<b>{series.name}</b><br>', pointFormat: '{point.x:%e. %b}: {point.y:.0f}' },
        series: [{name: 'Daily Tickets', data: data2}]
    });

    $('#container3').highcharts({
        chart: { type: 'spline' },
        title: { text: 'Reason Tickets' },
        xAxis: { title: { text: 'Reason' }, categories: reasons },
        yAxis: { title: {text: ' '}, min: 0 },
        series: [{name: 'Reason Tickets', data: data3}]
    });    
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