<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	</div><!-- #main -->

	<div class="footer" id="site-footer" role="contentinfo">

		<div class="col-wrapper">
        	<?php
			$menu_locations = get_nav_menu_locations();
			
			$menu_location = 'fmenu1';
			$menu_object = (isset($menu_locations[$menu_location]) ? wp_get_nav_menu_object($menu_locations[$menu_location]) : null);
			$menu_name = (isset($menu_object->name) ? $menu_object->name : 'Footer Menu 1');
			?>
            <div class="col">
            	<div class="col-title"><?=strtoupper($menu_name)?></div><!-- .col-title -->
                <div class="col-content">
                <?php wp_nav_menu( array(
					'theme_location' => 'fmenu1',
					'container' => false,
					'fallback_cb' => function() {?>
					<ul>
                    
                    	<li>Who are we?</li>
                        <li><a href="http://www.fireexitguild.com/roster/">Roster</a></li>
                        <li>Contact us</li>
                        <li>Apply</li>
                    
                    </ul>
					<?php },
				
				)); ?> 
                </div> <!-- .col-content -->
            </div> <!-- .col -->
            <?php
			$menu_location = 'fmenu2';
			$menu_object = (isset($menu_locations[$menu_location]) ? wp_get_nav_menu_object($menu_locations[$menu_location]) : null);
			$menu_name = (isset($menu_object->name) ? $menu_object->name : 'Footer Menu 2');
			?>
            <div class="col">
            	<div class="col-title"><?=strtoupper($menu_name)?></div><!-- .col-title -->
                <div class="col-content">
                <?php wp_nav_menu( array(
					'theme_location' => 'fmenu2',
					'container' => false,
					'fallback_cb' => function() {?>
                	<ul>
                    
                    	<li>The Select Few</li>
                        <li>Meme Archive</li>
                        <li>Create-a-Meme</li>
                        <li>Suggestion Box</li>
                    
                    </ul>
                    <?php },
				
				)); ?> 
                </div> <!-- .col-content -->
            </div> <!-- .col -->
            
            <?php
			
			$menu_location = 'fmenu3';
			$menu_object = (isset($menu_locations[$menu_location]) ? wp_get_nav_menu_object($menu_locations[$menu_location]) : null);
			$menu_name = (isset($menu_object->name) ? $menu_object->name : 'Footer Menu 3');
			
			?>
            
            <div class="col">
            	<div class="col-title"><?=strtoupper($menu_name)?></div><!-- .col-title -->
                <div class="col-content">
                <?php wp_nav_menu( array(
					'theme_location' => 'fmenu3',
					'container' => false,
					'fallback_cb' => function() {?>
                	<ul>
                    
                    	<li>Home</li>
                        <li>Our Theme</li>
                        <li>Support</li>
                        <li>Chat</li>
                    
                    </ul>
                    <?php },
				
				)); ?>
                </div> <!-- .col-content -->
            </div> <!-- .col -->
            
            <?php
			
			$menu_location = 'fmenu4';
			$menu_object = (isset($menu_locations[$menu_location]) ? wp_get_nav_menu_object($menu_locations[$menu_location]) : null);
			$menu_name = (isset($menu_object->name) ? $menu_object->name : 'Footer Menu 4');
			?>
            
            <div class="col">
            	<div class="col-title"><?=strtoupper($menu_name)?></div><!-- .col-title -->
                <div class="col-content">
                <?php wp_nav_menu( array(
					'theme_location' => 'fmenu4',
					'container' => false,
					'fallback_cb' => function() {?>
                	<ul>
                    
                    	<li>My Account</li>
                        <li><a href="/link-your-character">Link my Character</a></li>
                        <li>Reset Password</li>
                        <li><a href="/forum/index.php?action=register">Register</a></li>
                    
                    </ul>
                    <?php },
				
				)); ?>
                </div> <!-- .col-content -->
            </div> <!-- .col -->
            
            <div class="clr"> </div>
            
        </div> <!-- .col-wrapper-->
        
        <div id="lower-footer">
            <div class="below-text">
            	<div id="fern-adv">
                
                    <img src="http://91ferns.com/img/91ferns.png" width="220" height="68" alt="">
                
                </div> <!-- #fern-advertisement -->
            </div>
        </div>
			
	</div><!-- #site-footer -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>