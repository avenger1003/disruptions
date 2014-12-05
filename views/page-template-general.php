<?php


get_header();

?>
<div id="content" class="clearfix">

    <div id="single_wrap" class="column_1_2 first clearfix">

        <div id="single_col_1_2" class="column_1_2 first clearfix">

            <div id="single_two_column" class="single content_wrap content_bg clearfix">

                <div <?php post_class('page content_bg content-single entry_content clearfix') ?>>

                    <div class="the_content clearfix">
                        <?php the_content(); ?>
                    </div>

                </div>

            </div><!-- // #loop.column_1 -->

        </div><!-- // #single_col_1_2 -->

    </div><!-- // #single_wrap.colomn_1_2 -->

    <div id="single_page_right_sidebar" class="column_3 sidebar clearfix">
        <?php //ts_dynamic_sidebar(array('closings-page','weather-page','default','home-right')); ?>
    </div><!--#primary_sidebar.sidebar-->

</div><!-- // #content -->

<?php

get_footer();