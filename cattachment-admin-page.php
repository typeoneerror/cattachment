<?php

if (!empty($_POST["cattachment_categories"]))
{
    $categories = trim($_POST["cattachment_categories"]);
    $options = cattachment_get_options_array($categories);
    update_option(CATTACHMENT_OPTION_NAME, $options);
    $message = "Categories Saved";
}

$title = "Cattachment Manager";
$options = cattachment_get_options();
$opt_val = is_array($options) ? join("\n", $options) : $options;

?>

<div class="wrap">
    <?php screen_icon(); ?>
    <h2><?php echo esc_html(__($title, CATTACHMENT_DOMAIN)); ?></h2>
    <?php
    if (!empty($message)):
    ?>
    <div class="updated">
        <p>
            <strong><?php _e($message, CATTACHMENT_DOMAIN); ?></strong>
            <?php echo " [" . join(", ", $options) . "]"; ?>
        </p>
    </div>
    <?php
    endif;
    ?>
    <form name="cattachment_categories_form" method="post" action="">
        <p>
            <?php _e("Gallery categories; one per line.", CATTACHMENT_DOMAIN); ?>
        </p>
        <textarea name="cattachment_categories" rows="8" cols="40"><?php echo $opt_val; ?></textarea>
        <p>
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e("Save Changes", CATTACHMENT_DOMAIN); ?>"/>
        </p>
    </form>
</div>