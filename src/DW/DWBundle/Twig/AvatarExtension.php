<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Entity\User;
use DW\DWBundle\Manager\UserManager;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class AvatarExtension extends \Twig_Extension
{
    private $userManager;
    private $imagineManager;

    public function __construct(UserManager $userManager, CacheManager $cacheManager)
    {
        $this->userManager = $userManager;
        $this->imagineManager = $cacheManager;
    }

    public function getFunctions()
    {
        return array(
            'getAvatar' => new \Twig_Function_Method($this, 'getAvatar')
        );
    }

    public function getAvatar($user, $filter)
    {
        if ($user instanceof User) {
            $avatarFile = $user->getAvatar();
            $email = $user->getEmail();
        } else {
            $avatarFile = $user["avatar"];
            $email = $user["email"];
        }

        if ($avatarFile == null) {
            $avatar = $this->userManager->getGravatar($email);
        } else {
            $avatar = 'uploads/images/avatar/' . $avatarFile;
            $avatar = $this->imagineManager->getBrowserPath($avatar, $filter);
        }

        return $avatar;
    }

    public function getName()
    {
        return 'getAvatarExtension';
    }
}