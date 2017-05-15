<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Manager\VoteManager;
use Symfony\Component\HttpFoundation\Session\Session;

class TopScoringUsersExtension extends \Twig_Extension
{
    private $voteManager;

    public function __construct(VoteManager $voteManager)
    {
        $this->voteManager = $voteManager;
    }

    public function getFunctions()
    {
        return array(
            'topScoringUsers' => new \Twig_Function_Method($this, 'topScoringUsers')
        );
    }

    public function topScoringUsers()
    {
        $users = $this->voteManager->getTopScoringUsers();
        return $users;
    }

    public function getName()
    {
        return 'topScoringUsersWidgetExtension';
    }
}