<?php

namespace DW\DWBundle\Repository\Cached;

use DW\DWBundle\Entity\Comment;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Helper\CacheHelper;
use DW\DWBundle\Repository\VoteRepository as VoteRepositoryInterface;
use DW\DWBundle\Repository\Doctrine\ORM\VoteRepository as VoteRepositoryDoctrine;

class VoteRepository extends CustomRepository implements VoteRepositoryInterface
{
    private $voteRepository;
    private $cacheHelper;

    public function __construct(VoteRepositoryDoctrine $voteRepository, CacheHelper $cacheHelper)
    {
        parent::__construct($voteRepository);

        $this->voteRepository = $voteRepository;
        $this->cacheHelper = $cacheHelper;
    }

    public function findVoteByUserAndComment(User $user, Comment $comment)
    {
        return $this->voteRepository->findVoteByUserAndComment($user, $comment);
    }

    public function findTopScoringUsers()
    {
        return $this->voteRepository->findTopScoringUsers();
    }
}