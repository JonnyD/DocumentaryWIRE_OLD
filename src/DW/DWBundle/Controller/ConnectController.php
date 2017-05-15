<?php

namespace DW\DWBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use HWI\Bundle\OAuthBundle\Controller\ConnectController as BaseController;

class ConnectController extends BaseController
{
    public function connectServiceAction(Request $request, $service)
    {
        $response = parent::connectServiceAction($request, $service);

        $content = $response->getContent();

        return $this->container->get('templating')->renderResponse(
            'DocumentaryWIREBundle:Connect:connect.html.'.
            $this->container->getParameter('hwi_oauth.templating.engine'),
            array("content" => $content),
            $response // The previous response that has been rendered by the parent class, by this is not necessary
        );
    }

    public function connectAction(Request $request)
    {
        $response = parent::connectAction($request);

        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->container->get('templating')->renderResponse(
            'DocumentaryWIREBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            ),
            $response // The previous response that has been rendered by the parent class, by this is not necessary
        );
    }
}