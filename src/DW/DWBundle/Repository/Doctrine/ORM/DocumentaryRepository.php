<?php

namespace DW\DWBundle\Repository\Doctrine\ORM;

use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\ResultSetMapping;
use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\Tag;
use DW\DWBundle\Entity\Category;
use DW\DWBundle\Repository\DocumentaryRepository as DocumentaryRepositoryInterface;

/**
 * DocumentaryRepository
 */
class DocumentaryRepository extends CustomRepository implements DocumentaryRepositoryInterface
{
    public function findDocumentaryById($id)
    {
        $query = $this->getEntityManager()
            ->createQuery('
               SELECT d, c FROM DocumentaryWIREBundle:Documentary d
               JOIN d.category c
               WHERE d.id = :id')
            ->setParameter('id', $id);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findDocumentaryBySlug($slug)
    {
        $query = $this->getEntityManager()
            ->createQuery('
               SELECT d, c FROM DocumentaryWIREBundle:Documentary d
               JOIN d.category c
               WHERE d.slug = :slug')
            ->setParameter('slug', $slug);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findDocumentaryByTruncatedSlug($slug)
    {
        $query = $this->getEntityManager()
            ->createQuery('
               SELECT d, c FROM DocumentaryWIREBundle:Documentary d
               JOIN d.category c
               WHERE d.slug LIKE :slug')
            ->setParameter('slug', $slug.'%');

        $query->setMaxResults(1);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findPublishedDocumentaryKeysOrderedByTitleDesc()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               WHERE d.status = "publish"
               ORDER BY d.title DESC', $rsm);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysOrderedByTitleAsc()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               WHERE d.status = "publish"
               ORDER BY d.title ASC', $rsm);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysOrderedByViewsDesc()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               WHERE d.status = "publish"
               ORDER BY d.views DESC', $rsm);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysOrderedByViewsAsc()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               WHERE d.status = "publish"
               ORDER BY d.views ASC', $rsm);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysOrderedByDateDesc()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               WHERE d.status = "publish"
               ORDER BY d.created DESC', $rsm);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysOrderedByDateAsc()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               WHERE d.status = "publish"
               ORDER BY d.created ASC', $rsm);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysOrderedByLikesDesc()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               WHERE d.status = "publish"
               ORDER BY d.likeCount DESC', $rsm);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysOrderedByLikesAsc()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               WHERE d.status = "publish"
               ORDER BY d.likeCount ASC', $rsm);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysOrderedByCommentsDesc()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               WHERE d.status = "publish"
               ORDER BY d.comment_count DESC', $rsm);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysOrderedByCommentsAsc()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               WHERE d.status = "publish"
               ORDER BY d.comment_count ASC', $rsm);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByDateDesc($categoryId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               JOIN category on d.category_id = category.id
               WHERE category.id = :categoryId
                AND d.status = "publish"
                ORDER BY d.created DESC', $rsm);

        $query->setParameter('categoryId', $categoryId);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByDateAsc($categoryId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               JOIN category on d.category_id = category.id
               WHERE category.id = :categoryId
                AND d.status = "publish"
                ORDER BY d.created ASC', $rsm);

        $query->setParameter('categoryId', $categoryId);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByTitleDesc($categoryId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               JOIN category on d.category_id = category.id
               WHERE category.id = :categoryId
                AND d.status = "publish"
                ORDER BY d.title DESC', $rsm);

        $query->setParameter('categoryId', $categoryId);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByTitleAsc($categoryId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               JOIN category on d.category_id = category.id
               WHERE category.id = :categoryId
                AND d.status = "publish"
                ORDER BY d.title ASC', $rsm);

        $query->setParameter('categoryId', $categoryId);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByViewsDesc($categoryId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               JOIN category on d.category_id = category.id
               WHERE category.id = :categoryId
                AND d.status = "publish"
                ORDER BY d.views DESC', $rsm);

        $query->setParameter('categoryId', $categoryId);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByViewsAsc($categoryId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               JOIN category on d.category_id = category.id
               WHERE category.id = :categoryId
                AND d.status = "publish"
                ORDER BY d.views ASC', $rsm);

        $query->setParameter('categoryId', $categoryId);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByLikesDesc($categoryId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               JOIN category on d.category_id = category.id
               WHERE category.id = :categoryId
                AND d.status = "publish"
                ORDER BY d.likeCount DESC', $rsm);

        $query->setParameter('categoryId', $categoryId);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByLikesAsc($categoryId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               JOIN category on d.category_id = category.id
               WHERE category.id = :categoryId
                AND d.status = "publish"
                ORDER BY d.likeCount ASC', $rsm);

        $query->setParameter('categoryId', $categoryId);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByCommentsDesc($categoryId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               JOIN category on d.category_id = category.id
               WHERE category.id = :categoryId
                AND d.status = "publish"
                ORDER BY d.comment_count DESC', $rsm);

        $query->setParameter('categoryId', $categoryId);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByCommentsAsc($categoryId)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT d.id as id, d.slug as slug
               FROM documentary d
               JOIN category on d.category_id = category.id
               WHERE category.id = :categoryId
                AND d.status = "publish"
                ORDER BY d.comment_count ASC', $rsm);

        $query->setParameter('categoryId', $categoryId);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByYearOrderedByDateDesc($year)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($year == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year IS NULL
                    AND d.status = "publish"
                    ORDER BY d.created DESC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year = :year
                    AND d.status = "publish"
                    ORDER BY d.created DESC', $rsm);
            $query->setParameter('year', $year);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByYearOrderedByDateAsc($year)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($year == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year IS NULL
                    AND d.status = "publish"
                    ORDER BY d.created ASC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year = :year
                    AND d.status = "publish"
                    ORDER BY d.created ASC', $rsm);
            $query->setParameter('year', $year);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByYearOrderedByTitleDesc($year)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($year == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year IS NULL
                    AND d.status = "publish"
                    ORDER BY d.title DESC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year = :year
                    AND d.status = "publish"
                    ORDER BY d.title DESC', $rsm);
            $query->setParameter('year', $year);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByYearOrderedByTitleAsc($year)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($year == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year IS NULL
                    AND d.status = "publish"
                    ORDER BY d.title ASC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year = :year
                    AND d.status = "publish"
                    ORDER BY d.title ASC', $rsm);
            $query->setParameter('year', $year);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByYearOrderedByViewsDesc($year)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($year == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year IS NULL
                    AND d.status = "publish"
                    ORDER BY d.views DESC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year = :year
                    AND d.status = "publish"
                    ORDER BY d.views DESC', $rsm);
            $query->setParameter('year', $year);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByYearOrderedByViewsAsc($year)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($year == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year IS NULL
                    AND d.status = "publish"
                    ORDER BY d.views ASC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year = :year
                    AND d.status = "publish"
                    ORDER BY d.views ASC', $rsm);
            $query->setParameter('year', $year);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByYearOrderedByLikesDesc($year)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($year == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year IS NULL
                    AND d.status = "publish"
                    ORDER BY d.likeCount DESC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year = :year
                    AND d.status = "publish"
                    ORDER BY d.likeCount DESC', $rsm);
            $query->setParameter('year', $year);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByYearOrderedByLikesAsc($year)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($year == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year IS NULL
                    AND d.status = "publish"
                    ORDER BY d.likeCount ASC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year = :year
                    AND d.status = "publish"
                    ORDER BY d.likeCount ASC', $rsm);
            $query->setParameter('year', $year);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByYearOrderedByCommentsDesc($year)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($year == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year IS NULL
                    AND d.status = "publish"
                    ORDER BY d.comment_count DESC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year = :year
                    AND d.status = "publish"
                    ORDER BY d.comment_count DESC', $rsm);
            $query->setParameter('year', $year);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByYearOrderedByCommentsAsc($year)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($year == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year IS NULL
                    AND d.status = "publish"
                    ORDER BY d.comment_count ASC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.year = :year
                    AND d.status = "publish"
                    ORDER BY d.comment_count ASC', $rsm);
            $query->setParameter('year', $year);
        }

        return $query->getArrayResult();
    }

    public function findFeaturedDocumentaries()
    {
        $query = $this->getEntityManager()->createQuery('
               SELECT d FROM DocumentaryWIREBundle:Documentary d
                WHERE d.featured = 1
                ORDER BY d.featuredOrder, d.created DESC');

        return $query->getResult();
    }

    public function findYears()
    {
        $sql = 'SELECT year AS year, count(*) as amount FROM documentary
                GROUP BY year
                ORDER BY year DESC';

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('year', 'year');
        $rsm->addScalarResult('amount', 'amount');
        $query = $this->_em->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }

    public function findAmountOfDocumentariesByDuration($from, $to)
    {
        $sql = 'SELECT count(distinct length) as amount FROM documentary
                where length >= ' . $from . '
                    AND length <= ' . $to;

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('amount', 'amount');
        $query = $this->_em->createNativeQuery($sql, $rsm);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findAmountOfDocumentariesWithNoDuration()
    {
        $sql = 'SELECT count(*) as amount FROM documentary
                where length IS NULL';

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('amount', 'amount');
        $query = $this->_em->createNativeQuery($sql, $rsm);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByDateDesc($from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($from == "unknown" || $to == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length IS NULL
                    ORDER BY d.created DESC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length >= :from
                    AND d.length <= :to
                    ORDER BY d.created DESC', $rsm);
        }
        $query->setParameter('from', $from);
        $query->setParameter('to', $to);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByDateAsc($from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($from == "unknown" || $to == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length IS NULL
                    ORDER BY d.created ASC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length >= :from
                    AND d.length <= :to
                    ORDER BY d.created ASC', $rsm);
        }
        $query->setParameter('from', $from);
        $query->setParameter('to', $to);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByTitleDesc($from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($from == "unknown" || $to == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length IS NULL
                    ORDER BY d.title DESC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length >= :from
                    AND d.length <= :to
                    ORDER BY d.title DESC', $rsm);
        }
        $query->setParameter('from', $from);
        $query->setParameter('to', $to);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByTitleAsc($from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($from == "unknown" || $to == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length IS NULL
                    ORDER BY d.title ASC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length >= :from
                    AND d.length <= :to
                    ORDER BY d.title ASC', $rsm);
        }
        $query->setParameter('from', $from);
        $query->setParameter('to', $to);

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByViewsDesc($from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($from == "unknown" || $to == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length IS NULL
                    ORDER BY d.views DESC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length >= :from
                    AND d.length <= :to
                    ORDER BY d.views DESC', $rsm);
            $query->setParameter('from', $from);
            $query->setParameter('to', $to);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByViewsAsc($from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($from == "unknown" || $to == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length IS NULL
                    ORDER BY d.views ASC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length >= :from
                    AND d.length <= :to
                    ORDER BY d.views ASC', $rsm);
            $query->setParameter('from', $from);
            $query->setParameter('to', $to);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByLikesDesc($from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($from == "unknown" || $to == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length IS NULL
                    ORDER BY d.likeCount DESC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length >= :from
                    AND d.length <= :to
                    ORDER BY d.likeCount DESC', $rsm);
            $query->setParameter('from', $from);
            $query->setParameter('to', $to);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByLikesAsc($from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($from == "unknown" || $to == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length IS NULL
                    ORDER BY d.likeCount ASC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length >= :from
                    AND d.length <= :to
                    ORDER BY d.likeCount ASC', $rsm);
            $query->setParameter('from', $from);
            $query->setParameter('to', $to);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByCommentsDesc($from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($from == "unknown" || $to == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length IS NULL
                    ORDER BY d.comment_count DESC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length >= :from
                    AND d.length <= :to
                    ORDER BY d.comment_count DESC', $rsm);
            $query->setParameter('from', $from);
            $query->setParameter('to', $to);
        }

        return $query->getArrayResult();
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByCommentsAsc($from, $to)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('slug', 'slug');

        if ($from == "unknown" || $to == "unknown") {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length IS NULL
                    ORDER BY d.comment_count ASC', $rsm);
        } else {
            $query = $this->getEntityManager()->createNativeQuery(
                'SELECT d.id as id, d.slug as slug
                   FROM documentary d
                    WHERE d.status = "publish"
                    AND d.length >= :from
                    AND d.length <= :to
                    ORDER BY d.comment_count ASC', $rsm);
            $query->setParameter('from', $from);
            $query->setParameter('to', $to);
        }

        return $query->getArrayResult();
    }

    public function findDocumentariesByTag(Tag $tag)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT d
                FROM DocumentaryWIREBundle:Documentary d
                JOIN d.tags t
                WHERE t.slug = :slug')
            ->setParameter('slug', $tag->getSlug());

        return $query->getResult();
    }

    public function findDocumentaries($status, $orderBy, $order, $max)
    {
        $query = $this->getEntityManager()
            ->createQuery('
               SELECT d FROM DocumentaryWIREBundle:Documentary d
                WHERE d.status = :status
                ORDER BY d.' . $orderBy . ' ' . $order);

        if (!empty($status))
        {
            $query->setParameter('status', $status);
        }

        if (!empty($max) && $max > 0)
        {
            $query->setMaxResults($max);
        }

        return $query->getResult();
    }

    public function findDocumentariesByCategory($categoryId, $status, $orderBy, $order, $max)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT d, c FROM DocumentaryWIREBundle:Documentary d
                JOIN d.category c
                WHERE c.id = :id
                AND d.status = :status
                ORDER BY d.' . $orderBy . ' ' . $order)
            ->setParameter('id', $categoryId);

        if (!empty($status))
        {
            $query->setParameter('status', $status);
        }

        if (!empty($max) && $max > 0)
        {
            $query->setMaxResults($max);
        }

        return $query->getResult();
    }

    public function findRelatedDocumentaries(Documentary $documentary)
    {
        $category = $documentary->getCategory();

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('title', 'title');
        $rsm->addScalarResult('slug', 'slug');
        $rsm->addScalarResult('views', 'views');
        $rsm->addScalarResult('likeCount', 'likeCount');
        $rsm->addScalarResult('comment_count', 'comment_count');

        $query = $this->getEntityManager()->createNativeQuery(
            'SELECT title, slug, views, likeCount, comment_count
                FROM documentary
                WHERE category_id = :categoryId
                ORDER BY rand() LIMIT 12', $rsm);
        $query->setParameter('categoryId', $category->getId());

        return $query->getResult();
    }

    public function findAll()
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT d
                FROM DocumentaryWIREBundle:Documentary d');

        return $query->getResult();
    }

    public function findLastUpdatedDocumentary()
    {
        $query = $this->getEntityManager()
            ->createQuery('
               SELECT d FROM DocumentaryWIREBundle:Documentary d
                ORDER BY d.updated DESC');

        $query->setMaxResults(1);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findLastUpdatedDocumentaryInCategory(Category $category)
    {
        $query = $this->getEntityManager()
            ->createQuery('
               SELECT d, c FROM DocumentaryWIREBundle:Documentary d
                JOIN d.category c
                WHERE c = :category
                ORDER BY d.updated DESC')
            ->setParameter('category', $category);

        $query->setMaxResults(1);

        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}