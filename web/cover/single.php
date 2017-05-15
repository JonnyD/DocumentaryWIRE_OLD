<?php get_header(); ?>


		<div id="contentleft">

			<div id="content">



<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<div class="singlepost maincontent">

						<div class="post clearfix" id="post-<?php the_ID(); ?>">
							<h1 class="banner468"><?php the_title(); ?></h1>


<div class="postinfo3">
<div style="float:left;">
<img src="http://www.documentarywire.com/images/folder.png" /> <?php the_category(', ') ?>
</div>

<div style="float:right;margin-right:15px;">
<img src="http://www.documentarywire.com/images/comments.png" /> <a href="<?php the_permalink() ?>#comment" rel="nofollow">                                        <?php comments_number('0', '1', '%');                    ?>                   </a> <img src="http://www.documentarywire.com/images/star.png" /> <?php $blog_rating = wp_gdsr_rating_article($post->ID); ?><?php $blog_rating = wp_gdsr_rating_article($post->ID); ?><?php if($blog_rating->rating == '') { $rating = 'n/y';} else {$rating = $blog_rating->rating;} echo $rating; ?>
</div>

</div>
<div style="clear:both"></div>
							<div class="entry clearfix">
								<?php get_the_image(array(
	'custom_key' => array('post_thumbnail','thumbnail'),
	'default_size' => 'thumbnail',
	'default_image' => false,
	'link_to_post' => false,
	'image_class' => "singlethumbnail",
)); ?>

<div align="justify"><?php the_content(''); ?></div>
							</div>
							


 <div class="vid">

<div style="float: left; width:40%;">
<div id="command">
                <a class="lightSwitcher" href="#" onClick="return false">Turn off the lights</a>
            </div>
</div>

                    
                    <div class="splash">
            

