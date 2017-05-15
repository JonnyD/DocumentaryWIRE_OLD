<?php

namespace DW\DWBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DW\DWBundle\Entity\DocumentaryFilter;
use DW\DWBundle\Entity\DocumentaryStatus;
use DW\DWBundle\Entity\Order;
use Symfony\Component\HttpFoundation\Response;

class FeedController extends Controller
{
    public function siteFeedAction()
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getDocumentaries(DocumentaryStatus::PUBLISH,
            "created", Order::DESC, 5);

        $feed = $this->get('eko_feed.feed.manager')->get('documentary');
        $feed->addFromArray($documentaries);

        $response = new Response($feed->render('rss')); // or 'atom'
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }

    public function categoryFeedAction($slug)
    {
        $categoryManager = $this->get("documentary_wire.category_manager");
        $category = $categoryManager->getCategoryBySlug($slug);

        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        //$documentaries = $documentaryManager->getDocumentariesByCategory($category, DocumentaryStatus::PUBLISH,
        //    DocumentaryFilter::DATE, Order::DESC, 5);

        $feed = $this->get('eko_feed.feed.manager')->get('documentary');
        //$feed->addFromArray($documentaries);

        $response = new Response($feed->render('rss')); // or 'atom'
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }
}