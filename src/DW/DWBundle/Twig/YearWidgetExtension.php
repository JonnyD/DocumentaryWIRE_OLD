<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Manager\DocumentaryManager;

class YearWidgetExtension extends \Twig_Extension
{
    private $documentaryManager;

    public function __construct(DocumentaryManager $documentaryManager) {
        $this->documentaryManager = $documentaryManager;
    }

    public function getFunctions()
    {
        return array(
            'yearWidget' => new \Twig_Function_Method($this, 'yearWidget')
        );
    }

    public function yearWidget()
    {
        $years = $this->documentaryManager->getYears();
        return $years;
    }

    public function getName()
    {
        return 'yearWidgetExtension';
    }
}