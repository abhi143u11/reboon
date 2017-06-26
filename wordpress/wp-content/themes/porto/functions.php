<?php

/**
 * Define variables
 */

define('porto_lib',                   get_template_directory() . '/inc');       // library directory
define('porto_admin',                 porto_lib . '/admin');                    // admin directory
define('porto_plugins',               porto_lib . '/plugins');                  // plugins directory
define('porto_content_types',         porto_lib . '/content_types');            // content_types directory
define('porto_menu',                  porto_lib . '/menu');                     // menu directory
define('porto_functions',             porto_lib . '/functions');                // functions directory
define('porto_options_dir',           porto_admin . '/theme_options');          // options directory

define('porto_dir',                   get_template_directory());                 // template directory
define('porto_uri',                   get_template_directory_uri());             // template directory uri
define('porto_css',                   porto_uri . '/css');                       // css uri

define('porto_js',                    porto_uri . '/js');                       // javascript uri
define('porto_plugins_uri',           porto_uri . '/inc/plugins');              // plugins uri
define('porto_options_uri',           porto_uri . '/inc/admin/theme_options');        // plugins uri

$theme = wp_get_theme();
define('porto_version',               '3.3.1');                    // set current version

/**
 * Wordpress theme check
 */
// set content width
if ( ! isset( $content_width ) ) $content_width = 900;

/**
 * Porto content types functions
 */

require_once(porto_functions . '/content_type.php');

/**
 * Porto functions
 */
require_once(porto_functions . '/functions.php');

/**
 * Menu
 */
require_once(porto_menu . '/menu.php');

/**
 * Content Types
 */
require_once(porto_content_types . '/content_types.php');

/**
 * Install Plugins
 */
require_once(porto_plugins . '/plugins.php');

/**
 * Theme support & Theme setup
 */
// theme setup
if ( ! function_exists( 'porto_setup' ) ) :
    function porto_setup() {

        add_theme_support( "title-tag" );
        //add_theme_support( "custom-header", array() );
        //add_theme_support( 'custom-background', array() );
        add_editor_style( array( 'style.css', 'style_rtl.css' ) );

        if ( defined( 'WOOCOMMERCE_VERSION' ) ) {
            if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
                add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
            } else {
                define( 'WOOCOMMERCE_USE_CSS', false );
            }
        }

        // translation
        load_theme_textdomain('porto', porto_dir.'/languages');
        load_child_theme_textdomain('porto', get_stylesheet_directory().'/languages');

        /**
         * Porto admin options
         */
        require_once(porto_admin . '/admin.php');

        global $porto_settings;

        // default rss feed links
        add_theme_support('automatic-feed-links');

        // add support for post thumbnails
        add_theme_support( 'post-thumbnails' );

        // add image sizes
      //  add_image_size( 'blog-large', 1140, 445, true );
      //  add_image_size( 'blog-medium', 463, 348, true );
      //  add_image_size( 'related-post', (isset($porto_settings['post-related-image-size']) && (int)$porto_settings['post-related-image-size']['width']) ? (int)$porto_settings['post-related-image-size']['width'] : 450, (isset($porto_settings['post-related-image-size']) && (int)$porto_settings['post-related-image-size']['height']) ? (int)$porto_settings['post-related-image-size']['height'] : 231, true );

       // if (isset($porto_settings['enable-portfolio']) && $porto_settings['enable-portfolio']) {
          //  add_image_size( 'portfolio-grid-one', 1140, 595, true );
          //  add_image_size( 'portfolio-grid-two', 560, 560, true );
          //  add_image_size( 'portfolio-grid', 367, 367, true );
          //  add_image_size( 'portfolio-full', 1140, 595, true );
          //  add_image_size( 'portfolio-large', 560, 367, true );
          //  add_image_size( 'portfolio-medium', 367, 367, true );
          //  add_image_size( 'portfolio-timeline', 560, 560, true );
          //  add_image_size( 'related-portfolio', 367, 367, true );
     //   }

       // if (isset($porto_settings['enable-member']) && $porto_settings['enable-member']) {
         //   add_image_size( 'member-two', 560, 560, true );
          //  add_image_size( 'member', 367, 367, true );
      //  }

     //   add_image_size( 'widget-thumb-medium', 85, 85, true );
      //  add_image_size( 'widget-thumb', 50, 50, true );

        // woocommerce support
        add_theme_support('woocommerce');

        // allow shortcodes in widget text
        add_filter('widget_text', 'do_shortcode');

        // register menus
        register_nav_menus( array(
            'main_menu' => __('Main Menu', 'porto'),
            'sidebar_menu' => __('Sidebar Menu', 'porto'),
            'top_nav' => __('Top Navigation', 'porto'),
            'view_switcher' => __('View Switcher', 'porto'),
            'currency_switcher' => __('Currency Switcher', 'porto')
        ));

        // add post formats
        add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio', 'chat'));

        // disable master slider woocommerce product slider
        $options = get_option( 'msp_woocommerce' );

        if ( isset( $options ) && isset($options['enable_single_product_slider'] ) && $options['enable_single_product_slider'] == 'on' ) {
            $options['enable_single_product_slider'] = '';
            update_option('msp_woocommerce', $options);
        }
    }
endif;
add_action( 'after_setup_theme', 'porto_setup' );

/**
 * Enqueue css, js files
 */
add_action('wp_enqueue_scripts',    'porto_css', 1000);
add_action('wp_enqueue_scripts',    'porto_scripts', 1000);
add_action('admin_enqueue_scripts', 'porto_admin_css', 1000);
add_action('admin_enqueue_scripts', 'porto_admin_scripts', 1000);
add_action( 'wp_footer',            'porto_footer_hook', 1 );

function porto_css() {

    // deregister plugin styles
    wp_dequeue_style( 'font-awesome' );
    wp_dequeue_style( 'yith-wcwl-font-awesome' );
    wp_dequeue_style( 'bsf-Simple-Line-Icons' );

    // load visual composer styles
    if (!wp_style_is('js_composer_front'))
        wp_enqueue_style('js_composer_front');

    // load ultimate addons default js
    $bsf_options = get_option('bsf_options');
    $ultimate_global_scripts = (isset($bsf_options['ultimate_global_scripts'])) ? $bsf_options['ultimate_global_scripts'] : false;
    if ($ultimate_global_scripts !== 'enable') {
        $ultimate_css = get_option('ultimate_css');
        if ($ultimate_css == "enable") {
            if (!wp_style_is('ultimate-style-min'))
                wp_enqueue_style('ultimate-style-min');
        } else {
            if (!wp_style_is('ultimate-style'))
                wp_enqueue_style('ultimate-style');
        }
    }

    global $porto_settings;

    // bootstrap styles
    wp_deregister_style( 'porto-bootstrap' );
    if (is_rtl()) {
        $css_file = porto_dir.'/css/bootstrap_rtl_'.porto_get_blog_id().'.css';
        if (file_exists($css_file)) {
            wp_register_style( 'porto-bootstrap', porto_uri.'/css/bootstrap_rtl_'.porto_get_blog_id().'.css?ver=' . porto_version );
        } else {
            wp_register_style( 'porto-bootstrap', porto_uri.'/css/bootstrap_rtl.css?ver=' . porto_version );
        }
    } else {
        $css_file = porto_dir.'/css/bootstrap_'.porto_get_blog_id().'.css';
        if (file_exists($css_file)) {
            wp_register_style( 'porto-bootstrap', porto_uri.'/css/bootstrap_'.porto_get_blog_id().'.css?ver=' . porto_version );
        } else {
            wp_register_style( 'porto-bootstrap', porto_uri.'/css/bootstrap.css?ver=' . porto_version );
        }
    }
    wp_enqueue_style( 'porto-bootstrap' );

    // plugins styles
    wp_deregister_style( 'porto-plugins' );
    if (is_rtl()) {
        $css_file = porto_dir.'/css/plugins_rtl_'.porto_get_blog_id().'.css';
        if (file_exists($css_file)) {
            wp_register_style( 'porto-plugins', porto_uri.'/css/plugins_rtl_'.porto_get_blog_id().'.css?ver=' . porto_version );
        } else {
            wp_register_style( 'porto-plugins', porto_uri.'/css/plugins_rtl.css?ver=' . porto_version );
        }
    } else {
        $css_file = porto_dir.'/css/plugins_'.porto_get_blog_id().'.css';
        if (file_exists($css_file)) {
            wp_register_style( 'porto-plugins', porto_uri.'/css/plugins_'.porto_get_blog_id().'.css?ver=' . porto_version );
        } else {
            wp_register_style( 'porto-plugins', porto_uri.'/css/plugins.css?ver=' . porto_version );
        }
    }
    wp_enqueue_style( 'porto-plugins' );

    // porto styles
    // elements styles
    wp_deregister_style( 'porto-theme-elements' );
    if (is_rtl()) {
        $css_file = porto_dir.'/css/theme_rtl_elements_'.porto_get_blog_id().'.css';
        if (file_exists($css_file)) {
            wp_register_style( 'porto-theme-elements', porto_uri.'/css/theme_rtl_elements_'.porto_get_blog_id().'.css?ver=' . porto_version );
        } else {
            wp_register_style( 'porto-theme-elements', porto_uri.'/css/theme_rtl_elements.css?ver=' . porto_version );
        }
    } else {
        $css_file = porto_dir.'/css/theme_elements_'.porto_get_blog_id().'.css';
        if (file_exists($css_file)) {
            wp_register_style( 'porto-theme-elements', porto_uri.'/css/theme_elements_'.porto_get_blog_id().'.css?ver=' . porto_version );
        } else {
            wp_register_style( 'porto-theme-elements', porto_uri.'/css/theme_elements.css?ver=' . porto_version );
        }
    }
    wp_enqueue_style( 'porto-theme-elements' );

    // default styles
    wp_deregister_style( 'porto-theme' );
    if (is_rtl()) {
        $css_file = porto_dir.'/css/theme_rtl_'.porto_get_blog_id().'.css';
        if (file_exists($css_file)) {
            wp_register_style( 'porto-theme', porto_uri.'/css/theme_rtl_'.porto_get_blog_id().'.css?ver=' . porto_version );
        } else {
            wp_register_style( 'porto-theme', porto_uri.'/css/theme_rtl.css?ver=' . porto_version );
        }
    } else {
        $css_file = porto_dir.'/css/theme_'.porto_get_blog_id().'.css';
        if (file_exists($css_file)) {
            wp_register_style( 'porto-theme', porto_uri.'/css/theme_'.porto_get_blog_id().'.css?ver=' . porto_version );
        } else {
            wp_register_style( 'porto-theme', porto_uri.'/css/theme.css?ver=' . porto_version );
        }
    }
    wp_enqueue_style( 'porto-theme' );

    // woocommerce styles
    if (class_exists('WooCommerce')) {
        wp_deregister_style( 'porto-theme-shop' );
        if (is_rtl()) {
            $css_file = porto_dir.'/css/theme_rtl_shop_'.porto_get_blog_id().'.css';
            if (file_exists($css_file)) {
                wp_register_style( 'porto-theme-shop', porto_uri.'/css/theme_rtl_shop_'.porto_get_blog_id().'.css?ver=' . porto_version );
            } else {
                wp_register_style( 'porto-theme-shop', porto_uri.'/css/theme_rtl_shop.css?ver=' . porto_version );
            }
        } else {
            $css_file = porto_dir.'/css/theme_shop_'.porto_get_blog_id().'.css';
            if (file_exists($css_file)) {
                wp_register_style( 'porto-theme-shop', porto_uri.'/css/theme_shop_'.porto_get_blog_id().'.css?ver=' . porto_version );
            } else {
                wp_register_style( 'porto-theme-shop', porto_uri.'/css/theme_shop.css?ver=' . porto_version );
            }
        }
        wp_enqueue_style( 'porto-theme-shop' );
    }

    // bbpress, buddypress styles
    if (class_exists('bbPress') || class_exists('BuddyPress')) {
        wp_deregister_style( 'porto-theme-bbpress' );
        if (is_rtl()) {
            $css_file = porto_dir.'/css/theme_rtl_bbpress_'.porto_get_blog_id().'.css';
            if (file_exists($css_file)) {
                wp_register_style( 'porto-theme-bbpress', porto_uri.'/css/theme_rtl_bbpress_'.porto_get_blog_id().'.css?ver=' . porto_version );
            } else {
                wp_register_style( 'porto-theme-bbpress', porto_uri.'/css/theme_rtl_bbpress.css?ver=' . porto_version );
            }
        } else {
            $css_file = porto_dir.'/css/theme_bbpress_'.porto_get_blog_id().'.css';
            if (file_exists($css_file)) {
                wp_register_style( 'porto-theme-bbpress', porto_uri.'/css/theme_bbpress_'.porto_get_blog_id().'.css?ver=' . porto_version );
            } else {
                wp_register_style( 'porto-theme-bbpress', porto_uri.'/css/theme_bbpress.css?ver=' . porto_version );
            }
        }
        wp_enqueue_style( 'porto-theme-bbpress' );
    }

    // skin styles
    wp_deregister_style( 'porto-skin' );
    if (is_rtl()) {
        $css_file = porto_dir.'/css/skin_rtl_'.porto_get_blog_id().'.css';
        if (file_exists($css_file)) {
            wp_register_style( 'porto-skin', porto_uri.'/css/skin_rtl_'.porto_get_blog_id().'.css?ver=' . porto_version );
        } else {
            wp_register_style( 'porto-skin', porto_uri.'/css/skin_rtl.css?ver=' . porto_version );
        }
    } else {
        $css_file = porto_dir.'/css/skin_'.porto_get_blog_id().'.css';
        if (file_exists($css_file)) {
            wp_register_style( 'porto-skin', porto_uri.'/css/skin_'.porto_get_blog_id().'.css?ver=' . porto_version );
        } else {
            wp_register_style( 'porto-skin', porto_uri.'/css/skin.css?ver=' . porto_version );
        }
    }
    wp_enqueue_style( 'porto-skin' );

    // custom styles
    wp_deregister_style( 'porto-style' );
    wp_register_style( 'porto-style', porto_uri . '/style.css' );
    wp_enqueue_style( 'porto-style' );

    if (is_rtl()) {
        wp_deregister_style( 'porto-style-rtl' );
        wp_register_style( 'porto-style-rtl', porto_uri . '/style_rtl.css' );
        wp_enqueue_style( 'porto-style-rtl' );
    }

    // Load Google Fonts
    $gfont = array();
    $gfont_weight = array(200,300,400,700,800);
    $fonts = array('body', 'alt', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'menu', 'menu-side', 'menu-popup');
    foreach ($fonts as $option) {
        if (isset($porto_settings[$option.'-font']['google']) && $porto_settings[$option.'-font']['google'] !== 'false') {
            $font = urlencode($porto_settings[$option.'-font']['font-family']);
            $font_weight = $porto_settings[$option.'-font']['font-weight'];
            if (!in_array($font, $gfont))
                $gfont[] = $font;
            if (!in_array($font_weight, $gfont_weight)) {
                $gfont_weight[] = $font_weight;
            }
        }
    }
    $gfont_weight = implode(',', $gfont_weight);

    $font_family = '';
    foreach ($gfont as $font)
        $font_family .= $font . ':' . $gfont_weight . '%7C';

    if ($font_family) {
        $charsets = '';
        if (isset($porto_settings['select-google-charset']) && isset($porto_settings['select-google-charset']) && isset($porto_settings['google-charsets']) && $porto_settings['google-charsets']) {
            $i = 0;
            foreach ($porto_settings['google-charsets'] as $charset) {
                if ($i == 0) $charsets .= $charset;
                else $charsets .= ",".$charset;
                $i++;
            }
            if ($charsets)
                $charsets = "&amp;subset=" . $charsets;
        }

        wp_register_style( 'porto-google-fonts', "//fonts.googleapis.com/css?family=" . $font_family . $charsets );
        wp_enqueue_style( 'porto-google-fonts' );
    }

    global $wp_styles;
    wp_deregister_style( 'porto-ie' );
    wp_register_style( 'porto-ie', porto_uri.'/css/ie.css?ver=' . porto_version );
    wp_enqueue_style( 'porto-ie' );
    $wp_styles->add_data( 'porto-ie', 'conditional', 'lt IE 10' );

    if ( current_user_can( 'edit_theme_options' ) ) {
        // admin style
        wp_enqueue_style('porto_admin_bar', porto_css . '/admin_bar.css', false, porto_version, 'all');
    }

    porto_enqueue_revslider_css();
    porto_enqueue_custom_css();
}

