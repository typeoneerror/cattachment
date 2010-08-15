<?php

/**
 * Find attachments related to a post by type/category.
 *
 * @param string $type  The type of images to look for.
 * @param int $id       ID of post to get attachments for.
 * @return array  Wordpress results list of posts.
 */
function get_the_attachments($type = null, $id = 0)
{
    global $wpdb;
    $post = &get_post($id);
    $id = isset($post->ID) ? (int) $post->ID : (int) $id;

    if (!empty($type))
    {
        $type = strtolower(preg_replace("/ /i", "_", $type));
    }

    $sql = "SELECT p.*, m.meta_value " . CATTACHMENT_GALLERY_CATEGORY_FIELD_NAME . "
            FROM {$wpdb->posts} p
            LEFT JOIN {$wpdb->postmeta} m ON
                (p.id = m.post_id AND m.meta_key = '" . CATTACHMENT_GALLERY_CATEGORY_METADATA . "')
            WHERE p.post_parent = {$id}
            AND p.post_type = 'attachment'
            AND p.post_status != 'trash'
            " . (!empty($type) ? "AND m.meta_value = '%s'" : "") . "
            ORDER BY p.menu_order ASC";

    $statement = $wpdb->prepare($sql, $type);
    $results = $wpdb->get_results($statement);
    return $results;
}