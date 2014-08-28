<?php

/*
Plugin Name: Meme Selection Committee Plugin
Plugin URI: http://fireexitguild.com/theme
Description: Lots of WoW stuff dependent on the integration with SMF
Author: Stephen Parente
Version: 1
Author URI: http://91ferns.com/sparente
*/

 
function meme_install () {
   global $wpdb;

   $table_name = $wpdb->prefix . "characters"; 
   $table_name2 = $wpdb->prefix . "armory_requests";
   
	$sql = "CREATE TABLE IF NOT EXISTS `".$table_name."` (
	  id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  entered datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  name tinytext NOT NULL,
	  realm tinytext NOT NULL,
	  accountid mediumint(9) NOT NULL,
	  validated tinyint(1) DEFAULT 0 NOT NULL,
	  armorydata text NOT NULL,
	  data text NOT NULL,
	  default_char tinyint(1) DEFAULT 0 NOT NULL,
	  armoryurl VARCHAR(55) DEFAULT '' NOT NULL
	);";
	
	$sql2 = "CREATE TABLE IF NOT EXISTS `".$table_name2."` (
	  id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	  entered int NOT NULL,
	  ip varchar(30) NOT NULL,
	  url tinytext NOT NULL,
	  accountid mediumint(9) DEFAULT 0 NOT NULL,
	  count int(10) DEFAULT 0 NOT NULL,
	  response text NOT NULL
	);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	$wpdb->query( $sql );
	$wpdb->query( $sql2 );
}

class ArmoryRequest {
	
	private $APIPrefix = "http://us.battle.net/api/wow/";
	private $url, $fresh_index;
	
	public function __construct( $url, $freshness = 86400 ) {
		
		$this->url = $url;
		$this->fresh_index = $freshness;
		$this->validateURL(); //make sure the URL is a valid blizzard URL or else we fix it
		
		//now we have everything validated so we need to wait until they submit the request. Other options may happen here
		return $this;
		
		
	}
	
	private function validateURL( ) {
		
		//validate URL using the APIPrefix
		if ( strstr( $this->url, $this->APIPrefix ) ) {
			//this means we're okay
			return true;	
		} else {
			$this->url = $this->APIPrefix . $this->url;
			return false;	
		}
		
		//this is incredibly rudimentary for now
		
	}
	
	private function fetch( $OVERRIDE_CACHE = 0 ) {
		
		//when we fetch the data, we want to check if it is in the log and fresh first, unless override cache is on
		
		if ($OVERRIDE_CACHE == 0) {
			global $wpdb;
			
			$table_name = $wpdb->prefix . "armory_requests";
			
			$sql = $wpdb->prepare( "SELECT response,id,count FROM `$table_name` WHERE entered > %d AND url = '%s' ORDER BY entered DESC LIMIT 1",
											time()-$this->fresh_index,
											$this->url);
			
			//now let's access the database with the url
			$result = $wpdb->get_row($sql, ARRAY_A);								
			
			if ($result != null) {
				//now we need to add one to the count
				//let's get the result data
				$id = $result['id'];
				$count = $result['count']+1;
				
				$wpdb->update( $table_name,
								array( 'count' => $count ),
								array( 'id' => $id ),
								array( '%s' ),
								array( '%d' ) );
				
				//now return the response
				return json_decode(stripslashes($result['response']));
				
			} 
				
			//this means we need to get it from the server
			
			$headers = get_headers( $this->url, 1 );
			
			if ($headers[0] == 'HTTP/1.1 200 OK') {
				
				$contents = file_get_contents( $this->url );
				$ob = json_decode($contents);
				
				$this->log( $contents );
				
				return $ob;
				
				
			} else {
				
				return false;	
				
			}
			
			
		}
		
	}
	
	private function log( $response ) {
		global $wpdb;
		$table_name = $wpdb->prefix . "armory_requests";
		$wpdb->insert( $table_name,
						array(
							'entered' => time(),
							'ip' => $_SERVER['REMOTE_ADDR'],
							'url' => $this->url,
							'accountid' => msc_ext_smf_user_id(),
							'count' => 1,
							'response' => mysql_real_escape_string( $response )
						),
						array('%d', '%s', '%s', '%d', '%d', '%s')
					);
					
		return true;
		
	}
	
