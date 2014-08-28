<?php
/**
 * @todo
 */

/** Set Variables **/

date_default_timezone_set('America/Chicago');
$GLOBALS['SMF_CONTEXT'] = $context;

$GLOBALS['classdata'] = array(   1 => array("name" => "Warrior", "color" => "#C79C6E"),
					  2 => array("name" => "Paladin", "color" => "#F58CBA"),
					  3 => array("name" => "Hunter", "color" => "#ABD473"),
					  4 => array("name" => "Rogue", "color" => "#FFF569"),
					  5 => array("name" => "Priest", "color" => "#FFFFFF"),
					  6 => array("name" => "Death Knight", "color" => "#C41F3B"),
					  7 => array("name" => "Shaman", "color" => "#0070DE"),
					  8 => array("name" => "Mage", "color" => "#69CCF0"),
					  9 => array("name" => "Warlock", "color" => "#9482C9"),
					  10=> array("name" => "Monk", "color" => "#00FF96"),
					  11=> array("name" => "Druid", "color" => "#FF7D0A"));
					  
					  
$gender = array('male', 'female');
$GLOBALS['races'] = array(0=>'Orc',1,2,3,4,5=>'Undead',6=>'Tauren',7,8=>'Troll',9=>'Goblin',10=>'Blood Elf',26=>'Pandaren');
$ranks = array(0=>"Guild Master", 1=>"Raid Leader", 2=>"Officer", 3=> "Officer Alt",
			   4=> "Meme Legend", 5=> "Veteran Raider", 6=>"Member", 7=>"Recruit");

global $classdata, $races;

/** ARMORY FUNCTIONS **/

function get_char_armory_data($username=null,$realm=null) {
	global $SMF_CONTEXT;
	$context = $SMF_CONTEXT;
	if ($username==null)
		$username = (isset($context['user']['name']) and $context['user']['name'] != "") ? $context['user']['name'] : $context['user']['username'];
		
	//check if this person exists
	$url = "http://us.battle.net/api/wow/character/";
	$realm = ($realm==null) ? "maelstrom" : $realm;
	$url .= $realm."/";
	
	//add character from username
	$url .= $username;
	
	return(json_decode(file_get_contents($url)));
		
}


function the_char_thumbnail($text) {
	$url = "http://us.battle.net/static-render/us/".$text;
	return $url;
}

/** SMF FUNCTIONS **/

function ext_smf_logged_in( ) {
	global $SMF_CONTEXT;
	if ($SMF_CONTEXT['user']['is_guest']) {
		return false;	
	} else {
		return true;
	}
}

function smf_login_name() {
	global $SMF_CONTEXT;
	$context = $SMF_CONTEXT;
	$name = $context['user']['name'];
	return $name;	
}

function smf_data() {
	global $SMF_CONTEXT;
	$context = $SMF_CONTEXT;
	return $context;	
}



function meme_comment_form() {
	
	list($name, $class, $thumbnail) = default_character_info();
	?>
    <div id="commentform-wrapper">
    <form action="http://www.fireexitguild.com/wp-comments-post.php" method="post" id="commentform">
    <div class="c-left">
    	<img alt="" src="<?=$thumbnail?>" class="vcard avatar avatar-58 photo" height="60" width="60">
    </div>
    <div class="c-right">
    <div class="name"><span class="class-<?=$class?>"><?=$name?></span><input name="author" type="hidden" value="<?=$name?>"></div>
    <div class="textarea-wrapper"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></div>
    <div class="button-wrapper"><input type="submit" value="Post" class="button"></div>
    </div>
    <input type='hidden' name='comment_post_ID' value='<?php the_ID()?>' id='comment_post_ID' />
	<input type='hidden' name='comment_parent' id='comment_parent' value="<?=(isset($_GET['replytocom'])) ? intval($_GET['replytocom']) : 0;?>">
    <?=wp_comment_form_unfiltered_html_nonce()?>    
    </form>
	</div><?php	
	
}


function time_elapsed($timestamp) {
	
	$currtime = time();
	
	$elapsed = $currtime - $timestamp;
	
	if ($elapsed < 60) { //less than 5 minutes ago
		return "About a minute ago.";
	} elseif ($elapsed > 59 && $elapsed < 60*60) {
		$mins = floor($elapsed/60);
		$x = ($mins==1) ? '' : 's';
		return $mins." minute".$x." ago.";;
	} elseif ($elapsed >= 3600 and $elapsed < 3600*24) {
		$hours = floor($elapsed/3600);
		$x = ($hours==1) ? '' : 's';
		return $hours." hour".$x." ago.";;
	} elseif ($elapsed >= 86400 and $elapsed < 86400*30) {
		$days = floor($elapsed/86400);
		$x = ($days==1) ? '' : 's';
		return $days." day".$x." ago.";
	} elseif ($elapsed >= 2592000 and $elapsed < 2592000*12) {
		$months = floor($elapsed/2592000);
		$x = ($months==1) ? '' : 's';
		return $months." month".$x." ago.";
	} else {
		$years = floor($elapsed/31104000);
		$x = ($years==1) ? '' : 's';
		return $years." year".$x." ago.";;	
	}
		
}
function nullify() {
	return "[...]";
}
//add_filter('excerpt_more', 'nullify');


