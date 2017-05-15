<?php

namespace DW\DWBundle\Repository\Cached;

use DW\DWBundle\Cache\YearCache;
use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\Tag;
use DW\DWBundle\Entity\Category;
use DW\DWBundle\Helper\CacheHelper;
use DW\DWBundle\Cache\DocumentariesCache;
use DW\DWBundle\Cache\DocumentaryCache;
use DW\DWBundle\Cache\CategoryDocumentariesCache;
use DW\DWBundle\Repository\DocumentaryRepository as DocumentaryRepositoryInterface;
use DW\DWBundle\Repository\Doctrine\ORM\DocumentaryRepository as DocumentaryRepositoryDoctrine;

class DocumentaryRepository implements DocumentaryRepositoryInterface
{
    private $documentaryRepository;
    private $cacheHelper;

    public function __construct(DocumentaryRepositoryDoctrine $documentaryRepository, CacheHelper $cacheHelper)
    {
        $this->documentaryRepository = $documentaryRepository;
        $this->cacheHelper = $cacheHelper;
    }

    public function save($entity, $sync = true)
    {
        $this->documentaryRepository->save($entity, $sync);
    }

    public function flush()
    {
        $this->documentaryRepository->flush();
    }

    public function findDocumentaryById($id)
    {
        $key = DocumentaryCache::KEY_DOCUMENTARY_ID."_".$id;
        $name = DocumentaryCache::CACHE_DOCUMENTARY;
        $documentary = $this->cacheHelper->getFromCache($key, $name, "DW\DWBundle\Entity\Documentary");
        if ($documentary == null) {
            $documentary = $this->documentaryRepository->findDocumentaryById($id);
            $this->cacheHelper->saveToCache($key, $name, $documentary);
        }
        return $documentary;
    }

    public function findDocumentaryBySlug($slug)
    {
        $key = DocumentaryCache::KEY_DOCUMENTARY_SLUG."_".$slug;
        $name = DocumentaryCache::CACHE_DOCUMENTARY;
        $documentary = $this->cacheHelper->getFromCache($key, $name, "DW\DWBundle\Entity\Documentary");
        if ($documentary == null) {
            $documentary = $this->documentaryRepository->findDocumentaryBySlug($slug);
            if ($documentary != null) {
                $this->cacheHelper->saveToCache($key, $name, $documentary);
            }
        }
        return $documentary;
    }

    public function findDocumentaryByTruncatedSlug($slug)
    {
        return $this->documentaryRepository->findDocumentaryByTruncatedSlug($slug);
    }

    public function findDocumentaries($status, $orderBy, $order, $max)
    {
        return $this->documentaryRepository->findDocumentaries($status, $orderBy, $order, $max);
    }

