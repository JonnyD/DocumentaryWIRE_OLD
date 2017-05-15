<?php

namespace DW\DWBundle\Manager;

use Doctrine\ORM\EntityManager;
use DW\DWBundle\Entity\Category;
use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\Tag;
use DW\DWBundle\Event\DocumentaryEvent;
use DW\DWBundle\Event\DocumentaryEvents;
use DW\DWBundle\Repository\DocumentaryRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DocumentaryManager
{
	private $documentaryRepository;
    private $eventDispatcher;

	public function __construct(DocumentaryRepository $documentaryRepository,
                                EventDispatcherInterface $eventDispatcher)
	{
        $this->documentaryRepository = $documentaryRepository;
        $this->eventDispatcher = $eventDispatcher;
	}

    public function createDocumentary($title, $slug, $description, $excerpt, $status)
    {
        $documentary = new Documentary();
        $documentary->setTitle($title);
        $documentary->setSlug($slug);
        $documentary->setDescription($description);
        $documentary->setExcerpt($excerpt);
        $documentary->setStatus($status);
        return $documentary;
    }

    public function addDocumentary(Documentary $documentary)
    {
        $this->documentaryRepository->save($documentary);
        $this->eventDispatcher->dispatch(
            DocumentaryEvents::NEW_DOCUMENTARY_ADDED,
            new DocumentaryEvent($documentary)
        );
    }

    public function getDocumentaryById($id)
    {
        return $this->documentaryRepository->findDocumentaryById($id);
    }

    public function getDocumentaryBySlug($slug)
    {
        return $this->documentaryRepository->findDocumentaryBySlug($slug);
    }

    public function getDocumentaryByTruncatedSlug($slug)
    {
        return $this->documentaryRepository->findDocumentaryByTruncatedSlug($slug);
    }

    public function getAllDocumentaries()
    {
        return $this->documentaryRepository->findAll();
    }

    public function getOrderBy($orderBy)
    {
        $orderByTypes = array(
            'date' => 'date',
            'title' => 'title',
            'views' => 'views',
            'likes' => 'likes',
            'comments' => 'comments'
        );
        //'random'

        if (array_key_exists($orderBy, $orderByTypes))
        {
            $orderBy = $orderByTypes[$orderBy];
        }
        else
        {
            $orderBy = 'date';
        }

        return $orderBy;
    }

    public function getOrder($orderRequested)
    {
        $order = 'desc';
        if (strtolower($orderRequested) == 'asc' ) {
            $order = 'asc';
        }
        return $order;
    }

    public function getDocumentaries($status, $orderBy, $order, $limit)
    {
        return $this->documentaryRepository->findDocumentaries($status, $orderBy, $order, $limit);
    }

    public function getPublishedDocumentaryKeys($orderBy, $order)
    {
        $orderBy = $this->getOrderBy($orderBy);
        $order = $this->getOrder($order);

        $method = "findPublishedDocumentaryKeysOrderedBy".ucfirst($orderBy).ucfirst($order);
        $documentaryKeys = $this->documentaryRepository->$method();

        return $documentaryKeys;
    }

    public function getPublishedDocumentaryKeysByCategory($categoryId, $orderBy, $order)
    {
        $orderBy = $this->getOrderBy($orderBy);
        $order = $this->getOrder($order);

        $method = "findPublishedDocumentaryKeysByCategoryOrderedBy".ucfirst($orderBy).ucfirst($order);
        $documentaryKeys = $this->documentaryRepository->$method($categoryId);

        return $documentaryKeys;
    }

    public function getPublishedDocumentaryKeysByYear($year, $orderBy, $order)
    {
        $orderBy = $this->getOrderBy($orderBy);
        $order = $this->getOrder($order);

        $method = "findPublishedDocumentaryKeysByYearOrderedBy".ucfirst($orderBy).ucfirst($order);
        $documentaryKeys = $this->documentaryRepository->$method($year);

        return $documentaryKeys;
    }

    public function getFeaturedDocumentaries()
    {
        return $this->documentaryRepository->findFeaturedDocumentaries();
    }

    public function getYears()
    {
        return $this->documentaryRepository->findYears();
    }

    public function getDurations()
    {
        $short = $this->getAmountOfDocumentariesByDuration(0, 20);
        $short['description'] = "0-20min";
        $short['title'] = "Short";
        $short['slug'] = "short";

        $mid = $this->getAmountOfDocumentariesByDuration(21, 40);
        $mid['description'] = "21-40min";
        $mid['title'] = "Mid";
        $mid['slug'] = "mid";

        $long = $this->getAmountOfDocumentariesByDuration(41, 999);
        $long['description'] = "41+min";
        $long['title'] = "Long";
        $long['slug'] = "long";

        $unknown = $this->getAmountOfDocumentariesWithNoDuration();
        $unknown['description'] = "";
        $unknown['title'] = "Unknown";
        $unknown['slug'] = "unknown";

        $durations = array();
        $durations['short'] = $short;
        $durations['mid'] = $mid;
        $durations['long'] = $long;
        $durations['unknown'] = $unknown;

        return $durations;

    }

    public function getPublishedDocumentaryKeysByDuration($length, $orderBy, $order)
    {
        $orderBy = $this->getOrderBy($orderBy);
        $order = $this->getOrder($order);

        $length = strtolower($length);
        if ($length == "short") {
            $from = 0;
            $to = 20;
        } else if ($length == "mid") {
            $from = 21;
            $to = 40;
        } else if ($length == "long") {
            $from = 41;
            $to = 999;
        } else {
            $from = "unknown";
            $to = "unknown";
        }

        $method = "findPublishedDocumentaryKeysByDurationOrderedBy".ucfirst($orderBy).ucfirst($order);
        $documentaryKeys = $this->documentaryRepository->$method($from, $to);

        return $documentaryKeys;
    }

    public function getAmountOfDocumentariesByDuration($from, $to)
    {
        return $this->documentaryRepository->findAmountOfDocumentariesByDuration($from, $to);
    }

    public function getAmountOfDocumentariesWithNoDuration()
    {
        return $this->documentaryRepository->findAmountOfDocumentariesWithNoDuration();
    }

    public function getDocumentariesByTag(Tag $tag)
    {
        return $this->documentaryRepository->findDocumentariesByTag($tag);
    }

    public function getDocumentariesByCategory(Category $category, $status, $orderBy, $order, $max)
    {
        $order = $this->getOrder($order);
        $orderBy = $this->getOrderBy($orderBy);

        return $this->documentaryRepository->findDocumentariesByCategory($category->getId(), $status, $orderBy, $order, $max);
    }

    public function getRelatedDocumentaries(Documentary $documentary)
    {
        return $this->documentaryRepository->findRelatedDocumentaries($documentary);
    }

    function getEmbedCode($video_src, $video_url, $video_width, $video_height, $autoplay)
    {
        $embeds = array (
            "56" => '<embed src="http://player.56.com/' . $video_url . '.swf" type="application/x-shockwave-flash" width="' . $video_width . '" height="' . $video_height . '" allowNetworking="all" allowScriptAccess="always"></embed>',
            "blip" => '<embed src="http://blip.tv/play/' . $video_url . '" type="application/x-shockwave-flash" width="' . $video_width . '" height="' . $video_height . '" allowscriptaccess="always" allowfullscreen="true"></embed>',
            "dailymotion" => '<iframe frameborder="0" width="' . $video_width . '" height="' . $video_height . '" src="http://www.dailymotion.com/embed/video/' . $video_url . '?width=' . $video_width . '"></iframe>',
            "disclose" => '<object id="dtvplayer" width="' . $video_width . '" height="' . $video_height . '"> 	<param name="movie" value="http://www.disclose.tv/swf/player.swf" />  	<param name="wmode" value="transparent" /> 	<param name="allowFullScreen" value="true" />   <param name="allowscriptaccess" value="always" />    	<param name="flashvars"  		value="config=http://www.disclose.tv/videos/config/flv/' . $video_url . '.js" />  	<embed type="application/x-shockwave-flash" width="' . $video_width . '" height="' . $video_height . '" allowFullScreen="true"  	src="http://www.disclose.tv/swf/player.swf" 	flashvars="config=http://www.disclose.tv/videos/config/flv/' . $video_url . '.js"/></embed></object>',
            "embed" => $video_url,
            "forum-network" => '<object name="kaltura_player_1304432839" id="kaltura_player_1304432839" type="application/x-shockwave-flash" allowScriptAccess="always" allowNetworking="all" allowFullScreen="true" height="'.$video_height.'" width="'.$video_width.'" data="http://www.kaltura.com/index.php/kwidget/wid/1_rdxpkc3j/uiconf_id/'.$video_url.'"><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="allowFullScreen" value="true" /><param name="bgcolor" value="#000000" /><param name="movie" value="http://www.kaltura.com/index.php/kwidget/wid/1_rdxpkc3j/uiconf_id/'.$video_url.'"/><param name="flashVars" value=""/></object>',
            "google" => '<embed style="width:' . $video_width . 'px; height:' . $video_height . 'px; id="VideoPlayback" type="application/x-shockwave-flash" src="http://video.google.com/googleplayer.swf?docId=' . $video_url . '" allowFullScreen="true" flashvars="fs=true"> </embed>',
            "krishnatube" => '<embed src="http://krishnatube.com/nvplayer.swf?config=http://krishnatube.com/nuevo/econfig.php?key=' . $video_url . '" width="' . $video_width . '" height="' . $video_height . '" wmode="transparent" allowscriptaccess="always" allowfullscreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" />',
            "megavideo" => '<object width="' . $video_width . '" height="' . $video_height . '"><param name="movie" value="' . $video_url . '"></param><param name="allowFullScreen" value="true"></param><embed src="' . $video_url . '" type="application/x-shockwave-flash" allowfullscreen="true" width="' . $video_width . '" height="' . $video_height . '"></embed></object>',
            "myspace" => '<object width="' . $video_width . 'px" height="' . $video_height . 'px" ><param name="allowScriptAccess" value="always"/><param name="allowFullScreen" value="true"/><param name="wmode" value="transparent"/><param name="movie" value="http://mediaservices.myspace.com/services/media/embed.aspx/m=' . $video_url . ',t=1,mt=video"/><embed src="http://mediaservices.myspace.com/services/media/embed.aspx/m=' . $video_url . ',t=1,mt=video" width="' . $video_width . '" height="' . $video_height . '" allowFullScreen="true" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always"></embed></object>',
            "novamov" => '<iframe style="overflow: hidden; border: 0; width: ' . $video_width . 'px; height: ' . $video_height . 'px" src="http://embed.novamov.com/embed.php?width=' . $video_width . '&height=' . $video_height . '&v=' . $video_url . '&px=1" scrolling="no"></iframe>',
            "pbs" => '<object width = "' . $video_width . '" height = "' . $video_height . '" > <param name = "movie" value = "http://www-tc.pbs.org/video/media/swf/PBSPlayer.swf" > </param><param name="flashvars" value="video=' . $video_url . '&player=viral" /> <param name="allowFullScreen" value="true"></param > <param name = "allowscriptaccess" value = "always" > </param><param name="wmode" value="transparent"></param ><embed src="http://www-tc.pbs.org/video/media/swf/PBSPlayer.swf" flashvars="video=' . $video_url . '&player=viral" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" allowfullscreen="true" width="' . $video_width . '" height="' . $video_height . '" bgcolor="#000000"></embed></object>',
            "rutube" => '<OBJECT width="' . $video_width . '" height="' . $video_height . '"><PARAM name="movie" value="http://video.rutube.ru/' . $video_url . '"></PARAM><PARAM name="wmode" value="window"></PARAM><PARAM name="allowFullScreen" value="true"></PARAM><EMBED src="http://video.rutube.ru/' . $video_url . '" type="application/x-shockwave-flash" wmode="window" width="' . $video_width . '" height="' . $video_height . '" allowFullScreen="true" ></EMBED></OBJECT>',
            "sevenload" => '<script type="text/javascript" src="http://de.sevenload.com/pl/' . $video_url . '/' . $video_width . 'x' . $video_height . '"></script>',
            "snagfilms" => '<object width="'.$video_width.'" height="'.$video_height.'" data="http://o.snagfilms.com/film.swf" type="application/x-shockwave-flash" id="f-183"><param name="allowNetworking" value="all" /><param name="allowScriptAccess" value="always" /><param name="movie" value="http://o.snagfilms.com/film.swf" /><param name="wmode" value="transparent" /><param name="flashvars" value="id=183&cid=f-183-off_the_grid" /></object>',
            "stagevu" => '<iframe style="overflow: hidden; border: 0; width: ' . $video_width . 'px; height: ' . $video_height . 'px" src="http://stagevu.com/embed?width=' . $video_width . '&amp;height=' . $video_height . '&amp;background=000&amp;uid=' . $video_url . '" scrolling="yes"></iframe>',
            "tudou" => '<embed src="http://www.tudou.com/v/'.$video_url.'/v.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="'.$video_width.'" height="'.$video_height.'"></embed>',
            "veoh" => '<object width="' . $video_width . '" height="' . $video_height . '" id="veohFlashPlayer" name="veohFlashPlayer"><param name="movie" value="http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.5.4.1015&permalinkId=' . $video_url . '&player=videodetailsembedded&videoAutoPlay=0&id=anonymous"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.veoh.com/static/swf/webplayer/WebPlayer.swf?version=AFrontend.5.5.4.1015&permalinkId=' . $video_url . '&player=videodetailsembedded&videoAutoPlay=0&id=anonymous" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $video_width . '" height="' . $video_height . '" id="veohFlashPlayerEmbed" name="veohFlashPlayerEmbed"></embed></object>',
            "viddler" => '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$video_width.'" height="'.$video_height.'" id="viddler_7b5ea40d"><param name="movie" value="http://www.viddler.com/simple/'.$video_url.'/" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><embed src="http://www.viddler.com/simple/'.$video_url.'/" width="'.$video_width.'" height="'.$video_height.'" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="true" name="viddler_7b5ea40d"></embed></object>',
            "vimeo" => '<iframe src="http://player.vimeo.com/video/'.$video_url.'" width="'.$video_width.'" height="'.$video_height.'" frameborder="0"></iframe>',
            "youtube playlist" => '<object width="'.$video_width.'" height="'.$video_height.'"><param name="movie" value="http://www.youtube.com/p/'.$video_url.'?hl=en_GB&fs=1&hd=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/p/'.$video_url.'?hl=en_GB&fs=1&hd=1" type="application/x-shockwave-flash" width="'.$video_width.'" height="'.$video_height.'" allowscriptaccess="always" allowfullscreen="true"></embed></object>',
            "youtube" => '<iframe width="'.$video_width.'" height="'.$video_height.'" src="http://www.youtube.com/embed/'.$video_url.'?autoplay=1&cc_load_policy=0&modestbranding=1&iv_load_policy=3" frameborder="0" allowfullscreen></iframe>',
            "zshare" => '<iframe src="http://www.zshare.net/videoplayer/player.php?SID=dl073&FID='.$video_url.'&FN=ceprov.flv&iframewidth='.$video_width.'&iframeheight=200&width='.$video_width.'&height=250&H=70886430149e13aa" height="500" width="'.$video_width.'"  border=0 frameborder=0 scrolling=no></iframe>',
        );
        return $embeds[$video_src];
    }

    public function getLastUpdatedDocumentary()
    {
        return $this->documentaryRepository->findLastUpdatedDocumentary();
    }

    public function getLastUpdatedDocumentaryInCategory(Category $category)
    {
        return $this->documentaryRepository->findLastUpdatedDocumentaryInCategory($category);
    }
}