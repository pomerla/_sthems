<?php
/**
 * sthems functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package sthems
 */

if ( ! function_exists( 'sthems_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function sthems_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on sthems, use a find and replace
		 * to change 'sthems' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'sthems', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'sthems' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'sthems_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'sthems_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function sthems_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'sthems_content_width', 640 );
}
add_action( 'after_setup_theme', 'sthems_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function sthems_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'sthems' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'sthems' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'sthems_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function sthems_scripts() {
	wp_enqueue_style( 'sthems-style', get_stylesheet_uri() );

	wp_enqueue_script( 'sthems-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'sthems-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'sthems_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

//disable update wordpress
add_filter('pre_site_transient_update_core',create_function('$a', "return null;"));
wp_clear_scheduled_hook('wp_version_check');
//disable update plugins
remove_action( 'load-update-core.php', 'wp_update_plugins' );
add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );
wp_clear_scheduled_hook( 'wp_update_plugins' );
//disable update themes
remove_action('load-update-core.php','wp_update_themes');
add_filter('pre_site_transient_update_themes',create_function('$a', "return null;"));
wp_clear_scheduled_hook('wp_update_themes');

/**
 *  чистим от хлама шапку
 */
function fb_disable_feed() {
    wp_redirect(get_option('siteurl'));
}

add_action('do_feed', 'fb_disable_feed', 1);
add_action('do_feed_rdf', 'fb_disable_feed', 1);
add_action('do_feed_rss', 'fb_disable_feed', 1);
add_action('do_feed_rss2', 'fb_disable_feed', 1);
add_action('do_feed_atom', 'fb_disable_feed', 1);
add_theme_support( 'post-thumbnails' ); 
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'rsd_link' );
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link'); 
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
//remove adminbar
add_filter('show_admin_bar', '__return_false');

/**
 * Убираем заголовки сайдбара
 */
add_filter( 'widget_title', 'hide_widget_title' );
function hide_widget_title( $title ) {
    if ( empty( $title ) ) return '';
    if ( $title[0] == '!' ) return '';
    return $title;
}
//Удалить пункт коментариев с админ меню
add_action('admin_menu', 'remove_menus');
function remove_menus(){
	global $menu;
	$restricted = array(
		//__('Dashboard'), //главная страница админки (консоль)
        //__('Comments'), //комментарии
		//__('Posts'), //пункт меню "Записи"
		//__('Media'), //пункт меню "Медиафайлы" (картинки, видео и т.п.)
		//__('Links'), //никому не нужный, пункт меню "Ссылки"
		//__('Pages'), //пункт меню "Страницы"
		//__('Appearance'), //пункт меню "Внешний вид"
		//__('Tools'), //пункт меню "инструменты"
		//__('Users'), //пользователи
		//__('Settings'), //пункт меню "Настройки"
		//__('Plugins') //ну и наконец, святое: пункт меню "Плагины"
	);
	end ($menu);
	while (prev($menu)){
		$value = explode(' ', $menu[key($menu)][0]);
		if( in_array( ($value[0] != NULL ? $value[0] : "") , $restricted ) ){
			unset($menu[key($menu)]);
		}
	}
}
//Добавляем страницу настроек темы
//include('functions/settings.php');
include('functions/duplicate.php');
//include('functions/options/mataboxes.php');
//include('functions/options_tabs/mataboxes.php');

/**
 * Required: set 'ot_theme_mode' filter to true.
 */
add_filter( 'ot_theme_mode', '__return_true' );

/**
 * Required: include OptionTree.
 */
require( trailingslashit( get_template_directory() ) . 'themesettings/ot-loader.php' );

//debug admin menu
//add_action( 'admin_init', 'wpse_136058_debug_admin_menu' );
function wpse_136058_debug_admin_menu() {
    echo '<pre>' . print_r( $GLOBALS[ 'menu' ], TRUE) . '</pre>';
}

//hide in menu setting OptionTree
//add_action('admin_menu', 'remove_admin_menu2', 999);
function remove_admin_menu2() {
	remove_submenu_page( 'ot-settings', 'ot-settings'); // 
  	//remove_submenu_page( 'ot-settings', 'ot-documentation'); // 
}
//add_action('admin_menu', 'remove_admin_menu');
function remove_admin_menu() {
	remove_menu_page('ot-settings'); 
}

//hide in menu setting OptionTree
//add_filter( 'ot_show_pages', '__return_false' );