function porto_scripts() {
    global $porto_settings;

    if (!is_admin() && !in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) )) {
        wp_reset_postdata();

        // comment reply
        if ( is_singular() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        // load wc variation script
        wp_enqueue_script( 'wc-add-to-cart-variation' );

        // load visual composer default js
        if (!wp_script_is('wpb_composer_front_js')) {
            wp_enqueue_script('wpb_composer_front_js');
        }

        // load ultimate addons default js
        $bsf_options = get_option('bsf_options');
        $ultimate_global_scripts = (isset($bsf_options['ultimate_global_scripts'])) ? $bsf_options['ultimate_global_scripts'] : false;
        if ($ultimate_global_scripts !== 'enable') {
            $isAjax = false;
            $ultimate_ajax_theme = get_option('ultimate_ajax_theme');
            if ($ultimate_ajax_theme == 'enable')
                $isAjax = true;
            $ultimate_js = get_option('ultimate_js', 'disable');
            $bsf_dev_mode = (isset($bsf_options['dev_mode'])) ? $bsf_options['dev_mode'] : false;
            if (($ultimate_js == 'enable' || $isAjax == true) && ($bsf_dev_mode != 'enable') ) {
                if (!wp_script_is('ultimate-script')) {
                    wp_enqueue_script('ultimate-script');
                }
            }
        }

        // porto scripts
        wp_deregister_script( 'porto-plugins' );
        wp_register_script( 'porto-plugins', porto_js .'/plugins'.(WP_DEBUG?'':'.min').'.js', array('jquery', 'jquery-migrate'), porto_version, false );
        wp_enqueue_script( 'porto-plugins' );

        // load porto theme js file

        wp_deregister_script( 'porto-theme' );
        wp_register_script( 'porto-theme', porto_js .'/theme'.(WP_DEBUG?'':'.min').'.js', array('jquery'), porto_version, true );
        wp_enqueue_script( 'porto-theme' );

        // compatible check with product filter plugin
        $js_wc_prdctfltr = false;
        if (class_exists('WC_Prdctfltr')) {
            $porto_settings['category-ajax'] = false;
            if ( get_option( 'wc_settings_prdctfltr_use_ajax', 'no' ) == 'yes' ) {
                $js_wc_prdctfltr = true;
            }
        }

        $sticky_header = porto_get_meta_value('sticky_header');
        $show_sticky_header = false;
        if ('no' !== $sticky_header && ('yes' === $sticky_header || ('yes' !== $sticky_header && $porto_settings['enable-sticky-header']))) {
            $show_sticky_header = true;
        }

        wp_localize_script( 'porto-theme', 'js_porto_vars', array(
            'rtl' => esc_js(is_rtl() ? true : false),
            'ajax_url' => esc_js(admin_url( 'admin-ajax.php' )),
            'change_logo' => esc_js($porto_settings['change-header-logo']),
            'container_width' => esc_js($porto_settings['container-width']),
            'grid_gutter_width' => esc_js($porto_settings['grid-gutter-width']),
            'show_sticky_header' => esc_js($show_sticky_header),
            'show_sticky_header_tablet' => esc_js($porto_settings['enable-sticky-header-tablet']),
            'show_sticky_header_mobile' => esc_js($porto_settings['enable-sticky-header-mobile']),
            'ajax_loader_url' => esc_js(str_replace(array('http:', 'https'), array('', ''), porto_uri . '/images/ajax-loader@2x.gif')),
            'category_ajax' => esc_js($porto_settings['category-ajax']),
            'prdctfltr_ajax' => esc_js($js_wc_prdctfltr),
            'show_minicart' => esc_js($porto_settings['show-minicart']),
            'slider_loop' => esc_js($porto_settings['slider-loop']),
            'slider_autoplay' => esc_js($porto_settings['slider-autoplay']),
            'slider_autoheight' => esc_js($porto_settings['slider-autoheight']),
            'slider_speed' => esc_js($porto_settings['slider-speed']),
            'slider_nav' => esc_js($porto_settings['slider-nav']),
            'slider_nav_hover' => esc_js($porto_settings['slider-nav-hover']),
            'slider_margin' => esc_js($porto_settings['slider-margin']),
            'slider_dots' => esc_js($porto_settings['slider-dots']),
            'slider_animatein' => esc_js($porto_settings['slider-animatein']),
            'slider_animateout' => esc_js($porto_settings['slider-animateout']),
            'product_thumbs_count' => esc_js($porto_settings['product-thumbs-count']),
            'product_zoom' => esc_js($porto_settings['product-zoom']),
            'product_zoom_mobile' => esc_js($porto_settings['product-zoom-mobile']),
            'product_image_popup' => esc_js($porto_settings['product-image-popup']),
            'zoom_type' => esc_js($porto_settings['zoom-type']),
            'zoom_scroll' => esc_js($porto_settings['zoom-scroll']),
            'zoom_lens_size' => esc_js($porto_settings['zoom-lens-size']),
            'zoom_lens_shape' => esc_js($porto_settings['zoom-lens-shape']),
            'zoom_contain_lens' => esc_js($porto_settings['zoom-contain-lens']),
            'zoom_lens_border' => esc_js($porto_settings['zoom-lens-border']),
            'zoom_border_color' => esc_js($porto_settings['zoom-border-color']),
            'zoom_border' => esc_js($porto_settings['zoom-type'] == 'inner' ? 0 : $porto_settings['zoom-border']),
            'screen_lg' => esc_js($porto_settings['container-width'] + $porto_settings['grid-gutter-width']),
            'mfp_counter' => esc_js(__('%curr% of %total%', 'porto')),
            'mfp_img_error' => esc_js(__('<a href="%url%">The image</a> could not be loaded.', 'porto')),
            'mfp_ajax_error' => esc_js(__('<a href="%url%">The content</a> could not be loaded.', 'porto')),
            'popup_close' => esc_js(__('Close', 'porto')),
            'popup_prev' => esc_js(__('Previous', 'porto')),
            'popup_next' => esc_js(__('Next', 'porto')),
            'request_error' => esc_js(__('The requested content cannot be loaded.<br/>Please try again later.', 'porto'))
        ) );
    }
}

function porto_admin_css() {
    // simple line icon font
    wp_dequeue_style( 'bsf-Simple-Line-Icons' );
    wp_dequeue_style( 'porto_shortcodes_simpleline' );
    wp_enqueue_style('porto-sli-font', porto_css . '/Simple-Line-Icons/Simple-Line-Icons.css', false, porto_version, 'all');

    // wp default styles
    wp_enqueue_style( 'wp-color-picker' );

    // codemirror
    wp_enqueue_style('porto_codemirror', porto_css . '/codemirror.css', false, porto_version, 'all');

    // admin style
    wp_enqueue_style('porto_admin', porto_css . '/admin.css', false, porto_version, 'all');
    wp_enqueue_style('porto_admin_bar', porto_css . '/admin_bar.css', false, porto_version, 'all');

    porto_enqueue_revslider_css();
}

function porto_admin_scripts() {
    if (function_exists('add_thickbox'))
        add_thickbox();

    wp_enqueue_media();

    global $pagenow;
    if (in_array($pagenow, array('post.php', 'post-new.php', 'term.php'))) {
        // codemirror
        wp_register_script('porto-codemirror', porto_js.'/codemirror.js', array('jquery'), porto_version, true);
        wp_enqueue_script('porto-codemirror');
        wp_register_script('porto-codemirror-css', porto_js.'/codemirror/css.js', array('porto-codemirror'), porto_version, true);
        wp_enqueue_script('porto-codemirror-css');
        wp_register_script('porto-codemirror-js', porto_js.'/codemirror/javascript.js', array('porto-codemirror'), porto_version, true);
        wp_enqueue_script('porto-codemirror-js');
    }

    // admin script
    wp_register_script('porto-admin', porto_js.'/admin.js', array('common', 'jquery', 'media-upload', 'thickbox', 'wp-color-picker'), porto_version, true);
    wp_enqueue_script('porto-admin');

    wp_localize_script( 'porto-admin', 'js_porto_admin_vars', array(
        'import_options_msg' => __('If you want to import demo, please backup current theme options in "Import / Export" section before import. Do you want to import demo?', 'porto'),
        'theme_option_url' => admin_url('admin.php?page=porto_settings')
    ) );
}

// Disable the WordPress Admin Bar for all but admins
if (! current_user_can('edit_posts')):
    show_admin_bar(false);
endif;

function porto_footer_hook() {
    add_filter('style_loader_tag', 'porto_style_loader_tag');
}

function porto_style_loader_tag($tag) {
    return str_replace("rel='stylesheet'", "rel='stylesheet' property='stylesheet'", $tag);
}

function porto_enqueue_custom_css() {
    global $porto_settings;

    if ($porto_settings['logo-type'] === 'text')
        return;

    $logo_width = (isset($porto_settings['logo-width']) && (int)$porto_settings['logo-width']) ? (int)$porto_settings['logo-width'] : 170;
    $logo_width_wide = (isset($porto_settings['logo-width-wide']) && (int)$porto_settings['logo-width-wide']) ? (int)$porto_settings['logo-width-wide'] : 250;
    $logo_width_tablet = (isset($porto_settings['logo-width-tablet']) && (int)$porto_settings['logo-width-tablet']) ? (int)$porto_settings['logo-width-tablet'] : 110;
    $logo_width_mobile = (isset($porto_settings['logo-width-mobile']) && (int)$porto_settings['logo-width-mobile']) ? (int)$porto_settings['logo-width-mobile'] : 110;
    $logo_width_sticky = (isset($porto_settings['logo-width-sticky']) && (int)$porto_settings['logo-width-sticky']) ? (int)$porto_settings['logo-width-sticky'] : 80;
    ?><style rel="stylesheet" property="stylesheet" type="text/css">.ms-loading-container .ms-loading, .ms-slide .ms-slide-loading { background-image: none !important; background-color: transparent !important; box-shadow: none !important; } #header .logo { max-width: <?php
        echo $logo_width ?>px; } @media (min-width: <?php echo ($porto_settings['container-width'] + $porto_settings['grid-gutter-width']) ?>px) { #header .logo { max-width: <?php
        echo $logo_width_wide ?>px; } } @media (max-width: 991px) { #header .logo { max-width: <?php
        echo $logo_width_tablet ?>px; } } @media (max-width: 767px) { #header .logo { max-width: <?php
        echo $logo_width_mobile ?>px; } } <?php if ($porto_settings['change-header-logo']) : ?>#header.sticky-header .logo { max-width: <?php
        echo $logo_width_sticky * 1.25 ?>px; }<?php endif; ?></style><?php
}

