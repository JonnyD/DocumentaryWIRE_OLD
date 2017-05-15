<?php

namespace DW\DWBundle\Controller;

use DW\DWBundle\Entity\ActivityComponent;
use DW\DWBundle\Entity\ActivityType;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Form\Registration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityController extends Controller
{
	public function loginAction()
    {
        $request = $this->getRequest();
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

        return $this->render(
            'DocumentaryWIREBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }

    public function registerAction(Request $request)
    {
        $user = new User();
        $registrationForm = $this->createForm(new Registration(), $user);

        $registrationForm->handleRequest($request);
        $data = $registrationForm->getData();
        $username = $data->getUsername();
        $email = $data->getEmail();
        $password = $data->getPassword();

        if ($username != null && $email != null) {
            if ($request->isMethod('POST')) {
                if(!ctype_alnum(str_replace(array('-', '_'), '', $username))) {
                    $registrationForm->addError(new FormError("Username can only contain letters, digits, - or _."));
                }
                $userManager = $this->get('documentary_wire.user_manager');
                $foundUser = $userManager->getUserByUsername($username);
                if ($foundUser != null) {
                    $registrationForm->addError(new FormError("Username is already in use."));
                }
                $foundUser = $userManager->getUserByEmail($email);
                if ($foundUser != null) {
                    $registrationForm->addError(new FormError("Email is already in use."));
                }

                if ($registrationForm->isValid()) {
                    $user->setDisplayName($username);
                    $user->setUsernameCanonical(strtolower($username));
                    $user->setEmailCanonical(strtolower($user->getEmail()));
                    $user->setSalt(uniqid(mt_rand()));

                    $roleManager = $this->get('documentary_wire.role_manager');
                    $role = $roleManager->getRoleByName("user");
                    $user->addRole($role);

                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($user);
                    $password = $encoder->encodePassword($password, $user->getSalt());
                    $user->setPassword($password);

                    $activationKey = sha1(mt_rand(10000,99999).time().$user->getUsername());
                    $user->setActivationKey($activationKey);
                    $userManager->addUser($user);

                    $flashBag = $this->get('session')->getFlashBag();
                    $flashBag->get('registered');
                    $flashBag->set('registered', "An email containing instructions to verify your email address has been sent. This email should be received within the next 10 minutes (usually instantly).");
                }
            }
        }

        return $this->render('DocumentaryWIREBundle:Security:register.html.twig', array(
            'registrationForm' => $registrationForm->createView(),
        ));
    }
}
