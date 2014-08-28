<?php
/**
 * Template Name: Link Character Template
 * Description: A page that allows a user to link their character to the site SMF installation.
 *
 * @package MSC
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

// Enqueue showcase script for the slider

get_header(); ?>
<div id="the-content-container">
		
		<?php while ( have_posts() ) : the_post(); ?>
        <?php
		if (isset($_POST['op']) and $_POST['op'] = "step1") {
		$conn = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$confirmed_query = mysqli_query( $conn, 
								"SELECT * FROM `blog_signups` WHERE raidid = '".mysqli_real_escape_string($conn, trim($raidID))."' AND status = 1 ORDER BY role ASC LIMIT 10" );
		}
		?>
		<div id="secondary" class="widget-area" role="complementary">
            
            <aside id="other" class="widget">
                <h3 class="widget-title">My Characters</h3>
                <ul id="chars-list">
                	<?php
                    //plugin function
					$chars = get_characters();
					if (count($chars) > 0):
					foreach ($chars as $data):
					$dd = json_decode($data['armorydata']);
					?>
                    <li class="character <?=$data['name']."-".$data['realm']?>" data-id="0" data-url="<?=$data['name']."@".$data['realm']?>">
                    <span class="class-<?=$dd->class?>"><?=$data['name']?></span> 
					<?=$dd->level?> <?=$classdata[$dd->class]['name']?>
                    </li><?php 
					endforeach;
					?><li class="no-message">Please load more of your characters on the left</li><?php
					else:
					?><li class="no-message">Please load your characters on the left</li><?php
					endif;
					?>
                </ul>
            </aside>
        
        </div> <!--#secondary-->
		<div id="primary" class="showcase">
        	
			<div id="content" role="main">
            <div class="post single">
            <div class="entry-header">
                <h1 class="entry-title" style="font: 52px Palatino Linotype, Sans-serif; color: white;"><?php the_title(); ?></h1>
            </div><!-- .entry-header -->
        
            <div class="entry-content roster">
					
                    <div class="profile-load">
                        
                        <?php if (!ext_smf_logged_in( )): $_SESSION['login_url']=get_permalink(); ?><div class="button-wrapper"><a class="button" href="<?=$GLOBALS['SMF_CONTEXT']['menu_buttons']['login']['href']?>">LOGIN TO SIGNUP</a></div><?php else: ?>
                        
                        <?php //print_r( smf_data() ); ?>
                        <?php $data = smf_data(); $user_id = $data['user']['id']; ?>
                        
                        <h3 style="text-transform: uppercase; color: #FEF092; font-size: 17px; border-bottom: 2px solid rgb(85, 43, 18);" >Profile Information</h3>
                        <? the_content(); ?>
                        
                        <input type="text" placeholder="Server" name="server" id="server-select">
                        <input placeholder="Character Name" type="text" name="charname" id="char-select">
                        
                        <span class="forum-submit-with-load">
                            <a id="lbut" class="button">Load</a>
                            <span style="padding-left: 10px;"><img class="loader" src="/wp-content/themes/memeselection/images/load.gif" style="display:none;"></span>
                      	</span>
                        <input type="hidden" id="used-autocomplete-server" value="0">
                        
                        <script>
						$(function() {
                        <?php
                        @$contents = file_get_contents('http://us.battle.net/api/wow/realm/status'); 
                        if ($contents) {
							$json = json_decode($contents);
							if ($json->status != "ok") {
								$realmslist = $json->realms;
								?>var tags = [""<?php
								foreach ($realmslist as $data):
									echo ',"'.$data->name.'"';
								endforeach;?>];<?php
							} else {
								//problem
								?>alert('Problem connecting to Blizzard\'s API');<?php
							}
						}
						?>
						$('#server-select').autocomplete({
							source: function(request, response) {
								var results = $.ui.autocomplete.filter(tags, request.term);
								response(results.slice(0, 7));
							}
						});
						$('#lbut').click(function() {
							realm = $('#server-select').val();
							char = $("#char-select").val();
							url = "//fireexitguild.com/wp-admin/admin-ajax.php"
							data = {action: 'add_character', name: char, realm: realm};
							$.ajax({
								url: url,
								data: data,
								dataType: 'json',
								timeout: 2000,
								success: function(data) {
									if (data.status=="ok") {
										ul = $('#chars-list');
										$('.no-message', ul).hide();
										data=data.response;
										text = '<span class="class-'+data.class+'">'+data.name+"</span> "+data.level+" "+'<a class="validate-char-button" href="javascript:void(0);">'+'Verify'+'</a>';
										$('<li></li>').html(text).attr('data-id', data.id).attr('data-url', data.name+"@"+realm ).addClass('character').addClass(data.name+"-"+realm).appendTo('#chars-list');
										
										/** SET UP FOR VALIDATION **/
										$('.validate-char-button').attr('data-id', data.id).click(function() {
							
											//okay we need to get the char id from the button
											url = "//fireexitguild.com/wp-admin/admin-ajax.php"
											id = $(this).attr('data-id');
											$.getJSON(url, {action: 'validate_character', charid: id}, function(data) {
												
												if (data.status=="ok") {
													//yay	
													alert(data.response);
												} else {
													//the problems are listed in the response array
													problemsArray = data.response;
													if (problemsArray.length<1) {alert("Something went wrong.");}
													else {
														modal = $("#modal-wrapper .modal-box");
														$('.modal-header h1', modal).html("Uh oh: There was a problem!");
														content = $('.modal-content', modal);
														
														ul = $("<ul></ul>");
														for (x in problemsArray) {
															li = $("<li></li>").html(problemsArray[x]).css('color', 'white');	
															li.appendTo(ul);
														}
														
														content.html("<p>Sorry! We found some problems with the data we read. This could be because you haven't logged out yet. Please correct the following issues: </p><div class=\"list-wrapper\"><ul>"+ul.html()+"</ul></div>");
														$('#modal-wrapper').fadeIn(1200);
														$('#modal-wrapper .modal-header a').click(function() {
															$('#modal-wrapper').fadeOut(800);
														});
														window.setTimeout(function() {$('#modal-wrapper').fadeOut(800);}, 8000);
														
													}
												}
												
											});
											
										});
										
										/** END SET UP **/
										$('.further-instructions').fadeIn(1300);
										$('.bracers', '.further-instructions').html(data.bracers);
										$('.helm', '.further-instructions').html(data.helm);
										$('.gloves', '.further-instructions').html(data.gloves);
										$('.char-name-lower').html(data.name);
										
									} else {
										alert(data.response);	
									}
								},
								statusCode: {
									404: function(XHR,status,error) {
										alert("We couldn't find that character");	
									}
								},
								complete: function() {
									//change submit button back	
									$('.loader', '.forum-submit-with-load').hide();
									$('.button', '.forum-submit-with-load').show();
								},
								beforeSend: function() {
									//change submit button	
									newclass = char+"-"+realm;
									if ($('.'+newclass).length > 0) {
										alert('You have added that character already.');
										return false;
									} else {
										$('.button', '.forum-submit-with-load').hide();
										$('.loader', '.forum-submit-with-load').show();
									}
								},
								error: function(XHR,textStatus,errorThrown) {
									alert("Couldn't find that character");
									
								}
							});
						});
						
						}); //end of that thing;
						</script>
                        
                        <?php endif; ?>
                    
                    </div> <!--.profile-load-->
                    
                    <div class="further-instructions" style="display:none;">
                    	
                    	<h3 style="text-transform: uppercase; color: #FEF092; font-size: 17px; border-bottom: 2px solid rgb(85, 43, 18);" >Validate Your Character</h3>    
                    
                        <p>Thank you for adding your character, but you aren't done yet! In order for us to know that <span class="charname">name</span> is your character, you need to follow the following instructions.</p>
                        
                        <ul>
                            <li>Unequip your <span class="bracers">bracers</span>.</li>
                            <li>Unequip your <span class="helm">helm</span>.</li>
                            <li>Uneqip your <span class="gloves">gloves</span>.</li>
                        </ul>
                        
                        <p>After you follow the instructions listed, please logout. Then, push the button next to the character you have followed the instructions for, or push the one below</p>
                        <p><a href="javascript:void(0);" class="validate-char-button button">Validate <span class="char-name-lower">your Character</span></a></p>
                    </div>
                    
                    <div id="character-wrapper"></div>
                    
			</div> <!-- .entry-content -->
            </div>
			
            <?php comments_template( '', true ); ?>
            
            </div><!-- #content -->
            
		</div><!-- #primary -->
        <?php endwhile; ?>
</div><!---#content-container-->
<?php get_footer(); ?>