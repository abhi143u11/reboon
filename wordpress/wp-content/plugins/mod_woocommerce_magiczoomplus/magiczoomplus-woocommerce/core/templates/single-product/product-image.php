<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product;

?>
<div class="images">

	<?php
		if ( has_post_thumbnail() ) {
                        
                        $plugin = $GLOBALS['magictoolbox']['WooCommerceMagicZoomPlus'];
                        
                        /*set watermark options for all profiles START */
                        $defaultParams = $plugin->params->getParams('default');
                        $wm = array();
                        $profiles = $plugin->params->getProfiles();
                        foreach ($defaultParams as $id => $values) {
                            if (($values['group']) == 'Watermark') {
                                $wm[$id] = $values;
                            }
                        }
                        foreach ($profiles as $profile) {
                            $plugin->params->appendParams($wm,$profile);
                        }
                        /*set watermark options for all profiles END */
                        $plugin->params->setProfile('product');
                        
                    
                    
                        $GLOBALS['custom_template_headers'] = true;
                        
                        $apath = str_replace(join(DIRECTORY_SEPARATOR, array('templates', 'single-product')),'',dirname(__FILE__));
                        require_once($apath . 'magictoolbox.templatehelper.class.php');
                        MagicToolboxTemplateHelperClass::setPath($apath.'templates');
                        
                        
                        MagicToolboxTemplateHelperClass::setOptions($plugin->params);
                        
                        $thumbs = WooCommerce_MagicZoomPlus_get_prepared_selectors();
                        
                        $img_name = str_replace(get_site_url(),'',wp_get_attachment_url( get_post_thumbnail_id($post) ));
                        $id = '_Main';
                        $thumb = WooCommerce_MagicZoomPlus_get_product_image($img_name,'thumb');
                        $thumb2x = WooCommerce_MagicZoomPlus_get_product_image($img_name,'thumb2x');
                        
                        
                        
                         
                        
                        
                        $additionalDescription = preg_replace ('/<a[^>]*><img[^>]*><\/a>/is','',$post->post_excerpt);
                        $description = preg_replace ('/<a[^>]*><img[^>]*><\/a>/is','',$post->post_content);
                        $description = preg_replace ('/\[caption id=\"attachment_[0-9]+\"[^\]]*?\][^\[]*?\[\/caption\]/is','',$description);
                        
                        WooCommerce_MagicZoomPlus_get_product_variations(); //call only for onload variation check

                        $img = WooCommerce_MagicZoomPlus_get_product_image($img_name,'original');
                        $img_result = $plugin->getMainTemplate(compact('img','thumb','thumb2x','id','title','description','additionalDescription','link'));
                        $img_result = preg_replace('/(<a.*?class=\".*?)\"/is', "$1" . ' lightbox-added"', $img_result);
                        $GLOBALS['magictoolbox']['MagicZoomPlus']['main'] = $img_result;
                        $mainHTML = $GLOBALS['magictoolbox']['MagicZoomPlus']['main'];

                        $containersData = WooCommerce_MagicZoomPlus_get_containers_data($thumbs,$product->get_id());
                        $thumbs = $containersData['thumbs'];
                        $mainHTML = '';
                        foreach($containersData['containersData'] as $containerId => $containerHTML) {
                            $activeClass = $GLOBALS['defaultContainerId'] == $containerId ? ' mt-active' : '';
                            $mainHTML .= "<div class=\"magic-slide{$activeClass}\" data-magic-slide=\"{$containerId}\">{$containerHTML}</div>";
                        }

                        if (isset($GLOBALS['MAGICTOOLBOX_'.strtoupper('MagicZoomPlus').'_VARIATIONS_SELECTORS']) && count($GLOBALS['MAGICTOOLBOX_'.strtoupper('MagicZoomPlus').'_VARIATIONS_SELECTORS'])) { //if variation selectors are present
                            $thumbs = array_merge($thumbs,$GLOBALS['MAGICTOOLBOX_'.strtoupper('MagicZoomPlus').'_VARIATIONS_SELECTORS']);
                        }
                        
                        if(!empty($containersData['productImagesHTML'])){
                            $thumbs = array_merge($thumbs, $containersData['productImagesHTML']);
                        }
                        
                        
                        
                        
                        
                        $invisImg = '<figure class="woocommerce-product-gallery__image--placeholder"><a class="zoom invisImg wp-post-image" href="'.$img.'" style="display:none;"><img style="display:none;" src="'.$thumb.'"/></a></figure>';
                        
                        $scroll =  WooCommerce_MagicZoomPlus_LoadScroll($plugin);
                        
                        $html = MagicToolboxTemplateHelperClass::render(array(
                            'main' => $mainHTML,
                            'thumbs' => (count($thumbs) >= 1) ? $thumbs : array(),
                            'magicscrollOptions' => $scroll ? $scroll->params->serialize(false, '', 'product-magicscroll-options') : '',
                            'pid' => $product->get_id(),
                        ));
                        $html .= magictoolbox_WooCommerce_MagicZoomPlus_getMagicToolBoxEvent($plugin, $product->get_id());
                        echo $invisImg.$html;
                            
                        
		} 

?>

</div>
