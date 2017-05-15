<?php

namespace DW\DWBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $featuredDocumentaries = $documentaryManager->getFeaturedDocumentaries();

        return $this->render('DocumentaryWIREBundle:Home:index.html.twig', array(
            'featuredDocumentaries' => $featuredDocumentaries
        ));
    }
}
