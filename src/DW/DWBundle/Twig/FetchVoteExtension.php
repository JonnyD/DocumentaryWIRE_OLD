<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Entity\Comment;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Helper\UserHelper;
use DW\DWBundle\Manager\LikeManager;
use DW\DWBundle\Manager\VoteManager;

class FetchVoteExtension extends \Twig_Extension
{
    private $voteManager;
    private $userHelper;

    public function __construct(VoteManager $voteManager, UserHelper $userHelper)
    {
        $this->voteManager = $voteManager;
        $this->userHelper = $userHelper;
    }

    public function getFunctions()
    {
        return array(
            'fetchVote' => new \Twig_Function_Method($this, 'fetchVote')
        );
    }

    public function fetchVote(User $user = null, Comment $comment)
    {
        $vote = null;
        if ($user != null) {
            $vote = $this->voteManager->getVoteByUserAndComment($user, $comment);
        }
        return $vote;
    }

    public function getName()
    {
        return 'fetchVoteExtension';
    }
}