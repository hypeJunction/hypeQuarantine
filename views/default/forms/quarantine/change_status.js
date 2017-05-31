define(function (require) {

    var $ = require('jquery');

    $(document).on('change', '.quarantine-status-select', function () {
        var $elem = $(this);
        var $delete = $elem.closest('form').find('.quarantine-delete-field');

        if ($elem.val() == '-7') {
            $delete.removeClass('hidden');
        } else {
            $delete.addClass('hidden');
        }
    });
});