<?php

namespace DW\DWBundle\Manager;

use Doctrine\ORM\EntityManager;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Event\UserEvent;
use DW\DWBundle\Event\UserEvents;
use DW\DWBundle\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserManager
{
    private $userRepository;
    private $eventDispatcher;
	
	public function __construct(UserRepository $userRepository,
                                EventDispatcherInterface $eventDispatcher)
	{
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
	}

    public function save($user)
    {
        $this->userRepository->save($user);
    }

    public function addUser(User $user)
    {
        $this->userRepository->save($user);
        $this->eventDispatcher->dispatch(
            UserEvents::USER_JOINED,
            new UserEvent($user)
        );
    }

    public function confirmUser(User $user)
    {
        $this->userRepository->save($user);
        $this->eventDispatcher->dispatch(
          UserEvents::USER_CONFIRMED,
          new UserEvent($user)
        );
    }

    public function loginUser(User $user)
    {
        $user->setLastActive(new \DateTime());
        $this->userRepository->save($user);
        $this->eventDispatcher->dispatch(
            UserEvents::USER_LOGIN,
            new UserEvent($user)
        );
    }

    public function getAllUsers()
    {
        return $this->userRepository->findAll();
    }
	
	public function getLatestUsers()
	{
        return $this->userRepository->findLatestUsers(16);
	}
	
	public function getActiveUsers()
	{
		return $this->userRepository->findRecentlyActiveUsers(16);
	}

    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }

    public function getUserByUsername($username)
    {
        return $this->userRepository->findOneByUsername($username);
    }

    public function getUsersWithFacebook()
    {
        return $this->userRepository->findUsersWithFacebook();
    }

    public function getGravatar( $email, $s = 80, $d = 'wavatar', $r = 'g', $img = false, $atts = array() )
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    public function getUserByUsernameOrEmail($usernameOrEmail)
    {
        return $this->userRepository->findUserByUsernameOrEmail($usernameOrEmail);
    }

    public function getUserByEmailOrFacebookId($email, $facebookId)
    {
        return $this->userRepository->findUserByEmailOrFacebookId($email, $facebookId);
    }

    public function getUserByFacebookId($facebookId)
    {
        return $this->userRepository->findUserByFacebookId($facebookId);
    }

    public function getUserByEmail($email)
    {
        return $this->userRepository->findOneByEmail($email);
    }

    public function getUserByResetKey($key)
    {
        return $this->userRepository->findUserByResetKey($key);
    }

    public function getUserByActivationKey($key)
    {
        return $this->userRepository->findUserByActivationKey($key);
    }
}