function porto_enqueue_revslider_css() {
    global $porto_settings;

    $style = '';
    if ($porto_settings['skin-color']) {
        $style = '.tparrows:before{color:' . $porto_settings['skin-color'] . ';text-shadow:0 0 3px #fff;}';
    }
    $style .= '.revslider-initialised .tp-loader{z-index:18;}';

    wp_add_inline_style('rs-plugin-settings', $style);
}

function cptui_register_my_taxes_model() {

	/**
	 * Taxonomy: Models.
	 */

	$labels = array(
		"name" => __( 'Models', 'porto-child' ),
		"singular_name" => __( 'Model', 'porto-child' ),
	);

	$args = array(
		"label" => __( 'Models', 'porto-child' ),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => true,
		"label" => "Models",
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'model', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => false,
		"rest_base" => "",
		"show_in_quick_edit" => true,
	);
	register_taxonomy( "model", array( "product" ), $args );
}

add_action( 'init', 'cptui_register_my_taxes_model' );

/**
 * Get taxonomies terms links.
 *
 * @see get_object_taxonomies()
 */
function wpdocs_custom_taxonomies_terms_links() {
    
    $taxonomy = 'Models';
    
    // Get post by post ID.
    $post = get_post( $post->ID );
 
    // Get post type by post.
    $post_type = 'product';
 
    // Get post type taxonomies.
    $taxonomies = get_object_taxonomies( $post_type, 'objects' );
 
    $out = array();
 
    foreach ( $taxonomies as $taxonomy_slug => $taxonomy ){
 
        // Get the terms related to post.
        $terms = get_the_terms( $post->ID, $taxonomy_slug );
 
        if ( ! empty( $terms ) ) {
            $out[] = "<h2>" . $taxonomy->label . "</h2>\n<ul>";
            foreach ( $terms as $term ) {
                $out[] = sprintf( '<li><a href="%1$s">%2$s</a></li>',
                    esc_url( get_term_link( $term->slug, $taxonomy_slug ) ),
                    esc_html( $term->name )
                );
            }
            $out[] = "\n</ul>\n";
        }
    }
    return implode( '', $out );
}

/* TRD--  подключает экшен для пользовательского AJAX */
add_action( 'wp_enqueue_scripts', function(){

    wp_enqueue_script( 'trd_search_site', get_template_directory_uri() . '/js/trd_scripts.js', array( 'jquery' ));
    wp_localize_script( 'trd_search_site', 'TRDJS', array('ajax_url' => admin_url( 'admin-ajax.php' )));
});
/* TRD--  подключает экшен для пользовательского AJAX */


/* TRD--  возвращает количество моделей телефонов в базе */
function trd_getCountModels(){
    global $wpdb;

    $query = "SELECT COUNT(term_id) FROM u1co1ss6_terms WHERE term_id IN 
	(SELECT term_id FROM u1co1ss6_term_taxonomy WHERE `taxonomy` = 'product_tag' AND parent = 0)";

    return $wpdb->get_var($query);
}
/* TRD--  возвращает количество моделей телефонов в базе */

/* TRD--  возвращает словарь для текстовых элементов функций для текущего языка */
function trd_getVocabulary($string){
    global $wpdb;
    $lang = strtolower(get_locale());
    $query = "SELECT {$lang} FROM u1co1ss6_trd_vocabulary WHERE `de_DE` = '{$string}'";

    $result = $wpdb->get_var($query);

    return $result;
} // trd_getVocabulary
/* TRD--  возвращает словарь для текстовых элементов функций для текущего языка */

/* TRD--  возвращает словарь для текстовых элементов функций для текущего языка */
function trd_getVocabularyForAJAX(){
    global $wpdb;
    $string = urldecode($_POST['string']);
    $lang = strtolower(get_locale());
    $query = "SELECT {$lang} FROM u1co1ss6_trd_vocabulary WHERE `de_DE` = '{$string}'";
    echo $query;
    $result = $wpdb->get_var($query);

    //echo $result;
    exit();
} // trd_getVocabularyForAJAX
add_action('wp_ajax_trd_getVocabulary', 'trd_getVocabularyForAJAX');
add_action('wp_ajax_nopriv_trd_getVocabulary', 'trd_getVocabularyForAJAX');
/* TRD--  возвращает словарь для текстовых элементов функций для текущего языка */

/* TRD--                     вывод подходящих телефонов для товара */
function trd_showModelsForProduct(){
    global $wpdb;
    global $post;
    
    $terms = get_the_terms( $post->ID, 'product_cat' );
    $coverName = str_replace(' ', '-', $terms[0] -> name);
        
    $query = "SELECT DISTINCT postmeta.meta_value 'model', posts.guid FROM `u1co1ss6_postmeta` postmeta JOIN u1co1ss6_posts posts ON posts.id = postmeta.post_id 
		WHERE  posts.post_status = 'publish' AND meta_key = 'device_name' AND post_id IN (
            SELECT object_id FROM u1co1ss6_term_relationships WHERE term_taxonomy_id = 
            (SELECT term_id FROM u1co1ss6_terms WHERE slug LIKE '%{$coverName}')) ORDER BY postmeta.meta_value";

    // возвращает массив всех моделей телефонов для конкретного чехла
    $results = $wpdb->get_results($query);

    // массив для группировки телефонов по производителям
    $phonesArrayByProducer = array();
	$urls = array(); 	// массив ссылок на телефоны
    foreach($results as $result) { 
			$producer = substr($result->model,0,strpos($result->model," "));
            
            $phonesArrayByProducer[$producer][] = $result->model;
			$urls[$result->model] = $result->guid;
    } // foreach $result
	
    ksort(strtoupper($phonesArrayByProducer));
	
	echo '<div class="row ps-val-green">';
    foreach($phonesArrayByProducer as $prod => $models){
        asort($models);
        echo '<div class="col-md-3">';
            echo '<div class="green-box">';
			$modelUrl = "model/".$wpdb->get_var("SELECT slug FROM $wpdb->terms WHERE name LIKE '{$prod}'");	// алиас бренда
			
                echo "<h4><a href='/{$modelUrl}' target='_blank'>".$prod."</a></h4>";
                echo '<p>';
                foreach ($models as $model){
                    echo "<a href='{$urls[$model]}' target='_blank'>".$model.'</a><br/>';
                }
                echo '</p>';
            echo '</div>';
        echo '</div>';
    }
    echo "</div>";
} // trd_showModelsForProduct
add_shortcode('trd_showModels', 'trd_showModelsForProduct');
/* TRD--        вывод подходящих телефонов для товара         */

/* TRD--        вывод полей для ссылок на товары для клиентов B2B        */

add_shortcode('trd_links_for_b2b', 'trd_linksForB2B');



function trd_linksForB2B(){    
    $current_user = wp_get_current_user();     
	$user_id = $current_user->ID;
	
	global $wpdb;        
	
	$results = $wpdb->get_results("SELECT url, boon_id  FROM u1co1ss6_trd_link_merchants WHERE merch_id = {$user_id}");
	
	// преобразовываем результат в удобный массив
	$linkByBoons = array();
	foreach ($results as $item) {            
		$linkByBoons[$item->boon_id] = $item->url;
	}

	// вывод шапки для "формы"
	echo '<div class="container"><div class="row psa-ecomerse" style="margin-top:30px;">';
    echo trd_getVocabulary('<h4>Benutzerdefinierte Suchfunktion</h4><p>Vereinfachen Sie die Suche nach den passenden reboon Hüllen auf Ihrer Website.</p><h5>Benutzerdefinierte Suchfunktion erstellen</h5><p>Mit der benutzerdefinierten Suchfunktion von reboon wird zu Ihrer Website ein Suchfeld hinzugefügt, damit Nutzer schneller die passende reboon Hülle zum Endgerät auf Ihrer Website finden. Unsere Datenbank wir ständig erweitert und erfasst derzeit ');
    echo trd_getCountModels();
    echo trd_getVocabulary(' Modelle, die mit dem reboon Sortiment passgenau abgedeckt werden.</p><ul><li><strong>1. Schritt:</strong> Tragen Sie die Links der reboon Produkte auf ihrer Website bzw. die Affiliate-Links in die jeweiligen Felder ein.</li><li><strong>2. Schritt:</strong> Bestätigen Sie die Eingabe mit „Speichern“. Die Links werden gespeichert und bleiben beim zukünftigen Einloggen weiter bestehen.</li><li><strong>3. Schritt:</strong> Benutzen Sie den unterstehenden Code, um die reboon Suche auf ihrer Website einzubinden.</li></ul><h5>Links updaten</h5><p>Zum Verändern der abgespeicherten Links, geben Sie die neuen URLs ein und bestätigen Sie Ihre Eingabe mit „Speichern“.</p><p>Die URL-Adresse zu Ihrer Benutzerdefinierten Suchfunktion bleibt dabei unverändert, sodass keine weiteren Aktionen notwendig sind.</p>');
	echo '<div class="panel panel-default widget"><div class="panel-body"><ul class="list-group">';
	$placeholder = trd_getVocabulary('URL zum Produkt hier eingeben');
	// вывод полей со ссылками        
		echo <<<HTML
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/02/boonflip-stand-iPhone2-leder-black.png" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[1]}">
							boonflip XS</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="1" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[1]}">
						 </div>
					</div>
				</div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/02/boonflip-stand-iPhone2-leder-black.png" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[2]}">
							boonflip XS2</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="2" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[2]}">
						 </div>
					</div>
				</div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/02/boonflip-stand-iPhone2-leder-black.png" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[3]}">
							boonflip XS3</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="2" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[3]}">
						 </div>
					</div>
				</div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/02/boonflip-stand-iPhone2-leder-black.png" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[4]}">
							boonflip XS4</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="4" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[4]}">
						 </div>
					</div>
				</div>
			</div>
		</li>
		
		<hr />
		
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/01/booncover-XS-fur-smartphones-black.jpg" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[5]}">
							booncover XS</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="5" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[5]}">
						 </div>
					</div>
				</div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/01/booncover-XS-fur-smartphones-black.jpg" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[6]}">
							booncover XS2</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="6" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[6]}">
						 </div>
					</div>
				</div>
			</div>
		</li>
		
		<hr />

		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/01/booncover_S_black_Standposition_mit_Tablet.png" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[7]}">
							booncover S</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="7" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[7]}">
						 </div>
					</div>
				</div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/01/booncover_S2_black_Front.png" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[8]}">
							booncover S2</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="8" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[8]}">
						 </div>
					</div>
				</div>
			</div>
		</li>            
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/01/booncover_S3_beige_Standposition_mit_Tablet.png" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[9]}">
							booncover S3</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="9" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[9]}">
						 </div>
					</div>
				</div>
			</div>
		</li>            
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/01/booncover_M_black_Standposition_mit_Tablet.png" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[10]}">
							booncover M</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="10" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[10]}">
						 </div>
					</div>
				</div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/01/booncover_M2_black_Front.png" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[11]}">
							booncover M2</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="11" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[11]}">
						 </div>
					</div>
				</div>
			</div>
		</li>            
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/01/booncover_L_black_Standposition_mit_Tablet.png" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[12]}">
							booncover L</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="12" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[12]}">
						 </div>
					</div>
				</div>
			</div>
		</li>
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/02/booncover_L2_black_leder_Front.png" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[13]}">
							booncover L2</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="13" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[13]}">
						 </div>
					</div>
				</div>
			</div>
		</li>            
		<li class="list-group-item">
			<div class="row">
				<div class="col-xs-2 col-md-2">
					<img src="https://www.reboon.de/wp-content/uploads/2017/01/booncover_XL_leder_Front.jpg" class="img-responsive" alt="" /></div>
				<div class="col-xs-10 col-md-10">
					<div>
						<a href="{$linkByBoons[14]}">
							booncover XL</a>
					</div>
					<div class="action">
						 <div class="input-group">
							  <div class="input-group-addon">
								  URL
							  </div>
							 <input type="text" data_id_boon="14" class="form-control trd_link" placeholder="{$placeholder}" value="{$linkByBoons[14]}">
						 </div>
					</div>
				</div>
			</div>
		</li>
HTML;

        // вывод футера для "формы"
        echo "</ul>            
            <p class=\"text-center\" style=\"margin-top: 15px;\">
                     <button type=\"button\" class=\"btn btn-success btn-md\" onClick=\"trd_save_links({$user_id})\" title=\"Approved\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i> ";
        echo trd_getVocabulary('SPEICHERN');
        echo '</button></p></div></div></div></div>';

	echo "<div class='iframe-area'><h5>";
	echo trd_getVocabulary('Code zum Einfügen auf der Website');
	echo "</h5><p><textarea><iframe src='https://www.reboon.de/boonsearch/search.php?merchant=".$user_id."' frameborder='0' width='100%' height='400px'></iframe></textarea><button class='copy-butt'>";
	echo trd_getVocabulary('CODE KOPIEREN');
    echo "</button></p><p class='message-success'><i class='fa fa-check-circle' aria-hidden='true'></i> ";
    echo trd_getVocabulary('Code wurde erfolgreich in Ihre Zwischenablage kopiert!');
    echo "</p></div>";
} // trd_linksForB2B


