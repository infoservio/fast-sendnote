var $oldiframe = $('#template-old-iframe');
$oldiframe.ready(function() {
    $oldiframe.contents().find("body").append(oldContent);
    $oldiframe.css('height', ($oldiframe.contents().find("body").height()));
});

var $newiframe = $('#template-new-iframe');
$newiframe.ready(function() {
    $newiframe.contents().find("body").append(newContent);
    $newiframe.css('height', ($newiframe.contents().find("body").height()));
});