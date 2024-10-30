<?php
// Adds the settings page to the wp-admin menu
add_action('admin_menu', 'pipedrive_create_menu');
function pipedrive_create_menu() {
    define('PIPEDRIVE_NAME', __('LeadBooster Chatbot by Pipedrive', 'leadbooster-by-pipedrive'));
    define('PIPEDRIVE_SHORTNAME', __('LeadBooster', 'leadbooster-by-pipedrive'));
    add_options_page(PIPEDRIVE_NAME, PIPEDRIVE_NAME, 'manage_options', 'pipedrive', 'pipedrive_settings_page');
}

// Adds link to the settings page to the top-level admin bar
if (isset(get_option('Pipedrive_settings')['pipedrive_admin_bar_enabled'])) {
    add_action('admin_bar_menu', 'pipedrive_add_link_to_admin_bar', 999);
}
function pipedrive_add_link_to_admin_bar()
{
    global $wp_version;
    global $wp_admin_bar;

	defined('PIPEDRIVE_NAME') or define('PIPEDRIVE_NAME', __('LeadBooster Chatbot by Pipedrive', 'leadbooster-by-pipedrive'));
	defined('PIPEDRIVE_SHORTNAME') or define('PIPEDRIVE_SHORTNAME', __('LeadBooster', 'leadbooster-by-pipedrive'));

    $pipedrive_icon = '<img src="' . PIPEDRIVE_ICON_PATH . '" style="width:auto;height:13px">';

    $args = array(
        'id' => 'pipedrive-admin-menu',
        'title' => '<span class="ab-icon" ' . ($wp_version < 3.8 && !is_plugin_active('mp6/mp6.php') ? ' style="margin-top: 3px;"' : '') . '>' . $pipedrive_icon . '</span><span class="ab-label">' . PIPEDRIVE_NAME . '</span>',
        'parent' => FALSE,
        'href' => admin_url('options-general.php?page=pipedrive'),
        'meta' => array('title' => PIPEDRIVE_NAME)
    );

    if (!file_exists(PIPEDRIVE_ICON_PATH_DIR)) {
        $args['title'] = '<span class="ab-label">' . PIPEDRIVE_NAME . '</span>';
    }

    $wp_admin_bar->add_node($args);
}