function nk_linksSales(){    
//        $current_user = wp_get_current_user();     
//	$user_id = $current_user->ID;
//	
//	global $wpdb;        
//	
//	$results = $wpdb->get_results("SELECT url, boon_id  FROM u1co1ss6_trd_link_merchants WHERE merch_id = {$user_id}");
//	
//	// преобразовываем результат в удобный массив
//	$linkByBoons = array();
//	foreach ($results as $item) {            
//		$linkByBoons[$item->boon_id] = $item->url;
//	}

	
	// вывод полей со ссылками        
		echo <<<HTML
	<h3>Sales page</h3>
HTML;
           
        // вывод футера для "формы"
        echo "</ul>            
            <p class=\"text-center\" style=\"margin-top: 15px;\">
                     <button type=\"button\" class=\"btn btn-success btn-md\" onClick=\"trd_save_links({$user_id})\" title=\"Approved\"><i class=\"fa fa-check\" aria-hidden=\"true\"></i> ";
        echo trd_getVocabulary('SPEICHERN');
        echo '</button></p></div></div></div></div>';

	echo "<div class='iframe-area'><h5>";
	echo trd_getVocabulary('Code zum Einfügen auf der Website');
	echo "</h5><p><textarea><iframe src='https://www.reboon.de/boonsearch/search.php?merchant=".$user_id."' frameborder='0' width='100%' height='400px'></iframe></textarea><button class='copy-butt'>";
	echo trd_getVocabulary('CODE KOPIEREN');
    echo "</button></p><p class='message-success'><i class='fa fa-check-circle' aria-hidden='true'></i> ";
    echo trd_getVocabulary('Code wurde erfolgreich in Ihre Zwischenablage kopiert!');
    echo "</p></div>";
    
} // nk_linksSales()

// добавление дополнительного меню в my-account
function trd_iconic_account_menu_items( $items ) {
	$current_user = wp_get_current_user(); 
//	$temp = array();
       
    
    // если администратор или клиент работает по b2b	
    if(in_array('administrator', $current_user->roles) || in_array('onlinemerchant', $current_user->roles)){		
		
		$temp = array_slice($items,0,1,true);
		$temp['information'] = __(trd_getVocabulary('eCommerce Tools'), 'iconic' );
                
                //добавление пункта меню. если роль админ или продавец
//                if(in_array('administrator', $current_user->roles) || in_array('assistant', $current_user->roles)){
//                    $temp['sales'] = __('Sales', 'iconic' );
//                }
		$temp = array_merge($temp,array_slice($items,1,count($items)-1,true));		
	} else{
		$temp = $items;
	}// if
	
	return $temp;
}

add_filter( 'woocommerce_account_menu_items', 'trd_iconic_account_menu_items', 10, 1 );

/**
 * Add endpoint
 */
//добавление конечных страниц
function iconic_add_my_account_endpoint() {
    
         
 //my-accaunt/information
 add_rewrite_endpoint( 'information', EP_PAGES );
 
 
 //add_rewrite_endpoint( 'sales',  EP_PAGES );  
 
// var_dump(EP_PAGES);
 //my-accaunt/sales
// if(iconic_sales_endpoint_content("sales")){
//     $x=iconic_sales_endpoint_content("sales");
//     echo "<h1>".$x."</h1>";
// }
}//iconic_add_my_account_endpoint()



//вызов функции добавления конечных страниц
add_action( 'init', 'iconic_add_my_account_endpoint' );


/**
 * Information content
 */
//вывод разметки привыборе пункта меню information
function iconic_information_endpoint_content() {
	trd_linksForB2B();
}

function iconic_sales_endpoint_content() {
    nk_linksSales();
    // echo '<h1>ВЫЗОВ Функции</h1>';
    //header("Location: sales.php");
}

function iconic_is_endpoint( $endpoint = false ) {
 
    global $wp_query;
 
    if( !$wp_query )
        return false;
 
    return isset( $wp_query->query[ $endpoint ] );
 
}

add_action( 'woocommerce_account_information_endpoint', 'iconic_information_endpoint_content' );

add_action( 'woocommerce_account_sales_endpoint', 'iconic_sales_endpoint_content' );

/* TRD--        вывод полей для ссылок на товары для клиентов B2B        */

/* TRD--        подключение скрипта для добавления линков для клиентов B2B */       
add_action('wp_ajax_trd_link_js', 'trd_links_for_merchants');

function trd_links_for_merchants(){
    $current_user = wp_get_current_user();
    $user =  $current_user->ID;

    // если клиент работает по b2b     
    if(in_array('administrator', $current_user->roles) || in_array('onlinemerchant', $current_user->roles)){
        $user_id = $current_user->ID;
        $urls = $_POST['mapLinks'];

        global $wpdb;
        
        for($i = 0; $i < count($urls); $i++){
			$id = $i+1;      
			$link = esc_sql($urls[$i]);
			$query = "INSERT INTO u1co1ss6_trd_link_merchants (link_id, boon_id, merch_id, url) VALUE ((SELECT * FROM 
					(SELECT link_id FROM u1co1ss6_trd_link_merchants WHERE boon_id = {$id} AND merch_id = {$user}) AS t1),
					{$id}, {$user}, '{$link}') ON DUPLICATE KEY UPDATE url = '{$link}'";
		   
			$res = $wpdb->query("INSERT INTO u1co1ss6_trd_link_merchants (link_id, boon_id, merch_id, url) VALUE ((SELECT * FROM 
				(SELECT link_id FROM u1co1ss6_trd_link_merchants WHERE boon_id = {$id} AND merch_id = {$user}) AS t1),
				{$id}, {$user}, '{$link}') ON DUPLICATE KEY UPDATE url = '{$link}'");
        } // for i

    } // if
    
} // trd_links_for_merchants

// добавление AJAX функции
add_action('wp_ajax_trd_ecommerce_tools', 'trd_links_for_merchants');
/* TRD--        подключение скрипта для добавления линков для клиентов B2B        */

/* TRD--	вывод ссылки на второй чехол для телефона, если есть */
function trd_showLinkForAnotherCase(){
	global $wpdb, $post;

	$id = $post->ID;
	$query = "SELECT posts.id, posts.post_name 'link', posts.post_title 'title', posts.post_excerpt 'image', post_meta.meta_value 'device' 
	FROM u1co1ss6_posts posts JOIN u1co1ss6_postmeta post_meta ON posts.id = post_meta.post_id WHERE post_status = 'publish' AND posts.id IN 
	(SELECT post_id FROM u1co1ss6_postmeta WHERE meta_value = (SELECT meta_value FROM `u1co1ss6_postmeta` WHERE meta_key = 'device_name' AND post_id = {$id})) 
	AND post_meta.meta_key = 'device_name'";	
		
	// возвращает массив всех id и post_name для данного телефона
	$results = $wpdb->get_results($query);			

	// строка для формирования ссылки на телефон
	$link = '';	
	
	// если более 1 чехла для данного телефона
	if (count($results) > 1) {
		foreach($results as $result) {			
			if ($result->id != $id) {
				$request = get_site_url()."/shop/".$result->link;	
                
				$model = $result->device;

                $device_type = (stripos($result->title, "flip") > 0) ? "flip-cover" : "book-cover";
				echo "<div class='trd_another_case'><div><a href='".$request."'>".$result->image."</a>";	
				echo "<p><span>".trd_getVocabulary('Für das ') . $model . trd_getVocabulary(" gibt es die Handyhülle auch als ") .$device_type. ":</span><a href='".$request."'>".$result->title."</a>";
				echo "<a href='".$request."' class='btn ps-btn'>".trd_getVocabulary("Anzeigen")."</a>";
				echo "</p></div></div>";
			}	// if			
		} // foreach $result
	} // if
} // trd_showLinkForAnotherCase
add_shortcode('trd_showAnotherLink', 'trd_showLinkForAnotherCase');
/* TRD--	вывод ссылки на второй чехол для телефона, если есть */

/* TRD--	ajax-запросы для поисковой формы*/
function trd_returnAllPhones() { 
	global $wpdb;
	
	// возвращает модели телефонов без учета тех, которые на draft
    /*$query = "SELECT DISTINCT postmeta.meta_value 'name' FROM $wpdb->postmeta postmeta
				INNER JOIN  $wpdb->posts posts ON postmeta.post_id = posts.id
					WHERE posts.post_status = 'publish' AND postmeta.meta_key = 'device_name'";*/
    $query = "SELECT name FROM u1co1ss6_terms 
           JOIN u1co1ss6_term_relationships ON term_taxonomy_id = term_id
           JOIN u1co1ss6_postmeta pm ON object_id = post_id
WHERE meta_key = 'release_year' AND term_id IN
                (SELECT term_id FROM u1co1ss6_term_taxonomy WHERE `taxonomy` = 'product_tag' AND parent = 0) ORDER BY meta_value DESC, `u1co1ss6_terms`.`name` ASC";

	$result = $wpdb->get_results($query);
	$strPhones = array();
	foreach ($result as $phone) {
		if ($phone->name)
			$strPhones[] = $phone->name;
	} // foreach
	
	array_push($strPhones, "booncover XS","booncover XS2","booncover S","booncover M","booncover L","booncover S2",
            "booncover M2","booncover L2","booncover XL","booncover S3","boonflip XS","boonflip XS2",
            "boonflip XS3","boonflip XS4");

        //asort($strPhones);
        echo implode(",", $strPhones);    
    exit; //чтобы в ответ не попало ничего лишнего
} // trd_returnAllPhones

// возвращает подходящие чехлы или ссылку на чехол
function trd_getAcceptPhones() {
	global $wpdb;
	$str = esc_sql(urldecode($_POST['query'])); // введенный запрос
	
	// если выбран чехол (booncover или boonflip)
		if (stripos($str,"booncover") !== false || stripos($str,"boonflip") !== false) {
		    $query = "SELECT guid FROM u1co1ss6_posts WHERE post_status = 'publish' AND post_title LIKE '{$str}' LIMIT 1";
            $res_boons = $wpdb->get_results($query);
			
            if (count($res_boons) > 0) {
                foreach($res_boons as $row) {
					$result = $row->guid; 
                } // foreach
            } else {
                $result = '<p class="info">'.trd_getVocabulary("Keine Suchergebnisse").'</p>';
            } // if

        }else { // если выбран телефон
			//$locale = substr(get_locale(),0,2);					// используется для многоязычных сайтов
			// возвращает модели телефонов без учета тех, которые на draft
			$query = "SELECT posts.guid, posts.post_excerpt 'image', posts.post_title 'title', posts.post_name 'name', posts.guid, pm.meta_value 'value' 
						FROM u1co1ss6_posts posts INNER JOIN u1co1ss6_postmeta pm ON posts.id = pm.post_id						
						WHERE pm.meta_key = '_yoast_wpseo_metadesc' AND posts.post_status = 'publish' AND posts.ID IN 
						(SELECT post_id FROM u1co1ss6_postmeta WHERE meta_key = 'device_name' AND meta_value LIKE '{$str}')";
			$res_boons = $wpdb->get_results($query);
           
            $result = "";	// результативный html
            
            if (count($res_boons) > 0) {
				if(count($res_boons) == 1){									
					//$result = "/shop/".$res_boons[0]->name;					
					$result = $res_boons[0]->guid;					
				}else{
					$result .= '<div class="psa-oder">'.trd_getVocabulary("oder").'</div>';
					foreach($res_boons as $row) {
						// возвращает только ссылку на картинку
						$image = substr($row->image, strpos($row->image, 'src="'),
							-strlen(substr($row->image, strpos($row->image, ' alt='))));
						
						// ссылка на товар
						//$url = "/shop/".$row->name;
						$url = $row->guid;
						
						// возвращает название чехла
						$boon = substr($row->value, strripos($row->value, 'boon'));
						
						$result .= '<div class="psa-item"><div class="image-block">';
						$result .= '<a class="trd_link_search" href="'.$url.'"><img class="psa-image';
						$title = "Flip-Cover";
                        
						if(stripos($row->title,"(flip)") === false) {
                            $result .= ' trd_image_cover';		// изменяем размер картинки для booncover
                            $title = "Book-Cover";
                        }
						$result .= '" ' . $image . '/></a>';
						$result .= '</div><div class="title-block">';
                        
						$result .= '<h3>' . $title . '</h3>';   
						
						// ссылка на страницу товара
						$result .= '<a href="'.$url.'" class="btn psa-button"><!--' . $boon . '-->'.trd_getVocabulary("Entdecke mehr").'</a></div></div>';
					} // foreach
				}
            } else {
                $result = '<p class="info">'.trd_getVocabulary("Keine Suchergebnisse").'</p>';
            } // if
		}
		echo $result;
		exit;
} // trd_getAcceptPhones

// Поиск с выбором модели
function trd_phonesByModel() { 
	global $wpdb;
	$model = urldecode($_POST['model']);
	$query = "SELECT DISTINCT postmeta.meta_value FROM `u1co1ss6_postmeta` postmeta INNER JOIN  $wpdb->posts posts ON postmeta.post_id = posts.id
					WHERE posts.post_status = 'publish' AND postmeta.meta_key =  'device_name' AND postmeta.post_id IN 
						(SELECT object_id FROM u1co1ss6_term_relationships WHERE `term_taxonomy_id` = {$model}) 
					ORDER BY postmeta.meta_value";
	
	$results = $wpdb->get_results($query);					
	
	$resOptions = "<option disabled selected value='-1'>und wähle dein Modell</option>"; // HTML код телефонов
	foreach ($results as $phone) {
		if ($phone->meta_value)
			$resOptions .= "<option value='{$phone->meta_value}'>{$phone->meta_value}</option>";		
	} // foreach
           
	echo $resOptions;
    exit; //чтобы в ответ не попало ничего лишнего
} // trd_phonesByModel
 
