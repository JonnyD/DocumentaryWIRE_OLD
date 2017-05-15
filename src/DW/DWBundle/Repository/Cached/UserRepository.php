<?php

namespace DW\DWBundle\Repository\Cached;

use DW\DWBundle\Cache\UserCache;
use DW\DWBundle\Helper\CacheHelper;
use DW\DWBundle\Repository\UserRepository as UserRepositoryInterface;
use DW\DWBundle\Repository\Doctrine\ORM\UserRepository as UserRepositoryDoctrine;

class UserRepository extends CustomRepository implements UserRepositoryInterface
{
    private $userRepository;
    private $cacheHelper;

    public function __construct(UserRepositoryDoctrine $userRepository, CacheHelper $cacheHelper)
    {
        parent::__construct($userRepository);

        $this->userRepository = $userRepository;
        $this->cacheHelper = $cacheHelper;
    }

    public function findLatestUsers($max = 3)
    {
        $key = UserCache::KEY_LATEST;
        $name = UserCache::CACHE_USERS;
        $users = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\User>");
        if ($users == null) {
            $users = $this->userRepository->findLatestUsers($max);
            $this->cacheHelper->saveToCache($key, $name, $users);
        }
        return $users;
    }

    public function findRecentlyActiveUsers($max = 3)
    {
        $key = UserCache::KEY_ACTIVE;
        $name = UserCache::CACHE_USERS;
        $users = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\User>");
        if ($users == null) {
            $users = $this->userRepository->findRecentlyActiveUsers($max);
            $this->cacheHelper->saveToCache($key, $name, $users);
        }
        return $users;
    }

    public function findUsers($orderBy, $limit)
    {
        return $this->userRepository->findUsers($orderBy, $limit);
    }

    public function findUsersWithFacebook()
    {
        return $this->userRepository->findUsersWithFacebook();
    }

    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        return $this->userRepository->findUserByUsernameOrEmail($usernameOrEmail);
    }

    public function findUserByEmailOrFacebookId($email, $facebookId)
    {
        return $this->userRepository->findUserByEmailOrFacebookId($email, $facebookId);
    }

    public function findUserByFacebookId($facebookId)
    {
        return $this->userRepository->findUserByFacebookId($facebookId);
    }

    public function findAll()
    {
        return $this->userRepository->findAll();
    }

    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    public function findOneByUsername($username)
    {
        $key = UserCache::KEY_USERNAME."_".$username;
        $name = UserCache::CACHE_USERS;
        $user = $this->cacheHelper->getFromCache($key, $name, "DW\DWBundle\Entity\User");
        if ($user == null) {
            $user = $this->userRepository->findOneByUsername($username);
            $this->cacheHelper->saveToCache($key, $name, $user);
        }
        return $user;
    }

    public function findOneByEmail($email)
    {
        return $this->userRepository->findOneByEmail($email);
    }

    public function findUserByResetKey($key)
    {
        return $this->userRepository->findUserByResetKey($key);
    }

    public function findUserByActivationKey($key)
    {
        return $this->userRepository->findUserByActivationKey($key);
    }
}