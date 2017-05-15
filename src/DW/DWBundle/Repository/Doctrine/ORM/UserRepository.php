<?php

namespace DW\DWBundle\Repository\Doctrine\ORM;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use DW\DWBundle\Repository\UserRepository as UserRepositoryInterface;

/**
 * UserRepository
 */
class UserRepository extends CustomRepository implements UserRepositoryInterface
{
	public function findLatestUsers($max = 3)
	{
		return $query = $this->getEntityManager()
			->createQuery('
			    SELECT u FROM DocumentaryWIREBundle:User u
			    WHERE u.activated IS NOT NULL
			    ORDER BY u.registered DESC')
			->setMaxResults($max)
			->getResult();
	}
	
	public function findRecentlyActiveUsers($max = 3)
	{
		return $query = $this->getEntityManager()
		->createQuery('SELECT u FROM DocumentaryWIREBundle:User u
				ORDER BY u.lastActive DESC')
		->setMaxResults($max)
		->getResult();
	}

    public function findUsers($orderBy, $limit)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT u FROM DocumentaryWIREBundle:User u
                ORDER BY u.' . $orderBy . ' DESC
            ');

        if (!empty($limit) && $limit > 0) {
            $query->setMaxResults($limit);
        }

        return $query->getResult();
    }

    public function findUsersWithFacebook()
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT u FROM DocumentaryWIREBundle:User u
            WHERE u.facebookId IS NOT NULL');

        return $query->getResult();
    }

    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT u FROM DocumentaryWIREBundle:User u
                WHERE u.username = :username
                  OR u.email = :email')
            ->setParameter('username', $usernameOrEmail)
            ->setParameter('email', $usernameOrEmail);

        try {
            return $query->getSingleResult();
        } catch(NoResultException $e) {
            return null;
        }
    }

    public function findUserByEmailOrFacebookId($email, $facebookId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT u, r FROM DocumentaryWIREBundle:User u
                JOIN u.roles r
                WHERE u.email = :email
                OR u.facebookId = :facebookId')
            ->setParameter('email', $email)
            ->setParameter('facebookId', $facebookId);

        try {
            return $query->getSingleResult();
        } catch(NoResultException $e) {
            return null;
        }
    }

    public function findUserByFacebookId($facebookId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT u FROM DocumentaryWIREBundle:User u
                WHERE u.facebookId = :facebookId')
            ->setParameter('facebookId', $facebookId);

        try {
            return $query->getSingleResult();
        } catch(NoResultException $e) {
            return null;
        }
    }

    public function findOneByUsername($username)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT u FROM DocumentaryWIREBundle:User u
                WHERE u.username = :username
            ')
            ->setParameter("username", $username);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findOneByEmail($email)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT u FROM DocumentaryWIREBundle:User u
                WHERE u.email = :email
            ')
            ->setParameter("email", $email);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findUserByResetKey($key)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT u FROM DocumentaryWIREBundle:User u
                WHERE u.resetKey = :key
            ')
            ->setParameter("key", $key);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findUserByActivationKey($key)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT u FROM DocumentaryWIREBundle:User u
                WHERE u.activationKey = :key
            ')
            ->setParameter("key", $key);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}