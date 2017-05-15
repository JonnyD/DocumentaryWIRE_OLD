<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Manager\UserManager;

class AvatarExtension extends \Twig_Extension
{
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function getFunctions()
    {
        return array(
            'getAvatar' => new \Twig_Function_Method($this, 'getAvatar')
        );
    }

    public function getAvatar($user)
    {
        $avatarFile = $user["avatar"];
        if ($avatarFile == null) {
            $email = $user["email"];
            $avatar = $this->userManager->getGravatar($email);
        } else {
            $avatar = 'uploads/images/avatar/' . $avatarFile;
        }
        return $avatar;
    }

    public function getName()
    {
        return 'displayAvatarExtension';
    }
}