add_action('wp_ajax_trd_get_allPhones', 'trd_returnAllPhones');
add_action('wp_ajax_nopriv_trd_get_allPhones', 'trd_returnAllPhones');
add_action('wp_ajax_trd_get_acceptPhones', 'trd_getAcceptPhones');
add_action('wp_ajax_nopriv_trd_get_acceptPhones', 'trd_getAcceptPhones');
add_action('wp_ajax_trd_get_phonesByModel', 'trd_phonesByModel');
add_action('wp_ajax_nopriv_trd_get_phonesByModel', 'trd_phonesByModel');
/* TRD--	возврат всех моделей телефонов, планшетов и book */

/* TRD--	поисковая форма */
function trd_search(){
    $enter_your_device = trd_getVocabulary("Gib dein Gerät hier ein");
	echo <<<SEARCH
	<section class="reboon-bg search">
        <div class="container">
          <div class="form-group">            
            <input type="text" class="form-control" id="InputSearch" placeholder="$enter_your_device">
			<div class="psa-loader"></div>
			<button type="submit" class="search-action"><i class="fa fa-search" aria-hidden="true"></i></button>              
              <div class="result-window">                       
              </div>
          </div>
        </div>
    </section>
    
    <section class="result">
        <div class="psa-container"> 
        </div>
    </section>
SEARCH;

} // trd_search
add_shortcode('trd_searchPanel', 'trd_search');
/* TRD--	поисковая форма */

/* TRD--	поисковая форма для футера*/
function trd_search_footer(){
    $enter_your_device = trd_getVocabulary("Gib dein Gerät hier ein");
	echo <<<SEARCH
	<section class="reboon-bg search">
        <div class="container">
          <div class="form-group">            
            <input type="text" class="form-control" id="InputSearch_footer" placeholder="$enter_your_device">
			<div class="psa-loader_footer"></div>
			<button type="submit" class="search-action_footer"><i class="fa fa-search" aria-hidden="true"></i></button>
			  <div class="result-window_footer">                       
              </div>
          </div>
        </div>
    </section>
    
	<section class="result_footer">
        <div class="psa-container"> 
        </div>
    </section>
SEARCH;

} // trd_search
add_shortcode('trd_searchPanel_footer', 'trd_search_footer');
/* TRD--	поисковая форма для футера*/

/* TRD--	поисковая форма со вводом размеров телефона*/
function trd_search_by_size(){	
	global $wpdb;
	$height = trd_proccessingOfSize($_POST['height']);	// введенная высота
	$width = trd_proccessingOfSize($_POST['width']);	// введенная ширина
	$depth = trd_proccessingOfSize($_POST['depth']);	// введенная толщина
	$bf = false;									// флаг нахождения flip
	$bc = false;									// флаг нахождения cover
	$bfModel = $bcModel = "NO CASE SUITS";			// модель для вывода для flip / cover
	
	// выбор flip
	if($height <= 127.5 && $width <= 65 && $depth <= 9.6){		// bf XS3
		$bfModel = "boonflip XS3";
		$bf = true;		
	}else if($height <= 145 && $width <= 72 && $depth <= 9){	// bf XS
		$bfModel = "boonflip XS";
		$bf = true;
	}else if($height <= 152.5 && $width <= 77 && $depth <= 9){	// bf XS4
		$bfModel = "boonflip XS4";
		$bf = true;
	}else if($height <= 160 && $width <= 80.5 && $depth <= 9){	// bf XS2
		$bfModel = "boonflip XS2";
		$bf = true;
	} // if
	
	// выбор cover
	if($height <= 147 && $width <= 74 && $depth <= 7.6){											// bc XS
		$bcModel = "booncover XS";
		$bс = true;
	}elseif($height <= 159 && $width <= 83 && $depth <= 8.7){										// bc XS2
		$bcModel = "booncover XS2";
		$bс = true;
	}elseif($height <= 179 && $width >= 110 && $width <= 130 && $depth <= 11.5){					// bc S3
		$bcModel = "booncover S3";
		$bс = true;
	}elseif($height <= 202 && ($width >= 101 && $width <= 121) && $depth <= 10.5){					// bc S2
		$bcModel = "booncover S2";
		$bс = true;
	}elseif($height >= 183 && $height <= 203 && $width >= 118 && $width <= 138 && $depth <= 8.5){	// bc S
		$bcModel = "booncover S";	
		$bс = true;
	}elseif($height > 203 && $height <= 222 && $width >= 115 && $width <= 135 && $depth <= 9){		// bc M2
		$bcModel = "booncover M2";
		$bс = true;
	}elseif($height > 222 && $height <= 245 && $width >= 158 && $width <= 178 && $depth <= 8.5){	// bc M
		$bcModel = "booncover M";
		$bс = true;
	}elseif($height >= 229 && $height <= 249 && $width > 178 && $width <= 191 && $depth <= 11){	// bc L
		$bcModel = "booncover L";
		$bс = true;
	}elseif($height > 249 && $height <= 268 && $width >= 163 && $width <= 183 && $depth <= 11.5){	// bc L2
		$bcModel = "booncover L2";
		$bс = true;
	}elseif($height > 286 && $height <= 2306 && $width >= 200 && $width <= 221 && $depth <= 11){	// bc XL
		$bcModel = "booncover XL";
		$bс = true;
	} // if-else	
			
	$query = "SELECT ps1.guid 'img', ps.guid 'url', ps.post_title 'name' FROM `u1co1ss6_posts` ps1 
				JOIN u1co1ss6_postmeta ON ID = meta_value 
					JOIN (SELECT id, guid, post_title FROM u1co1ss6_posts posts
					WHERE posts.`post_title` LIKE  '{$bfModel}' OR `post_title` LIKE  '{$bcModel}') AS ps
					ON post_id = ps.id WHERE meta_key =  '_thumbnail_id'";
					
	$results = $wpdb->get_results($query);
		
	$win_result = "";
	// если найдено 2 чехла
	if(count($results) > 1){
		$win_result = "<div class='size-search-result'>
						<div class='result-wrapper'>
						<p>Result:</p>";
						foreach($results as $case){
							$win_result .= "<div class='item'><a href='{$case->url}'><img src='{$case->img}' /> {$case->name}</a></div>";
						} // foreach
						
		$win_result .= "</div></div></div>";
		// если найден 1 чехол
	}elseif(count($results) == 1){
		$win_result = $results[0]->url;
	}else{
		$win_result = "<div class='size-search-result'>".trd_getVocabulary("Keine Suchergebnisse")."</div>";
	} // if-else
	
	echo $win_result;
	
	exit;
	
} // trd_search_by_size
add_action('wp_ajax_trd_searchSize', 'trd_search_by_size');
add_action('wp_ajax_nopriv_trd_searchSize', 'trd_search_by_size');
/* TRD--	поисковая форма со вводом размеров телефона*/

/* TRD--	обработка размеров телефонов*/
function trd_proccessingOfSize($size){
    return (double)str_replace(",", ".", urldecode($size));
} // trd_proccessingOfSize
/* TRD--	обработка размеров телефонов*/



