<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
 
	if (!isset($GLOBALS['events_arr'])) return;
	
	//first let's get the specific one we want
	$the_event = array();
	foreach ($GLOBALS['events_arr'] as $events) {
		//we only want the first one
		$id = $events[0]['id']; //only want the first
		break;
	}
	
	//$the_event = array('title', 'id');
	$e = get_page($id);
	$eventtime = get_post_meta($id,'eventtime', true);
	$eventtime = strtotime($eventtime);
	if ($eventtime > 0) { if ($eventtime > time()):
	$diff = $eventtime - time();
	//$diff is the time differential. If it is less than 19 hours we will say "TODAY." Less than 40 hours it will say TOMORROW
	if ($diff < 19*60*60) $time="TODAY";
	elseif ($diff < 40*60*60) $time = "TOMORROW";
	else $time = date('l',$eventtime);
	
?>

	<div id="post-<?=$id?>" <?php post_class(); ?>>
		<div class="header entry-header">
			
            <h2 class="entry-title"><a href="<?=$e->guid; ?>"><?=$e->post_title; ?></a></h2>
            
			<div class="entry-meta">
				Event created by <span class="yellow"><?php the_author($id); ?></span> for <strong style="color:white;"><?=$time; ?></strong> <span class="yellow">
				<span style="background-position: -15px -14px; background-image: url('http://us.battle.net/wow/static/images/layout/cms/blog_icons.gif');">&nbsp;&nbsp;&nbsp;&nbsp;</span><?=$e->comment_count ?></span>
			</div><!-- .entry-meta -->

		</div><!-- .entry-header -->
        
        <div class="entry-summ-container">
        	<div class="entry-summary" style="padding-top: 8px;">
            If you can make it, please be online by <?=date('g:i A', $eventtime)?> to come to this event! Also, if you can, please sign up so we know whom to expect!
            </div>
        </div> <!-- .entry-summ-container-->

		<div class="footer entry-meta">
        	<?php if (true): ?>
            <ul>
            	<li><div class="read-more"><a class="button" href="<?=$e->guid; ?>">View Event</a></div></li>
            </ul>
			<?php endif; ?>
		</div><!-- .entry-meta -->
	</div><!-- #post-<?= $id ?> -->
    
<?php endif; } ?>