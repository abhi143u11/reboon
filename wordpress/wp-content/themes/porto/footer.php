       
    <div class="container">


<?php  	
	if(strpos(get_permalink(),"shop/") == false) 
		$shortcode = do_shortcode('[vc_row full_width="stretch_row" bg_type="bg_color" el_id="find" bg_color_value="#fafafa" css=".vc_custom_1487617531768{border-top-width: 1px !important;border-top-color: #dddddd !important;border-top-style: solid !important;}"][vc_column][vc_column_text el_class="ps-inner-field"]
			<h3 style="text-align: center;">
				Finde deine Hülle			
			</h3>
			[/vc_column_text][vc_column_text]
			<h5 style="text-align: center;">
				reboon bietet Hüllen für mehr als 95% aller Smartphones, Tablets und eReader.			
			</h5>
			[/vc_column_text][/vc_column][/vc_row][vc_row full_width="stretch_row" el_id="psa-find" bg_type="grad" bg_grad="background: -webkit-gradient(linear, left top, left bottom, color-stop(51%, #00C5D7), color-stop(100%, #58A618));background: -moz-linear-gradient(left,#00C5D7 51%,#58A618 100%);background: -webkit-linear-gradient(left,#00C5D7 51%,#58A618 100%);background: -o-linear-gradient(left,#00C5D7 51%,#58A618 100%);background: -ms-linear-gradient(left,#00C5D7 51%,#58A618 100%);background: linear-gradient(left,#00C5D7 51%,#58A618 100%);" css=".vc_custom_1488356665746{margin-bottom: -40px !important;}"][vc_column][vc_row_inner][vc_column_inner width="5/12"][vc_column_text css=".vc_custom_1485622179863{margin-top: 7px !important;}"]
			<div class="psa-search-footer trd_search_wrap">[trd_searchPanel_footer]</div>
			[/vc_column_text][/vc_column_inner][vc_column_inner el_class="25" width="1/6"]
			[vc_custom_heading text="oder wähle" font_container="tag:p|font_size:22|text_align:center|color:%23ffffff" use_theme_fonts="yes" el_class="psa-thin" 
			css=".vc_custom_1488207908712{margin-top: 6px !important;}"][/vc_column_inner][vc_column_inner width="5/12"][vc_column_text][trd_searchWindows]
			[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]');
			
	else
		$shortcode = do_shortcode('[vc_row full_width="stretch_row" bg_type="bg_color" el_id="find" bg_color_value="#fafafa" css=".vc_custom_1487617531768{border-top-width: 1px !important;border-top-color: #dddddd !important;border-top-style: solid !important;}"][vc_column][vc_column_text el_class="ps-inner-field"]
			<h3 style="text-align: center;">			
				Finde deine Hülle<!--Du besitzt nicht das [dhvc_woo_product_page_custom_field key="device_name" label="" el_class=""]?-->
			</h3>
			[/vc_column_text][vc_column_text]
			<h5 style="text-align: center;">			
				Für mehr als 95% aller Tablets und Smartphones das passende Cover
			</h5>
			[/vc_column_text][/vc_column][/vc_row][vc_row full_width="stretch_row" el_id="psa-find" bg_type="grad" bg_grad="background: -webkit-gradient(linear, left top, left bottom, color-stop(51%, #00C5D7), color-stop(100%, #58A618));background: -moz-linear-gradient(left,#00C5D7 51%,#58A618 100%);background: -webkit-linear-gradient(left,#00C5D7 51%,#58A618 100%);background: -o-linear-gradient(left,#00C5D7 51%,#58A618 100%);background: -ms-linear-gradient(left,#00C5D7 51%,#58A618 100%);background: linear-gradient(left,#00C5D7 51%,#58A618 100%);" css=".vc_custom_1488356665746{margin-bottom: -40px !important;}"][vc_column][vc_row_inner][vc_column_inner width="5/12"][vc_column_text css=".vc_custom_1485622179863{margin-top: 7px !important;}"]
			<div class="psa-search-footer trd_search_wrap">[trd_searchPanel_footer]</div>
			[/vc_column_text][/vc_column_inner][vc_column_inner el_class="25" width="1/6"]
			[vc_custom_heading text="oder wähle" font_container="tag:p|font_size:22|text_align:center|color:%23ffffff" use_theme_fonts="yes" el_class="psa-thin" 
			css=".vc_custom_1488207908712{margin-top: 6px !important;}"][/vc_column_inner][vc_column_inner width="5/12"][vc_column_text][trd_searchWindows]
			[/vc_column_text][/vc_column_inner][/vc_row_inner][/vc_column][/vc_row]');

echo $shortcode; ?>
               
           </div>

<?php
global $porto_settings, $porto_layout;

$footer_type = $porto_settings['footer-type'];
$default_layout = porto_meta_default_layout();
$wrapper = porto_get_wrapper_type();
?>



        <?php get_sidebar(); ?>


        <?php if (porto_get_meta_value('footer', true)) : ?>

            <?php

            $cols = 0;
            for ($i = 1; $i <= 4; $i++) {
                if ( is_active_sidebar( 'content-bottom-'. $i ) )
                    $cols++;
            }

            if (is_404()) $cols = 0;

            if ($cols) : ?>
                <?php if ($wrapper == 'boxed' || $porto_layout == 'fullwidth' || $porto_layout == 'left-sidebar' || $porto_layout == 'right-sidebar') : ?>
                    <div class="container sidebar content-bottom-wrapper">
                <?php else :
                    if ($default_layout == 'fullwidth' || $default_layout == 'left-sidebar' || $default_layout == 'right-sidebar') :
                    ?>
                    <div class="container sidebar content-bottom-wrapper">
                    <?php else : ?>
                    <div class="container-fluid sidebar content-bottom-wrapper">
                    <?php
                    endif;
                endif; ?>

                <div class="row">

                    <?php
                    $col_class = array();
                    switch ($cols) {
                        case 1:
                            $col_class[1] = 'col-sm-12';
                            break;
                        case 2:
                            $col_class[1] = 'col-sm-12';
                            $col_class[2] = 'col-sm-12';
                            break;
                        case 3:
                            $col_class[1] = 'col-md-4';
                            $col_class[2] = 'col-md-4';
                            $col_class[3] = 'col-md-4';
                            break;
                        case 4:
                            $col_class[1] = 'col-md-3';
                            $col_class[2] = 'col-md-3';
                            $col_class[3] = 'col-md-3';
                            $col_class[4] = 'col-md-3';
                            break;
                    }
                    ?>
                        <?php
                        $cols = 1;
                        for ($i = 1; $i <= 4; $i++) {
                            if ( is_active_sidebar( 'content-bottom-'. $i ) ) {
                                ?>
                                <div class="<?php echo $col_class[$cols++] ?>">
                                    <?php dynamic_sidebar( 'content-bottom-'. $i ); ?>
                                </div>
                            <?php
                            }
                        }
                        ?>

                    </div>
                </div>
            <?php endif; ?>

            </div><!-- end main -->

            <?php
            do_action('porto_after_main');
            $footer_view = porto_get_meta_value('footer_view');
            ?>

            <div class="footer-wrapper<?php if ($porto_settings['footer-wrapper'] == 'wide') echo ' wide' ?> <?php echo $footer_view ?>">

                <?php if (porto_get_wrapper_type() != 'boxed' && $porto_settings['footer-wrapper'] == 'boxed') : ?>
                <div id="footer-boxed">
                <?php endif; ?>

                    <?php
                    get_template_part('footer/footer_'.$footer_type);
                    ?>

                <?php if (porto_get_wrapper_type() != 'boxed' && $porto_settings['footer-wrapper'] == 'boxed') : ?>
                </div>
                <?php endif; ?>

            </div>

        <?php else: ?>

            </div><!-- end main -->

        <?php
        do_action('porto_after_main');
        endif;
        ?>

    </div><!-- end wrapper -->
    <?php do_action('porto_after_wrapper'); ?>

<?php

// navigation panel
get_template_part('panel');

// mobile sidebar
$mobile_sidebar = $porto_settings['show-mobile-sidebar'];
if ($mobile_sidebar && ($porto_layout == 'wide-left-sidebar' || $porto_layout == 'wide-right-sidebar' || $porto_layout == 'left-sidebar' || $porto_layout == 'right-sidebar')) {
    get_template_part('sidebar-mobile');
}

?>

<!--[if lt IE 9]>
<script src="<?php echo esc_url(porto_js) ?>/html5shiv.min.js"></script>
<script src="<?php echo esc_url(porto_js) ?>/respond.min.js"></script>
<![endif]-->

<?php wp_footer(); ?>



<?php
$n = 0;
$m = 0;
$taxonomy = 'model';
$termsBrands = get_terms($taxonomy, array('hide_empty' => true, 'parent' => 0));
$termsModels = get_terms($taxonomy, array('hide_empty' => true));
?>
    <?php
        if ( ! empty( $termsBrands ) && ! is_wp_error( $termsBrands ) ){
            foreach ( $termsBrands as $term )  $n++;
        }
        if ( ! empty( $termsModels ) && ! is_wp_error( $termsModels ) ){
            foreach ( $termsModels as $term )  $m++;
        }
    ?>

        <div class="tags-cloud">
            <p class="tags-info"><a href="/model-list/" class="link-menu-top">Zeige alle Marken <i class="Defaults-angle-right" style="font-size:14px;"></i></a><br />
                <!--<span><?php echo $n ?> Marken mit insgesamt <?php echo $m ?> Modellen</span>--></p>
        <?php wp_tag_cloud( array( 'taxonomy' => 'model' ) ); ?>
        </div>


<?php
// js code (Theme Settings/General)
if (isset($porto_settings['js-code']) && $porto_settings['js-code']) { ?>
<script type="text/javascript">    
    <?php echo $porto_settings['js-code']; ?>
    </script>
<?php } ?>


<script src="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
<script type="text/javascript" src="https://www.reboon.de/wp-content/themes/porto-child/custom.js"></script>

<!-- Google Code for Onlineshop Reboon Conversion Page -->
<script type="text/javascript">
var google_conversion_id = 974495916;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "74iTCMmFi28QrMHW0AM";
var google_conversion_value = 1.00;
var google_conversion_currency = "EUR"; 
var google_remarketing_only = false;
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/974495916/?value=1.00&amp;currency_code=EUR&amp;label=74iTCMmFi28QrMHW0AM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!--
<script>
    var $ = jQuery.noConflict();
    $('.images').ready(function(){
    setTimeout(function() { 
    $('.thumbnails.columns-3').hide();
    }, 500);  
    });
</script>
-->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter44601811 = new Ya.Metrika({
                    id:44601811,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:false
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/44601811" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<!--<script data-skip-moving="true">
        (function(w,d,u,b){
                s=d.createElement('script');r=(Date.now()/1000|0);s.async=1;s.src=u+'?'+r;
                h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://cdn.bitrix24.ru/b4110761/crm/site_button/loader_2_avkse5.js');
</script>-->
</body>
</html>