<?php

namespace DW\DWBundle\Repository\Doctrine\ORM;

use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Repository\CommentRepository as CommentRepositoryInterface;

/**
 * CommentRepository
 */
class CommentRepository extends CustomRepository implements CommentRepositoryInterface
{
    public function findCommentById($id)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT c FROM DocumentaryWIREBundle:Comment c
            WHERE c.id = :id')
            ->setParameter('id', $id);

        return $query->getSingleResult();
    }

    public function findAll()
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT c FROM DocumentaryWIREBundle:Comment c'
            );

        return $query->getResult();
    }

    public function findCommentsByDocumentary(Documentary $documentary)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT c, u FROM DocumentaryWIREBundle:Comment c
             LEFT OUTER JOIN c.user u
             WHERE c.documentary = :documentary
             ORDER BY c.created DESC'
            )
            ->setParameter('documentary', $documentary);

        return $query->getResult(2);
    }

    public function findParentCommentsByDocumentaryOrderedByPoints(Documentary $documentary)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT c, u FROM DocumentaryWIREBundle:Comment c
             LEFT OUTER JOIN c.user u
             WHERE c.documentary = :documentary
               AND c.parent IS NULL
             ORDER BY c.points DESC, c.created DESC'
            )
            ->setParameter('documentary', $documentary);

        return $query->getResult();
    }

    public function findParentCommentsByDocumentaryOrderedByDate(Documentary $documentary)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT c, u FROM DocumentaryWIREBundle:Comment c
             LEFT OUTER JOIN c.user u
             WHERE c.documentary = :documentary
               AND c.parent IS NULL
             ORDER BY c.created DESC'
        )
            ->setParameter('documentary', $documentary);

        return $query->getResult();
    }

    public function findCommentsByUser(User $user)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT c FROM DocumentaryWIREBundle:Comment c
             WHERE c.user = :user
             ORDER BY c.created DESC'
            )
            ->setParameter('user', $user);

        return $query->getResult();
    }

    public function findCommentsByEmail($email)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT c FROM DocumentaryWIREBundle:Comment c
             WHERE c.email = :email
             ORDER BY c.created DESC'
        )
            ->setParameter('email', $email);

        return $query->getResult();
    }

    public function findCommentsByEmailWithNoUser($email)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT c FROM DocumentaryWIREBundle:Comment c
             WHERE c.email = :email
              AND c.user IS NULL
             ORDER BY c.created DESC'
        )
            ->setParameter('email', $email);

        return $query->getResult();
    }

    public function findCommentsWithUser()
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT c FROM DocumentaryWIREBundle:Comment c
             WHERE c.user IS NOT NULL
             ORDER BY c.created DESC');

        return $query->getResult();
    }
}