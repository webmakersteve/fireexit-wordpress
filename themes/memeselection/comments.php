<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to twentyeleven_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>
	<div id="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'twentyeleven' ); ?></p>
	</div><!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>
    <?php if (!ext_smf_logged_in( )): $_SESSION['login_url']=get_permalink(); ?><div class="button-wrapper"><a class="button" href="<?=$GLOBALS['SMF_CONTEXT']['menu_buttons']['login']['href']?>">ADD A REPLY</a></div><?php else: ?>
	<?php meme_comment_form();//comment_form(); ?>
	<?php endif; ?>
	<?php if ( have_comments() ) : ?>
    	<div id="the-comment-wrapper">
		<ul class="commentlist">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use twentyeleven_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define twentyeleven_comment() and that will be used instead.
				 * See twentyeleven_comment() in twentyeleven/functions.php for more.
				 */
				wp_list_comments( array( 'callback' => 'meme_comment', 'reverse_top_level' => true ) );
			?>
		</ul>
        </div>
        <script type="text/javascript">
		
		var addComment = {
			moveForm : function(id, commentid, hashtag, othernumber) {

				liOb = $('#li-'+id);
				$('.js-reply').remove();
				//first check if there is already a reply section
				if ($('ul.children', "#li-"+id).length>0) {
					//this means there is already a children section so we need to prepend one
				} else {
					//this means we have to add one and then append to it hte li
					$('<ul></ul>').addClass("children").appendTo("#li-"+id);	
				}
				li = $("<li></li>").addClass('comment').addClass("js-reply").addClass('odd').addClass('alt').addClass('depth-2'); //add classes
				charname = $('#charname');
				charclass = charname.attr('class');
				charname = charname.html();
				thumbnail = $('#login-button .vcard').attr('src');
				
					wrapper = $('<div></div>').addClass('comment');
					wrapper.html('<form action="http://www.fireexitguild.com/wp-comments-post.php" method="post" id="commentform"><div class="comment-meta c-right"><div class="comment-author vcard"><img width="60" height="60" alt="" src="'+thumbnail+'"></div></div><div class="comment-content"><div class="comment-info"><strong class="'+charclass+'">'+charname+'</strong><input name="author" type="hidden" value="'+charname+'"></div><div class="textarea-wrapper"><textarea id="comment" name="comment" cols="30" rows="8" aria-required="true"></textarea></div><div class="reply"><input type="submit" value="Post" class="button"> | <a href="javascript: void(0);" class="cancel-comment">Cancel</a></div></div><input type="hidden" name="comment_post_ID" value="'+othernumber+'" id="comment_post_ID"><input type="hidden" name="comment_parent" id="comment_parent" value="'+commentid+'"></form>');
					li.append(wrapper).prependTo($('#li-'+id+' ul.children'));
			}
		};
		
		/*if(window.location.hash) {
			  var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
			  alert (hash);
			  
			  // hash found
		  } else {
			  // No hash found
		  }
		*/
		$(function() {
			href = $('.comment-reply-link').attr("href");
			$('.comment-reply-link').attr('href', 'javascript:void(0);');
		});
		</script>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'twentyeleven' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentyeleven' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyeleven' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we only want the note on posts and pages that had comments in the first place.
		 */
		if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="nocomments"><?php _e( 'Comments are closed.' , 'twentyeleven' ); ?></p>
		<?php endif; ?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'twentyeleven' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentyeleven' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyeleven' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

</div><!-- #comments -->