/** ADD CUSTOM POST TYPES **/

function meme_init() {

	register_post_type(
		'slider',
		array(
			'labels' => array(
				'name' => 'Featured',
				'singular_name' => 'Featured'
			),
			'public' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'query_var' => "slide", // This goes to the WP_Query schema
			'has_archive' => false,
			'supports' => array('title', 'editor', 'thumbnail','custom-fields'),
			'can_export' => true,
			'capability_type' => 'post'
		)
	);
}

add_action('init', 'meme_init');

/** END CLASS DATA **/


/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 584;

/**
 * Tell WordPress to run twentyeleven_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'twentyeleven_setup' );

if ( ! function_exists( 'twentyeleven_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyeleven_setup() in a child theme, add your own twentyeleven_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, custom headers
 * 	and backgrounds, and post formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_setup() {

	/* Make Twenty Eleven available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Eleven, use a find and replace
	 * to change 'twentyeleven' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'twentyeleven', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Load up our theme options page and related code.
	require( get_template_directory() . '/inc/theme-options.php' );

	// Grab Twenty Eleven's Ephemera widget.
	require( get_template_directory() . '/inc/widgets.php' );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'twentyeleven' ) );
	
	register_nav_menu( 'fmenu1', 'Footer Menu 1' );
	
	register_nav_menu( 'fmenu2', 'Footer Menu 2' );
	
	register_nav_menu( 'fmenu3', 'Footer Menu 3' );
	
	register_nav_menu( 'fmenu4', 'Footer Menu 4' );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );

	$theme_options = twentyeleven_get_theme_options();
	if ( 'dark' == $theme_options['color_scheme'] )
		$default_background_color = '1d1d1d';
	else
		$default_background_color = 'e2e2e2';

	// Add support for custom backgrounds.
	add_theme_support( 'custom-background', array(
		// Let WordPress know what our default background color is.
		// This is dependent on our current color scheme.
		'default-color' => $default_background_color,
	) );

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );

	// Add support for custom headers.
	$custom_header_support = array(
		// The default header text color.
		'default-text-color' => '000',
		// The height and width of our custom header.
		'width' => apply_filters( 'twentyeleven_header_image_width', 1000 ),
		'height' => apply_filters( 'twentyeleven_header_image_height', 288 ),
		// Support flexible heights.
		'flex-height' => true,
		// Random image rotation by default.
		'random-default' => true,
		// Callback for styling the header.
		'wp-head-callback' => 'twentyeleven_header_style',
		// Callback for styling the header preview in the admin.
		'admin-head-callback' => 'twentyeleven_admin_header_style',
		// Callback used to display the header preview in the admin.
		'admin-preview-callback' => 'twentyeleven_admin_header_image',
	);

	add_theme_support( 'custom-header', $custom_header_support );

	if ( ! function_exists( 'get_custom_header' ) ) {
		// This is all for compatibility with versions of WordPress prior to 3.4.
		define( 'HEADER_TEXTCOLOR', $custom_header_support['default-text-color'] );
		define( 'HEADER_IMAGE', '' );
		define( 'HEADER_IMAGE_WIDTH', $custom_header_support['width'] );
		define( 'HEADER_IMAGE_HEIGHT', $custom_header_support['height'] );
		add_custom_image_header( $custom_header_support['wp-head-callback'], $custom_header_support['admin-head-callback'], $custom_header_support['admin-preview-callback'] );
		add_custom_background();
	}

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be the size of the header image that we just defined
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( $custom_header_support['width'], $custom_header_support['height'], true );

	// Add Twenty Eleven's custom image sizes.
	// Used for large feature (header) images.
	add_image_size( 'large-feature', $custom_header_support['width'], $custom_header_support['height'], true );
	// Used for featured posts if a large-feature doesn't exist.
	add_image_size( 'small-feature', 500, 300 );

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'wheel' => array(
			'url' => '%s/images/headers/wheel.jpg',
			'thumbnail_url' => '%s/images/headers/wheel-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Wheel', 'twentyeleven' )
		),
		'shore' => array(
			'url' => '%s/images/headers/shore.jpg',
			'thumbnail_url' => '%s/images/headers/shore-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Shore', 'twentyeleven' )
		),
		'trolley' => array(
			'url' => '%s/images/headers/trolley.jpg',
			'thumbnail_url' => '%s/images/headers/trolley-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Trolley', 'twentyeleven' )
		),
		'pine-cone' => array(
			'url' => '%s/images/headers/pine-cone.jpg',
			'thumbnail_url' => '%s/images/headers/pine-cone-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Pine Cone', 'twentyeleven' )
		),
		'chessboard' => array(
			'url' => '%s/images/headers/chessboard.jpg',
			'thumbnail_url' => '%s/images/headers/chessboard-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Chessboard', 'twentyeleven' )
		),
		'lanterns' => array(
			'url' => '%s/images/headers/lanterns.jpg',
			'thumbnail_url' => '%s/images/headers/lanterns-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Lanterns', 'twentyeleven' )
		),
		'willow' => array(
			'url' => '%s/images/headers/willow.jpg',
			'thumbnail_url' => '%s/images/headers/willow-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Willow', 'twentyeleven' )
		),
		'hanoi' => array(
			'url' => '%s/images/headers/hanoi.jpg',
			'thumbnail_url' => '%s/images/headers/hanoi-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Hanoi Plant', 'twentyeleven' )
		)
	) );
}
endif; // twentyeleven_setup

if ( ! function_exists( 'twentyeleven_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_header_style() {
	$text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail.
	if ( $text_color == HEADER_TEXTCOLOR )
		return;

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $text_color ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo $text_color; ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // twentyeleven_header_style

if ( ! function_exists( 'twentyeleven_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_theme_support('custom-header') in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
		font-family: "Helvetica Neue", Arial, Helvetica, "Nimbus Sans L", sans-serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 32px;
		line-height: 36px;
		text-decoration: none;
	}
	#desc {
		font-size: 14px;
		line-height: 23px;
		padding: 0 0 3em;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	#headimg img {
		max-width: 1000px;
		height: auto;
		width: 100%;
	}
	</style>
<?php
}
endif; // twentyeleven_admin_header_style

if ( ! function_exists( 'twentyeleven_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_theme_support('custom-header') in twentyeleven_setup().
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_admin_header_image() { ?>
	<div id="headimg">
		<?php
		$color = get_header_textcolor();
		$image = get_header_image();
		if ( $color && $color != 'blank' )
			$style = ' style="color:#' . $color . '"';
		else
			$style = ' style="display:none"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php if ( $image ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // twentyeleven_admin_header_image

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function twentyeleven_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyeleven_excerpt_length' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyeleven_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function twentyeleven_auto_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'twentyeleven_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function twentyeleven_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyeleven_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyeleven_custom_excerpt_more' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function twentyeleven_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyeleven_page_menu_args' );

/**
 * Register our sidebars and widgetized areas. Also register the default Epherma widget.
 *
 * @since Twenty Eleven 1.0
 */
