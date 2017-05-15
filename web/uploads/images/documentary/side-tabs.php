<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/tabs.js"></script>

	<ul class="tabs clearfix">
  
		<li><a href="javascript:tabSwitch_2(1, 4, 'tab_', 'content_');" id="tab_1" class="on"><?php _e("Subscribe"); ?></a></li>
		<li><a href="javascript:tabSwitch_2(2, 4, 'tab_', 'content_');" id="tab_2"><?php _e("Comments"); ?></a></li>  
		
		<li><a href="javascript:tabSwitch_2(3, 4, 'tab_', 'content_');" id="tab_3"><?php _e("Popular"); ?></a></li>
<li><a href="javascript:tabSwitch_2(3, 4, 'tab_', 'content_');" id="tab_4"><?php _e("Top Rated"); ?></a></li>
	</ul>

	<div style="clear:both;"></div>

	<div id="content_1" class="cat_content">
		<ul>
			<li class="email">
				<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=DocumentaryWire', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
					<input type="hidden" value="DocumentaryWire" name="uri"/>
					<input type="hidden" name="loc" value="en_US"/>
					<p><img src="<?php bloginfo('stylesheet_directory'); ?>/images/email.gif" alt="email" style="float:left;margin: 4px 5px 0 0;" />Subscribe Via Email<br />
					<input onblur='if (this.value == &apos;&apos;) {this.value = &apos;Your email address...&apos;;}' onfocus='if (this.value == &apos;Your email address...&apos;) {this.value = &apos;&apos;;}' type="text" id="sub" name="email" value="Your email address..." /> <input id="subbutton" type="submit" value="<?php _e('submit'); ?>" /><br />
				</form>
			</li>
			<li class="feeds">
				<a href="http://feeds.feedburner.com/documentarywire"><?php _e("Subscribe via RSS Feed"); ?></a>
			</li>
			<li class="twitter">
				<a href="http://www.twitter.com/documentaries"><?php _e("Follow on Twitter"); ?></a>
			</li>
			<li class="facebook">
				<a href="http://www.facebook.com/freedocumentaries"><?php _e("Like on Facebook"); ?></a>
			</li>

		</ul> 
	</div>

	<div id="content_2" class="cat_content" style="display:none">
		<div class="sidebox">
			<div id="recentcomments" class="dsq-widget"><script type="text/javascript" src="http://documentarywire.disqus.com/recent_comments_widget.js?num_items=8&hide_avatars=0&avatar_size=32&excerpt_length=100"></script></div>
<a href="http://www.documentarywire.com/community">View All</a>
		</div> 
	</div>
                     

	<div id="content_3" class="cat_content" style="display:none">
		
<table>
<?php $r = new WP_Query(array('v_sortby' => 'views', 'v_order' => 'desc', 'showposts' => 10, 'what_to_show' => 'posts', 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1));
		if ($r->have_posts()) :
?>
<table>

			<?php  while ($r->have_posts()) : $r->the_post(); ?>
<tr>

<td><a href="<?php the_permalink(); ?>"><img src="<?php echo get_post_meta($post->ID, 'thumbnail', true); ?>"
alt="<?php the_title(); ?>" width="50" height="50"  /></a><td> 

			<td><a href="<?php the_permalink() ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?> </a></li></td>
</tr>

			<?php endwhile; ?>

</table>
<?php endif; ?>

	</div>

<div id="content_4" class="cat_content" style="display:none">
		<div class="sidebox">
			<?php
    wp_gdsr_render_rating_results(array('template_id' => 45, 'rows' => 10, 'min_votes' => 1,  'image_from' => 'custom', 'image_custom' => 'thumbnail'));
?>
		</div>
	</div>