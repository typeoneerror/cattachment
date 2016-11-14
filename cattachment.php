<?php

/*
Plugin Name: Cattachment
Description: Adds a drop-down to post gallery options for selecting the type of image. Adds the value as a metadata value in the attachment metadata.
Version: 0.2
Author: Benjamin Borowski
Author URI: http://typeoneerror.com
License: GPL2
License URI: http://www.gnu.org/licenses/gpl.html
*/

/*
Copyright 2016 Benjamin Borowski

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// default category name
if (!defined("CATTACHMENT_GALLERY_CATEGORY_DEFAULT")) define("CATTACHMENT_GALLERY_CATEGORY_DEFAULT", "Normal");

// name of the form field used in the gallery editor
if (!defined("CATTACHMENT_GALLERY_CATEGORY_FIELD_NAME")) define("CATTACHMENT_GALLERY_CATEGORY_FIELD_NAME", "image_category");

// name of the metadata field inserted into the postmeta table
if (!defined("CATTACHMENT_GALLERY_CATEGORY_METADATA")) define("CATTACHMENT_GALLERY_CATEGORY_METADATA", "_wp_attachment_image_category");

// name of domain of plugin
if (!defined("CATTACHMENT_DOMAIN")) define("CATTACHMENT_DOMAIN", "com.typeoneerror.cattachment");

// option name to save in database
if (!defined("CATTACHMENT_OPTION_NAME")) define("CATTACHMENT_OPTION_NAME", "cattachment_options");

/**
 * Simple logging function.
 *
 * @param mixed $object  Object to print out formatted.
 * @return void
 */
function cattachment_debug($object)
{
    echo "<pre>";
    print_r($object);
    echo "</pre>";
}

/**
 * Fetches the currently registered categories.
 *
 * @return array
 */
function cattachment_get_options()
{
    $option = get_option(CATTACHMENT_OPTION_NAME);
    return $option;
}

/**
 * Creates an array out of the category list.
 *
 * @param string $opt_val  Category list delimited by lines.
 * @return array  Category list.
 */
function cattachment_get_options_array($opt_val)
{
    $values = preg_split("/\R/", $opt_val);
    $options = array();
    foreach ($values as $option)
    {
        $option = preg_replace("/[^\w ]/i", "", $option);
        $var = strtolower(preg_replace("/ /i", "_", $option));
        $options[$var] = $option;
    }
    return $options;
}

/**
 * Called when the plugin is first activated.
 *
 * @return void
 */
function cattachment_install()
{
    $opt_val = get_option(CATTACHMENT_OPTION_NAME);
    if (empty($opt_val))
    {
        add_option(CATTACHMENT_OPTION_NAME, CATTACHMENT_GALLERY_CATEGORY_DEFAULT);
    }
}

if (is_admin())
{
    register_activation_hook(dirname(__FILE__) . "/cattachment.php", "cattachment_install");
    include_once "cattachment-admin.php";
}
else
{
    include_once "cattachment-client.php";
}
