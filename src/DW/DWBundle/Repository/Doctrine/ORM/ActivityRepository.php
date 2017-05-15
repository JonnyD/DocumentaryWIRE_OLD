<?php

namespace DW\DWBundle\Repository\Doctrine\ORM;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Repository\ActivityRepository as ActivityRepositoryInterface;

/**
 * ActivityRepository
 */
class ActivityRepository extends CustomRepository implements ActivityRepositoryInterface
{

    public function find($id)
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT a FROM DocumentaryWIREBundle:Activity a
            WHERE a.id = :id')
            ->setParameter('id', $id);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findActivityByUserAndTypeBetweenDates($user, $type, $from, $to)
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT a FROM DocumentaryWIREBundle:Activity a
            WHERE a.user = :owner
              AND a.type = :type
              AND a.created >= :from
              AND a.created <= :to
            ORDER BY a.groupNumber DESC, a.created DESC")
            ->setParameter('owner', $user)
            ->setParameter('type', $type)
            ->setParameter('from', $from->format('Y-m-d H:i:s'))
            ->setParameter('to', $to->format('Y-m-d H:i:s'));

        return $query->getResult();
    }

    public function findActivityByTypeBetweenDates($type, $from, $to)
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT a FROM DocumentaryWIREBundle:Activity a
            WHERE a.type = :type
              AND a.created >= :from
              AND a.created <= :to
            ORDER BY a.groupNumber DESC, a.created DESC")
            ->setParameter('type', $type)
            ->setParameter('from', $from->format('Y-m-d H:i:s'))
            ->setParameter('to', $to->format('Y-m-d H:i:s'));

        return $query->getResult();
    }

    public function updateActivityByTypeBetweenDates($type, $from, $to, $groupNumber)
    {
        $query = $this->getEntityManager()->createQuery("
            UPDATE DocumentaryWIREBundle:Activity a
            SET a.groupNumber = :groupNumber
            WHERE a.type = :type
                AND a.created >= :from
                AND a.created <= :to")
            ->setParameter('groupNumber', $groupNumber)
            ->setParameter('type', $type)
            ->setParameter('from', $from->format('Y-m-d H:i:s'))
            ->setParameter('to', $to->format('Y-m-d H:i:s'));

        $query->execute();
    }

    public function findRecentActivity($limit)
    {
        $query = $this->getEntityManager()->createQuery("
          SELECT a, u FROM DocumentaryWIREBundle:Activity a
          JOIN a.user u
          ORDER BY a.groupNumber DESC, a.created DESC");
        $query->setFetchMode("DocumentaryWIRE:Activity", "user", "EAGER");

        if ($limit > 0) {
            $query->setMaxResults($limit);
        }

        return $query->getResult();
    }

    public function findRecentCommentActivity($limit)
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT a, u FROM DocumentaryWIREBundle:Activity a
            JOIN a.user u
            WHERE a.type = 'comment'
            ORDER by a.created DESC");
        $query->setFetchMode("DocumentaryWIRE:Activity", "user", "EAGER");

        if ($limit > 0) {
            $query->setMaxResults($limit);
        }

        return $query->getResult();
    }

    public function findRecentLikeActivity($limit)
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT a, u FROM DocumentaryWIREBundle:Activity a
            JOIN a.user u
            WHERE a.type = 'like'
            ORDER by a.created DESC");
        $query->setFetchMode("DocumentaryWIRE:Activity", "user", "EAGER");

        if ($limit > 0) {
            $query->setMaxResults($limit);
        }

        return $query->getResult();
    }

    public function findActivityByUser(User $user)
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT a, u FROM DocumentaryWIREBundle:Activity a
            JOIN a.user u
            WHERE u = :user
            ORDER BY a.created DESC')
            ->setParameter("user", $user);

        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function findActivityOrderedByCreated()
    {
        $query = $this->getEntityManager()->createQuery("
          SELECT a, u FROM DocumentaryWIREBundle:Activity a
          JOIN a.user u
          ORDER BY a.created DESC");
        $query->setFetchMode("DocumentaryWIRE:Activity", "user", "EAGER");

        return $query->getResult();
    }

    public function findActivityOrderedByCreatedASC()
    {
        $query = $this->getEntityManager()->createQuery("
          SELECT a FROM DocumentaryWIREBundle:Activity a
          JOIN a.user u
          ORDER BY a.created ASC");

        return $query->getResult();
    }

    public function findPreviousGroupNumber($groupNumber)
    {
        $query = $this->getEntityManager()->createQuery("
              SELECT a FROM DocumentaryWIREBundle:Activity a
              WHERE a.type = 'joined'
                AND a.groupNumber < :groupNumber
              ORDER BY a.groupNumber DESC")
            ->setParameter("groupNumber", $groupNumber);

        $query->setMaxResults(1);

        return $query->getResult();
    }

    public function findAmountForRecentWidget()
    {
        $sql = "select sum(count) as sum from (select count(*) as count from activity
                where activity.type = 'like' or activity.type = 'comment' or activity.type = 'joined'
                group by activity.group_number
                order by activity.group_number DESC, activity.created DESC
                limit 12) as A";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('sum', 'amount');
        $query = $this->_em->createNativeQuery($sql, $rsm);

        return $query->getSingleScalarResult();
    }

    public function findRawActivity()
    {
        $sql = "SELECT id, data FROM activity";

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('data', 'data');
        $query = $this->_em->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }

    public function findActivityByUserObjectType($user, $objectId, $type)
    {
        $query = $this->getEntityManager()->createQuery('
            SELECT a, u FROM DocumentaryWIREBundle:Activity a
            JOIN a.user u
            WHERE u = :user
              AND a.objectId = :objectId
              AND a.type = :type
            ORDER BY a.created DESC')
            ->setParameter("user", $user)
            ->setParameter("objectId", $objectId)
            ->setParameter("type", $type);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findActivityByGroupNumber($groupNumber)
    {
        $query = $this->getEntityManager()->createQuery("
            SELECT a FROM DocumentaryWIREBundle:Activity a
            WHERE a.groupNumber = :groupNumber")
            ->setParameter("groupNumber", $groupNumber);

        return $query->getResult();
    }

    public function updateActivityForMigration($id, $data)
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("UPDATE activity SET data = :data WHERE id = :id");
        $statement->bindValue('data', $data);
        $statement->bindValue('id', $id);
        $statement->execute();
    }
}