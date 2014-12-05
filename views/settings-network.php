<div class="wrap">
    <?php if($_GET['updated'] == 'true') { ?>
    <div class="updated fade" id="<?php echo self::GENERAL_NETWORK_SETTINGS; ?>-updated"><p><strong><?php _e('Settings Saved'); ?></strong></p></div>
    <?php } ?>
    <?php screen_icon('options-general'); ?>
    <h2><?php echo $title; ?></h2>
    <form method="post" action="<?php echo add_query_arg(array('action' => self::SLUG), network_admin_url('edit.php')); ?>">
        <?php do_settings_sections(self::SLUG); ?>
        <p class="submit">
            <?php wp_nonce_field(self::NONCE_ACTION, self::NONCE_NAME); ?>
            <input type="submit" name="<?php echo self::NONCE_ACTION; ?>" class="button-primary" value="<?php _e('Save Changes'); ?>" />
        </p>
    </form>
</div>