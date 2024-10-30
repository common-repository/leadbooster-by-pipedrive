<?php
// Adds the LeadBooster Chatbot by Pipedrive Javascript snippet to the <head> tag
add_action('wp_head', 'pipedrive_add_embed_code');

function pipedrive_add_embed_code()
{
    // Ignore feed, robots or trackbacks
    if (is_feed() || is_robots() || is_trackback()) {
        return;
    }

    $options = get_option('Pipedrive_settings');
    if (is_home()) {
    	$current_page_id = (int)get_option('page_on_front');
    } else {
    	$current_page_id = get_the_ID();
    }

    // If options is empty then exit
    if(empty($options)) {
        return;
    }

    // Check to see if LeadBooster Chatbot by Pipedrive is enabled
    if (esc_attr($options['pipedrive_enabled']) == "yes")
    {
        if ($current_page_id == 0) {
        	$pipedrive_tag = $options['pipedrive_widget_code_global'];
        } else {
	        if (isset($options['pipedrive_widget_code_page_' . $current_page_id]) && $options['pipedrive_widget_code_page_' . $current_page_id] != '') {
	        	$pipedrive_tag = $options['pipedrive_widget_code_page_' . $current_page_id];
	        } else {
		        $pipedrive_tag = $options['pipedrive_widget_code_global'];;
	        }
        }

        // Insert tracker code
        if ($pipedrive_tag !== '') {
            echo "<!-- Start of the LeadBooster Chatbot by Pipedrive code -->\n";
            echo $pipedrive_tag . "\n";
            echo "<!-- End of the LeadBooster Chatbot by Pipedrive Code. -->\n";
        }
    }
}