    public function findDocumentariesByCategory($categoryId, $status, $orderBy, $order, $max)
    {
        $key = $categoryId.$status.$orderBy.$order;
        $name = "category_documentaries";
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Documentary>");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findDocumentariesByCategory($categoryId, $status, $orderBy, $order, $max);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysOrderedByTitleDesc()
    {
        $key = DocumentaryCache::KEY_PUBLISHED_TITLE_DESC;
        $name = DocumentaryCache::CACHE_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysOrderedByTitleDesc();
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysOrderedByTitleAsc()
    {
        $key = DocumentaryCache::KEY_PUBLISHED_TITLE_ASC;
        $name = DocumentaryCache::CACHE_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysOrderedByTitleAsc();
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysOrderedByDateDesc()
    {
        $key = DocumentaryCache::KEY_PUBLISHED_DATE_DESC;
        $name = DocumentaryCache::CACHE_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysOrderedByDateDesc();
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysOrderedByDateAsc()
    {
        $key = DocumentaryCache::KEY_PUBLISHED_DATE_ASC;
        $name = DocumentaryCache::CACHE_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysOrderedByDateAsc();
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysOrderedByViewsDesc()
    {
        $key = DocumentaryCache::KEY_PUBLISHED_VIEWS_DESC;
        $name = DocumentaryCache::CACHE_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysOrderedByViewsDesc();
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysOrderedByViewsAsc()
    {
        $key = DocumentaryCache::KEY_PUBLISHED_VIEWS_ASC;
        $name = DocumentaryCache::CACHE_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysOrderedByViewsAsc();
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysOrderedByLikesDesc()
    {
        $key = DocumentaryCache::KEY_PUBLISHED_LIKES_DESC;
        $name = DocumentaryCache::CACHE_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysOrderedByLikesDesc();
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysOrderedByLikesAsc()
    {
        $key = DocumentaryCache::KEY_PUBLISHED_LIKES_ASC;
        $name = DocumentaryCache::CACHE_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysOrderedByLikesAsc();
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysOrderedByCommentsDesc()
    {
        $key = DocumentaryCache::KEY_PUBLISHED_COMMENTS_DESC;
        $name = DocumentaryCache::CACHE_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysOrderedByCommentsDesc();
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysOrderedByCommentsAsc()
    {
        $key = DocumentaryCache::KEY_PUBLISHED_COMMENTS_ASC;
        $name = DocumentaryCache::CACHE_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysOrderedByCommentsAsc();
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    /***************************************************************
        ************ Published Documentaries by Category **************
     ***************************************************************/
    public function findPublishedDocumentaryKeysByCategoryOrderedByDateDesc($categoryId)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_DATE_DESC."_".$categoryId;
        $name = DocumentaryCache::CACHE_CATEGORY_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByCategoryOrderedByDateDesc($categoryId);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByDateAsc($categoryId)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_DATE_DESC."_".$categoryId;
        $name = DocumentaryCache::CACHE_CATEGORY_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByCategoryOrderedByDateAsc($categoryId);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByTitleDesc($categoryId)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_TITLE_DESC."_".$categoryId;
        $name = DocumentaryCache::CACHE_CATEGORY_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByCategoryOrderedByTitleDesc($categoryId);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByTitleAsc($categoryId)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_TITLE_ASC."_".$categoryId;
        $name = DocumentaryCache::CACHE_CATEGORY_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByCategoryOrderedByTitleAsc($categoryId);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByViewsDesc($categoryId)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_VIEWS_DESC."_".$categoryId;
        $name = DocumentaryCache::CACHE_CATEGORY_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByCategoryOrderedByViewsDesc($categoryId);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByViewsAsc($categoryId)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_VIEWS_ASC."_".$categoryId;
        $name = DocumentaryCache::CACHE_CATEGORY_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByCategoryOrderedByViewsAsc($categoryId);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByLikesDesc($categoryId)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_LIKES_DESC."_".$categoryId;
        $name = DocumentaryCache::CACHE_CATEGORY_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByCategoryOrderedByLikesDesc($categoryId);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByLikesAsc($categoryId)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_LIKES_ASC."_".$categoryId;
        $name = DocumentaryCache::CACHE_CATEGORY_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByCategoryOrderedByLikesAsc($categoryId);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByCommentsDesc($categoryId)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_COMMENTS_DESC."_".$categoryId;
        $name = DocumentaryCache::CACHE_CATEGORY_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByCategoryOrderedByCommentsDesc($categoryId);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByCategoryOrderedByCommentsAsc($categoryId)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_COMMENTS_ASC."_".$categoryId;
        $name = DocumentaryCache::CACHE_CATEGORY_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByCategoryOrderedByCommentsAsc($categoryId);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    /********************** Published by Year ***/
    public function findPublishedDocumentaryKeysByYearOrderedByDateDesc($year)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_DATE_DESC."_".$year;
        $name = DocumentaryCache::CACHE_YEAR_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByYearOrderedByDateDesc($year);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByYearOrderedByDateAsc($year)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_DATE_DESC."_".$year;
        $name = DocumentaryCache::CACHE_YEAR_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByYearOrderedByDateAsc($year);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByYearOrderedByTitleDesc($year)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_TITLE_DESC."_".$year;
        $name = DocumentaryCache::CACHE_YEAR_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByYearOrderedByTitleDesc($year);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByYearOrderedByTitleAsc($year)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_TITLE_ASC."_".$year;
        $name = DocumentaryCache::CACHE_YEAR_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByYearOrderedByTitleAsc($year);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByYearOrderedByViewsDesc($year)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_VIEWS_DESC."_".$year;
        $name = DocumentaryCache::CACHE_YEAR_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByYearOrderedByViewsDesc($year);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByYearOrderedByViewsAsc($year)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_VIEWS_ASC."_".$year;
        $name = DocumentaryCache::CACHE_YEAR_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByYearOrderedByViewsAsc($year);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByYearOrderedByLikesDesc($year)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_LIKES_DESC."_".$year;
        $name = DocumentaryCache::CACHE_YEAR_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByYearOrderedByLikesDesc($year);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByYearOrderedByLikesAsc($year)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_LIKES_ASC."_".$year;
        $name = DocumentaryCache::CACHE_YEAR_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByYearOrderedByLikesAsc($year);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByYearOrderedByCommentsDesc($year)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_COMMENTS_DESC."_".$year;
        $name = DocumentaryCache::CACHE_YEAR_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByYearOrderedByCommentsDesc($year);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByYearOrderedByCommentsAsc($year)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_COMMENTS_ASC."_".$year;
        $name = DocumentaryCache::CACHE_YEAR_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByYearOrderedByCommentsAsc($year);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findFeaturedDocumentaries()
    {
        $key = DocumentaryCache::KEY_FEATURED;
        $name = DocumentaryCache::CACHE_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Documentary>");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findFeaturedDocumentaries();
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findYears()
    {
        $key = YearCache::KEY_LIST;
        $name = YearCache::CACHE_YEAR;
        $years = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($years == null) {
            $years = $this->documentaryRepository->findYears();
            $this->cacheHelper->saveToCache($key, $name, $years);
        }
        return $years;
    }

    public function findAmountOfDocumentariesByDuration($from, $to)
    {
        $key = $from.$to;
        $name = "duration";
        $duration = $this->cacheHelper->getFromCache($key, $name, "integer");
        if ($duration == null) {
            $duration = $this->documentaryRepository->findAmountOfDocumentariesByDuration($from, $to);
            if ($duration != null) {
                $this->cacheHelper->saveToCache($key, $name, $duration);
            }
        }
        return $duration;
    }

    public function findAmountOfDocumentariesWithNoDuration()
    {
        $key = "unknown";
        $name = "duration";
        $duration = $this->cacheHelper->getFromCache($key, $name, "integer");
        if ($duration == null) {
            $duration = $this->documentaryRepository->findAmountOfDocumentariesWithNoDuration();
            if ($duration != null) {
                $this->cacheHelper->saveToCache($key, $name, $duration);
            }
        }
        return $duration;
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByDateDesc($from, $to)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_DATE_DESC."_".$from.$to;
        $name = "duration_documentaries";
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByDurationOrderedByDateDesc($from, $to);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByDateAsc($from, $to)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_DATE_ASC."_".$from.$to;
        $name = DocumentaryCache::CACHE_DURATION_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByDurationOrderedByDateAsc($from, $to);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByTitleDesc($from, $to)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_TITLE_DESC."_".$from.$to;
        $name = DocumentaryCache::CACHE_DURATION_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByDurationOrderedByTitleDesc($from, $to);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByTitleAsc($from, $to)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_TITLE_ASC."_".$from.$to;
        $name = DocumentaryCache::CACHE_DURATION_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByDurationOrderedByTitleAsc($from, $to);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByViewsDesc($from, $to)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_VIEWS_DESC."_".$from.$to;
        $name = DocumentaryCache::CACHE_DURATION_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByDurationOrderedByViewsDesc($from, $to);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByViewsAsc($from, $to)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_VIEWS_ASCC."_".$from.$to;
        $name = DocumentaryCache::CACHE_DURATION_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByDurationOrderedByViewsAsc($from, $to);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByLikesDesc($from, $to)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_LIKES_DESC."_".$from.$to;
        $name = DocumentaryCache::CACHE_DURATION_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByDurationOrderedByLikesDesc($from, $to);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByLikesAsc($from, $to)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_LIKES_ASC."_".$from.$to;
        $name = DocumentaryCache::CACHE_DURATION_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByDurationOrderedByLikesAsc($from, $to);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByCommentsDesc($from, $to)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_COMMENTS_DESC."_".$from.$to;
        $name = DocumentaryCache::CACHE_DURATION_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByDurationOrderedByCommentsDesc($from, $to);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findPublishedDocumentaryKeysByDurationOrderedByCommentsAsc($from, $to)
    {
        $key = DocumentaryCache::KEY_PUBLISHED_COMMENTS_ASC."_".$from.$to;
        $name = DocumentaryCache::CACHE_DURATION_DOCUMENTARIES;
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findPublishedDocumentaryKeysByDurationOrderedByCommentsAsc($from, $to);
            $this->cacheHelper->saveToCache($key, $name, $documentaries);
        }
        return $documentaries;
    }

    public function findDocumentariesByTag(Tag $tag)
    {
        return $this->documentaryRepository->findDocumentariesByTag($tag);
    }

    public function findAll()
    {
        return $this->documentaryRepository->findAll();
    }

    public function findRelatedDocumentaries(Documentary $documentary)
    {
        $key = "related_slug_".$documentary->getSlug();
        $name = "documentaries";
        $documentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($documentaries == null) {
            $documentaries = $this->documentaryRepository->findRelatedDocumentaries($documentary);
            if ($documentaries != null) {
                $this->cacheHelper->saveToCache($key, $name, $documentaries);
            }
        }
        return $documentaries;
    }

    public function findLastUpdatedDocumentary()
    {
        return $this->documentaryRepository->findLastUpdatedDocumentary();
    }

    public function findLastUpdatedDocumentaryInCategory(Category $category)
    {
        return $this->documentaryRepository->findLastUpdatedDocumentaryInCategory($category);
    }
}