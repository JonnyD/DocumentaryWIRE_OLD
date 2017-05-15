<?php

namespace DW\DWBundle\Repository;

use DW\DWBundle\Entity\Comment;
use DW\DWBundle\Entity\User;

interface VoteRepository
{
    public function findVoteByUserAndComment(User $user, Comment $comment);
    public function findTopScoringUsers();
}