<div style="clear:both"></div>
<div id="shadow"></div>
                    <?php
                        $video_src = get_post_meta($post->ID, 'video_source', true);
                        $video_url = get_post_meta($post->ID, 'video_url', true);

                        $width = 550;
                        $height = 350;

                        if ($video_src && $video_url) {
						switch($video_src){
									
						
                            case "youtube":
echo '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$video_url.'?fs=1&rel=0&hd=1" frameborder="0" allowfullscreen></iframe>';
break;

case "youtube playlist":
echo '<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube.com/p/'.$video_url.'?hl=en_GB&fs=1&hd=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/p/'.$video_url.'?hl=en_GB&fs=1&hd=1" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
break;

                            case "google":
                                echo '<embed style="width:' . $width . 'px; height:' . $height . 'px; id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=' . $video_url . '" allowFullScreen="true" flashvars="fs=true"> </embed>';
								break;
								
                            case "megavideo":
                                echo '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="' . $video_url . '"></param><param name="allowFullScreen" value="true"></param><embed src="' . $video_url . '" type="application/x-shockwave-flash" allowfullscreen="true" width="' . $width . '" height="' . $height . '"></embed></object>';
								break;
								
                           case "blip":
                                echo '<embed src="http://blip.tv/play/' . $video_url . '" type="application/x-shockwave-flash" width="' . $width . '" height="' . $height . '" allowscriptaccess="always" allowfullscreen="true"></embed>';
								break;
								
                            case "myspace":
                                echo '<object width="' . $width . 'px" height="' . $height . 'px" ><param name="allowScriptAccess" value="always"/><param name="allowFullScreen" value="true"/><param name="wmode" value="transparent"/><param name="movie" value="http://mediaservices.myspace.com/services/media/embed.aspx/m=' . $video_url . ',t=1,mt=video"/><embed src="http://mediaservices.myspace.com/services/media/embed.aspx/m=' . $video_url . ',t=1,mt=video" width="' . $width . '" height="' . $height . '" allowFullScreen="true" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always"></embed></object>';
								break;
								
                            case "embed":
                                echo $video_url;
								break;
								
							case "veoh":
                                echo '<object width="' . $width . '" height="' . $height . '" id="veohFlashPlayer" name="veohFlashPlayer"><param name="movie" value="http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.5.4.1015&permalinkId=' . $video_url . '&player=videodetailsembedded&videoAutoPlay=0&id=anonymous"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.5.4.1015&permalinkId=' . $video_url . '&player=videodetailsembedded&videoAutoPlay=0&id=anonymous" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $width . '" height="' . $height . '" id="veohFlashPlayerEmbed" name="veohFlashPlayerEmbed"></embed></object>';
								break;
								
                            case "disclose":
                                echo '<object id="dtvplayer" width="' . $width . '" height="' . $height . '"> 	<param name="movie" value="http://www.disclose.tv/swf/player.swf" />  	<param name="wmode" value="transparent" /> 	<param name="allowFullScreen" value="true" />   <param name="allowscriptaccess" value="always" />    	<param name="flashvars"  		value="config=http://www.disclose.tv/videos/config/flv/' . $video_url . '.js" />  	<embed type="application/x-shockwave-flash" width="' . $width . '" height="' . $height . '" allowFullScreen="true"  	src="http://www.disclose.tv/swf/player.swf" 	flashvars="config=http://www.disclose.tv/videos/config/flv/' . $video_url . '.js"/></embed></object>';
								break;
								
                            case "56":
                                echo '<embed src="http://player.56.com/' . $video_url . '.swf" type="application/x-shockwave-flash" width="' . $width . '" height="' . $height . '" allowNetworking="all" allowScriptAccess="always"></embed>';
								break;
								
                            case "novamov":
                                echo '<iframe style="overflow: hidden; border: 0; width: ' . $width . 'px; height: ' . $height . 'px" src="http://embed.novamov.com/embed.php?width=' . $width . '&height=' . $height . '&v=' . $video_url . '&px=1" scrolling="no"></iframe>';
								break;
								
                            case "rutube":
                                echo '<OBJECT width="' . $width . '" height="' . $height . '"><PARAM name="movie" value="http://video.rutube.ru/' . $video_url . '"></PARAM><PARAM name="wmode" value="window"></PARAM><PARAM name="allowFullScreen" value="true"></PARAM><EMBED src="http://video.rutube.ru/' . $video_url . '" type="application/x-shockwave-flash" wmode="window" width="' . $width . '" height="' . $height . '" allowFullScreen="true" ></EMBED></OBJECT>';
								break;
								
                            case "sevenload":
                                echo '<script type="text/javascript" src="http://de.sevenload.com/pl/' . $video_url . '/' . $width . 'x' . $height . '"></script>';
								break;
								
                            case "stagevu":
                                echo '<iframe style="overflow: hidden; border: 0; width: ' . $width . 'px; height: ' . $height . 'px" src="http://stagevu.com/embed?width=' . $width . '&amp;height=' . $height . '&amp;background=000&amp;uid=' . $video_url . '" scrolling="yes"></iframe>';
								break;
								
                            case "krishnatube":
                                echo '<embed src="http://krishnatube.com/nvplayer.swf?config=http://krishnatube.com/nuevo/econfig.php?key=' . $video_url . '" width="' . $width . '" height="' . $height . '" wmode="transparent" allowscriptaccess="always" allowfullscreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" />';
								break;
								
                            case "vimeo":
                                echo '<iframe src="http://player.vimeo.com/video/'.$video_url.'" width="'.$width.'" height="'.$height.'" frameborder="0"></iframe>';
								break;
								
                            case "zshare":
                                $frameHeight = $height - 50;
                                echo '<iframe src="http://www.zshare.net/videoplayer/player.php?SID=dl073&FID='.$video_url.'&FN=ceprov.flv&iframewidth='.$width.'&iframeheight=200&width='.$width.'&height=250&H=70886430149e13aa" height="500" width="'.$width.'"  border=0 frameborder=0 scrolling=no></iframe>';
								break;
								
                            case "tudou":
				echo '<embed src="http://www.tudou.com/v/'.$video_url.'/v.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="'.$width.'" height="'.$height.'"></embed>';
				break;
				
				case "forum-network":
	echo '<object name="kaltura_player_1304432839" id="kaltura_player_1304432839" type="application/x-shockwave-flash" allowScriptAccess="always" allowNetworking="all" allowFullScreen="true" height="'.$height.'" width="'.$width.'" data="http://www.kaltura.com/index.php/kwidget/wid/1_rdxpkc3j/uiconf_id/'.$video_url.'"><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="allowFullScreen" value="true" /><param name="bgcolor" value="#000000" /><param name="movie" value="http://www.kaltura.com/index.php/kwidget/wid/1_rdxpkc3j/uiconf_id/'.$video_url.'"/><param name="flashVars" value=""/><a href="http://corp.kaltura.com">video platform</a><a href="http://corp.kaltura.com/video_platform/video_management">video management</a><a href="http://corp.kaltura.com/solutions/video_solution">video solutions</a><a href="http://corp.kaltura.com/video_platform/video_publishing">video player</a></object>';
	break;
case "viddler":
echo '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$width.'" height="'.$height.'" id="viddler_7b5ea40d"><param name="movie" value="http://www.viddler.com/simple/'.$video_url.'/" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><embed src="http://www.viddler.com/simple/'.$video_url.'/" width="'.$width.'" height="'.$height.'" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="true" name="viddler_7b5ea40d"></embed></object>';
break;
case "snagfilms":
echo '<object width="'.$width.'" height="'.$height.'" data="http://o.snagfilms.com/film.swf" type="application/x-shockwave-flash" id="f-183"><param name="allowNetworking" value="all" /><param name="allowScriptAccess" value="always" /><param name="movie" value="http://o.snagfilms.com/film.swf" /><param name="wmode" value="transparent" /><param name="flashvars" value="id=183&cid=f-183-off_the_grid" /></object>';
break;
                        }
						}

                    ?>
                    </div>
                </div>
					


