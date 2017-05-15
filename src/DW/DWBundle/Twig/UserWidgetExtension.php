<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Manager\UserManager;
use DW\DWBundle\Helper\UserHelper;
use Symfony\Component\HttpFoundation\Session\Session;

class UserWidgetExtension extends \Twig_Extension
{
    private $userManager;
    private $session;

    public function __construct(UserManager $userManager, Session $session) {
        $this->userManager = $userManager;
        $this->session = $session;
    }

    public function getFunctions()
    {
        return array(
            'userWidget' => new \Twig_Function_Method($this, 'userWidget')
        );
    }

    public function userWidget($type)
    {
        if ($type == "newest") {
            $users = $this->userManager->getLatestUsers();
        } else {
            $users = $this->userManager->getActiveUsers();
        }
        return $users;
    }

    public function getName()
    {
        return 'userWidgetExtension';
    }
}