<?php

namespace DW\DWBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use DW\DWBundle\Entity\Like;

class LikeEvent extends Event
{
    private $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function getLike()
    {
        return $this->like;
    }
}