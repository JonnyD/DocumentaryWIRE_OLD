<?php

namespace DW\DWBundle\Handler;

use DW\DWBundle\Helper\UserHelper;
use DW\DWBundle\Manager\UserManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;
    private $userHelper;
    private $userManager;
    private $eventDispatcher;

    public function __construct(RouterInterface $router, UserHelper $userHelper,
                                UserManager $userManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->router = $router;
        $this->userHelper = $userHelper;
        $this->userManager = $userManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $user = $this->userHelper->getLoggedInUser();
        if ($user->getActivated() == null) {
            $this->userHelper->logoutUser();
            $route = $this->router->generate('documentary_wire.activation_required');
        } else {
            $route = $this->router->generate('documentary_wire_homepage');
            $user->setLastActive(new \DateTime());
            $this->userManager->loginUser($user);
        }
        return new RedirectResponse($route);
    }
}