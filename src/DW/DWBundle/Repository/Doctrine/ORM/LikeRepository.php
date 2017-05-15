<?php

namespace DW\DWBundle\Repository\Doctrine\ORM;

use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Repository\LikeRepository as LikeRepositoryInterface;

/**
 * LikeRepository
 */
class LikeRepository extends CustomRepository implements LikeRepositoryInterface
{
    public function findAllLikes()
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT l, d FROM DocumentaryWIREBundle:Like l');

        return $query->getResult();
    }

    public function deleteLikeByUserAndDocumentary(User $user, Documentary $documentary)
    {
        $query = $this->getEntityManager()->createQuery(
            'DELETE FROM DocumentaryWIREBundle:Like l
              WHERE l.user = :user
                AND l.documentary = :documentary')
            ->setParameter('user', $user)
            ->setParameter('documentary', $documentary);

        $query->execute();
    }

    public function findLikesByUser(User $user)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT l, d FROM DocumentaryWIREBundle:Like l
            JOIN l.documentary d
            WHERE l.user = :user
            ORDER BY l.created DESC')
            ->setParameter('user', $user);

        return $query->getResult();
    }

    public function findLikeByUserAndDocumentary(User $user, Documentary $documentary)
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT l FROM DocumentaryWIREBundle:Like l
            WHERE l.user = :user
              AND l.documentary = :documentary')
            ->setParameter('user', $user)
            ->setParameter('documentary', $documentary);

        return $query->getSingleResult();
    }

    public function findLikesByDocumentary(Documentary $documentary)
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT l FROM DocumentaryWIREBundle:Like l
            WHERE l.documentary = :documentary')
            ->setParameter('documentary', $documentary);

        return $query->getResult();
    }
}