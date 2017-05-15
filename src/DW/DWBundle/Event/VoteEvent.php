<?php

namespace DW\DWBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use DW\DWBundle\Entity\Vote;

class VoteEvent extends Event
{
    private $vote;

    public function __construct(Vote $vote)
    {
        $this->vote = $vote;
    }

    public function getVote()
    {
        return $this->vote;
    }
}