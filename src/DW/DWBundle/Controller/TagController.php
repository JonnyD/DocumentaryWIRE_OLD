<?php

namespace DW\DWBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TagController extends Controller
{
    public function listTagsWidgetAction()
    {
        $tagManager = $this->get('documentary_wire.tag_manager');
        $tags = $tagManager->getAllTags();

        return $this->render('DocumentaryWIREBundle:Tag:listTagsWidget.html.twig', array(
            'tags' => $tags
        ));
    }

    public function showTagAction($slug, Request $request)
    {
        $tagManager = $this->get('documentary_wire.tag_manager');
        $tag = $tagManager->getTagBySlug($slug);

        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getDocumentariesByTag($tag);

        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $documentaries = $paginateManager->paginate($documentaries, 15, $request);

        return $this->render('DocumentaryWIREBundle:Tag:show.html.twig', array(
            'documentaries' => $documentaries,
            'tag' => $tag
        ));
    }
}