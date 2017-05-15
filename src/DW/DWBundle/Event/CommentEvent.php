<?php

namespace DW\DWBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use DW\DWBundle\Entity\Comment;

class CommentEvent extends Event
{
    private $comment;

    public function __construct(comment $comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }
}