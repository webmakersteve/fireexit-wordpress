<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyeleven' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="http://fireexitguild.com/wp-content/themes/memeselection/favicon.ico" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>

<script type="text/javascript" src="http://static.wowhead.com/widgets/power.js"></script><script>var wowhead_tooltips = { "colorlinks": true, "iconizelinks": true, "renamelinks": true }</script>
</head>

<body <?php body_class(); ?>>
<div id="modal-wrapper">
	<div class="modal-background"> </div>
    <div class="modal-wrapper">
    	<div class="modal-box">
        	<div class="modal-header">
				<div class="modal-close"><a class="modal-close">x</a></div>
	        	<h1>Login</h1>
            </div>
            <div class="modal-content">
		        <?php ssi_login(); ?>
        	</div>
        </div>
    </div>
</div> <!-- #modal-wrapper -->
<div id="page" class="hfeed">
	<div class="header" id="top">
    	<div id="other">
		
			<div id="search">
				<?php get_search_form(); ?>
          	</div>
        
        </div>
    	<div id="logo">
        	<img src="<?=get_template_directory_uri();?>/images/logo.png" width="288" height="120">
        </div>
        
	</div><!-- #branding -->
	
    <div id="under-header" style="height: 80px;">
    	
        <div id="login-button">
        <div id="charvatar">
        <?php
		//let's get the default character image
		list($name,$class,$thumbnail) = default_character_info();
//		print_r(default_character_info()
//		die($thumbnail);
		?><img class="vcard" width="58" height="58" alt="" src="<?=$thumbnail?>"> </div>
        
        <div class="text-content">
        <?php if ( ext_smf_logged_in( ) ): ?>
        <small style="text-transform:capitalize">Hello,</small>
        <div><a id="charname" class="class-<?=$class?>" href="javascript:void(0);"><?=$name?></a></div>
        <?php else: ?>
        <a href="/forum/index.php?action=login">Log in now</a> to gain full access to the site
        <?php endif; ?>
        </div>
        
        
        </div>
        
		<?php $menu = (array) wp_get_nav_menu_items(2); ?>
        <?php
            if (count($menu) > 0):
        ?>
        <div class="nav" id="main-nav">
            <ul>
                <?php $i=0; foreach ( $menu as $v ): ?>
                    <li style="background-position-x: <?=$i?>px;"><a href="<?=$v->url?>"><?=$v->title?></a></li>
                <?php $i=$i-104; endforeach; ?>
            </ul>
            <div class="clear"></div>
        </div>
        <?php endif; ?>
    
    	<div class="submenu" id="char-submenu">
        <?php 
		$characters = get_characters( );
		$charactersArray = array();
		
		if (count($characters) > 0) {
			foreach ($characters as $char) {
				$armoryData = json_decode($char['armorydata']);
				$charactersArray[] = array('name' => $char['name'],
										 'realm' => $char['realm'],
										 'class' => $armoryData->class,
										 'thumbnail' => "http://us.battle.net/static-render/us/".$armoryData->thumbnail,
										 'race' => $armoryData->race,
										 'level' => $armoryData->level,
										 'default' => $char['default_char'],
										 'id' => $char['id']);
			}
		}
		?>
            <ul>
            	<li class="nohover">
               Character List
                </li>
            <?php foreach ($charactersArray as $character):?>
                <li class="character-of-list" data-id="<?=$character['id'];?>">
                	<div class="def-icon <?=$character['default'] ? "yes-def" : "no-def"?>"></div>
                    <a href="javascript:void(0);" class="wrapper">
                        <div class="name"><?=$character['name']?></div>
                        <div class="meta class-<?=$character['class']?>"><?=$character['level']?> <?=$GLOBALS['races'][$character['race']]?> <?=$GLOBALS['classdata'][$character['class']]['name']?></div>
                        <div class="realm"><?=$character['realm']?></div>
                    </a>
                </li>
            <?php endforeach; ?>
            	<li class="nohover" style="background: none;">
                	<div class="def-icon" style="background-position: 0 -265px; margin-top: 7px;"></div>
                	<a href="/link-your-character" class="wrapper">
                    	<div style="color:#00B6FF">Manage Characters</div>
                        <div style="color: #5b616a;font-size: 11px;">Add characters to your list!</div>
                    </a>
                </li>
            </ul>
        </div> <!-- .submenu -->
    
    </div>
    <?php if (!ext_smf_logged_in()): ?>
	<script type="text/javascript">
	$('#login-button .text-content a').attr('href', "javascript: void(0);").click(function() {
		$('#modal-wrapper').fadeIn(1000);
		$('#modal-wrapper .modal-header a').click(function() {
			$('#modal-wrapper').fadeOut(300);
		});
	});
	</script>
    <?php else: ?>
	<script type="text/javascript">
	$('#charname').click(function() {
		$('#char-submenu').show();
	});
	
	$('#char-submenu .character-of-list').click(function() {
		charID = $(this).attr('data-id');
		$.getJSON( '//fireexitguild.com/wp-admin/admin-ajax.php',
				   {action: 'set_default_character',id:charID},
				   function(data) {
					   if (data.status=="ok") {
						   window.location.reload();
							return true;   
					   } else return false;
				   });
	});
	</script>
	<?php endif;; ?>
	<div id="main">
