<?php
/**
 * cjwsstrm functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package cjwsstrm
 */

if ( ! function_exists( 'cjwsstrm_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function cjwsstrm_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on cjwsstrm, use a find and replace
		 * to change 'cjwsstrm' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'cjwsstrm', get_template_directory() . '/languages' );

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
			'menu-1' => esc_html__( 'Primary', 'cjwsstrm' ),
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
		add_theme_support( 'custom-background', apply_filters( 'cjwsstrm_custom_background_args', array(
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
add_action( 'after_setup_theme', 'cjwsstrm_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function cjwsstrm_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'cjwsstrm_content_width', 640 );
}
add_action( 'after_setup_theme', 'cjwsstrm_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function cjwsstrm_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'cjwsstrm' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'cjwsstrm' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'cjwsstrm_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function cjwsstrm_scripts() {
	wp_enqueue_style( 'cjwsstrm-style', get_stylesheet_directory_uri() . '/style.min.css', array(), false );

	wp_enqueue_script( 'cjwsstrm-navigation', get_template_directory_uri() . '/js/navigation.js', array(), true, true );
	wp_script_add_data( 'cjwsstrm-navigation', 'async' , true );

	wp_enqueue_script( 'cjwsstrm-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), true, true );

	wp_enqueue_script( 'cjwsstrm-public', get_template_directory_uri() . '/js/cjwsstrm-public.min.js', array(), true, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'cjwsstrm_scripts' );

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

add_action( 'wp_head', 'bust_cache', 7 );

// Questions: 
// Why "$handle => $script"? there is no script key/value in either $wp_styles or $wp_scripts
function bust_cache() {
	global $wp_scripts;
	global $wp_styles;
	// CSS busting
	if ( !isset( $wp_styles->__cache_busted ) && is_object( $wp_styles ) ) {
		foreach ( $wp_styles->registered as $handle => $style ) {
			$modification_time = modification_time( $style->src );
			if ( $modification_time ) {
				if ( !empty ( $style->ver ) ) {
					$version = $style->ver . '-' . $modification_time;
				} else {
					$version = $modification_time;
				}
				$wp_styles->registered[$handle]->ver = $version;
			}
		}

		$wp_styles->__cache_busted = true;
	}
	// JS busting
	if ( !isset( $wp_scripts->__cache_busted ) && is_object( $wp_scripts ) ) {
		foreach ( $wp_scripts->registered as $handle => $script ) {
			$modification_time = modification_time( $script->src );
			if ( $modification_time ) {
				if ( !empty ( $script->ver ) ) {
					$version = $script->ver . '-' . $modification_time;
				} else {
					$version = $modification_time;
				}
				$wp_scripts->registered[$handle]->ver = $version;
			}
		}

		$wp_scripts->__cache_busted = true;
	}
}

// Why not use straight timestamp string instead of filemtime?
function modification_time( $src ) {
	if ( strpos( $src, content_url() ) !== false ) {
		$src = WP_CONTENT_DIR . str_replace( content_url(), '', $src );
	}

	$file = realpath( $src );

	if ( file_exists( $file ) ) {
		return filemtime( $file );
	}

	return false;
}	