// Creates the settings page for the plugin
function pipedrive_settings_page() {
    $options = get_option('Pipedrive_settings');

    $pipedrive_activated = false;
    if (isset($options['pipedrive_enabled']) && esc_attr($options['pipedrive_enabled']) == 'yes') {
        $pipedrive_activated = true;
        wp_cache_flush();
    }
?>
<?php // Content of the actual settings page ?>
<div class="wrap">
    <form name="pipedrive-form" action="options.php" method="post" enctype="multipart/form-data">
        <?php settings_fields('Pipedrive_settings_group'); ?>
        <noscript>
            For full functionality of this page it is necessary to enable JavaScript. Here are the <a href="https://www.enable-javascript.com/"> instructions how to enable JavaScript in your web browser</a>.
        </noscript>

        <h1><?php echo PIPEDRIVE_NAME; ?></h1>
        <h3><?php echo __('Basic options', 'leadbooster-by-pipedrive'); ?></h3>

        <input type="button" name="pipedrive_delete_settings_from_db" id="pipedrive_delete_settings_from_db" class="button button-primary" value="<?php echo __('Delete settings from the database', 'leadbooster-by-pipedrive'); ?>" data-nonce="<?php echo wp_create_nonce('delete_pipedrive_settings'); ?>">
        <?php //Prints message if the plugin functions are enabled
        if (!$pipedrive_activated) { ?>
            <div id="status" class="disabled">
                <?php echo PIPEDRIVE_NAME . ' ' . __('is currently', 'leadbooster-by-pipedrive') . ' ' . '<strong>' . __('disabled', 'leadbooster-by-pipedrive') . '</strong>.'; ?>
            </div>
        <?php } elseif ($pipedrive_activated) { ?>
            <div id="status" class="enabled">
                <?php echo PIPEDRIVE_NAME . ' ' . __('is currently', 'leadbooster-by-pipedrive') . ' ' . '<strong>' . __('enabled', 'leadbooster-by-pipedrive') . '</strong>.'; ?>
            </div>
        <?php } ?>
        <?php do_settings_sections('Pipedrive_settings_group'); ?>

        <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
            <tr>
                <th>
                    <label for="pipedrive_enabled"><?php echo PIPEDRIVE_NAME . ' ' . __('is', 'leadbooster-by-pipedrive') . ':'; ?></label>
                </th>
                <td>
                    <?php
                    echo '<select name="Pipedrive_settings[pipedrive_enabled]"  id="pipedrive_enabled">\n';

                    echo '<option value="yes"';
                    if ($pipedrive_activated) { echo ' selected="selected"'; }
                    echo ">" . __('Enabled', 'leadbooster-by-pipedrive') . "</option>\n";

                    echo '<option value="no"';
                    if (!$pipedrive_activated) { echo ' selected="selected"'; }
                    echo ">" . __('Disabled', 'leadbooster-by-pipedrive') . "</option>\n";
                    echo "</select>\n";
                    ?>
                </td>
            </tr>

            <tr>
                <th>
                    <label for="pipedrive_admin_bar_enabled"><?php echo __('Show link to settings in admin bar menu', 'leadbooster-by-pipedrive') . ':'; ?></label>
                </th>
                <td>
                    <input type="checkbox" name="Pipedrive_settings[pipedrive_admin_bar_enabled]"  id="pipedrive_admin_bar_enabled" value="1" <?php checked('1', isset(get_option('Pipedrive_settings')['pipedrive_admin_bar_enabled'])) ?>>
                </td>
            </tr>
        </table>
        <table class="form-table" cellspacing="2" cellpadding="5" width="100%">
            <tr>
                <th class="valign-top">
                    <label for="Pipedrive_widget_code_global"><?php echo __('Global Pipedrive JS code snippet:', 'leadbooster-by-pipedrive'); ?></label>
                </th>
                <td>
                    <textarea rows="5" cols="50" placeholder="<!-- <?php echo __('Insert the Pipedrive tag here', 'leadbooster-by-pipedrive'); ?> -->" name="Pipedrive_settings[pipedrive_widget_code_global]"><?php echo esc_attr(isset($options['pipedrive_widget_code_global']) ? $options['pipedrive_widget_code_global'] : ''); ?></textarea>
                    <p style="margin: 5px 10px;"><?php echo __('Enter your Pipedrive JS code snippet above.', 'leadbooster-by-pipedrive'); ?></p>
                    <p style="margin: 0 10px;"><?php echo __('To find this snippet, log in to your account on <a href="https://www.pipedrive.com/?utm_source=leadbooster_wordpress_plugin&utm_medium=growth" target="_blank" rel="nofollow">pipedrive.com</a>. In your company’s profile, go to <strong>Pipedrive Settings > LeadBooster</strong>. Then, go to the <strong>Playbook</strong> of your choice and you will find the WordPress snippet under <strong>Embed</strong>.', 'leadbooster-by-pipedrive'); ?></p>
                </td>
            </tr>
        </table>

        <h3><?php echo __('Options for specific pages', 'leadbooster-by-pipedrive'); ?></h3>
        <div id="single-pages-section">
            <?php
            //Query of all pages
            $pages_query_args = array(
	            'post_type' => 'page',
				'suppress_filters' => true,
                'nopaging' => true,
                'lang' => 'all'
            );

            $pages_query = new WP_Query($pages_query_args);

            //Prints all pages to the settings page through the standard loop
            if ($pages_query->have_posts()) {
	            echo '<div class="hidden filter-input-placeholder">' . __('Filter page list...', 'leadbooster-by-pipedrive') . '</div>';
	            echo '<table class="form-table">';
	            while ($pages_query->have_posts()) {
                    $pages_query->the_post();
                    //Get language of a post (page)
                    $pageLang = '';
                    if (getPageLanguage(get_the_ID()) !== null && !empty(getPageLanguage(get_the_ID()))) {
                        $getPageLanguage = getPageLanguage(get_the_ID());
                        if (is_array($getPageLanguage)) {
                            if ($getPageLanguage[0] == 'Polylang' || $getPageLanguage[0] == 'WPML') {
                                $pageLang = '<span class="page-lang">' . ' | ' . '<span title="' . $getPageLanguage[2] . '">' . $getPageLanguage[1] . '</span>' . '</span>';
                            }
                        } else {
                            $pageLang = '<span class="page-lang">' . ' | ' . $getPageLanguage . '</span>';
                        }
                    }
		            if (isset($options['pipedrive_widget_code_page_' . get_the_ID()])) { //Check if there is a snippet set in DB for this page
			            echo '<tr><td class="page-title">' . get_the_title() . $pageLang . '</td><td><textarea rows="5" cols="50" placeholder="<!--' . __('Insert the Pipedrive tag here', 'leadbooster-by-pipedrive') . '-->" name="Pipedrive_settings[pipedrive_widget_code_page_' . get_the_ID() . ']">' . esc_attr($options['pipedrive_widget_code_page_' . get_the_ID()]) . '</textarea></td></tr>';
		            } else {
			            echo '<tr><td class="page-title">' . get_the_title() . $pageLang . '</td><td><textarea rows="5" cols="50" placeholder="<!--' . __('Insert the Pipedrive tag here', 'leadbooster-by-pipedrive') . '-->" name="Pipedrive_settings[pipedrive_widget_code_page_' . get_the_ID() . ']"></textarea></td></tr>';
                    }
	            }
	            echo '</table>';
            }
            ?>
        </div>
        <p class="submit">
            <?php echo submit_button(); ?>
        </p>
    </form>
</div>
<?php }

add_action('wp_ajax_pipedrive_delete_settings_from_db', 'pipedrive_delete_settings_from_db');

function pipedrive_delete_settings_from_db() {
	check_ajax_referer('delete_pipedrive_settings', '_nonce');
	if ($_POST['delete'] == 'delete' && current_user_can('manage_options')) {
		delete_option('Pipedrive_settings');
		echo 'deleted';
    } else {
	    echo 'not deleted';
    }
	wp_die();
}


function getPageLanguage($post_id) {
    $lang = '';

    if (function_exists('wpml_object_id_filter') || function_exists('icl_object_id')) {
        $wpml_object = wpml_get_language_information($post_id);
        $lang = array();
        $lang[0] = 'WPML';
        $lang[1] = strtoupper($wpml_object['language_code']);
        $lang[2] = $wpml_object['display_name'];
    }
    if (class_exists('Polylang')) {
        $lang = array();
        $lang[0] = 'Polylang';
        $lang[1] = strtoupper(pll_get_post_language($post_id));
        $lang[2] = pll_get_post_language($post_id, 'name');
    }


    if ($lang != '' && !empty($lang)) {
        return $lang;
    }
    return null;
}
?>