<div style="margin-bottom:20px">

<div style="float: left; font-size: 13px; width: 40%;border:1px solid #e9e9e9;padding:5px 2px 5px 5px;background:#efefef;height:60px;text-align:center;-moz-border-radius:5px;

-webkit-border-radius:5px;

-khtml-border-radius:5px;

border-radius:5px;margin:10px 0px 0px 0px;"> 

<b>Share this documentary via:</b> 

<div> 

<a href='http://www.facebook.com/sharer.php?u=<?php echo get_permalink(); ?>&t=<?php echo the_title(); ?>' rel='external nofollow' target='_blank' title="Share documentary via Facebook"><img alt='Facebook' src='http://www.documentarywire.com/images/facebook_32.png' style='width:40px; height:40px;'/></a> 

<a href='http://twitter.com/home?status=Check this Out: <?php echo get_permalink(); ?> | <?php echo the_title(); ?>' rel='external nofollow' target='_blank' title="Share documentary via Twitter"><img alt='Twitter' src='http://www.documentarywire.com/images/twitter_32.png' style='width:40px; height:40px;'/></a> 

<a href='http://digg.com/submit?url=<?php echo get_permalink(); ?>&title=<?php echo the_title(); ?>' rel='external nofollow' target='_blank' title="Share documentary via Digg"><img alt='Digg' src='http://www.documentarywire.com/images/digg_32x32.png' style='width:40px; height:40px;'/></a> 

<a href='http://www.stumbleupon.com/submit?url=<?php echo get_permalink(); ?>&title=<?php echo the_title(); ?>' rel='external nofollow' target='_blank' title="Share documentary via Stumbleupon"><img alt='Stumble' src='http://www.documentarywire.com/images/stumbleupon_32x32.png' style='width:40px; height:40px;'/></a> 

<a href='http://del.icio.us/post?url=<?php echo get_permalink(); ?>&title=<?php echo the_title(); ?>' rel='external nofollow' target='_blank' title="Share documentary via Delicious"><img alt='Delicious' src='http://www.documentarywire.com/images/delicious_32x32.png' style='width:40px; height:40px;'/></a> 



</div> 

</div> 



<div style="float: right; font-size: 13px;font-weight:bold; width: 45%;border: 1px solid #e9e9e9;padding:5px 2px 5px 5px;height:60px;line-height:1.2em; background:#efefef;text-align:center;-moz-border-radius:5px;

-webkit-border-radius:5px;

-khtml-border-radius:5px;

border-radius:5px;margin:10px 0px 0px 0px;"> 

Rate this documentary:

<?php wp_gdsr_render_article(); ?>

</div> 

</div>

<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="<?php echo get_permalink(); ?>" layout="button_count" show_faces="false" width="100" height="100" send="true"></fb:like>

						</div>


<div class="widgettitle2">Related Documentaries</div>
<?php get_related_posts_thumbnails(); ?>
						

						<?php comments_template('', true); ?>

<?php endwhile; endif; ?>

					</div>

			</div>


		</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>