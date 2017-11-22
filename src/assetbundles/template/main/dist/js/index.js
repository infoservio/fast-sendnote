$(document).ready(function() {
    $('#template-table').DataTable({
        "order": [[ 0, "desc" ]]
    });

    $('.delete-template').click(function () {
        if (confirm("Are you sure?")) {
            $.ajax({
                type: "POST",
                url: 'mail-manager/delete',
                data: { id: $(this).attr('value')},
            });
        }
        return false;
    });
});