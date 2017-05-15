<?php

namespace DW\DWBundle\EventListener;

use DW\DWBundle\Cache\ActivityCache;
use DW\DWBundle\Cache\CategoryCache;
use DW\DWBundle\Cache\DocumentaryCache;
use DW\DWBundle\Cache\LikeCache;
use DW\DWBundle\Cache\UserCache;
use DW\DWBundle\Cache\YearCache;
use DW\DWBundle\Event\ActivityEvent;
use DW\DWBundle\Event\CommentEvent;
use DW\DWBundle\Event\CommentEvents;
use DW\DWBundle\Event\DocumentaryEvent;
use DW\DWBundle\Event\DocumentaryEvents;
use DW\DWBundle\Event\LikeEvent;
use DW\DWBundle\Event\LikeEvents;
use DW\DWBundle\Event\UserEvent;
use DW\DWBundle\Event\UserEvents;
use DW\DWBundle\Helper\CacheHelper;
use DW\DWBundle\Manager\CommentManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InvalidateCacheListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            DocumentaryEvents::NEW_DOCUMENTARY_ADDED => "onDocumentaryAdded",
            LikeEvents::DOCUMENTARY_LIKED => "onDocumentaryLiked",
            UserEvents::USER_JOINED => "onUserJoined",
            CommentEvents::DOCUMENTARY_COMMENT_ADDED => "onCommentAdded"
        );
    }

    public function __construct(CacheHelper $cacheHelper)
    {
        $this->cacheHelper = $cacheHelper;
    }

    public function onDocumentaryAdded(DocumentaryEvent $documentaryEvent)
    {
        $documentary = $documentaryEvent->getDocumentary();
        $user = $documentary->getAddedBy();
        $username = $user->getUsername();
        $category = $documentary->getCategory();
        $categoryId = $category->getId();
        $categorySlug = $category->getSlug();
        $year = $documentary->getYear();

        $this->cacheHelper->deleteFromCache(UserCache::KEY_USERNAME."_".$username, UserCache::CACHE_USERS);

        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_FEATURED, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_COMMENTS_ASC, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_COMMENTS_DESC, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_DATE_ASC, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_DATE_DESC, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_LIKES_ASC, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_LIKES_DESC, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_VIEWS_ASC, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_VIEWS_DESC, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_TITLE_ASC, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_TITLE_DESC, DocumentaryCache::CACHE_DOCUMENTARIES);

        $this->cacheHelper->deleteFromCache(CategoryCache::KEY_LIST, CategoryCache::CACHE_CATEGORY);
        $this->cacheHelper->deleteFromCache(CategoryCache::KEY_LIST_WITH_DOCUMENTARIES, CategoryCache::CACHE_CATEGORY);
        $this->cacheHelper->deleteFromCache(CategoryCache::KEY_SLUG."_".$categorySlug, CategoryCache::CACHE_CATEGORY);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_COMMENTS_ASC."_".$categoryId, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_COMMENTS_DESC."_".$categoryId, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_DATE_ASC."_".$categoryId, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_DATE_DESC."_".$categoryId, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_LIKES_ASC."_".$categoryId, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_LIKES_DESC."_".$categoryId, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_VIEWS_ASC."_".$categoryId, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_VIEWS_DESC."_".$categoryId, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_TITLE_ASC."_".$categoryId, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_TITLE_DESC."_".$categoryId, DocumentaryCache::CACHE_DOCUMENTARIES);

        $this->cacheHelper->deleteFromCache(YearCache::KEY_LIST, YearCache::CACHE_YEAR);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_COMMENTS_ASC."_".$year, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_COMMENTS_DESC."_".$year, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_DATE_ASC."_".$year, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_DATE_DESC."_".$year, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_LIKES_ASC."_".$year, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_LIKES_DESC."_".$year, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_VIEWS_ASC."_".$year, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_VIEWS_DESC."_".$year, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_TITLE_ASC."_".$year, DocumentaryCache::CACHE_DOCUMENTARIES);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_PUBLISHED_TITLE_DESC."_".$year, DocumentaryCache::CACHE_DOCUMENTARIES);
    }

    public function onDocumentaryLiked(LikeEvent $likeEvent)
    {
        $like = $likeEvent->getLike();
        $documentaryId = $like->getDocumentaryId();
        $documentarySlug = $like->getDocumentarySlug();
        $user = $like->getUser();
        $userId = $user->getId();
        $username = $user->getUsername();

        $this->cacheHelper->deleteFromCache(UserCache::KEY_USERNAME."_".$username, UserCache::CACHE_USERS);
        $this->cacheHelper->deleteFromCache(LikeCache::KEY_USER_ID."_".$userId, LikeCache::CACHE_LIKES);

        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_DOCUMENTARY_ID."_".$documentaryId,
            DocumentaryCache::CACHE_DOCUMENTARY);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_DOCUMENTARY_SLUG."_".$documentarySlug,
            DocumentaryCache::CACHE_DOCUMENTARY);
    }

    public function onUserJoined(UserEvent $userEvent)
    {
        $this->cacheHelper->deleteFromCache(UserCache::KEY_LATEST, UserCache::CACHE_USERS);
    }

    public function onUserLogin(UserEvent $userEvent)
    {
        $this->cacheHelper->deleteFromCache(UserCache::KEY_ACTIVE, UserCache::CACHE_USERS);
    }

    public function onCommentAdded(CommentEvent $commentEvent)
    {
        $comment = $commentEvent->getComment();
        $documentary = $comment->getDocumentary();
        $documentaryId = $documentary->getId();
        $documentarySlug = $documentary->getSlug();
        $user = $comment->getUser();
        $username = $user->getUsername();

        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_DOCUMENTARY_ID."_".$documentaryId,
            DocumentaryCache::CACHE_DOCUMENTARY);
        $this->cacheHelper->deleteFromCache(DocumentaryCache::KEY_DOCUMENTARY_SLUG."_".$documentarySlug,
            DocumentaryCache::CACHE_DOCUMENTARY);
        $this->cacheHelper->deleteFromCache(UserCache::KEY_USERNAME."_".$username, UserCache::CACHE_USERS);
    }

    public function onActivityAdded(ActivityEvent $activityEvent)
    {
        $activity = $activityEvent->getActivity();
        $user = $activity->getUser();
        $userId = $user->getId();

        $this->cacheHelper->deleteFromCache(ActivityCache::KEY_USER_ID."_".$userId, ActivityCache::CACHE_ACTIVITY);
        $this->cacheHelper->deleteFromCache(ActivityCache::KEY_ALL_WIDGET, ActivityCache::CACHE_ACTIVITY);
        $this->cacheHelper->deleteFromCache(ActivityCache::KEY_COMMENTS_WIDGET, ActivityCache::CACHE_ACTIVITY);
        $this->cacheHelper->deleteFromCache(ActivityCache::KEY_LIKES_WIDGET, ActivityCache::CACHE_ACTIVITY);

    }
}