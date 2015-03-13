<?php
/**
 * hellonote functions and definitions
 *
 * @package hellonote
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'hellonote_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function hellonote_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on hellonote, use a find and replace
	 * to change 'hellonote' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'hellonote', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'hellonote' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'hellonote_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // hellonote_setup
add_action( 'after_setup_theme', 'hellonote_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function hellonote_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'hellonote' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'hellonote_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function hellonote_scripts() {
	wp_enqueue_style( 'hellonote-style', get_stylesheet_uri() );

	wp_enqueue_script( 'hellonote-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'hellonote-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'hellonote_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
 
// This tells WordPress to call the function named "setup_theme_admin_menus"
// when it's time to create the menu pages.
add_action("admin_menu", "setup_theme_admin_menus");

function setup_theme_admin_menus() {
    add_submenu_page('themes.php', 
        'Front Page Elements', 'Front Page', 'manage_options', 
        'front-page-elements', 'theme_front_page_settings'); 
}

function theme_front_page_settings() {
    echo "Hello, world!";
}

// change category checkbox to radio button
function my_print_footer_scripts() {
echo '<script type="text/javascript">
  //<![CDATA[
  jQuery(document).ready(function($){
    $(".categorychecklist input[type=\"checkbox\"]").each(function(){
      $check = $(this);
      var checked = $check.attr("checked") ? \' checked="checked"\' : \'\';
      $(\'<input type="radio" id="\' + $check.attr("id")
        + \'" name="\' + $check.attr("name") + \'"\'
    	+ checked
  		+ \' value="\' + $check.val()
  		+ \'"/>\'
      ).insertBefore($check);
      $check.remove();
    });
  });
  //]]>
  </script>';
}
add_action( 'admin_print_footer_scripts' , 'my_print_footer_scripts' , 21 );

/* set a featured image with external image URL */
// for admin ui
add_filter( 'admin_post_thumbnail_html', 'thumbnail_url_field' );

// for saving thumbnail url
add_action( 'save_post', 'thumbnail_url_field_save', 10, 2 );

// for displaying
add_filter( 'post_thumbnail_html', 'thumbnail_external_replace', 10, PHP_INT_MAX );

// 
function url_is_image( $url ) {
	if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
		return FALSE;
	}
	$ext = array( 'jpeg', 'jpg', 'gif', 'png' );
	$info = (array) pathinfo( parse_url( $url, PHP_URL_PATH ) );
	return isset( $info['extension'] ) && in_array( strtolower( $info['extension'] ), $ext, TRUE );
}

// output textbox in admin
function thumbnail_url_field( $html ) {
	global $post;
	$value = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE ) ? : "";
	$nonce = wp_create_nonce( 'thumbnail_ext_url_' . $post->ID . get_current_blog_id() );
	$html .= '<input type="hidden" name="thumbnail_ext_url_nonce" value="' . esc_attr( $nonce ) . '">';
	$html .= '<div><p>' . __('Or', 'txtdomain') . '</p>';
	$html .= '<p>' . __( 'Enter the url for external image', 'txtdomain' ) . '</p>';
	$html .= '<p><input type="url" name="thumbnail_ext_url" value="' . $value . '"></p>';
	if ( ! empty($value) && url_is_image( $value ) ) {
		$html .= '<p><img style="max-width:150px;height:auto;" src="' . esc_url($value) . '"></p>';
		$html .= '<p>' . __( 'Leave url blank to remove.', 'txtdomain' ) . '</p>';
	}
	$html .= '</div>';
	return $html;
}

// save thumbnail url
function thumbnail_url_field_save( $pid, $post ) {
	$cap = $post->post_type === 'page' ? 'edit_page' : 'edit_post';
	if(!current_user_can($cap, $pid) || ! post_type_supports( $post->post_type, 'thumbnail') || defined('DOING_AUTOSAVE')) {
		return;
	}
	$action = 'thumbnail_ext_url_' . $pid . get_current_blog_id();
	$nonce = filter_input( INPUT_POST, 'thumbnail_ext_url_nonce', FILTER_SANITIZE_STRING );
	$url = filter_input( INPUT_POST,  'thumbnail_ext_url', FILTER_VALIDATE_URL );
	if(empty($nonce) || ! wp_verify_nonce($nonce, $action) || (!empty($url) && ! url_is_image( $url ))){
		return;
	}
	if ( ! empty( $url ) ) {
		update_post_meta( $pid, '_thumbnail_ext_url', esc_url($url) );
		if ( ! get_post_meta( $pid, '_thumbnail_id', TRUE ) ) {
	  		update_post_meta( $pid, '_thumbnail_id', 'by_url' );
		}
	} elseif ( get_post_meta( $pid, '_thumbnail_ext_url', TRUE ) ) {
		delete_post_meta( $pid, '_thumbnail_ext_url' );
		if ( get_post_meta( $pid, '_thumbnail_id', TRUE ) === 'by_url' ) {
	  		delete_post_meta( $pid, '_thumbnail_id' );
		}
	}
}

// display the image
function thumbnail_external_replace( $html, $post_id ) {
	$url =  get_post_meta( $post_id, '_thumbnail_ext_url', TRUE );
	if ( empty( $url ) || ! url_is_image( $url ) ) {
		return $html;
	}
	$alt = get_post_field( 'post_title', $post_id ) . ' ' .  __( 'thumbnail', 'txtdomain' );
	$attr = array( 'alt' => $alt );
	$attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, NULL );
	$attr = array_map( 'esc_attr', $attr );
	$html = sprintf( '<img src="%s"', esc_url($url) );
	foreach ( $attr as $name => $value ) {
		$html .= " $name=" . '"' . $value . '"';
	}
	$html .= ' />';
	return $html;
}
/* set a featured image with external image URL */

/* show featured image in admin all post page*/
function manage_posts_columns($columns) {
    $columns['thumbnail'] = __('Thumbnail');
    return $columns;
}
function add_column($column_name, $post_id) {
    if ( 'thumbnail' == $column_name) {
        $thum = get_the_post_thumbnail($post_id, array(50,50), 'thumbnail');
	    if ( isset($thum) && $thum ) {
	        echo "<div style='max-height: 50px; max-width:50px; overflow: hidden;'>".$thum."</div>";
	    } elseif( esc_attr(get_post_meta($post_id, 'eye', true)) ) {
	        echo '<img src="';
	        echo esc_attr(get_post_meta($post_id, 'eye', true));
	        echo '" width="50" height="50" alt="Thumbnail" class="wp-post-image">';
	    } else {
	        echo '<span style="font-size:1.5em;color:red;font-weight:bold">';
	        echo __('None');
	        echo '</span>';
	    }
    }
}
add_filter( 'manage_posts_columns', 'manage_posts_columns' );
add_action( 'manage_posts_custom_column', 'add_column', 10, 2 );