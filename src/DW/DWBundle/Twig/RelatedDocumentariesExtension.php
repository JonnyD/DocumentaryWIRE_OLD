<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Manager\DocumentaryManager;

class RelatedDocumentariesExtension extends \Twig_Extension
{
    private $documentaryManager;

    public function __construct(DocumentaryManager $documentaryManager) {
        $this->documentaryManager = $documentaryManager;
    }

    public function getFunctions()
    {
        return array(
            'relatedDocumentaries' => new \Twig_Function_Method($this, 'relatedDocumentaries')
        );
    }

    public function relatedDocumentaries($documentary)
    {
        $documentaries = $this->documentaryManager->getRelatedDocumentaries($documentary);
        return $documentaries;
    }

    public function getName()
    {
        return 'relatedDocumentariesExtension';
    }
}