function meme_widgets_init() {

	register_widget( 'Twenty_Eleven_Ephemera_Widget' );

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'twentyeleven' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar(array(
	  'name' => __( 'Event Sidebar' ),
	  'id' => 'right-sidebar',
	  'description' => __( 'This will appear on the events page' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	  'after_widget'  => '</aside>',
	));
	
	register_sidebar(array(
	  'name' => __( 'Categories Sidebar' ),
	  'id' => 'cat-sidebar',
	  'description' => __( 'This will appear on the categories page' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	  'after_widget'  => '</aside>',
	));

}
add_action( 'widgets_init', 'meme_widgets_init' );

if ( ! function_exists( 'twentyeleven_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function twentyeleven_content_nav( $html_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<div id="<?php echo esc_attr( $html_id ); ?>" style="text-align: right; padding-right: 10px;">
			<div class="nav-previous"><?php next_posts_link( "<span class=\"button\">Next</span>" ); ?></div>
			<div class="nav-next"><?php previous_posts_link( "<span class=\"button\">Prev</span>" ); ?></div>
		</div><!-- #nav-above -->
	<?php endif;
}
endif; // twentyeleven_content_nav

/**
 * Return the URL for the first link found in the post content.
 *
 * @since Twenty Eleven 1.0
 * @return string|bool URL or false when no link is present.
 */
function twentyeleven_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function twentyeleven_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

if ( true or  ! function_exists( 'twentyeleven_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyeleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Eleven 1.0
 */
function meme_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyeleven' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	$author = get_comment_author();
	//now let's see if this is a character
	list($name,$class,$thumbnail) = get_character_info($author);
	
    ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-meta">
				<div class="comment-author vcard" style="height:60px;">
					<img src="<?=$thumbnail?>" width="60" height="60" alt="">
				</div><!-- .comment-author .vcard -->

			</div>

			<div class="comment-content">
				<div class="comment-info"><?php
				$elapsed = time_elapsed(get_comment_time( 'U' ));
				
				?><strong class="the-comment-author class-<?=$class?>"><?=$name?></strong> <span class="the-comment-time"><?=$elapsed?></span></div>
			<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyeleven' ); ?></em>
				<?php else: comment_text();endif; ?></div>

			<?php if (ext_smf_logged_in()): ?><div class="reply">
	            <?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
            <?php endif; ?>
		</div><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for meme_comment()

/**
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_body_classes( $classes ) {

	if ( function_exists( 'is_multi_author' ) && ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_singular() && ! is_home() && ! is_page_template( 'showcase.php' ) && ! is_page_template( 'sidebar-page.php' ) )
		$classes[] = 'singular';

	return $classes;
}
add_filter( 'body_class', 'twentyeleven_body_classes' );

