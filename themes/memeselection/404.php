<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

get_header(); ?>
        
		<div id="main" class="search-page">
        
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
                        
                        <h1 class="page-title">Not Found</h1>
                        
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
    				
                    <div class="left_nosidebar"></div>
    
    				<div class="main_nosidebar">
                
                    <div id="post-0" class="search-post no-results not-found">
                        <div class="page-header">
                            <h2 class="page-subtitle">Lyndrae ate the page</h2>
                        </div><!-- .page-header -->
    
                        <div class="post-content">
                            <h3>404: That's an error</h3>
                            <p>
                            So... you looked for page. It was going to be great. Everything you ever wanted, actually. And then, you clicked that link or searched for that special something, and BAM! 404. SOWWRRY. Why not use the search bar above and maybe that will help?
                            </p>
                                <ul>
                                    <li>Make sure all words are spelled correctly</li>
                                    <li>Try using more general keywords</li>
                                </ul>
                        </div>
                    </div><!-- #post-0 -->
                
                </div>
            
            	<div class="clear clr"></div>
                
			</div> <!-- .content -->
            
            
            
            </div> <!-- .sub-wrapper -->

		</div> <!-- main -->

<?php get_footer(); ?>