<?php
// Register settings
function pipedrive_register_settings()
{
    register_setting('Pipedrive_settings_group', 'Pipedrive_settings');
}
add_action('admin_init', 'pipedrive_register_settings');

// Delete settings on uninstall
function pipedrive_uninstall()
{
    delete_option('Pipedrive_settings');
}
register_uninstall_hook(__FILE__, 'pipedrive_uninstall');
