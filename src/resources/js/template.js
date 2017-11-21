$(document).ready(function() {
    $('#template-table').DataTable();

    $('#inputTemplateName').on('input',function(e){
        let slug = e.target.value.toLowerCase();
        slug = slug.replace(/\s/g, "-");
        if (slug[slug.length - 1] == '-') {
           slug = slug.substring(0, slug.length - 1);
        }
        $('#inputTemplateSlug').val(slug);
    });
});