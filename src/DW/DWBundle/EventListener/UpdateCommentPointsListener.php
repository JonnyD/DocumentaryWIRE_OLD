<?php

namespace DW\DWBundle\EventListener;

use DW\DWBundle\Cache\ActivityCache;
use DW\DWBundle\Cache\CategoryCache;
use DW\DWBundle\Cache\DocumentaryCache;
use DW\DWBundle\Cache\LikeCache;
use DW\DWBundle\Cache\UserCache;
use DW\DWBundle\Cache\YearCache;
use DW\DWBundle\Event\ActivityEvent;
use DW\DWBundle\Event\CommentEvent;
use DW\DWBundle\Event\CommentEvents;
use DW\DWBundle\Event\DocumentaryEvent;
use DW\DWBundle\Event\DocumentaryEvents;
use DW\DWBundle\Event\LikeEvent;
use DW\DWBundle\Event\LikeEvents;
use DW\DWBundle\Event\UserEvent;
use DW\DWBundle\Event\UserEvents;
use DW\DWBundle\Event\VoteEvent;
use DW\DWBundle\Event\VoteEvents;
use DW\DWBundle\Helper\CacheHelper;
use DW\DWBundle\Manager\CommentManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentPointsListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            VoteEvents::VOTE_CREATED => "onVoteCreated",
            VoteEvents::VOTE_UPDATED => "onVoteUpdated"
        );
    }

    public function __construct()
    {

    }

    public function onVoteCreated(VoteEvent $voteEvent)
    {
        $vote = $voteEvent->getVote();
        $comment = $vote->getComment();


    }

    public function onVoteUpdated(VoteEvent $voteEvent)
    {

    }
}