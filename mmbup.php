<?php

/**
 * Plugin Name: MM WP Bulk Update Posts
 * Plugin URI: https://budiharyono.id/
 * Description: Bulk update posts
 * Version: 1.0.0
 */

defined('ABSPATH') or die('No script kiddies please!');


function mm_add_bulk_update_action($bulk_actions)
{
    $bulk_actions['mm_bulk_update_date'] = 'Update Publish Date';
    return $bulk_actions;
}
add_filter('bulk_actions-edit-post', 'mm_add_bulk_update_action');

function mm_handle_bulk_update_date($redirect_to, $doaction, $post_ids)
{
    if ($doaction !== 'mm_bulk_update_date') {
        return $redirect_to;
    }

    foreach ($post_ids as $post_id) {
        $post_data = array(
            'ID' => $post_id,
            'post_date' => current_time('mysql'),
            'post_date_gmt' => current_time('mysql', 1)
        );
        wp_update_post($post_data);
    }

    $redirect_to = add_query_arg('mm_bulk_updated', count($post_ids), $redirect_to);
    return $redirect_to;
}
add_filter('handle_bulk_actions-edit-post', 'mm_handle_bulk_update_date', 10, 3);

function mm_bulk_update_admin_notice()
{
    if (empty($_REQUEST['mm_bulk_updated'])) {
        return;
    }

    $updated_count = intval($_REQUEST['mm_bulk_updated']);
    printf('<div id="message" class="updated fade"><p>' . __('%s posts updated.', 'your-textdomain') . '</p></div>', $updated_count);
}
add_action('admin_notices', 'mm_bulk_update_admin_notice');
