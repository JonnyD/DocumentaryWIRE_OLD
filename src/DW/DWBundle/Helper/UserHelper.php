<?php

namespace DW\DWBundle\Helper;

use Symfony\Component\Security\Core\SecurityContext;

class UserHelper
{
    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function getLoggedInUser()
    {
        $token = $this->securityContext->getToken();
        $user = $token->getUser();
        return $user;
    }

    public function isLoggedIn()
    {
        return $this->getLoggedInUser() != null;
    }

    public function logoutUser()
    {
        $this->securityContext->setToken(null);
    }
}