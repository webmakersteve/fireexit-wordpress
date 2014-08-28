<?php
/**
 * Template Name: Guild Roster Template
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
$ranks = array(0=>"Lead Officer", 1=>"Raid Officer", 2=>"Bank Officer", 3=> "Personnel Officer",
			   4=> "Veteran", 5=> "Raider", 6=>"Member", 7=>"Recruit");

get_header(); ?>
<div id="the-content-container">
<?php get_sidebar(); ?>
		<div id="primary" class="showcase">
			<div id="content" role="main">
            <div class="post single">
            <header class="entry-header">
                <h1 class="entry-title" style="color:#fdeea4"><?php the_title(); ?></h1>
            </header><!-- .entry-header -->
        
            <div class="entry-content" class="roster">
                	<?php while ( have_posts() ) : the_post();
                    
					the_content();
					
					endwhile;
					
					$url = "http://us.battle.net/api/wow/guild/Maelstrom/Fire%20Exit?fields=members";
					$contents = file_get_contents($url);
					$json = json_decode($contents);
					
					$orig=$members = $json->members;
					$numMembers = count($orig);
					
					if (count($members) > 0):?>
					<table class="roster-table" width="500" cellpadding="0" cellspacing="0">
                    
                    <thead>
                    	<tr>
                    		<th align="left">Name</th>
                            <th align="center">Race</th>
                            <th align="center">Class</th>
                            <th align="center">Level</th>
                            <th align="center">Rank</th>
                            <th align="center">Achievement Points</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $orderedArray = array(); 
					foreach ($members as $memdata) {
						$rank = $memdata->rank;
						if ($memdata->character->level >= 70) $orderedArray[$rank][]=$memdata;	
					}
					?>
					<?php $c=2; for($i=0; $i<=count($orderedArray); $i++) {$members = $orderedArray[$i]; ; foreach ($members as $memdata): $c++;
					$char = $memdata->character;
					
					$name = $char->name;
					$tnail = $char->thumbnail;
					$level = $char->level;
					$class = $char->class;
					$aPoints = $char->achievementPoints;
					$gender = $char->gender;
					$race = $char->race;
					$rank = $memdata->rank;
					
					?><tr class="zebra-<?=($c%2)+1?>">
                    	<td align="left"><a style="color:<?=$classdata[$class]['color']?> !important; text-decoration:none;" target="_blank" href="http://us.battle.net/wow/en/character/maelstrom/<?=$name?>/simple"><?=$name?></a></td>
						<td align="center"><img width="15" height="15" src="http://us.battle.net/static-render/us/<?=$tnail?>" alt=""></td>
						<td align="center"><?=$classdata[$class]['name']?></td>
						<td align="center"><?=$level?></td>
                        <td align="center"><?=$ranks[$rank]?></td>
                        <td align="center"><?=$aPoints?></td>
					</tr><?php
					endforeach; }
					
					?></tbody></table><?php endif; ?>
                    <p><?=$numMembers?> total members.</p>
                </div>
			</div>
			</div><!-- #content -->
		</div><!-- #primary -->
</div><!---#content-container-->
<?php get_footer(); ?>