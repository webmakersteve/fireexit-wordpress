<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="header entry-header">
			<?php if ( is_sticky() ) : ?>
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php else : ?>
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentyeleven' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<?php endif; ?>

			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				by <span class="yellow"><?php the_author(); ?></span> <?php echo time_elapsed(get_the_time('U')); ?> <span class="yellow">
				<span style="background-position: -15px -14px; background-image: url('http://us.battle.net/wow/static/images/layout/cms/blog_icons.gif');">&nbsp;&nbsp;&nbsp;&nbsp;</span><?php comments_number( "0", "1", "%" ); ?></span>
			</div><!-- .entry-meta -->
			<?php endif; ?>

		</div><!-- .entry-header -->

		<?php if ( !is_single() ) :  ?>
        <div class="entry-summ-container<?= has_post_thumbnail() ? ' has-thumbnail' : ''?>">
        <?php if ( has_post_thumbnail() ): ?>
        	<div class="post-thumbnail"><?php the_post_thumbnail(array(115,115)); ?></div>
        <?php endif; ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
            <div class="read-more"><a class="" href="<?php the_permalink(); ?>">More</a></div>
		</div><!-- .entry-summary -->
        </div> <!-- .entry-summ-container-->
		<?php else : ?>
		<div class="entry-content">
			<?php the_content( ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<div class="footer entry-meta">
        	<?php if (!is_single()): ?>
            <?php /*
            <ul>
            	<!--<li><div class="read-more"><a class="button" href="<?php the_permalink(); ?>">Read More</a></div></li>-->
				<!--<li><div class="edit"><?php edit_post_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link button">', '</span>' );?></div></li>-->
            </ul>
			*/ ?>
			<?php endif; ?>
		</div><!-- .entry-meta -->
	</div><!-- #post-<?php the_ID(); ?> -->
