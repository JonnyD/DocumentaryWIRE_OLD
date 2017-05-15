<?php

namespace DW\DWBundle\Manager;

use DW\DWBundle\Entity\Comment;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Entity\Vote;
use DW\DWBundle\Entity\VoteType;
use DW\DWBundle\Event\VoteEvent;
use DW\DWBundle\Event\VoteEvents;
use DW\DWBundle\Repository\VoteRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class VoteManager
{
    private $voteRepository;
    private $eventDispatcher;

    public function __construct(VoteRepository $voteRepository,
                                EventDispatcherInterface $eventDispatcher)
    {
        $this->voteRepository = $voteRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createVote(User $user = null, Comment $comment = null, $action)
    {
        $vote = new Vote();
        $vote->setUser($user);
        $value = $this->calculateValueByVoteType($action);
        $vote->setValue($value);
        $points = $this->calculatePointsByVoteType($action);
        $comment->addPoints($points);
        $vote->setComment($comment);
        $vote->setCreated(new \DateTime());
        $this->persistVote($vote);

        $this->eventDispatcher->dispatch(
            VoteEvents::VOTE_UPDATED,
            new VoteEvent($vote)
        );

        return $points;
    }

    public function updateVote(Vote $vote, $action)
    {
        $oldVoteType = $vote->getVoteType();
        $value = $this->calculateValueByOldAndNewVoteType($oldVoteType, $action);
        $vote->setValue($value);
        $comment = $vote->getComment();
        $points = $this->calculatePointsByOldAndNewVoteType($oldVoteType, $action);
        $comment->addPoints($points);
        $this->persistVote($vote);

        $this->eventDispatcher->dispatch(
            VoteEvents::VOTE_UPDATED,
            new VoteEvent($vote)
        );

        return $points;
    }

    public function calculateValueByVoteType($voteType)
    {
        $value = 0;
        if ($voteType == VoteType::UPVOTE) {
            $value = 1;
        } else if ($voteType == VoteType::DOWNVOTE) {
            $value = -1;
        }
        return $value;
    }

    public function calculatePointsByVoteType($voteType)
    {
        $points = 0;
        if ($voteType == VoteType::UPVOTE) {
            $points = 1;
        } else if ($voteType == VoteType::DOWNVOTE) {
            $points = -1;
        }
        return $points;
    }

    public function calculatePointsByOldAndNewVoteType($oldVoteType, $newVoteType)
    {
        $points = 0;
        if ($oldVoteType == VoteType::UPVOTE) {
            if ($newVoteType == VoteType::UPVOTE) {
                $points = -1;
            } else if ($newVoteType == VoteType::DOWNVOTE) {
                $points = -2;
            }
        } else if ($oldVoteType == VoteType::DOWNVOTE) {
            if ($newVoteType == VoteType::UPVOTE) {
                $points = 2;
            } else if ($newVoteType == VoteType::DOWNVOTE) {
                $points = 1;
            }
        } else if ($oldVoteType == VoteType::NEUTRAL) {
            if ($newVoteType == VoteType::UPVOTE) {
                $points = 1;
            } else if ($newVoteType == VoteType::DOWNVOTE) {
                $points = -1;
            }
        }
        return $points;
    }

    public function calculateValueByOldAndNewVoteType($oldVoteType, $newVoteType)
    {
        $value = 0;
        if ($oldVoteType == VoteType::UPVOTE) {
            if ($newVoteType == VoteType::UPVOTE) {
                $value = 0;
            } else if ($newVoteType == VoteType::DOWNVOTE) {
                $value = -1;
            }
        } else if ($oldVoteType == VoteType::DOWNVOTE) {
            if ($newVoteType == VoteType::UPVOTE) {
                $value = 1;
            } else if ($newVoteType == VoteType::DOWNVOTE) {
                $value = 0;
            }
        } else if ($oldVoteType == VoteType::NEUTRAL) {
            if ($newVoteType == VoteType::UPVOTE) {
                $value = 1;
            } else if ($newVoteType == VoteType::DOWNVOTE) {
                $value = -1;
            }
        }
        return $value;
    }

    public function getValueByAction($action)
    {
        if ($action == VoteType::UPVOTE)
        {
            return 1;
        }
        else if ($action == VoteType::DOWNVOTE)
        {
            return -1;
        }
    }

    public function persistVote($vote)
    {
        $this->voteRepository->save($vote);
    }

    public function removeVote($vote)
    {
        $this->voteRepository->remove($vote);
    }

    public function getVoteByUserAndComment($user, $comment)
    {
        return $this->voteRepository->findVoteByUserAndComment($user, $comment);
    }

    public function getTopScoringUsers()
    {
        return $this->voteRepository->findTopScoringUsers();
    }
}