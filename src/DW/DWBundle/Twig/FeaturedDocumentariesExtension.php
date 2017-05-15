<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Manager\DocumentaryManager;

class FeaturedDocumentariesExtension extends \Twig_Extension
{
    private $documentaryManager;

    public function __construct(DocumentaryManager $documentaryManager) {
        $this->documentaryManager = $documentaryManager;
    }

    public function getFunctions()
    {
        return array(
            'featuredDocumentaries' => new \Twig_Function_Method($this, 'featuredDocumentaries')
        );
    }

    public function featuredDocumentaries()
    {
        $documentaries = $this->documentaryManager->getFeaturedDocumentaries();
        return $documentaries;
    }

    public function getName()
    {
        return 'featuredDocumentariesExtension';
    }
}