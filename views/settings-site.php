<script type="text/javascript">
(function($){
    $(document).ready(function ($) {
        // LEGACY CHECK
        $('.inside input.<?php echo self::LEGACY_OPTION_NAME;?>').live('click',function() {
            if($(this).attr("checked")){
                $(this).val(1);
            }else{
                $(this).val(0);
            }
        });
    });
})(jQuery);
</script>
<div class="wrap">
    <?php screen_icon('themes'); ?> <h2>Business Settings</h2>
    <?php
    //Save Page Settings
    if (isset($_POST['business_settings'])) {
        update_option(self::LEGACY_OPTION_NAME, $_POST[self::LEGACY_OPTION_NAME]);
        update_option(self::ZIPCODE_OPTION_NAME, $_POST[self::ZIPCODE_OPTION_NAME]);
        update_option(self::IS_ACTIVE_OPTION_NAME, $_POST[self::IS_ACTIVE_OPTION_NAME]);
        echo '<div class="updated fade"><p>'.__('Settings Saved.', 'mp').'</p></div>';
    }
    ?>
    <br/>
    <form id="bd-main-form" method="POST" action="">
        <input type="hidden" name="business_settings" value="1" />
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="<?php echo self::IS_ACTIVE_OPTION_NAME; ?>">
                        Activate Business Disruptions:
                    </label>
                </th>
                <td>
                    <input class="checkbox" type="checkbox" value="1" <?php checked( get_option(self::IS_ACTIVE_OPTION_NAME), 1 ); ?> id="<?php echo self::IS_ACTIVE_OPTION_NAME; ?>" name="<?php echo self::IS_ACTIVE_OPTION_NAME; ?>" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo self::LEGACY_OPTION_NAME; ?>">
                        Enforce Legacy user check:
                    </label>
                </th>
                <td>
                    <input class="checkbox" type="checkbox" value="1" <?php checked( get_option(self::LEGACY_OPTION_NAME), 1 ); ?> id="<?php echo self::LEGACY_OPTION_NAME; ?>" name="<?php echo self::LEGACY_OPTION_NAME; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="<?php echo self::ZIPCODE_OPTION_NAME; ?>">
                        Specify zipcodes for closing listings:
                    </label>
                </th>
                <td>
                    <textarea name="<?php echo self::ZIPCODE_OPTION_NAME; ?>" rows=3 cols=20 wrap=off><?php echo get_option(self::ZIPCODE_OPTION_NAME); ?></textarea>
                </td>
            </tr>
        </table>
        <p>
            <input type="submit" value="Submit settings" class="button-primary"/>
        </p>
    </form>
</div>