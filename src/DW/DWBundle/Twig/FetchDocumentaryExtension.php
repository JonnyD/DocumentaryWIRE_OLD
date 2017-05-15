<?php

namespace DW\DWBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FetchDocumentaryExtension extends \Twig_Extension
{
    private $documentaryManager;

    public function __construct(ContainerInterface $container) {
        $this->documentaryManager = $container->get('documentary_wire.documentary_manager');
    }

    public function getFunctions()
    {
        return array(
            'fetchDocumentary' => new \Twig_Function_Method($this, 'fetchDocumentary')
        );
    }

    public function fetchDocumentary($slug)
    {
        $documentary = $this->documentaryManager->getDocumentaryBySlug($slug);
        return $documentary;
    }

    public function getName()
    {
        return 'fetchDocumentaryExtension';
    }
}