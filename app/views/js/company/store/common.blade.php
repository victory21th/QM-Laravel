<script>
    $('textarea#description').liveEdit({
    	fileBrowser: "{{ asset('assets/wysiwyg/assetmanager/asset.php?company_id='.Session::get('company_id')) }}",
        height: 550,
        groups: [
                ["group1", "", ["Bold", "Italic", "Underline", "ForeColor", "RemoveFormat"]],
                ["group2", "", ["Bullets", "Numbering", "Indent", "Outdent"]],
                ["group3", "", ["Paragraph", "FontSize"]],
                ["group4", "", ["LinkDialog", "ImageDialog", "TableDialog", "SourceDialog"]],
                ]
    });
    $('#description').data('liveEdit').startedit();
    
    function onSetDescritpion() {
        var description = $('#description').data('liveEdit').getXHTML();
        $("textarea#description").val(description);
        return true;
    }
</script>