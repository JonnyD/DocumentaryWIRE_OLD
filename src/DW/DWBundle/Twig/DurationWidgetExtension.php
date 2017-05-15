<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Manager\DocumentaryManager;

class DurationWidgetExtension extends \Twig_Extension
{
    private $documentaryManager;

    public function __construct(DocumentaryManager $documentaryManager) {
        $this->documentaryManager = $documentaryManager;
    }

    public function getFunctions()
    {
        return array(
            'durationWidget' => new \Twig_Function_Method($this, 'durationWidget')
        );
    }

    public function durationWidget()
    {
        $duration = $this->documentaryManager->getDurations();
        return $duration;
    }

    public function getName()
    {
        return 'durationWidgetExtension';
    }
}