/* TRD--	Поиск с выбором модели */
function trd_search_windows(){
	global $wpdb;
	// получение моделей телефонов
	$results = $wpdb->get_results("SELECT term_id 'id', name FROM u1co1ss6_terms WHERE term_id IN 
		(SELECT term_id FROM u1co1ss6_term_taxonomy WHERE `taxonomy` = 'model' AND parent = 0) ORDER BY name");	
	
	// селект для брендов
	echo "<div class='trd_search_by_models'>";
	echo "<div class=select-wrap>";
	echo "<select id='brand'>
			<option value='-1'>".trd_getVocabulary("Hersteller")."</option>";
		foreach($results as $model){
			echo "<option value='{$model->id}'>{$model->name}</option>";
		} // foreach	
	echo "</select></div><div class='psa-loader_searchtrd'></div>";
	
	// пустой селект для телефонов (заполняется JS после выбора бренда)
	echo "<div class='select-wrap'>";
	echo "<select id='model' disabled='disabled'>
		<option value='-1'>".trd_getVocabulary("Modell")."</option>";
	echo "</select></div>";
	echo '<section class="resultByModel"><div class="psa-container"></div></div></section>';

} // trd_search

add_shortcode('trd_searchWindows', 'trd_search_windows');
/* TRD--	Поиск с выбором модели */

/* TRD--	Ставлю картинку 1 по умолчанию */
function trd_addDefaulImage(){
	global $wpdb;
	$query = "INSERT INTO u1co1ss6_postmeta (post_id, meta_key, meta_value) VALUES ";

	$brown = 'a:1:{s:8:\"pa_color\";s:5:\"braun\";}';
	$brown_boons = array(1050, 838, 1132, 953, 1179, 589);
	$brown_lether = 'a:1:{s:8:\"pa_color\";s:11:\"braun-leder\";}';
	$brown_lether_boons = array(1084, 1208, 3413, 6, 1345, 1556, 1594, 1657);

	// получение моделей телефонов
	$results = $wpdb->get_results("SELECT DISTINCT postmeta.post_id 'id' FROM `u1co1ss6_postmeta` postmeta WHERE post_id IN 
		(SELECT object_id FROM u1co1ss6_term_relationships WHERE term_taxonomy_id IN ($brown_lether_boons)) ORDER BY post_id");
	foreach($results as $result){
		$query .= "({$result->id},'_default_attributes',$brown_lether), ";
	}
	
	echo substr($query, 0, sizeof($query)-3);	
}
add_shortcode('trd_addDefaulImage', 'trd_addDefaulImage');
/* TRD--	Ставлю картинку 1 по умолчанию */

/* TRD--	Добавляю товар "купите также" (crosssell) */
/*
	113 => a:1:{i:0;i:77123;}
	114 => a:1:{i:0;i:77122;}
	115 => a:1:{i:0;i:77119;}
	116 => a:1:{i:0;i:55677;}
	
*/
function trd_addCrossSell(){
	global $wpdb;
	$query = "INSERT INTO u1co1ss6_postmeta (post_id, meta_key, meta_value) VALUES ";
	
	// id буна для получения всех его телефонов
	$boon_id = 1345;
	// получение моделей телефонов
	//$results = $wpdb->get_results("SELECT DISTINCT postmeta.post_id 'id' FROM `u1co1ss6_postmeta` postmeta WHERE post_id IN
	//	(SELECT object_id FROM u1co1ss6_term_relationships WHERE term_taxonomy_id = 1594) ORDER BY post_id");
	$results = $wpdb->get_results("SELECT DISTINCT postmeta.post_id 'id' FROM `u1co1ss6_postmeta` postmeta WHERE post_id IN 
		(SELECT object_id FROM u1co1ss6_term_relationships WHERE term_taxonomy_id = 1556) 
AND post_id NOT IN (SELECT post_id FROM `u1co1ss6_postmeta` WHERE meta_key = '_crosssell_ids')
ORDER BY post_id");
	foreach($results as $result){
		$query .= "({$result->id},'_crosssell_ids','a:1:{i:0;i:77119;}'), ({$result->id},'_upsell_ids','a:1:{i:0;i:77119;}'), ";
	}
	
	echo substr($query, 0, sizeof($query)-3);	
	echo get_locale();
}
add_shortcode('trd_addCrossSell', 'trd_addCrossSell');
/* TRD--	Добавляю товар "купите также" */

/* TRD--	Добавляю товар "купите также" (upsell) */
/*
	113 => a:1:{i:0;i:77123;}
	114 => a:1:{i:0;i:77122;}
	115 => a:1:{i:0;i:77119;}
	116 => a:1:{i:0;i:55677;}
	
*/
function trd_addUpSell(){
	global $wpdb;
	$query = "INSERT INTO u1co1ss6_postmeta (post_id, meta_key, meta_value) VALUES ";
	
	// id буна для получения всех его телефонов
	$boon_id = 1345;
	// получение моделей телефонов
	$results = $wpdb->get_results("SELECT DISTINCT postmeta.post_id 'id', meta_value 'value' FROM `u1co1ss6_postmeta` postmeta WHERE meta_key = '_crosssell_ids' AND post_id IN 
		(SELECT object_id FROM u1co1ss6_term_relationships WHERE term_taxonomy_id IN (1345, 1594)) ORDER BY post_id");	
	foreach($results as $result){
		$query .= "({$result->id},'_upsell_ids','{$result->value}'), ";		
	}
	
	echo substr($query, 0, sizeof($query)-3);	
}
add_shortcode('trd_addUpSell', 'trd_addUpSell');
/* TRD--	Добавляю товар "купите также" */

/* TRD--	Изменить title страниц телефонов */
function trd_changeTitle(){
    global $wpdb;

    $query = '';
    // получение моделей телефонов
    $results = $wpdb->get_results("SELECT DISTINCT postmeta.post_id 'id', meta_value 'value' FROM `u1co1ss6_postmeta` postmeta WHERE meta_key = 'device_name' AND post_id IN 
		(SELECT object_id FROM u1co1ss6_term_relationships WHERE term_taxonomy_id IN (1556, 1594, 1657)) ORDER BY post_id");
    foreach($results as $result){
        $query .= "UPDATE u1co1ss6_posts SET post_title  = '{$result->value} Hülle' WHERE id = {$result->id};";
    }

    echo $query;
}
add_shortcode('trd_changeTitle', 'trd_changeTitle');
/* TRD--	Изменить title страниц телефонов */

/* TRD--	Изменить description страниц телефонов */
function trd_changeDescription(){
    global $wpdb;

    $query = '';
    // получение моделей телефонов
    $results = $wpdb->get_results("SELECT DISTINCT postmeta.post_id 'id', meta_value 'value' FROM `u1co1ss6_postmeta` postmeta 
         WHERE meta_key = 'device_name' AND post_id IN 
		  (SELECT object_id FROM u1co1ss6_term_relationships WHERE term_taxonomy_id = 1657) ORDER BY post_id");

    $boon = 'boonflip XS4';
    foreach($results as $result){
        $query .= "UPDATE u1co1ss6_postmeta SET meta_value  = '{$result->value} Hülle - KFZ-Halterung, Tischaufsteller, Wandhalterung, Sicherer Schutz, Volle Bedienbarkeit (Kamera, Touch-Sensor) reboon {$boon}' WHERE meta_key = '_yoast_wpseo_metadesc' AND post_id = {$result->id};";
    }

    echo $query;
}
add_shortcode('trd_changeDescription', 'trd_changeDescription');
/* TRD--	Изменить description страниц телефонов */


/*=--------------- Вывод определенного видео для каждого товара =---------------*/
function psa_showvidos(){
    global $post;
    $terms = get_the_terms( $post->ID, 'product_cat' );
        

    switch($terms[0] -> name) {
            case "booncover S3": 
				echo "<iframe width='100%' height='550' src='https://www.youtube.com/embed/6rYT_nEqjSM?disablekb=1&rel=0&showinfo=0' frameborder='0' allowfullscreen></iframe>";
				break;
            case "booncover S": 
            case "booncover S2": 
            case "booncover M": 
            case "booncover M2": 
            case "booncover L": 
            case "booncover L2": 
            case "booncover XL": 
                echo "<iframe width='100%' height='550' src='https://www.youtube.com/embed/05AwwIIavD8?disablekb=1&rel=0&showinfo=0' frameborder='0' allowfullscreen></iframe>";
                break;
            case "booncover XS": 
            case "booncover XS2": 
                echo "<iframe width='100%' height='550' src='https://www.youtube.com/embed/CDJESM-MTEk?disablekb=1&rel=0&showinfo=0' frameborder='0' allowfullscreen></iframe>";
                break;
            case "boonflip XS": 
            case "boonflip XS2": 
            case "boonflip XS3": 
            case "boonflip XS4": 
                echo "<iframe width='100%' height='550' src='https://www.youtube.com/embed/xM95h4foXO4?disablekb=1&rel=0&showinfo=0' frameborder='0' allowfullscreen></iframe>";
                break;
    }
}
add_shortcode('psa_showvidos', 'psa_showvidos');
 
/*=--------------- Вывод названия чехла в товарах =---------------*/
function psa_show_cover_name($atts){
    
    $product = shortcode_atts( array( 
        'value' => '', 
    ), $atts );
    
    global $post;
    $terms = get_the_terms( $post->ID, 'product_cat' );
    $coverName = $terms[0] -> name;
    
    $value = $product['value'];
    $text;
    // real products 
    if ($value == 'real') { 
        $text = '<style>section.page-top.page-header-3 {display: none;}</style>
                 <h1 class="psa-real-title">Entdecke reboon '.$coverName.'</h1>';
    }
    // virtual products
    else {
        $text = 'reboon '.$coverName.'';
    }
    return $text;
}
add_shortcode('psa_show_cover_name', 'psa_show_cover_name');

/*=---------- Блок лендинга Functionen -----------=*/
function psa_show_functionen(){
    global $post;
    $terms = get_the_terms( $post->ID, 'product_cat' );
        
    // вывод блока Functionen в зависимости от имени чехла
    switch($terms[0] -> name) {
            case "booncover S": 
            case "booncover S2": 
            case "booncover S3": 
            case "booncover M": 
            case "booncover M2": 
            case "booncover L": 
            case "booncover L2": 
            case "booncover XL": 
                return do_shortcode('[vc_row_inner][vc_column_inner width="1/2"][interactive_banner banner_title="Tablethalterung fürs Auto" banner_desc="Google unterwegs die Adresse und mach dein Tablet gut sichtbar am Armaturenbrett fest. Dein Navi ist einsatzbereit – und auch das Ladekabel kannst du leicht anschließen." banner_image="id^77076|url^https://www.reboon.de/wp-content/uploads/2017/02/booncover_bg_1111.jpg|caption^null|alt^null|title^booncover_bg_1111|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][vc_column_inner width="1/2"][interactive_banner banner_title="Tischaufsteller" banner_desc="Stelle dein Tablet am Arbeitsplatz, am Frühstückstisch, oder beim Zähneputzen senkrecht auf – du wirst die Standfunktion nicht mehr missen wollen." banner_image="id^77078|url^https://www.reboon.de/wp-content/uploads/2017/02/booncover_bg_3333.jpg|caption^null|alt^null|title^booncover_bg_3333|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/3"][interactive_banner banner_title="Wandhalterung" banner_desc="Um ein Selfie zu schießen, mache dein booncover mit dem mitgelieferten boon einfach an der Wand fest. Bombenfest!" banner_image="id^77077|url^https://www.reboon.de/wp-content/uploads/2017/02/booncover_bg_2222.jpg|caption^null|alt^null|title^booncover_bg_2222|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][vc_column_inner width="1/3"][interactive_banner banner_title="Sicherer Schutz" banner_desc="Schütze dein Tablet vor Stößen und Kratzern." banner_image="id^77079|url^https://www.reboon.de/wp-content/uploads/2017/02/booncover_bg_4444.jpg|caption^null|alt^null|title^booncover_bg_4444|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][vc_column_inner width="1/3"][interactive_banner banner_title="Volle Bedienbarkeit (Kamera, Touch-Sensor)" banner_desc="Dank der umklappbaren Rückseite, ist der booncover für jede Kamera- und Touch-Sensor-Positionierung am Handy geeignet." banner_image="id^77080|url^https://www.reboon.de/wp-content/uploads/2017/02/booncover_bg_5555.jpg|caption^null|alt^null|title^booncover_bg_5555|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(2,3,4,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][/vc_row_inner]');
            case "booncover XS": 
            case "booncover XS2": 
                return do_shortcode('[vc_single_image image="76714" img_size="full" alignment="center"][vc_row_inner][vc_column_inner width="1/2"][interactive_banner banner_title="Handyhalterung fürs Auto" banner_desc="Google unterwegs die Adresse und mach dein Smartphone gut sichtbar am Armaturenbrett fest. Dein Navi ist einsatzbereit – und auch das Ladekabel kannst du leicht anschließen." banner_image="id^77046|url^https://www.reboon.de/wp-content/uploads/2017/02/booncover_xs_bg_555.jpg|caption^null|alt^null|title^booncover_xs_bg_555|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][vc_column_inner width="1/2"][interactive_banner banner_title="Tischaufsteller" banner_desc="Stelle dein Smartphone am Arbeitsplatz, am Frühstückstisch, oder beim Zähneputzen senkrecht auf – du wirst die Standfunktion nicht mehr missen wollen." banner_image="id^77044|url^https://www.reboon.de/wp-content/uploads/2017/02/booncover_xs_bg_333.jpg|caption^null|alt^null|title^booncover_xs_bg_333|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/2"][interactive_banner banner_title="Wandhalterung" banner_desc="Um ein Selfie zu schießen, mache dein booncover mit dem mitgelieferten boon einfach an der Wand fest. Bombenfest!" banner_image="id^77047|url^https://www.reboon.de/wp-content/uploads/2017/02/booncover_xs_bg_666.jpg|caption^null|alt^null|title^booncover_xs_bg_666|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][vc_column_inner width="1/2"][interactive_banner banner_title="Sicherer Schutz" banner_desc="Schütze dein Smartphone vor Stößen und Kratzern." banner_image="id^77045|url^https://www.reboon.de/wp-content/uploads/2017/02/booncover_xs_bg_444.jpg|caption^null|alt^null|title^booncover_xs_bg_444|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][/vc_row_inner]');
            case "boonflip XS": 
            case "boonflip XS2": 
            case "boonflip XS3": 
            case "boonflip XS4": 
                return do_shortcode('[vc_row_inner][vc_column_inner width="1/2"][interactive_banner banner_title="Handyhalterung fürs Auto" banner_desc="Google unterwegs die Adresse und mach dein Smartphone gut sichtbar am Armaturenbrett fest. Dein Navi ist einsatzbereit – und auch das Ladekabel kannst du leicht anschließen." banner_image="id^76318|url^https://www.reboon.de/wp-content/uploads/2017/01/boonflip_bg_car.jpg|caption^null|alt^null|title^boonflip_bg_car|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][vc_column_inner width="1/2"][interactive_banner banner_title="Tischaufsteller" banner_desc="Stelle dein Smartphone am Arbeitsplatz, am Frühstückstisch, oder beim Zähneputzen senkrecht auf – du wirst die Standfunktion nicht mehr missen wollen." banner_image="id^76287|url^https://www.reboon.de/wp-content/uploads/2017/01/boonflip_bg44.jpg|caption^null|alt^null|title^boonflip_bg44|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][/vc_row_inner][vc_row_inner][vc_column_inner width="1/3"][interactive_banner banner_title="Wandhalterung" banner_desc="Um ein Selfie zu schießen, mache dein boonflip mit dem mitgelieferten boon einfach an der Wand fest. Bombenfest!" banner_image="id^76285|url^https://www.reboon.de/wp-content/uploads/2017/01/boonflip_bg22.jpg|caption^null|alt^null|title^boonflip_bg22|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][vc_column_inner width="1/3"][interactive_banner banner_title="Sicherer Schutz" banner_desc="Schütze dein Smartphone vor Stößen und Kratzern." banner_image="id^76289|url^https://www.reboon.de/wp-content/uploads/2017/01/boonflip-bg66.jpg|caption^null|alt^null|title^boonflip-bg66|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(0,0,0,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][vc_column_inner width="1/3"][interactive_banner banner_title="Volle Bedienbarkeit (Kamera, Touch-Sensor)" banner_desc="Dank der umklappbaren Rückseite, ist der boonflip für jede Kamera- und Touch-Sensor-Positionierung am Handy geeignet." banner_image="id^76288|url^https://www.reboon.de/wp-content/uploads/2017/01/boonflip_bg55.jpg|caption^null|alt^null|title^boonflip_bg55|description^null" banner_style="style11" banner_bg_color="#00c6d7" banner_overlay_bg_color="rgba(2,3,4,0.5)" banner_title_font_size="desktop:20px;" banner_desc_font_size="desktop:20px;"][/vc_column_inner][/vc_row_inner]');
    }  
}
add_shortcode('psa_show_functionen', 'psa_show_functionen');

/*=--------------- Вывод определенного фрейма для каждого товара =---------------*/
function psa_show_iframe(){
    global $post;
    $terms = get_the_terms( $post->ID, 'product_cat' );
        

    switch($terms[0] -> name) {
            case "booncover S": 
            case "booncover S2": 
            case "booncover S3": 
            case "booncover M": 
            case "booncover M2": 
            case "booncover L": 
            case "booncover L2": 
            case "booncover XL": 
                echo '<iframe scrolling="no" id="boonflip-slider-2" frameborder="0" width="" src="https://reboon.de/sliders/reboon-anim-bc-tablet/reboon-anim.html" allowfullscreen="allowfullscreen"></iframe>';
                break;
            case "booncover XS": 
            case "booncover XS2": 
                echo '<iframe scrolling="no" id="boonflip-slider-3" frameborder="0" width="" src="https://reboon.de/sliders/reboon-anim-bc-xs/reboon-anim.html" allowfullscreen="allowfullscreen"></iframe>';
                break;
            case "boonflip XS": 
            case "boonflip XS2": 
            case "boonflip XS3": 
            case "boonflip XS4": 
                echo '<iframe scrolling="no" id="boonflip-slider" frameborder="0" width="" src="https://reboon.de/sliders/reboon-anim/reboon-anim.html" allowfullscreen="allowfullscreen"></iframe>';
                break;
    }
}
add_shortcode('psa_show_iframe', 'psa_show_iframe');

/*=--------------- Вывод картинки №2 Qualität & Design для каждого товара =---------------*/
function psa_show_image(){
    global $post;
    $terms = get_the_terms( $post->ID, 'product_cat' );
        
    switch($terms[0] -> name) {
            case "booncover S3": 
				return '<div class="psa-single-image"><img src="/wp-content/uploads/landing/booncover-s3-single.png" class="img-responsive"></div>';
            case "booncover S": 
            case "booncover S2": 
            case "booncover M": 
            case "booncover M2": 
            case "booncover L": 
            case "booncover L2": 
            case "booncover XL": 
                return '<div class="psa-single-image"><img src="/wp-content/uploads/landing/booncover-m-single.png" class="img-responsive"></div>';
            case "booncover XS": 
            case "booncover XS2": 
                return '<div class="psa-single-image"><img src="/wp-content/uploads/landing/booncover-xs-single.png" class="img-responsive"></div>';
            case "boonflip XS": 
            case "boonflip XS2": 
            case "boonflip XS3": 
            case "boonflip XS4": 
                return '<div class="psa-single-image"><img src="/wp-content/uploads/landing/boonflip-xs-single.png" class="img-responsive"></div>';
    }
}
add_shortcode('psa_show_image', 'psa_show_image');

/*=--------------- Вывод картинки №1 Qualität & Design для каждого товара =---------------*/
function psa_show_image_row(){
    global $post;
    $terms = get_the_terms( $post->ID, 'product_cat' );
        

    switch($terms[0] -> name) {
            case "booncover S3": 
				return '<div class="psa-row-images"><img src="/wp-content/uploads/landing/booncover-s3-row.png" class="img-responsive"></div>';
            case "booncover S": 
            case "booncover S2": 
            case "booncover M": 
            case "booncover M2": 
            case "booncover L": 
            case "booncover L2": 
            case "booncover XL": 
                return '<div class="psa-row-images"><img src="/wp-content/uploads/landing/booncover-m-row.png" class="img-responsive"></div>';
            case "booncover XS": 
            case "booncover XS2": 
                return '<div class="psa-row-images"><img src="/wp-content/uploads/landing/booncover-xs-row.png" class="img-responsive"></div>';
            case "boonflip XS": 
            case "boonflip XS2": 
            case "boonflip XS3": 
            case "boonflip XS4": 
                return '<div class="psa-row-images"><img src="/wp-content/uploads/landing/boonflip-xs-row.png" class="img-responsive"></div>';
    }
}
add_shortcode('psa_show_image_row', 'psa_show_image_row');

/*=--------------- Вывод фоновой картинки до Qualität & Design для каждого товара =---------------*/
function psa_show_full_bg(){
    global $post;
    $terms = get_the_terms( $post->ID, 'product_cat' );
        
    switch($terms[0] -> name) {
            case "booncover S": 
            case "booncover S2": 
            case "booncover S3": 
            case "booncover M": 
            case "booncover M2": 
            case "booncover L": 
            case "booncover L2": 
            case "booncover XL": 
                return do_shortcode('[vc_row full_width="stretch_row" full_height="yes" bg_type="image" parallax_style="vcpb-default" bg_image_new="id^76399|url^https://www.reboon.de/wp-content/uploads/2017/01/booncover_bg8.jpg|caption^null|alt^null|title^booncover_bg8|description^null" bg_image_repeat="no-repeat" bg_img_attach="fixed"][vc_column][vc_empty_space height="600px"][ult_buttons btn_title="JETZT KAUFEN - AB 49.90€" btn_link="url:%23JETZT_KAUFEN|||" btn_align="ubtn-center" btn_title_color="#020304" btn_bg_color="rgba(255,255,255,0.5)" btn_bg_color_hover="#ffffff" btn_title_color_hover="#020304" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#020304" btn_color_border_hover="#020304" btn_border_size="1" btn_radius="6" el_class="hash-scroll-wrap" btn_font_size="desktop:16px;"][/vc_column][/vc_row]');
            case "booncover XS": 
            case "booncover XS2": 
                return do_shortcode('[vc_row full_width="stretch_row" full_height="yes" bg_type="image" parallax_style="vcpb-default" bg_image_new="id^76083|url^https://www.reboon.de/wp-content/uploads/2016/12/booncover_xs_bg.jpg|caption^null|alt^null|title^booncover_xs_bg|description^null" bg_image_repeat="no-repeat" bg_img_attach="fixed"][vc_column][vc_empty_space height="600px"][ult_buttons btn_title="JETZT KAUFEN AB 34.90€" btn_link="url:%23JETZT_KAUFEN|||" btn_align="ubtn-center" btn_title_color="#ffffff" btn_bg_color="" btn_bg_color_hover="#ffffff" btn_title_color_hover="#020304" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#ffffff" btn_color_border_hover="#222425" btn_border_size="1" btn_radius="6" el_class="hash-scroll-wrap" btn_font_size="desktop:16px;"][/vc_column][/vc_row]');
            case "boonflip XS": 
            case "boonflip XS2": 
            case "boonflip XS3": 
            case "boonflip XS4": 
                return do_shortcode('[vc_row full_width="stretch_row" full_height="yes" bg_type="image" parallax_style="vcpb-default" bg_image_new="id^75985|url^https://www.reboon.de/wp-content/uploads/2017/01/boonflip_bg1.png|caption^null|alt^null|title^boonflip_bg1|description^null" bg_image_repeat="no-repeat" bg_img_attach="fixed"][vc_column][vc_empty_space height="600px"][ult_buttons btn_title="JETZT KAUFEN AB 34.90€" btn_link="url:%23JETZT_KAUFEN|||" btn_align="ubtn-center" btn_title_color="#020304" btn_bg_color="rgba(255,255,255,0.5)" btn_bg_color_hover="#ffffff" btn_title_color_hover="#020304" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#020304" btn_color_border_hover="#020304" btn_border_size="1" btn_radius="6" el_class="hash-scroll-wrap" btn_font_size="desktop:16px;"][/vc_column][/vc_row]');
    }
}
add_shortcode('psa_show_full_bg', 'psa_show_full_bg');

/*=--------------- Вывод общего названия для товаров =---------------*/
function psa_show_main_category_name(){
    global $post;
    $terms = get_the_terms( $post->ID, 'product_cat' );
        
    switch($terms[0] -> name) {
            case "booncover S": 
            case "booncover S2": 
            case "booncover M": 
            case "booncover M2": 
            case "booncover L": 
            case "booncover L2": 
            case "booncover XL": 
                return 'Tablet Hülle';
            case "booncover S3": 
                return 'eBook Reader Hülle';
            case "booncover XS": 
            case "booncover XS2": 
                return 'Handyhülle';
            case "boonflip XS": 
            case "boonflip XS2": 
            case "boonflip XS3": 
            case "boonflip XS4": 
                return 'Handyhülle';
    }
}
add_shortcode('psa_show_main_category_name', 'psa_show_main_category_name');

/*=--------------- Вывод Datenblatt для каждого товара =---------------*/
function psa_show_datenblatt(){
    global $post;
    $terms = get_the_terms( $post->ID, 'product_cat' );
        
    switch($terms[0] -> name) {
            case "booncover S": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">145 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">208 x 143 x 15 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">203 x 138 x 8.5 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Braun, Beige, Pink, Schwarz</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Blau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt) </p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "booncover S2": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">135 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">206 x 126 x 14 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">202 x 121 x 10.5 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Braun, Beige, Pink, Schwarz</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Blau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt) </p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "booncover S3": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">135 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">183 x 135 x 18 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">179 x 130 x 11.5 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Braun, Beige, Pink, Schwarz</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Blau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt) </p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "booncover M": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">230 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">249 x 181 x 14 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">245 x 178 x 8.5 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Braun, Beige, Pink, Schwarz, Schwarz Leder, Braun Leder</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Blau / Dunkelgrau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt), Echtleder bei den Farben Braun, Schwarz</p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "booncover M2": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">145 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">226 x 139 x 16 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">222 x 135 x 9 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Braun, Beige, Pink, Schwarz</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Blau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt) </p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "booncover L": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">230 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">251 x 196 x 17 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">249 x 191 x 11 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Braun, Beige, Pink, Schwarz</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Blau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt) </p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "booncover L2": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">270 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">272 x 188 x 17 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">268 x 183 x 11.5 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Braun, Beige, Pink, Schwarz, Schwarz Leder, Braun Leder</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Blau / Dunkelgrau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt), Echtleder bei den Farben Braun, Schwarz</p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "booncover XL": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">320 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">312 x 229 x 17 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">306 x 221 x 11 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Braun, Schwarz, Schwarz Leder </p></td></tr><tr class=""><th>Farbe innen</th><td><p>Dunkelgrau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt), Echtleder bei den Farben Schwarz</p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "booncover XS": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">80 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">149 x 79 x 14 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">147 x 74 x 7.6 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Braun, Beige, Pink, Rot, Schwarz, Schwarz Leder, Braun Leder</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Blau / Dunkelgrau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt), Echtleder bei den Farben Braun, Schwarz</p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "booncover XS2": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">90 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">163 x 88 x 17 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">159 x 83 x 8.7 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Braun, Beige, Pink, Rot, Schwarz, Schwarz Leder, Braun Leder</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Blau / Dunkelgrau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt), Echtleder bei den Farben Braun, Schwarz</p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "boonflip XS": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">85 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">152 x 76 x 15.5 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">145 x 72 x 9 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Pink, Rot, Schwarz Leder, Braun Leder, Blau</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Dunkelgrau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt), Echtleder bei den Farben Braun, Schwarz</p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "boonflip XS2":
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">90 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">167 x 84.5 x 15.5 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">160 x 80.5 x 9 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Pink, Rot, Schwarz Leder, Braun Leder, Blau</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Dunkelgrau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt), Echtleder bei den Farben Braun, Schwarz</p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break;
            case "boonflip XS3": 
                echo '<table class="table table-striped shop_attributes"> <tbody> <tr> <th>Gewicht</th> <td>80 g</td></tr><tr> <th>Aussenmasse</th> <td>134.5 x 69 x 16 mm</td></tr><tr> <th>Innenmasse</th> <td>127.5 x 65 x 9.6 mm</td></tr><tr> <th>Farbe aussen</th> <td><p>Pink, Rot, Schwarz Leder, Braun Leder, Blau</p></td></tr><tr> <th>Farbe innen</th> <td><p>Dunkelgrau</p></td></tr><tr> <th>Material aussen</th> <td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt), Echtleder bei den Farben Braun, Schwarz</p>/td> </tr><tr> <th>Material innen</th> <td><p>Mikrofaser</p></td></tr></tbody> </table>';
                break;
            case "boonflip XS4": 
                echo '<table class="table table-striped shop_attributes"><tbody><tr class=""><th>Gewicht</th><td class="">85 g</td></tr><tr class="alt"><th>Aussenmasse</th><td class="">159.5 x 81 x 15,5 mm</td></tr><tr class=""><th>Innenmasse</th><td class="">152.5 x 77 x 9 mm</td></tr><tr class="alt"><th>Farbe aussen</th><td><p>Pink, Rot, Schwarz Leder, Braun Leder, Blau</p></td></tr><tr class=""><th>Farbe innen</th><td><p>Dunkelgrau</p></td></tr><tr class="alt"><th>Material aussen</th><td><p>Polyuretahn-Soft-Touch-Oberflache (matteirt), Echtleder bei den Farben Braun, Schwarz</p></td></tr><tr class=""><th>Material innen</th><td><p>Mikrofaser</p></td></tr></tbody></table>';
                break; 
    }
}
add_shortcode('psa_show_datenblatt', 'psa_show_datenblatt');

/*=--------------- Вывод кнопки с ценой для каждого товара =---------------*/
function psa_btn_buy(){
    global $post;
    $terms = get_the_terms( $post->ID, 'product_cat' );
        

    switch($terms[0] -> name) {
            case "booncover S": 
            case "booncover S2": 
            case "booncover M2":
                return do_shortcode('[ult_buttons btn_title="JETZT KAUFEN AB 44.90€" btn_link="url:%23JETZT_KAUFEN|||" btn_align="ubtn-center" btn_size="ubtn-custom" btn_width="230" btn_height="50" btn_title_color="#020304" btn_bg_color="" icon="Defaults-angle-right" icon_size="14" icon_color="#00c6d7" btn_icon_pos="ubtn-sep-icon-at-right" el_class="hash-scroll-wrap" btn_font_size="desktop:14px;"]');
            case "booncover S3": 
                return do_shortcode('[ult_buttons btn_title="JETZT KAUFEN AB 39.90€" btn_link="url:%23JETZT_KAUFEN|||" btn_align="ubtn-center" btn_size="ubtn-custom" btn_width="230" btn_height="50" btn_title_color="#020304" btn_bg_color="" icon="Defaults-angle-right" icon_size="14" icon_color="#00c6d7" btn_icon_pos="ubtn-sep-icon-at-right" el_class="hash-scroll-wrap" btn_font_size="desktop:14px;"]');
            case "booncover M":
            case "booncover L": 
            case "booncover L2": 
                return do_shortcode('[ult_buttons btn_title="JETZT KAUFEN AB 49.90€" btn_link="url:%23JETZT_KAUFEN|||" btn_align="ubtn-center" btn_size="ubtn-custom" btn_width="230" btn_height="50" btn_title_color="#020304" btn_bg_color="" icon="Defaults-angle-right" icon_size="14" icon_color="#00c6d7" btn_icon_pos="ubtn-sep-icon-at-right" el_class="hash-scroll-wrap" btn_font_size="desktop:14px;"]');
            case "booncover XL": 
                return do_shortcode('[ult_buttons btn_title="JETZT KAUFEN AB 59.90€" btn_link="url:%23JETZT_KAUFEN|||" btn_align="ubtn-center" btn_size="ubtn-custom" btn_width="230" btn_height="50" btn_title_color="#020304" btn_bg_color="" icon="Defaults-angle-right" icon_size="14" icon_color="#00c6d7" btn_icon_pos="ubtn-sep-icon-at-right" el_class="hash-scroll-wrap" btn_font_size="desktop:14px;"]');
            case "booncover XS":
            case "booncover XS2":
            case "boonflip XS": 
            case "boonflip XS2":
            case "boonflip XS3":
            case "boonflip XS4": 
                return do_shortcode('[ult_buttons btn_title="JETZT KAUFEN AB 34.90€" btn_link="url:%23JETZT_KAUFEN|||" btn_align="ubtn-center" btn_size="ubtn-custom" btn_width="230" btn_height="50" btn_title_color="#020304" btn_bg_color="" icon="Defaults-angle-right" icon_size="14" icon_color="#00c6d7" btn_icon_pos="ubtn-sep-icon-at-right" el_class="hash-scroll-wrap" btn_font_size="desktop:14px;"]');
    }
}
add_shortcode('psa_btn_buy', 'psa_btn_buy');

/*=--------------- Вывод первого экрана для реальных товаров =---------------*/
function psa_first_screen(){
    global $post;
    $terms = get_the_terms( $post->ID, 'product_cat' );
        

    switch($terms[0] -> name) {
            case "booncover S3": 
                return do_shortcode('[vc_row el_id="starting" full_width="stretch_row" full_height="yes" bg_type="image" parallax_style="vcpb-default" bg_image_new="id^76185|url^https://www.reboon.de/wp-content/uploads/2016/12/ereader_booncover_s3_bg2.jpg|caption^null|alt^null|title^ereader_booncover_s3_bg2|description^null" bg_image_repeat="no-repeat" bg_img_attach="fixed" enable_overlay="enable_overlay_value" overlay_color="rgba(0,0,0,0.35)" css=".vc_custom_1495096961234{margin-top: -35px !important;margin-bottom: -35px !important;}"][vc_column width="1/2"][vc_custom_heading text="Entdecke reboon booncover S3 eBook Reader Hülle" font_container="tag:h1|text_align:left|color:%23ffffff" use_theme_fonts="yes"][vc_empty_space height="50px"][ult_buttons btn_title="ENTDECKE DAS BOONCOVER" btn_link="url:%23video|||" btn_title_color="#020304" btn_bg_color="rgba(255,255,255,0.5)" btn_bg_color_hover="#ffffff" btn_title_color_hover="#020304" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#ffffff" btn_color_border_hover="#222425" btn_border_size="1" btn_radius="6" el_class="hash-scroll-wrap" btn_font_size="desktop:16px;"][vc_column_text css=".vc_custom_1488526691638{margin-top: -20px !important;margin-bottom: 20px !important;}" el_class="hash-scroll-wrap"]<h5><span style="color: #fff;"><a style="color: #fff; font-size: 14px;" href="#jetzt_kaufen">JETZT KAUFEN 39.90€</a></span></h5>[/vc_column_text][/vc_column][vc_column width="1/2"][/vc_column][/vc_row]');
            case "booncover S": 
            case "booncover S2": 
            case "booncover M2":
                return do_shortcode('[vc_row el_id="starting" full_width="stretch_row" full_height="yes" bg_type="image" parallax_style="vcpb-default" bg_image_new="id^76035|url^https://www.reboon.de/wp-content/uploads/2016/12/reboon-booncover-tablet.jpg|caption^null|alt^null|title^reboon-booncover-tablet|description^null" bg_image_repeat="no-repeat" bg_img_attach="fixed" enable_overlay="enable_overlay_value" overlay_color="rgba(0,0,0,0.35)" css=".vc_custom_1495095874991{margin-top: -35px !important;margin-bottom: -35px !important;}"][vc_column width="1/2"][psa_show_cover_name value="real"][vc_empty_space height="50px"][ult_buttons btn_title="ENTDECKE DAS BOONCOVER" btn_link="url:%23find|||" btn_title_color="#020304" btn_bg_color="rgba(255,255,255,0.5)" btn_bg_color_hover="#ffffff" btn_title_color_hover="#020304" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#ffffff" btn_color_border_hover="#222425" btn_border_size="1" btn_radius="6" el_class="hash-scroll-wrap" btn_font_size="desktop:16px;"][vc_column_text el_class="hash-scroll-wrap"]<h5><span style="color: #fff;"><a style="color: #fff; font-size: 14px;" href="#jetzt_kaufen">JETZT KAUFEN AB 44.90€</a></span></h5>[/vc_column_text][/vc_column][vc_column width="1/2"][/vc_column][/vc_row]');
            case "booncover M":
            case "booncover L": 
            case "booncover L2": 
                return do_shortcode('[vc_row el_id="starting" full_width="stretch_row" full_height="yes" bg_type="image" parallax_style="vcpb-default" bg_image_new="id^76035|url^https://www.reboon.de/wp-content/uploads/2016/12/reboon-booncover-tablet.jpg|caption^null|alt^null|title^reboon-booncover-tablet|description^null" bg_image_repeat="no-repeat" bg_img_attach="fixed" enable_overlay="enable_overlay_value" overlay_color="rgba(0,0,0,0.35)" css=".vc_custom_1495095874991{margin-top: -35px !important;margin-bottom: -35px !important;}"][vc_column width="1/2"][psa_show_cover_name value="real"][vc_empty_space height="50px"][ult_buttons btn_title="ENTDECKE DAS BOONCOVER" btn_link="url:%23find|||" btn_title_color="#020304" btn_bg_color="rgba(255,255,255,0.5)" btn_bg_color_hover="#ffffff" btn_title_color_hover="#020304" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#ffffff" btn_color_border_hover="#222425" btn_border_size="1" btn_radius="6" el_class="hash-scroll-wrap" btn_font_size="desktop:16px;"][vc_column_text el_class="hash-scroll-wrap"]<h5><span style="color: #fff;"><a style="color: #fff; font-size: 14px;" href="#jetzt_kaufen">JETZT KAUFEN AB 49.90€</a></span></h5>[/vc_column_text][/vc_column][vc_column width="1/2"][/vc_column][/vc_row]');
            case "booncover XL": 
                return do_shortcode('[vc_row el_id="starting" full_width="stretch_row" full_height="yes" bg_type="image" parallax_style="vcpb-default" bg_image_new="id^76035|url^https://www.reboon.de/wp-content/uploads/2016/12/reboon-booncover-tablet.jpg|caption^null|alt^null|title^reboon-booncover-tablet|description^null" bg_image_repeat="no-repeat" bg_img_attach="fixed" enable_overlay="enable_overlay_value" overlay_color="rgba(0,0,0,0.35)" css=".vc_custom_1495095874991{margin-top: -35px !important;margin-bottom: -35px !important;}"][vc_column width="1/2"][psa_show_cover_name value="real"][vc_empty_space height="50px"][ult_buttons btn_title="ENTDECKE DAS BOONCOVER" btn_link="url:%23find|||" btn_title_color="#020304" btn_bg_color="rgba(255,255,255,0.5)" btn_bg_color_hover="#ffffff" btn_title_color_hover="#020304" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#ffffff" btn_color_border_hover="#222425" btn_border_size="1" btn_radius="6" el_class="hash-scroll-wrap" btn_font_size="desktop:16px;"][vc_column_text el_class="hash-scroll-wrap"]<h5><span style="color: #fff;"><a style="color: #fff; font-size: 14px;" href="#jetzt_kaufen">JETZT KAUFEN AB 59.90€</a></span></h5>[/vc_column_text][/vc_column][vc_column width="1/2"][/vc_column][/vc_row]');
            case "booncover XS":
            case "booncover XS2":
                return do_shortcode('[vc_row el_id="starting" full_width="stretch_row" full_height="yes" bg_type="image" parallax_style="vcpb-default" bg_image_new="id^76200|url^https://www.reboon.de/wp-content/uploads/2016/12/booncover_xs_bg_2.jpg|caption^null|alt^null|title^booncover_xs_bg_2|description^null" bg_image_repeat="no-repeat" bg_img_attach="fixed" enable_overlay="enable_overlay_value" overlay_color="rgba(0,0,0,0.35)" css=".vc_custom_1495095550393{margin-top: -35px !important;margin-bottom: -35px !important;}"][vc_column width="1/2"][psa_show_cover_name value="real"][vc_empty_space height="50px"][ult_buttons btn_title="ENTDECKE DAS BOONCOVER" btn_link="url:%23video|||" btn_title_color="#ffffff" btn_bg_color="" btn_bg_color_hover="#ffffff" btn_title_color_hover="#020304" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#ffffff" btn_color_border_hover="#222425" btn_border_size="1" btn_radius="6" el_class="hash-scroll-wrap" btn_font_size="desktop:16px;"][vc_column_text el_class="hash-scroll-wrap"]<h5><span style="color: #fff;"><a style="color: #fff; font-size: 14px;" href="#jetzt_kaufen">JETZT KAUFEN AB 34.90€</a></span></h5>[/vc_column_text][/vc_column][vc_column width="1/2"][/vc_column][/vc_row]');
            case "boonflip XS": 
            case "boonflip XS2":
            case "boonflip XS3":
            case "boonflip XS4": 
                return do_shortcode('[vc_row el_id="starting" el_class="boonflip-xser" full_width="stretch_row" full_height="yes" bg_type="image" parallax_style="vcpb-default" bg_image_new="id^66339|url^https://www.reboon.de/wp-content/uploads/2017/01/boonflip-standfunktion-anwendungsbild-wecker.jpg|caption^null|alt^null|title^boonflip-standfunktion-anwendungsbild-wecker|description^null" bg_image_repeat="no-repeat" bg_img_attach="fixed"][vc_column width="1/2"][psa_show_cover_name value="real"][vc_empty_space height="50px"][ult_buttons btn_title="ENTDECKE DAS BOONFLIP" btn_link="url:%23video|||" btn_title_color="#020304" btn_bg_color="rgba(255,255,255,0.5)" btn_bg_color_hover="#ffffff" btn_title_color_hover="#020304" icon_size="32" btn_icon_pos="ubtn-sep-icon-at-left" btn_border_style="solid" btn_color_border="#020304" btn_color_border_hover="#020304" btn_border_size="1" btn_radius="6" el_class="hash-scroll-wrap" btn_font_size="desktop:16px;"][vc_column_text css=".vc_custom_1488459201003{margin-top: -20px !important;margin-bottom: 20px !important;}" el_class="hash-scroll-wrap"]<h5><span style="color: #808080;"><a style="color: #808080; font-size: 14px;" href="#jetzt_kaufen">JETZT KAUFEN AB 34.90€</a></span></h5>[/vc_column_text][/vc_column][vc_column width="1/2"][/vc_column][/vc_row]');
    }
}
add_shortcode('psa_first_screen', 'psa_first_screen');

/* SNN-admin-ajax.php high CPU – Solution */
add_action( 'init', 'my_deregister_heartbeat', 1 );
function my_deregister_heartbeat() {
	global $pagenow;

	if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow )
		wp_deregister_script('heartbeat');
}

