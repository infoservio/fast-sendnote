(function () {
    var previous;
    var id;

    $("#settings-mailer").on('focus', function () {
        // Store the current value on focus and on change
        previous = this.value;
    }).change(function() {
        // Do something with the previous value after the change
        id = previous.replace(/\\/g, '-');
        $('#settings-' + id).addClass('hidden');

        previous = this.value;
        id = previous.replace(/\\/g, '-');
        $('#settings-' + id).removeClass('hidden');

    });
})();