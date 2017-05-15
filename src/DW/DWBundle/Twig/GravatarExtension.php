<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Manager\UserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GravatarExtension extends \Twig_Extension
{
    private $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function getFunctions()
    {
        return array(
            'getGravatar' => new \Twig_Function_Method($this, 'getGravatar')
        );
    }

    public function getGravatar($email)
    {
        $gravatar = $this->userManager->getGravatar($email);
        return $gravatar;
    }

    public function getName()
    {
        return 'gravatarExtension';
    }
}