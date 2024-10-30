jQuery('document').ready(function($){
    $('#single-pages-section table').fancyTable({
        pagination: true,
        searchable: true,
        globalSearch: true,
		globalSearchExcludeColumns: [1],
        perPage: 6,
        inputPlaceholder: $('#single-pages-section .filter-input-placeholder').text(),
		sortColumn: 0
    });

    $('#pipedrive_delete_settings_from_db').click(function() {
        var data = {
            action: 'pipedrive_delete_settings_from_db',
            delete: 'delete',
            _nonce: $('#pipedrive_delete_settings_from_db').data('nonce'),
        };

        $.post(ajaxurl, data, function(response) {
            if (response === 'deleted') {
                console.log('LeadBooster Chatbot by Pipedrive settings has been deleted from the database');
                location.reload();
            } else {
                console.log('There was an error during deletion of LeadBooster Chatbot by Pipedrive settings from the database');
                location.reload();
            }
        });
    });
});
