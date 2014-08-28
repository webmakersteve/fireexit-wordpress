<?php
/**
 * Template Name: Event Template
 * Description: A page that displays all of the guild members
 *
 * The showcase template in Twenty Eleven consists of a featured posts section using sticky posts,
 * another recent posts area (with the latest post shown in full and the rest as a list)
 * and a left sidebar holding aside posts.
 *
 * We are creating two queries to fetch the proper posts and a custom widget for the sidebar.
 *
 * @package MSC
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

// Enqueue showcase script for the slider
//wp_enqueue_script( 'twentyeleven-showcase', get_template_directory_uri() . '/js/showcase.js', array( 'jquery' ), '2011-04-28' );

$classdata = array(   1 => array("name" => "Warrior", "color" => "#C79C6E"),
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
$races = array(0=>'Orc',1,2,3,4,5=>'Undead',6=>'Tauren',7,8=>'Troll',9=>'Goblin',10=>'Blood Elf',26=>'Pandaren');
$ranks = array(0=>"Guild Master", 1=>"Raid Leader", 2=>"Officer", 3=> "Officer Alt",
			   4=> "Meme Legend", 5=> "Veteran Raider", 6=>"Member", 7=>"Recruit");

get_header(); ?>
<div id="the-content-container">
		
        <div class="" style="background-image: url('http://us.battle.net/wow/static/images/layout/content-topbot.jpg'); background-repeat:no-repeat; padding: 18px 3px 2px; height: 200px; background-position: -1px 0;">
        	<div class="event-header" style="background-image: url('<?php if (stristr(get_the_title(), "heart")): ?>http://nerdbulletin.com/wp-content/uploads/2012/12/Heart_of_Fear_loading_screen.jpg<?php else: ?>http://typhoonandrew.files.wordpress.com/2012/09/mogushan_vaults_loading_screen.jpg<?php endif; ?>'); background-position: -250px -340px;  border: 1px solid #552b12; height: 200px; padding: 0 0px;"></div>
		</div>
		<?php while ( have_posts() ) : the_post(); ?>
        <?php
		$eventtime=get_post_meta(get_the_ID(), 'eventtime',true); $time = strtotime($eventtime); //get the event time which will be used in the unique identifier
		//build the raid id
		$raidID = get_the_ID()."-".$time;
		//now we need to use the mysql connection
		$conn = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$confirmed_query = mysqli_query( $conn, 
								"SELECT * FROM `blog_signups` WHERE raidid = '".mysqli_real_escape_string($conn, trim($raidID))."' AND status = 1 ORDER BY role ASC LIMIT 10" );
		
		?>
		<div id="secondary" class="widget-area" role="complementary">
            
        	<aside id="confirmed" class="widget">
                <h3 class="widget-title">Confirmed</h3>
                <?php if (mysqli_num_rows( $confirmed_query ) > 0): ?>
                <ul>
                	<?php while ($result = mysqli_fetch_assoc( $confirmed_query )): ?>
                    	<li><span style="height: 16px; padding-left: 16px; margin-right: 4px; background-image: url('/wp-content/themes/memeselection/images/rolestiny.png'); background-position:-<?php echo (16*($result['role']-1)) ?>px 0;"></span><?=$result['character']?> <span style="color: #093;">Confirmed</span></li>
                    <?php endwhile; ?>
                    <li style="border:none;"><?=mysqli_num_rows( $confirmed_query )?> total.</li>
                </ul>
                <?php else: ?>
                Be the first to sign up!
                <?php endif; ?>
            </aside>
            
            <aside id="other" class="widget">
                <h3 class="widget-title">Other</h3>
                <ul>
                    <li>Breyada</li>
                </ul>
            </aside>
            
            <div style="padding: 14px 0;"><a class="button" href="http://www.fireexitguild.com/2013/03/official-loot-rules/">Invite</a></div>
            
            <?php dynamic_sidebar('right-sidebar'); ?>
        
        </div> <!--#secondary-->
		<div id="primary" class="showcase">
        	
			<!--div id="test" style="background-color: #211309; margin: 0 40px; position: absolute; top: -80px; padding: 13px 25px; background-image: url('http://us.battle.net/wow/static/images/wiki/box-bg.jpg'); background-repeat: no-repeat; background-position: 0 0; border-radius: 8px; width: 500px; height: 120px;">
	            <h3 class="entry-title" style="color:#fdeea4"><?php the_title(); ?>	</h3>
            </div-->
        	
			<div id="content" role="main">
            <div class="post single">
            <header class="entry-header">
                <h1 class="entry-title" style="color:#fdeea4"><?php the_title(); ?></h1>
                <small style="color:white;"><?=date('l, F jS', $time)?> at <?=date('g:i A', $time)?></small>
            </header><!-- .entry-header -->
        
            <div class="entry-content" class="roster">
                    
					<? the_content(); ?>
					
                    <div class="event-signup">
            
                        <h2>Sign up for the Event!</h2>
                        
                        <?php if (!ext_smf_logged_in( )): $_SESSION['login_url']=get_permalink(); ?><div class="button-wrapper"><a class="button" href="<?=$GLOBALS['SMF_CONTEXT']['menu_buttons']['login']['href']?>">LOGIN TO SIGNUP</a></div><?php else: ?>
                        
                        <?php $g = get_characters(); if (count($g) > 0): ?>
                        <p>Step 1: Choose your Character</p>
                        <form action="/" method="post">
                            <select>
                            <?php foreach ($g as $character): ?>
                            <option><?=$character['name']?></option>
                            <?php endforeach; ?>
                            </select>
                            <p>Step 2: Choose your Role</p>
                            <input type="radio" name="role" value="1"> Tank
                            <input type="radio" name="role" value="2"> Healer
                            <input type="radio" name="role" value="3"> DPS
                            <p>Step 3: Choose your status</p>
                            <select>
                            <option>Definitely Coming</option>
                            <option>Can be on standby</option>
                            <option>Can't make it.</option>
                            </select>
                            <p>Step 4: Additional notes</p>
                            <p>Please let us know anything you think worthy to be known.</p>
                            <div><textarea></textarea></div>
                            <input type="submit" class="button" value="Sign Up.">
                        </form>
                        <?php else: ?>
                        Howdy, <?=smf_login_name();?>! We need as many people as possible to help us, so please sign up! But it looks like you haven't yet linked your character(s) to your account. Please do so on this <a href="/link-your-character">page</a>.
                        <?php endif; ?>
                        <?php endif; ?>
                    
                    </div> <!--.event-signup-->
                    
			</div>
            
			</div><!-- #content -->
            <?php comments_template( '', true ); ?>
            
		</div><!-- #primary -->
        <?php endwhile; ?>
</div><!---#content-container-->
<?php get_footer(); ?>