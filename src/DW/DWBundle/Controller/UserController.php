<?php

namespace DW\DWBundle\Controller;

use DW\DWBundle\Form\ChangePasswordForm;
use DW\DWBundle\Form\ChangeUsernameForm;
use DW\DWBundle\Form\UploadAvatarForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DW\DWBundle\Manager\UserManager;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function showActivityAction($username, Request $request)
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $user = $userManager->getUserByUsername($username);

        if ($user == null) {
            throw $this->createNotFoundException("User doesn't exist");
        }

        $activityManager = $this->get('documentary_wire.activity_manager');
        $activity = $activityManager->getActivityByUser($user);

        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $activity = $paginateManager->paginate($activity, 10, $request);

        return $this->render('DocumentaryWIREBundle:User:show.html.twig', array(
            'user' => $user,
            'activity' => $activity
        ));
    }

    public function showLikedAction($username, Request $request)
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $user = $userManager->getUserByUsername($username);

        $likeManager = $this->get('documentary_wire.like_manager');
        $likes = $likeManager->getLikedDocumentariesByUser($user);

        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $likes = $paginateManager->paginate($likes, 9, $request);

        return $this->render('DocumentaryWIREBundle:User:favourites.html.twig', array(
            'user' => $user,
            'likes' => $likes
        ));
    }

    public function showFollowersAction($username)
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $user = $userManager->getUserByUsername($username);
        $followers = $user->getFollowers();

        return $this->render('DocumentaryWIREBundle:User:followers.html.twig', array(
            'user' => $user,
            'followers' => $followers
        ));
    }

    public function showFollowingAction($username)
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $user = $userManager->getUserByUsername($username);
        $following = $user->getFollowing();

        return $this->render('DocumentaryWIREBundle:User:following.html.twig', array(
            'user' => $user,
            'following' => $following
        ));
    }

    public function showProfileAction($username)
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $user = $userManager->getUserByUsername($username);

        return $this->render(
            'DocumentaryWIREBundle:User:profile.html.twig',
            array('user' => $user)
        );
    }

    public function showFriendsAction($username)
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $user = $userManager->getUserByUsername($username);

        return $this->render(
            'DocumentaryWIREBundle:User:friends.html.twig',
            array('user' => $user)
        );
    }

    public function listMembersWidgetAction()
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $users = $userManager->getLatestUsers();

        return $this->render(
            'DocumentaryWIREBundle:User:listMembersWidget.html.twig',
            array('users' => $users)
        );
    }

    public function listUsersAction()
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $users = $userManager->getUsersWithFacebook();

        return $this->render(
            'DocumentaryWIREBundle:User:listUsers.html.twig',
            array('users' => $users)
        );
    }

    public function editProfileAction()
    {
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('username', 'text')
            ->add('submit', 'submit')
            ->getForm();

        $userHelper = $this->get('documentary_wire.user_helper');
        $loggedInUser = $userHelper->getLoggedInUser();

        return $this->render('DocumentaryWIREBundle:User:editProfile.html.twig', array(
            'form' => $form->createView(),
            'user' => $loggedInUser
        ));
    }

    public function uploadAvatarAction(Request $request)
    {
        $userHelper = $this->get('documentary_wire.user_helper');
        $user = $userHelper->getLoggedInUser();

        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('file', 'file')
            ->add('submit', 'submit')
            ->getForm();

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            $data = $form->getData();

            $file = $data["file"];
            $mimeType = $file->getMimeType();
            if ($mimeType != "image/jpg"
                && $mimeType != "image/jpeg"
                && $mimeType != "image/png") {
                $form->addError(new FormError("You can only upload images in the following format: jpg, jpeg, and png."));
            }

            $size = $file->getSize();
            if ($size > 2000000) {
                $form->addError(new FormError("Image must be less than 2mb"));
            }

            if ($form->isValid()) {
                $file = $data["file"];

                $tmpFolderPathAbs = $this->get('kernel')->getRootDir() . '/../web/uploads/tmp/';
                $filename = sha1(uniqid(mt_rand(), true));
                $tmpImageName = $filename . '.' . $file->guessExtension();
                $file->move($tmpFolderPathAbs, $tmpImageName);
                $tmpImagePathRel = '/uploads/tmp/' . $tmpImageName;

                $dataManager = $this->get('liip_imagine.data.manager');
                $processedImage = $dataManager->find('avatar200', $tmpImagePathRel);
                $filterManager = $this->container->get('liip_imagine.filter.manager');
                $response = $filterManager->applyFilter($processedImage, 'avatar200');
                $avatar = $response->getContent();
                unlink($tmpFolderPathAbs . $tmpImageName); // eliminate unfiltered temp file.
                $permanentFolderPath = $this->get('kernel')->getRootDir() . '/../web/uploads/images/avatar/';
                $permanentImagePath = $permanentFolderPath . $tmpImageName;
                $f = fopen($permanentImagePath, 'w');
                fwrite($f, $avatar);
                fclose($f);

                $userManager = $this->get('documentary_wire.user_manager');
                $user->setAvatar($tmpImageName);
                $userManager->persist($user);

                $cacheHelper = $this->get('documentary_wire.cache_helper');
                $cacheHelper->deleteFromCache("recentWidget", "activity");
                $cacheHelper->deleteFromCache("all", "activity");
                $cacheHelper->deleteFromCache("latest", "users");
                $cacheHelper->deleteFromCache("user_username_".$user->getUsername(), "users");
                $cacheHelper->deleteFromCache("user_".$user->getId(), "activity");

                //return $this->redirect($this->generateUrl('documentary_wire.edit_profile'));
            }
        }

        return $this->render('DocumentaryWIREBundle:User:uploadAvatar.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    public function changeUsernameAction(Request $request)
    {
        $securityContext = $this->container->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();

        $currentUsername = $user->getUsername();
        $data = array("username" => $currentUsername);
        $form = $this->createFormBuilder($data)
            ->add('username', 'text')
            ->add('submit', 'submit')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            $newUsername = $data["username"];

            if (strlen($newUsername) < 4 || strlen($newUsername) > 16) {
                $form->addError(new FormError("Username must be between 6 and 15 characters."));
            }

            $userManager = $this->get('documentary_wire.user_manager');
            $userFound = $userManager->getUserByUsername($newUsername);
            if ($userFound != null && $currentUsername != $newUsername) {
                $form->addError(new FormError("Username is already taken."));
            }

            if(!ctype_alnum(str_replace(array('-', '_'), '', $newUsername))) {
                $form->addError(new FormError("Username can only contain letters, digits, - or _."));
            }

            if ($form->isValid()) {
                $user->setUsername($newUsername);
                $userManager->persist($user);

                $cacheHelper = $this->get('documentary_wire.cache_helper');
                $cacheHelper->deleteFromCache("recentWidget", "activity");
                $cacheHelper->deleteFromCache("all", "activity");
                $cacheHelper->deleteFromCache("latest", "users");
                $cacheHelper->deleteFromCache("user_username_".$currentUsername, "users");
            }
        }

        return $this->render('DocumentaryWIREBundle:User:changeUsername.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function changePasswordAction(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('currentPassword', 'password')
            ->add('newPassword', 'password')
            ->add('confirmPassword', 'password')
            ->add('submit', 'submit')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            $currentPassword = $data["currentPassword"];
            $newPassword = $data["newPassword"];
            $confirmPassword = $data["confirmPassword"];

            $userHelper = $this->get('documentary_wire.user_helper');
            $loggedInUser = $userHelper->getLoggedInUser();
            $oldPassword = $loggedInUser->getPassword();

            if ($currentPassword != $oldPassword) {
                $form->addError(new FormError("Password doesn't match the one in your account"));
            }

            if (strlen($newPassword) < 6 || strlen($newPassword) > 40) {
                $form->addError(new FormError("New Password must be between 6 and 40 characters"));
            }

            if ($newPassword != $confirmPassword) {
                $form->addError(new FormError("New Password and Confirm Password don't match"));
            }

            if ($newPassword == $oldPassword) {
                $form->addError(new FormError("New Password cannot be the same as old password"));
            }

            if ($form->isValid()) {
                $loggedInUser->setPassword($newPassword);

                $userManager = $this->get('documentary_wire.user_manager');
                $userManager->persist($loggedInUser);

                return $this->redirect($this->generateUrl('documentary_wire.edit_profile'));
            }
        }

        return $this->render('DocumentaryWIREBundle:User:changePassword.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function forgotPasswordAction(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('usernameOrEmail', 'text')
            ->add('submit', 'submit')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            $usernameOrEmail = $data["usernameOrEmail"];

            $userManager = $this->get('documentary_wire.user_manager');
            $user = $userManager->getUserByUsernameEmail($usernameOrEmail);
            if ($user == null) {
                $user = $userManager->getUserByUsername($usernameOrEmail);
            }
            if ($user == null) {
                $form->addError(new FormError("Username or email cannot be found."));
            }

            if ($form->isValid()) {
                $resetKey = sha1(mt_rand(10000,99999).time().$usernameOrEmail);
                $resetTime = new \DateTime();

                $user->setResetKey($resetKey);
                $user->setResetRequest($resetTime);

                $userManager->mergeAndPersist($user);

                $url = $this->generateUrl('documentary_wire.reset_password', array("key" => $resetKey), true);

                $message = \Swift_Message::newInstance()
                    ->setSubject('Reset Password')
                    ->setFrom(array('contact@documentarywire.com' => 'DocumentaryWIRE'))
                    ->setTo($user->getEmail())
                    ->setBody("Someone requested that the password be reset for the following account: " . $user->getUsername() . ".

If this was a mistake, just ignore this email and nothing will happen.

To reset your password, visit the following address: " . $url);
                $this->get('mailer')->send($message);

                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->get('forgot-password'); // gets message and clears type
                $flashBag->set('forgot-password', "An email containing further activation instructions has been sent. This email should be received within the next 10 minutes (usually instantly).");
            }
        }

        return $this->render('DocumentaryWIREBundle:User:forgotPassword.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function resetPasswordAction(Request $request)
    {
        $key = $request->query->get('key');

        $userManager = $this->get('documentary_wire.user_manager');
        $user = $userManager->getUserByResetKey($key);
        if ($user == null) {
            return $this->render('DocumentaryWIREBundle:User:invalidResetKey.html.twig');
        }
        $lastReset = $user->getLastReset();
        $resetRequest = $user->getResetRequest();
        $resetRequestPlus1 = clone $resetRequest;
        $resetRequestPlus1 = $resetRequestPlus1->add(new \DateInterval('P1D'));
        $currentDateTime = new \DateTime();

        if (($resetRequestPlus1 < $currentDateTime)
            || $lastReset > $resetRequest) {
            return $this->render('DocumentaryWIREBundle:User:expiredResetKey.html.twig');
        }

        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('newPassword', 'password')
            ->add('confirmPassword', 'password')
            ->add('submit', 'submit')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            $newPassword = $data["newPassword"];
            $confirmPassword = $data["confirmPassword"];

            if (strlen($newPassword) < 6 || strlen($newPassword) > 40) {
                $form->addError(new FormError("Password must be between 6 and 40 characters."));
            }

            if ($newPassword != $confirmPassword) {
                $form->addError(new FormError("Passwords don't match."));
            }

            if ($newPassword == $user->getUsername()) {
                $form->addError(new FormError("Password can't be your username"));
            }

            if ($form->isValid()) {
                $user->setSalt(uniqid(mt_rand()));
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword($newPassword, $user->getSalt());
                $user->setPassword($password);

                $user->setLastReset(new \DateTime());

                $userManager->persist($user);

                return $this->render('DocumentaryWIREBundle:User:passwordReset.html.twig');
            }
        }

        return $this->render('DocumentaryWIREBundle:User:resetPassword.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function activateAccountAction(Request $request)
    {
        $username = $request->query->get('username');
        $key = $request->query->get('key');
        $userManager = $this->get('documentary_wire.user_manager');
        $user = $userManager->getUserByUsername($username);
        if ($user == null || $user->getActivationKey() != $key) {
            return $this->render('DocumentaryWIREBundle:User:invalidActivationKey.html.twig');
        }
        $user->setActivated(new \DateTime());
        $userManager->confirmUser($user);
        return $this->render('DocumentaryWIREBundle:User:activationSuccessful.html.twig');
    }

    public function sendActivationEmailAction(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('email', 'text')
            ->add('submit', 'submit')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $data = $form->getData();
            $email = $data["email"];

            $userManager = $this->get('documentary_wire.user_manager');
            $user = $userManager->getUserByEmail($email);

            if ($user == null) {
                $form->addError(new FormError("Email doesn't exist."));
            } else {
                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->get('send-activation-email');

                if ($user->getActivated() != null) {
                    $flashBag->set('send-activation-email', "You have already activated your account.");
                } else {
                    $activationKey = sha1(mt_rand(10000,99999).time().$user->getUsername());
                    $user->setActivationKey($activationKey);

                    $userManager->persist($user);

                    $url = $this->generateUrl('documentary_wire.activate',
                        array("username" => $user->getUsername(), "key" => $activationKey), true);

                    $message = \Swift_Message::newInstance()
                        ->setSubject('Verify your email address at DocumentaryWIRE')
                        ->setFrom(array('contact@documentarywire.com' => 'DocumentaryWIRE'))
                        ->setTo($user->getEmail())
                        ->setBody("Please activate your account by clicking on the following link: " . $url);
                    $this->get('mailer')->send($message);

                    $flashBag->set('send-activation-email', "An email containing instructions to activate your email address has been sent. This email should be received within the next 10 minutes (usually instantly).");
                }
            }
        }

        return $this->render('DocumentaryWIREBundle:User:sendActivationEmail.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
