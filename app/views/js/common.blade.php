<script>
$(document).ready(function() {
    $("a#js-a-delete").click(function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        bootbox.confirm("Are you sure?", function(result) {
            if (result) {
                window.location.href = url;
            }
        });
    });
});
</script>