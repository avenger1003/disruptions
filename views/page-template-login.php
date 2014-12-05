<?php

if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) { die(); }
// if (CFCT_DEBUG) { cfct_banner(__FILE__); }

get_header();

?>
<div id="content" class="clearfix">

    <div id="single_wrap" class="clearfix">

        <div id="single_3_column" class="single content_wrap content_bg clearfix">

                <div class="clearfix">
                    <?php the_content(); ?>
                </div>


        </div><!-- // #loop.column_1 -->

    </div><!-- // #single_wrap.colomn_1_2 -->

</div><!-- // #content -->

<?php

get_footer();