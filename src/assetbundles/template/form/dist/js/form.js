$(document).ready(function() {
    $('#inputTemplateName').on('input', function (e) {
        let slug = e.target.value.toLowerCase();
        slug = slug.replace(/\s/g, "-");
        if (slug[slug.length - 1] == '-') {
            slug = slug.substring(0, slug.length - 1);
        }

        if (slug.length > 20) {
            slug = slug.substring(0, 20);
        }

        $('#inputTemplateSlug').val(slug);
    });

    $('#inputTemplateSlug').on('input', function (e) {
        let slug = $(this).val().toLowerCase();
        slug = slug.replace(/\s/g, "-");
        if (slug[slug.length - 1] == '-' && slug[slug.length - 2] == '-') {
            slug = slug.substring(0, slug.length - 1);
        }

        $(this).val(slug);
    });

    var form = $('#template-form').parsley();

    if (typeof errors !== undefined && errors) {
        for(key in errors) {
            var FieldInstance = $('[name=' + key + ']').parsley(),
                errorName = key + '-custom';
            /**
             * You'll probably need to remove the error first, so the error
             * doesn't show multiple times
             */
            window.ParsleyUI.removeError(FieldInstance, errorName);

            // now display the error
            window.ParsleyUI.addError(FieldInstance, errorName, errors[key]);
        }
    }
});