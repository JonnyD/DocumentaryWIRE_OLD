<?php

namespace DW\DWBundle\Repository;

use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\Tag;
use DW\DWBundle\Entity\Category;

interface DocumentaryRepository
{
    public function findDocumentaryById($id);
    public function findDocumentaryBySlug($slug);
    public function findDocumentaryByTruncatedSlug($slug);
    public function findPublishedDocumentaryKeysOrderedByTitleDesc();
    public function findPublishedDocumentaryKeysOrderedByTitleAsc();
    public function findPublishedDocumentaryKeysOrderedByDateDesc();
    public function findPublishedDocumentaryKeysOrderedByDateAsc();
    public function findPublishedDocumentaryKeysOrderedByViewsDesc();
    public function findPublishedDocumentaryKeysOrderedByViewsAsc();
    public function findPublishedDocumentaryKeysOrderedByLikesDesc();
    public function findPublishedDocumentaryKeysOrderedByLikesAsc();
    public function findPublishedDocumentaryKeysOrderedByCommentsDesc();
    public function findPublishedDocumentaryKeysOrderedByCommentsAsc();
    public function findPublishedDocumentaryKeysByCategoryOrderedByTitleDesc($categoryId);
    public function findPublishedDocumentaryKeysByCategoryOrderedByTitleAsc($categoryId);
    public function findPublishedDocumentaryKeysByCategoryOrderedByDateDesc($categoryId);
    public function findPublishedDocumentaryKeysByCategoryOrderedByDateAsc($categoryId);
    public function findPublishedDocumentaryKeysByCategoryOrderedByViewsDesc($categoryId);
    public function findPublishedDocumentaryKeysByCategoryOrderedByViewsAsc($categoryId);
    public function findPublishedDocumentaryKeysByCategoryOrderedByLikesDesc($categoryId);
    public function findPublishedDocumentaryKeysByCategoryOrderedByLikesAsc($categoryId);
    public function findPublishedDocumentaryKeysByCategoryOrderedByCommentsDesc($categoryId);
    public function findPublishedDocumentaryKeysByCategoryOrderedByCommentsAsc($categoryId);
    public function findPublishedDocumentaryKeysByYearOrderedByDateDesc($year);
    public function findPublishedDocumentaryKeysByYearOrderedByDateAsc($year);
    public function findPublishedDocumentaryKeysByYearOrderedByTitleDesc($year);
    public function findPublishedDocumentaryKeysByYearOrderedByTitleAsc($year);
    public function findPublishedDocumentaryKeysByYearOrderedByViewsDesc($year);
    public function findPublishedDocumentaryKeysByYearOrderedByViewsAsc($year);
    public function findPublishedDocumentaryKeysByYearOrderedByCommentsDesc($year);
    public function findPublishedDocumentaryKeysByYearOrderedByCommentsAsc($year);
    public function findPublishedDocumentaryKeysByYearOrderedByLikesDesc($year);
    public function findPublishedDocumentaryKeysByYearOrderedByLikesAsc($year);
    public function findPublishedDocumentaryKeysByDurationOrderedByDateDesc($from, $to);
    public function findPublishedDocumentaryKeysByDurationOrderedByDateAsc($from, $to);
    public function findPublishedDocumentaryKeysByDurationOrderedByTitleDesc($from, $to);
    public function findPublishedDocumentaryKeysByDurationOrderedByTitleAsc($from, $to);
    public function findPublishedDocumentaryKeysByDurationOrderedByViewsDesc($from, $to);
    public function findPublishedDocumentaryKeysByDurationOrderedByViewsAsc($from, $to);
    public function findPublishedDocumentaryKeysByDurationOrderedByCommentsDesc($from, $to);
    public function findPublishedDocumentaryKeysByDurationOrderedByCommentsAsc($from, $to);
    public function findPublishedDocumentaryKeysByDurationOrderedByLikesDesc($from, $to);
    public function findPublishedDocumentaryKeysByDurationOrderedByLikesAsc($from, $to);
    public function findFeaturedDocumentaries();
    public function findYears();
    public function findAmountOfDocumentariesByDuration($from, $to);
    public function findAmountOfDocumentariesWithNoDuration();
    public function findDocumentariesByTag(Tag $tag);
    public function findDocumentariesByCategory($categoryId, $status, $orderBy, $order, $max);
    public function findAll();
    public function findRelatedDocumentaries(Documentary $documentary);
    public function findLastUpdatedDocumentary();
    public function findLastUpdatedDocumentaryInCategory(Category $category);
}