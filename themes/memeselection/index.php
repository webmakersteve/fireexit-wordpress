<?php
/**
 * @todo index
 */

get_header(); ?>
<div id="the-content-container">
<?php get_sidebar(); ?>
		<div id="primary">
        	<?php 
			query_posts(array('post_type' => 'slider'));
			$thumbnails = array();
			while (have_posts()): the_post();
				$url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
				$thumbnails[] = $url;
			endwhile;
			wp_reset_query();
			?>
            <div id="slideshow" style="position: relative; width: 640px; height: 300px;">
            	
                <div class="controls">
                
                </div>
                
                <div class="mask" style="height: 300px; position: absolute; z-index: 10; top: 0; left: 0; background-image: url('<?=dirname(get_stylesheet_uri()) . '/slideshow/slideshow-mask.png'?>');"></div>
                <div class="featured" style="height: 300px; width: 640px;">
                	<?php if (count($thumbnails) > 0): foreach ($thumbnails as $tb): ?><div class="item" style="background-image: url('<?=$tb?>');height: 300px; width: 640px;"></div><?php endforeach; else: ?>
                    <div class="item"></div>
                    <?php endif; ?>
                </div>
                
            </div>
        
        	<?php if (!is_paged()): ?>
            <?php $EVENTS_ARR = array();
            $events = new WP_Query(array('post_type' => 'page', 'meta_key' => 'event')); if ( $events->have_posts() ):
            while ( $events->have_posts() ): $events->the_post();
			$eventtime = get_post_meta(get_the_ID(), 'eventtime', true);
			$eventtime = strtotime($eventtime);
			if ($eventtime < time()) continue;
			$EVENTS_ARR[$eventtime][] = array('title' => get_the_title(), 'id' => get_the_ID(), 'time' => $eventtime);
			
            endwhile; rsort(rsort($EVENTS_ARR));
            else:
            endif; wp_reset_query(); ?>
       		<?php endif; ?>
			<div id="content" role="main">
			
			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
                <?php if (count($EVENTS_ARR) > 0) {
					$GLOBALS['events_arr'] = $EVENTS_ARR;
					get_template_part('content-event', 'page');
				} ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', get_post_format() ); ?>

				<?php endwhile; ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>
			
            <div id="the-image-content-bottom" style="text-align: right;"><?php twentyeleven_content_nav( 'nav-below' ); ?></div>
            <div class="clear"> </div>
			</div><!-- #content -->
            
		</div><!-- #primary -->
        
</div><!--#the-content-container-->
<?php get_footer(); ?>