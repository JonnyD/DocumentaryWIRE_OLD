<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Entity\DocumentaryFilter;
use DW\DWBundle\Entity\DocumentaryStatus;
use DW\DWBundle\Entity\Order;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SectionExtension extends \Twig_Extension
{
    private $documentaryManager;

    public function __construct(ContainerInterface $container) {
        $this->documentaryManager = $container->get('documentary_wire.documentary_manager');
    }

    public function getFunctions()
    {
        return array(
            'getDocumentariesBySection' => new \Twig_Function_Method($this, 'getDocumentariesBySection')
        );
    }

    public function getDocumentariesBySection($section)
    {
        $documentaries = array();

        switch ($section) {
            case 'recent':
                $documentaries = $this->documentaryManager->getPublishedDocumentaryKeys(DocumentaryFilter::DATE, Order::DESC);
                break;
            case 'liked':
                $documentaries = $this->documentaryManager->getPublishedDocumentaryKeys(DocumentaryFilter::LIKES, Order::DESC);
                break;
            case 'popular':
                $documentaries = $this->documentaryManager->getPublishedDocumentaryKeys(DocumentaryFilter::VIEWS, Order::DESC);
                break;
            case 'discussed':
                $documentaries = $this->documentaryManager->getPublishedDocumentaryKeys(DocumentaryFilter::COMMENTS, Order::DESC);
                break;
        }

        return $documentaries;
    }

    public function getName()
    {
        return 'sectionExtension';
    }
}