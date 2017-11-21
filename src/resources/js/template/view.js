var $iframe = $('#template-iframe');
$iframe.ready(function() {
    $iframe.contents().find("body").append(content);
    $iframe.css('height', ($iframe.contents().find("body").height()));
});