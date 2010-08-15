<?php

/**
 * Includes the admin page for Cattachment.
 *
 * @return void
 */
function cattachment_admin()
{
    include_once "cattachment-admin-page.php";
}

/**
 * Adds the menu item to "Media" for Cattachment.
 *
 * @return void
 */
function cattachment_admin_menu()
{
    add_submenu_page("upload.php", "Cattachment", "Cattachment", 1, "cattachment", "cattachment_admin");
}
add_action("admin_menu", "cattachment_admin_menu");

/**
 * Generate a select dropdown for selecting a category.
 *
 * @param array $post       The post data.
 * @param string $selected  Selected image type.
 * @return string  An HTML string select box.
 */
function cattachment_select_field($post, $selected = '')
{
    $options = cattachment_get_options();
    if (empty($selected)) $selected = "normal";
    if (!array_key_exists((string) $selected, $options)) $selected = CATTACHMENT_GALLERY_CATEGORY_DEFAULT;

    $out = array();
    $fieldName = CATTACHMENT_GALLERY_CATEGORY_FIELD_NAME;
    $out[] = "<select id='image-category-{$post->ID}' name='attachments[{$post->ID}][{$fieldName}]'>";
    foreach ($options as $name => $label)
    {
        $name = esc_attr($name);
        $out[] = "<option value='$name'" . ($selected == $name ? " selected='selected'" : "") . ">$label</option>";
    }
    $out[] = "</select>";
    $html = join("\n", $out);

    return $html;
}

/**
 * Adds the image category select to the edit form.
 *
 * @param array $form_fields  The fields to edit.
 * @param array $post         The post data.
 * @return array  An array of fields.
 */
function cattachment_attachment_fields_to_edit($form_fields, $post)
{
    if (substr($post->post_mime_type, 0, 5) == "image")
    {
        $category = get_post_meta($post->ID, CATTACHMENT_GALLERY_CATEGORY_METADATA, true);
        if (empty($category))
            $category = '';

        $form_fields[CATTACHMENT_GALLERY_CATEGORY_FIELD_NAME] = array(
            "value" => $category,
            "label" => "Image Category",
            "input" => "html",
            "html"  => cattachment_select_field($post, $category),
            "helps" => "Category of the image"
        );
    }
    else
    {
        unset($form_fields[CATTACHMENT_GALLERY_CATEGORY_FIELD_NAME]);
    }

    return $form_fields;
}
add_filter("attachment_fields_to_edit", "cattachment_attachment_fields_to_edit", 10, 2);

/**
 * Saves the additional category metadata if set or changed.
 *
 * @param array $post        The post data.
 * @param array $attachment  Attachment (image) data.
 * @return array  The filtered post data after category insert.
 */
function cattachment_attachment_fields_to_save($post, $attachment)
{
    $post_id = $post["ID"];
    $fieldName = CATTACHMENT_GALLERY_CATEGORY_FIELD_NAME;

    if (isset($attachment[$fieldName]))
    {
        $image_category = get_post_meta($post_id, CATTACHMENT_GALLERY_CATEGORY_METADATA, true);
        if ($image_category != stripslashes($attachment[$fieldName]))
        {
            $image_category = wp_strip_all_tags(stripslashes($attachment[$fieldName]), true);
            update_post_meta($post_id, CATTACHMENT_GALLERY_CATEGORY_METADATA, addslashes($image_category));
        }
    }

    return $post;
}
add_filter("attachment_fields_to_save", "cattachment_attachment_fields_to_save", 10, 2);