//получить список моделей и цветов
function nk_getModelsWithColors(){
    $current_user = wp_get_current_user();
    $user =  $current_user->ID;
     
    $res=null;
    // если клиент работает по b2b     
    //if(in_array('administrator', $current_user->roles) || in_array('assistant', $current_user->roles)){
    //    $user_id = $current_user->ID;
   

        global $wpdb;
        
        $res = $wpdb->get_results("SELECT marks.`id`, name,color
                             FROM `nk_marks` marks JOIN u1co1ss6_trd_boons ON `id_boon`  = boon_id 
                             JOIN nk_colors colors ON `id_color` = colors.id ORDER BY name");
       
          
    //} // if
    
    return $res;
} //nk_getModelsWithColors()

//запись данных формы продажи в базу
function nk_set_valueForSalesSystem($model, $quantity){
    
    $current_user = wp_get_current_user();
    $user =  $current_user->ID;
    
    $date = date("d.m.y H:m:s");  
   // $curDate = $date->format("Y-m-d H:m:s");
    global $wpdb;
    $res = $wpdb->query("INSERT INTO `nk_operations`(`id_user`, `id_mark`, `quantity`, `operationDate`)"
            . " VALUES ( {$user},{$model},{$quantity},NOW())"); /*curdate() для записи даты*/
    
    return    $res;      
}//set_valueForSalesSystem


function is_user_role($user){
    
    $current_user = wp_get_current_user();
  
    return (in_array('administrator', $current_user->roles) || in_array($user, $current_user->roles));
    
}

//получить количество продаж за все время
function nk_get_totalSales(){
    
    $current_user = wp_get_current_user();
    $user =  $current_user->ID;
    
   
    global $wpdb;
    $res = $wpdb->get_var("SELECT SUM( quantity ) 
                            FROM  `nk_operations` 
                            WHERE id_user = {$user}"); 
    return    $res;      
}//get_totalSales

//получить количество продаж за все время
function nk_get_totalMarks(){
    
    $current_user = wp_get_current_user();
    $user =  $current_user->ID;
    
   
    global $wpdb;
    $res = $wpdb->get_var("SELECT SUM( marks.mark ) 
                            FROM  `nk_operations` 
                            JOIN nk_marks marks ON id_mark = marks.id
                            WHERE id_user ={$user}"); 
    return    $res;      
}//получить количество продаж за все время

//SELECT SUM(quantity) FROM `nk_operations` WHERE MONTH(operationDate) = MONTH(NOW()) AND YEAR(operationDate) = YEAR(NOW()) 
//получить количество продаж за все время
function nk_get_salesForMonth(){
    
    $current_user = wp_get_current_user();
    $user =  $current_user->ID;
    
   
    global $wpdb;
    $res = $wpdb->get_var("SELECT SUM( quantity ) 
                            FROM  `nk_operations` 
                            WHERE id_user = {$user} AND MONTH(operationDate) = MONTH(NOW()) AND YEAR(operationDate) = YEAR(NOW())"); 
    return    $res;      
}

//получить статистику продавца
function nk_get_assistanceData(){
    
    $current_user = wp_get_current_user();
    $user =  $current_user->ID;
    
   
    global $wpdb;
    $res = $wpdb->get_results("SELECT nk_operations.id, `quantity` ,  `operationDate` , boons.name, mark.mark, colors.color
            FROM nk_operations
            JOIN nk_marks mark ON nk_operations.id_mark = mark.id
            JOIN u1co1ss6_trd_boons boons ON mark.id_boon = boons.boon_id
            JOIN nk_colors colors ON mark.id_color = colors.id
            WHERE id_user ={$user}"
            ); 
                             
    return    $res;      
}//nk_get_assistanceData()

//удаление записи из таблицы по id
function nk_delete_sales($id){
      
    global $wpdb;
    $res = $wpdb->get_results("
            DELETE FROM `nk_operations`
            WHERE id ={$id}"
            ); 
                         
    return    $res; 
    
}//nk_delete_sales

