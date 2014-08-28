<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>
        
		<div id="main" class="search-page<?php if (have_posts()) {?> bg<?php } ?>">
        
            <div class="sub-wrapper">
            
            <div class="navigate_section">
            
                <div class="top-section">
                
                    <ul>
                        <li><a href="/">Fire Exit</a></li>
                        <li><a href="">Search</a></li>
                    </ul>
                
                </div>
                
			</div>
                
                <div class="under-nav-section">
                
                	<div class="left_nosidebar">
                        
                        <h1 class="page-title">Search</h1>
                        
                    </div>
                
                    <div class="main_nosidebar">
                    
                        <form method="get" id="searchform-big" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <input type="text" class="field" name="s" id="s" style="width:410px;" placeholder="<?php esc_attr_e( 'Search the site', 'twentyeleven' ); ?>" value="<?=(isset($_GET['s']) and !empty($_GET['s'])) ? $_GET['s'] : '' ?>" />
                            <input type="submit" class="submit button" name="submit" id="searchsubmit" value="Search" />
                        </form>
                    
                    </div>
                    
                    
                    <div class="clear clr"></div>
            
                </div> <!-- .under-nav-section -->
    
    			
                <div class="content">
                
                	<div class="left_nosidebar toolbar">
    					
                        <ul class="left_menu">
                        	<li>Summary</li>
                            <li>Characters</li>
                            <li>Forums</li>
                       	</ul>
                    	
                    </div>
    
    				<div class="main_nosidebar">
                <?php if ( have_posts() ) : ?>
    
                    <div class="page-header">
                        <h2 class="page-subtitle"><?php printf( __( 'Summary of results for %s' ), '<span class="search-object">' . get_search_query() . '</span>' ); ?></h2>
                    </div>
    
                    <?php /* Start the Loop */ ?>
                    <?php while ( have_posts() ) : the_post(); ?>
    
                        <?php
                            /* Include the Post-Format-specific template for the content.
                             * If you want to overload this in a child theme then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part( 'search-content', get_post_format() );
                        ?>
    
                    <?php endwhile; ?>
    
                <?php else : ?>
    
                    <div id="post-0" class="search-post no-results not-found">
                        <div class="page-header">
                            <h2 class="page-subtitle"><?php printf( 'Your search for %s has no matches.', '<span class="search-object">' . get_search_query() . '</span>' ); ?></h2>
                        </div><!-- .page-header -->
    
                        <div class="post-content">
                            <h3>Suggestions for searching:</h3>
                                <ul>
                                    <li>Make sure all words are spelled correctly</li>
                                    <li>Try using more general keywords</li>
                                </ul>
                        </div>
                    </div><!-- #post-0 -->
    
                <?php endif; ?>
                
                </div>
                
			</div> <!-- .content -->
            
            </div> <!-- .sub-wrapper -->

		</div> <!-- main -->

<?php get_footer(); ?>