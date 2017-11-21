$(function() {
    $('#template').redactor({
        fileUpload: 'upload.php',
        imageUpload: 'upload.php',
        imageManagerJson: '/src/images/images.json',
        plugins: [
            'filemanager',
            'fontcolor',
            'fontfamily',
            'fontsize',
            'imagemanager',
            'pagebreak',
            'source',
            'table',
            'video',
            'fullscreen'
        ],
        emptyHtml: template
    });

});