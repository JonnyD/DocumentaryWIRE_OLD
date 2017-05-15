<?php

namespace DW\DWBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SandboxController extends Controller
{

    public function sandboxAction()
    {
        $categoryManager = $this->get('documentary_wire.category_manager');
        $category = $categoryManager->getCategoryByName("Nature");

        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getDocumentariesByCategory($category, "publish", "title", "desc", 19);

        return $this->render(
            'DocumentaryWIREBundle:Sandbox:sandbox.html.twig',
            array('documentaries' => $documentaries)
        );
    }
}