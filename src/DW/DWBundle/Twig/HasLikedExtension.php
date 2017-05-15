<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Entity\User;
use DW\DWBundle\Manager\LikeManager;

class HasLikedExtension extends \Twig_Extension
{
    private $likeManager;

    public function __construct(LikeManager $likeManager) {
        $this->likeManager = $likeManager;
    }

    public function getFunctions()
    {
        return array(
            'hasLiked' => new \Twig_Function_Method($this, 'hasLiked')
        );
    }

    public function hasliked($user = null, $documentarySlug)
    {
        $hasLiked = false;
        if ($user != null && $user instanceof User) {
            $hasLiked = $this->likeManager->hasLiked($user, $documentarySlug);
        }
        return $hasLiked;
    }

    public function getName()
    {
        return 'haslikedExtension';
    }
}