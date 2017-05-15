<?php

namespace DW\DWBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use DW\DWBundle\Entity\Documentary;

class DocumentaryEvent extends Event
{
    private $documentary;

    public function __construct(Documentary $documentary)
    {
        $this->documentary = $documentary;
    }

    public function getDocumentary()
    {
        return $this->documentary;
    }
}