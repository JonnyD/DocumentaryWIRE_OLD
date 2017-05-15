		<div id="contentright">

			<div id="sidebar" class="clearfix">

			<ul>
			<center>
		<script type="text/javascript"><!--
google_ad_client = "ca-pub-4216849047659161";
/* sidebar small */
google_ad_slot = "9628804200";
google_ad_width = 300;
google_ad_height = 250;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</center>

</ul>
<br />
			
				<ul>
					<li id="side-tabs">
						<?php include (TEMPLATEPATH . "/side-tabs.php"); ?>
					</li>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Right Sidebar - Top') ) : ?>
<?php endif; ?>	

				</ul>			

			</div>

			<div id="sidebar-bottom-left">

				<ul>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Right Sidebar - Bottom Left') ) : ?>
<?php endif; ?>	

				</ul>

			</div>

			<div id="sidebar-bottom-right">

				<ul>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Right Sidebar - Bottom Right') ) : ?>
<?php endif; ?>	

				</ul>

			</div>


<div id="sidebar-bottom">

<li id="recent-posts-3" class="widget widget_recent_entries">		<h3 class="widgettitle2">Recently Added Documentaries</h3>
<?php
// GET POSTS
		$r = new WP_Query(array('showposts' => 7, 'what_to_show' => 'posts', 'nopaging' => 0, 'post_status' => 'publish', 'caller_get_posts' => 1));
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
			<?php echo $before_title . $title . $after_title; ?>
			<ul>
			<?php  while ($r->have_posts()) : $r->the_post(); ?>
			<li><a href="<?php the_permalink() ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?> </a></li>
			<?php endwhile; ?>
			</ul>	

<?php
		// echo widget closing tag
		echo $after_widget;

		endif;
?>
</li>

<li id="recent-posts-3" class="widget widget_recent_entries">		<h3 class="widgettitle2">Random Documentaries</h3>
<?php
      $random_query = new WP_Query(array(
      'post__not_in' => $do_not_duplicate,
      'showposts' => 4,
      'orderby' => 'rand'
      ));
      while ($random_query->have_posts()) : $random_query->the_post(); ?>
<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><img src="<?php $values = get_post_custom_values("thumbnail"); echo $values[0]; ?>" alt="" width="84" height="100" /></a>
    <?php endwhile; ?>
</li>


</div>

		</div>