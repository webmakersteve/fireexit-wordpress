<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 */

get_header(); ?>
<div id="the-content-container">
		<div id="secondary">
        	<?php if ( ! dynamic_sidebar( 'cat-sidebar' ) ) : ?>
            <?php endif; ?>
        </div>
		<div id="primary">
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

				<div id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-0 -->

			<?php endif; ?>
			
            <div id="the-image-content-bottom" style="text-align: right;"><?php twentyeleven_content_nav( 'nav-below' ); ?></div>
            <div class="clear"> </div>
			</div><!-- #content -->
            
		</div><!-- #primary -->
        
</div><!--#the-content-container-->
<?php get_footer(); ?>