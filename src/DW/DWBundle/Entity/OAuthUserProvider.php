<?php

namespace DW\DWBundle\Entity;

use DW\DWBundle\Manager\CommentManager;
use DW\DWBundle\Manager\RoleManager;
use DW\DWBundle\Manager\UserManager;
use DW\DWBundle\Manager\ActivityManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthUserProvider extends \HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider
{
    private $userManager;
    private $roleManager;
    private $commentManager;
    private $dataManager;
    private $filterManager;
    private $activityManager;
    private $encoderFactory;

    public function __construct(UserManager $userManager, RoleManager $roleManager,
                                CommentManager $commentManager, DataManager $dataManager,
                                FilterManager $filterManager, ActivityManager $activityManager,
                                EncoderFactoryInterface $encoderFactory)
    {
        $this->userManager = $userManager;
        $this->roleManager = $roleManager;
        $this->commentManager = $commentManager;
        $this->dataManager = $dataManager;
        $this->filterManager = $filterManager;
        $this->activityManager = $activityManager;
        $this->encoderFactory = $encoderFactory;
    }

    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $profilePicture = $response->getProfilePicture();
        $accessToken = $response->getAccessToken();
        $facebookId = $response->getResponse()["id"];
        $displayName = $response->getResponse()["name"];

        $previousUser = $this->userManager->getUserByFacebookId($facebookId);
        if ($previousUser != null) {
            $previousUser->setFacebookId(null);
            $previousUser->setFacebookAccessToken(null);
        }

        $user->setFacebookId($facebookId);
        $user->setFacebookAccessToken($accessToken);
        $user->setDisplayName($displayName);
        if ($user->getAvatar() == null) {
            $avatar = $this->getImageFromUrl($profilePicture);
            $user->setAvatar($avatar);
        }
        $this->userManager->save($user);
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $profilePicture = $response->getProfilePicture();
        $accessToken = $response->getAccessToken();
        $email = $response->getResponse()["email"];
        $facebookId = $response->getResponse()["id"];
        $name = $response->getResponse()["name"];

        $user = $this->userManager->getUserByFacebookId($facebookId);
        if ($user == null) {
            $user = $this->userManager->getUserByEmail($email);
            if ($user == null) {
                if ($name != null && $name != "") {
                    $username = str_replace(' ', '', $name);
                    $username = preg_replace('/[^A-Za-z0-9\-]/', '', $username);
                } else {
                    $username = $response->getResponse()["username"];
                }
                $username = strtolower($username);

                $user = new User();
                $user->setUsername($username);
                $user->setUsernameCanonical($username);
                $user->setEmail($email);
                $user->setEmailCanonical(strtolower($email));
                $user->setDisplayName($name);
                $user->setSalt(uniqid(mt_rand()));
                $password = sha1(uniqid(mt_rand(), true));
                $encoder = $this->encoderFactory->getEncoder($user);
                $password = $encoder->encodePassword($password, $user->getSalt());
                $user->setPassword($password);
                $user->setFacebookAccessToken($accessToken);
                $user->setFacebookAvatarFull($profilePicture);
                $avatar = $this->getImageFromUrl($profilePicture);
                $user->setAvatar($avatar);
                $role = $this->roleManager->getRoleByName("user");
                $user->addRole($role);
                $user->setFacebookId($facebookId);
                $user->setActivated(new \DateTime());
                $this->userManager->confirmUser($user);
                $this->commentManager->addUserToComments($user);
            } else {
                $user->setFacebookId($facebookId);
                $this->userManager->save($user);
            }
        }

        $this->userManager->loginUser($user);

        return $user;
    }

    private function getImageFromUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $headers = get_headers($url);
            $responseCode = substr($headers[0], 9, 3);
            if($responseCode != "404") {
                $contents = @file_get_contents($url);
                if ($contents != false) {
                    if (strpos($url, "?")) {
                        $urlArray = explode("?", $url, 2);
                        $url = $urlArray[0];
                    }
                    $filename = sha1(uniqid(mt_rand(), true));
                    $ext = pathinfo($url, PATHINFO_EXTENSION);
                    $tmpImageName = $filename . '.' . $ext;
                    $tmpImagePathRel = 'uploads/tmp/' . $tmpImageName;
                    file_put_contents($tmpImagePathRel, $contents);

                    $processedImage = $this->dataManager->find('avatar200', $tmpImagePathRel);
                    $response = $this->filterManager->applyFilter($processedImage, 'avatar200');
                    $avatar = $response->getContent();
                    unlink($tmpImagePathRel); // eliminate unfiltered temp file.
                    $permanentFolderPath = 'uploads/images/avatar/';
                    $permanentImagePath = $permanentFolderPath . $tmpImageName;
                    $f = fopen($permanentImagePath, 'w');
                    fwrite($f, $avatar);
                    fclose($f);

                    return $tmpImageName;
                }
            }
        }
    }
}