	public function submit($o=0) {
		return $this->fetch($o);
	}
	
	
}

function insert_character( $name, $realm ) {
	
	//$affectd = $wpdb->insert( $table_name, array() );	
	
}

register_activation_hook( __FILE__, 'meme_install' );

class RandomPostWidget extends WP_Widget
{
  function RandomPostWidget()
  {
    $widget_ops = array('classname' => 'RandomPostWidget', 'description' => 'Displays hot SMF posts' );
    $this->WP_Widget('RandomPostWidget', 'Hot SMF Posts', $widget_ops);
	//TODO come up with a better "HOT" algorithm
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE
    $x = ssi_topTopics('replies', 5, 'return');
	
	if (count($x) > 0) { //this means there are posts
		
		?>
        <ul>
        	<?php foreach ($x as $data): $data=(object)$data; ?>
            <li>
            <div><?=$data->link?></div>
            <div><span><?=$data->num_replies?> comments.<?php if (isset($data->board)): ?> <strong><?=$data->board?></strong><?php endif; ?></div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php
		
	} else {
		?>There are no popular forum topics at this time.<?php 
	}
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("RandomPostWidget");') );

class GuildNews extends WP_Widget
{
  function GuildNews()
  {
    $widget_ops = array('classname' => 'GuildNews', 'description' => 'Displays your guild news' );
    $this->WP_Widget('GuildNews', 'Guild News', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'guildname' => '', 'guildserver' => '' ) );
    $title = $instance['title'];
	$name = $instance['guildname'];
	$server = $instance['guildserver'];
?>
  <p>
  <label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label>
  <label for="<?php echo $this->get_field_id('guildname'); ?>">Guild Name: <input class="widefat" id="<?php echo $this->get_field_id('guildname'); ?>" name="<?php echo $this->get_field_name('guildname'); ?>" type="text" value="<?php echo attribute_escape($name); ?>" /></label>
  <label for="<?php echo $this->get_field_id('guildserver'); ?>">Realm: <input class="widefat" id="<?php echo $this->get_field_id('guildserver'); ?>" name="<?php echo $this->get_field_name('guildserver'); ?>" type="text" value="<?php echo attribute_escape($server); ?>" /></label>
  </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['guildname'] = $new_instance['guildname'];
    $instance['guildserver'] = $new_instance['guildserver'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;
 
    // WIDGET CODE GOES HERE
	$json = $this->fetchGuildJSON($instance['guildname'], $instance['guildserver']);
	$news = $json->news;
	
	if (count($news) > 0) {
	?><ul><?php $max = 5; $i=0;
        foreach ($news as $data):
        if ($i>$max) break;
        switch ($data->type) {
            
            case 'playerAchievement':
                $char = $data->character;
                $ach = $data->achievement;
                $achievementName = $ach->title;
                $achievementID = $ach->id;
                
                ?><li><?=$char?> earned achievement <a class="achieve" href="javascript: void(0);" rel="achievement=<?=$achievementID?>">[<?=$achievementName?>]</a></li><?php
                
            break;
			case 'itemCraft':
				$char = $data->character;
				$item = $data->itemId;
				?><li><?=$char?> crafted <a href="javascript: void(0);" rel="item=<?=$item?>">an item.</a></li><?php
			break;
			case 'itemLoot':
				$char = $data->character;
				$item = $data->itemId;
				?><li><?=$char?> looted <a href="javascript: void(0);" rel="item=<?=$item?>">an item.</a></li><?php
			break;
			case 'guildAchievement':
				?><li>We earned <a class="achieve" href="javascript: void(0);" rel="achievement=<?=$data->achievement->id?>">an achievement</a>!</li><?php
			break;
			case 'itemPurchase':
				$char = $data->character;
				$item = $data->itemId;
				?><li><?=$char?> purchased <a href="javascript: void(0);" rel="item=<?=$item?>">an item.</a></li><?php
			break;
            default:
            	print_r($data);
            break;	
            
            
        }
        $i++;
        endforeach;
	
	} else {
		echo "No news to report :(";	
	}
	
 
    echo $after_widget;
  }
  
  protected function fetchGuildJSON( $guildname, $guildserver ) {
	$url = "http://us.battle.net/api/wow/guild/".$guildserver."/".str_replace(" ", "%20", $guildname)."?fields=news";
	$contents = file_get_contents($url);
	return json_decode($contents);
		  
  }
  
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("GuildNews");') );

class CallToArms extends WP_Widget
{
  function CallToArms()
  {
    $widget_ops = array('classname' => 'CallToArms', 'description' => 'Allows you to put a list of items the guild is asked to collect.' );
    $this->WP_Widget('CallToArms', 'Call to Arms', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'json_list' => '') );
    $title = $instance['title'];
	$json_list = $instance['json_list'];
?>
  <p>
  <label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label>
  <label for="<?php echo $this->get_field_id('json_list'); ?>">JSON-ified List: <input class="widefat" id="<?php echo $this->get_field_id('json_list'); ?>" name="<?php echo $this->get_field_name('json_list'); ?>" type="text" value="<?php echo attribute_escape($json_list); ?>" /></label>
  </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['json_list'] = $new_instance['json_list'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;
 	$json_list = $instance['json_list'];
    // WIDGET CODE GOES HERe
	$json = json_decode($json_list);
	if (count($json) > 0): 
	?>
    <p>The guild needs you! to get us stuff! Please add these things to your farming lists</p>
    <ul>
    	<?php foreach ($json as $data): ////wowhead.com/item=<?=$data->id?>
        	<li><a href="javascript: void(0);" rel="item=<?=$data->id?>">this item</a> x <strong><?=$data->qt?></strong></li>
        <?php endforeach; ?>
    </ul>
    <?php
	else: echo 'The guild monster is satisfied!'; endif;
 
    echo $after_widget;
  }
  
 
}

add_action( 'widgets_init', create_function('', 'return register_widget("CallToArms");') );


class SorrowTracker extends WP_Widget
{
  function SorrowTracker()
  {
    $widget_ops = array('classname' => 'SorrowTracker', 'description' => 'Displays recent WoW Forum posts by Sorrow.' );
    $this->WP_Widget('SorrowTracker', 'Sorrow Tracker', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'url' => '') );
    $title = $instance['title'];
	$url = $instance['url'];
	$maxposts = $instance['maxposts'];
	if (!$maxposts) $maxposts = 3;
?>
  <p>
  <label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label>
  <label for="<?php echo $this->get_field_id('url'); ?>">URL: <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo attribute_escape($url); ?>" /></label>
  
  <label for="<?php echo $this->get_field_id('maxposts'); ?>">Max Posts: <input class="widefat" id="<?php echo $this->get_field_id('maxposts'); ?>" name="<?php echo $this->get_field_name('maxposts'); ?>" type="text" value="<?php echo attribute_escape($maxposts); ?>" /></label>
  </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['url'] = $new_instance['url'];
	$instance['maxposts'] = $new_instance['maxposts'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 	
    if (!empty($title))
      echo $before_title . $title . $after_title;
 	$json_list = $instance['json_list'];
    // WIDGET CODE GOES HERE

	$url = $instance['url'];
	
	@$contents = file_get_contents($url);
	if (isset($contents) and strlen($contents) > 0) {
	
		$contents = preg_replace("#\"result \"#", "\"result\"", $contents);
		
		$html = new DOMDocument();
		@$html->loadHTML($contents);
		
		$sorrowData = array();
		
		$xpath = new DomXpath($html);
		$results = $xpath->query("//*[@class='result']");
		
		if ($results->length > 0):
			$maxposts = $instance['maxposts'];
			$count = 0;
			
			foreach ($results as $result):
				
				if ($count>=$maxposts) break;
				$count++;
				
				//let's see if we can just echo the post title
				$rr = $result->childNodes->item(1);
				$a = $rr->childNodes->item(3);
				
				//link
				$link = $a->getAttribute('href');
				$title = $a->nodeValue;
				
				$rr = $result->childNodes->item(3);
				$datestr = $rr->childNodes->item(4)->nodeValue;
				$date = substr($datestr,strpos($datestr, 'on')+3);
				
				//content
				
				$rr = $result->childNodes->item(5);
				$content = $rr->childNodes->item(0)->nodeValue;
				
				$content = htmlspecialchars(trim(str_replace("", "...", $content)));
				
				$sorrowData[] = array("link"=>$link, "title"=>$title,"date"=>$date,"content"=>$content);
		
			endforeach;
			
		endif;
		
		if (count($sorrowData) > 0): //this means there are posts ?>
			
			<ul>
				<?php foreach ($sorrowData as $data): $data=(object)$data; ?>
				<li>
				<div><span style="color:white;">On </span><a href="http://us.battle.net/<?=$data->link?>"><?=$data->title?></a></div>
				<div><?=$data->content?></div>
	<div><small><?=$data->date?></small></div>
				</li>
				<?php endforeach; ?>
			</ul>
	
		<?php else: ?>Sorrow is unusually quiet...<?php endif;

	} else {
		
		echo "Error: 404.";	
		
	}
	
	//END WIDGET CODE

    echo $after_widget;
  }
  
 
}

add_action( 'widgets_init', create_function('', 'return register_widget("SorrowTracker");') );


//[{"id":7482,"qt":3}]
$GLOBALS['SMF_CONTEXT'] = $context;

function msc_ext_smf_logged_in( ) {
	global $SMF_CONTEXT;
	if (!isset($SMF_CONTEXT) or $SMF_CONTEXT['user']['is_guest']) {
		return false;
	} else {
		return true;
	}
}

function msc_ext_smf_user_id() {
	global $SMF_CONTEXT;
	if (msc_ext_smf_logged_in()) {
		return $SMF_CONTEXT['user']['id'];	
	} else return 0;
}

function autoapprove( $default, $data ) {
	
	if (msc_ext_smf_logged_in()) {
		return 1;	
	} else return $default;
		
}

add_filter( 'pre_comment_approved' , 'autoapprove', '5', 2 );

function get_characters( ) {
	if (!msc_ext_smf_logged_in()) return array();
	global $wpdb;
	$table_name = $wpdb->prefix . "characters";
	$sql = 'SELECT * FROM '.$table_name.' WHERE validated = 1 AND accountid = '.msc_ext_smf_user_id();
	
	$my_characters = $wpdb->get_results($sql, ARRAY_A);
	
	if ($my_characters==null) {
		return array();	
	} else {
		return $my_characters;	
	}
	
}

function default_character_info() {
	if (!msc_ext_smf_logged_in()) return false;
	$chars = get_characters();
	if ( count($chars) > 0 ):
		//now we need to find out if it is the default
		$defaultChar = array();
		$num = 0;
		foreach ($chars as $char) {
			//if num = 0 we will set it to the array. Otherwise, only do it if the defaultchar is set to 1
			if ($num == 0 or $char['default_char'] == 1) $defaultChar = $char;
			$num++;	
		}
		
		//now we have the info so output what we need
		$armoryData = json_decode($defaultChar['armorydata']);
		$thumbnail = "http://us.battle.net/static-render/us/".$armoryData->thumbnail;
		$class = $armoryData->class;
		$name = $armoryData->name;
		
		return array($name, $class, $thumbnail);
		
		
	else:
		//defaults
		global $SMF_CONTEXT;
		$context = $SMF_CONTEXT;
		$name = $context['user']['name'];
		
		return array($name, 0, "http://us.battle.net/wow/static/images/layout/cards-mop/avatar-neutral.jpg");
		
	endif;
	
}

function get_character_info( $name ) {
	global $wpdb;
	$table_name = $wpdb->prefix . "characters";
	$sql = $wpdb->prepare('SELECT armorydata FROM '.$table_name.' WHERE validated = 1 AND name = "%s" LIMIT 1', $name);
	$the_char = $wpdb->get_row($sql, ARRAY_A);
	if (count($the_char) > 0) {
		$armoryData = json_decode($the_char['armorydata']);
		$thumbnail = "http://us.battle.net/static-render/us/".$armoryData->thumbnail;
		$class = $armoryData->class;
		return array($name, $class, $thumbnail);
	} else {
		return array($name, 0, "http://us.battle.net/wow/static/images/layout/cards-mop/avatar-neutral.jpg");	
	}
}

/* NOW LET's MAKE THE MY CHARACTERS LIST WIDGET */

class MyCharacters extends WP_Widget
{
  function MyCharacters()
  {
    $widget_ops = array('classname' => 'MyCharacters', 'description' => 'Displays an authenticated forum user\'s character.' );
    $this->WP_Widget('MyCharacters', 'My Characters', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '') );
    $title = $instance['title'];
?>
  <p>
  <label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label>
  </p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
	global $SMF_CONTEXT,$wpdb;
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;
 	
    // WIDGET CODE GOES HERE
	
	$chars = get_characters();
	//print_r($chars);
	
	if (count($chars) < 1) {
		echo "You haven't added any characters yet.";	
	} else {
		?><ul><?php
        foreach (get_characters() as $k=>$data): ?>
        	<li class="character"><?=ucwords($data['name'])?></li>
		<?php endforeach; ?>
        </ul><?php
	}
	
	//END WIDGET CODE

    echo $after_widget;
  }
  
 
}

add_action( 'widgets_init', create_function('', 'return register_widget("MyCharacters");') );



//ajax to validate a character

$jsdir = plugin_dir_url( __FILE__ ) . 'js/';

function validate_character() {
	if (!msc_ext_smf_logged_in()) die("Not logged in");
	global $wpdb;
	$charid = $_GET['charid'];
	
	$table_name = $wpdb->prefix . "characters"; 
	//get it from the char id and make sure it is not validated
	$sql = $wpdb->prepare("SELECT * FROM `$table_name` WHERE validated = %d AND id = %d", 0, $charid);
	$charrow = $wpdb->get_row($sql, ARRAY_A);
	
	if ($charrow == null) {
		//couldn't find character	
		die('Couldn\'t find character');
	} else {
		//we found it. now we need to get a request for the character and make sure the items are off
		$suffix = "character/" . $charrow['realm'] . "/" . $charrow['name'] .  "?fields=items,guild";
		$request = new ArmoryRequest( $suffix, 0 );
		$response = $request->submit();
		
		//now check if helm, bracers, and gloves are off
		
		$items = $response->items;
		$guild = $response->guild;
		$rank = $response->character->rank;
		
		$continue = true;
		$problems = array();
		
		if (isset($items->wrist)) {
			$continue = false;	
			$problems[] = "Please unequip your ".$items->wrist->name;
		}
		if (isset($items->head)) {
			$continue = false;
			$problems[] = "Please unequip your ".$items->head->name;
		}
		if (isset($items->hands)) {
			$continue = false;
			$problems[] = "Please unequip your ".$items->hands->name;
		}
		
		if ($continue) {
			//update it to be validated
			
			if ($guild->name=="Fire Exit") {
				//yeh boi
				/*
					$ranks = array(0=>"Lead Officer", 1=>"Raid Officer", 2=>"Bank Officer", 3=> "Personnel Officer",
			   4=> "Veteran", 5=> "Raider", 6=>"Member", 7=>"Recruit");
				 */
				switch ($rank) {
					case '0':
					case '1':
					case '2':
					case '3':
						$appropriateGroupId = 9;
					break;
					case '4':
						$appropriateGroupId = 11;
					break;
					default:
						$appropriateGroupId = 10;
				}
				
			} else {
				//this is for visitors	
				//after this we will upgrade them to the appropriate permissions set
				$appropriateGroupId = 12;
				
			}
			ssi_setMembergroup( msc_ext_smf_user_id(), $appropriateGroupId );
			
			$wpdb->update( $table_name,
							array('validated' => 1, 'accountid' => msc_ext_smf_user_id()),
							array('id' => $charid),
							array('%d', '%d'),
							array('%d')
						 );
			
			die(json_encode(array("status" => "ok", "response" => "Thanks for validating your character, " . ucwords($charrow['name']) . "!")));	
		} else die(json_encode(array('status' => "nok", "response" => $problems)));
		
		
	}
}

function add_character() {
	if (!msc_ext_smf_logged_in()) die("Not logged in");
	global $wpdb;
	$char = $_GET['name'];
	$realm = $_GET['realm'];
	
	$url = "character/".$realm."/".$char."?fields=items,guild,progression";
	$table_name = $wpdb->prefix . "characters"; 
	//get it from the char id and make sure it is not validated
	$sql = $wpdb->prepare("SELECT id,validated FROM `$table_name` WHERE name = '%s' AND realm = '%s'", $char, $realm);
	$charrow = $wpdb->get_row($sql, ARRAY_A);
	
	if ($charrow == null || $charrow['validated'] == 0) {
		//couldn't find character, which is good. Or, it isn' validated, which is okay. Let's add it to the database
		
		$jsonRequest = new ArmoryRequest( $url, 124000 );
		$response = $jsonRequest->submit();
		if ($response) {
			
			if ($charrow == null) {$wpdb->insert( $table_name, array(
													"entered" => date('Y-m-d H:i:s'),
													"name" => ucwords($char),
													"realm" => $realm,
													"accountid" => msc_ext_smf_user_id(),
													"validated" => 0,
													"armorydata" => json_encode($response),
													"data" => '',
													"armoryurl" => $url
												));
			$id = $wpdb->insert_id;									
			} else {
				$id=$charrow['id'];
			}
			
			die(json_encode(array("status" => "ok", "response" => array(
				"message" => "Thank you for adding your character. Now you need to validate it.",
				'name' => ucwords($char),
				"class" => $response->class,
				"level" => $response->level,
				'bracers' => (isset($response->items->wrist)) ? $response->items->wrist->name : false,
				'helm' => (isset($response->items->head)) ? $response->items->head->name : false,
				'gloves' => (isset($response->items->hands)) ? $response->items->hands->name : false,
				'id' => $id
				)
			))); 
		
		} else die(json_encode(array("status" => "nok", "response" => "Character not found.")));
	} else {
		//we can't validate an already validated character
		die(json_encode(array("status" => "nok", "response" => ucwords($charrow['name']) . " is already validated.")));	
	}
}

add_action('wp_ajax_validate_character', 'validate_character');
add_action('wp_ajax_add_character', 'add_character');
add_action('wp_ajax_nopriv_validate_character', 'validate_character');
add_action('wp_ajax_nopriv_add_character', 'add_character');

/*
wp_localize_script( 'my-ajax-request', 'MSCAjax', array(
	// URL to wp-admin/admin-ajax.php to process the request
	'ajaxurl' => admin_url( 'wp-admin/admin-ajax.php' ),
	// generate a nonce with a unique ID "myajax-post-comment-nonce"
	// so that you can check it later when an AJAX request is sent
	'MSCNonce' => wp_create_nonce( 'msc-add-char-nonce' ),
	)
);
*/

function set_default_character() {
	if (!msc_ext_smf_logged_in()) die("Not logged in");
	global $wpdb;
	
	$charID = $_GET['id'];
	$table_name = $wpdb->prefix . "characters"; 
	
	$wpdb->query( 
		$wpdb->prepare("UPDATE `$table_name` SET default_char = 0 WHERE accountid = %d AND validated = 1", msc_ext_smf_user_id()) );
	
	$wpdb->query(
		$wpdb->prepare("UPDATE `$table_name` SET default_char = 1 WHERE accountid = %d AND validated = 1 AND id = %d LIMIT 1", msc_ext_smf_user_id(), $charID)
		);
	
	die(json_encode(array('status' => 'ok', 'response' => 'Changed default character')));
}

add_action('wp_ajax_set_default_character', 'set_default_character');
add_action('wp_ajax_nopriv_set_default_character', 'set_default_character');

require_once("/var/www/domains/fireexitguild.com/html/forum/SSI.php");