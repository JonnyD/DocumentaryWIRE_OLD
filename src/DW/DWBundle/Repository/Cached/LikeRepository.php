<?php

namespace DW\DWBundle\Repository\Cached;

use DW\DWBundle\Cache\LikeCache;
use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\Like;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Helper\CacheHelper;
use DW\DWBundle\Repository\LikeRepository as LikeRepositoryInterface;
use DW\DWBundle\Repository\Doctrine\ORM\LikeRepository as LikeRepositoryDoctrine;

class LikeRepository extends CustomRepository implements LikeRepositoryInterface
{
    private $likeRepository;
    private $cacheHelper;

    public function __construct(LikeRepositoryDoctrine $likeRepository, CacheHelper $cacheHelper)
    {
        parent::__construct($likeRepository);

        $this->likeRepository = $likeRepository;
        $this->cacheHelper = $cacheHelper;
    }

    public function remove(Like $like)
    {
        $this->likeRepository->remove($like);
        $this->cacheHelper->deleteFromCache('user_id_'.$like->getUserId(), 'likes');
        $this->cacheHelper->deleteFromCache('documentary_slug_'.$like->getDocumentarySlug(), 'documentary');
    }

    public function findAllLikes()
    {
        return $this->likeRepository->findAll();
    }

    public function deleteLikeByUserAndDocumentary(User $user, Documentary $documentary)
    {
        $this->likeRepository->deleteLikeByUserAndDocumentary($user, $documentary);
        $this->cacheHelper->deleteFromCache('user_id_'.$user->getId(), 'likes');
        $this->cacheHelper->deleteFromCache('documentary_slug_'.$documentary->getSlug(), 'documentary');
    }

    public function findLikesByUser(User $user)
    {
        $key = LikeCache::KEY_USER_ID."_".$user->getId();
        $name = LikeCache::CACHE_LIKES;

        $likedDocumentaries = $this->cacheHelper->getFromCache($key, $name, "array");
        if ($likedDocumentaries == null && !is_array($likedDocumentaries)) {
            $likes = $this->likeRepository->findLikesByUser($user);

            $likedDocumentaries = array();
            foreach ($likes as $like) {
                $documentary = $like->getDocumentary();
                $slug = $documentary->getSlug();
                $likedDocumentaries[$slug] = $like;
            }

            $this->cacheHelper->saveToCache($key, $name, $likedDocumentaries);
        }

        return $likedDocumentaries;
    }

    public function findLikeByUserAndDocumentary(User $user, Documentary $documentary)
    {
        return $this->likeRepository->findLikeByUserAndDocumentary($user, $documentary);
    }

    public function findLikesByDocumentary(Documentary $documentary)
    {
        return $this->likeRepository->findLikesByDocumentary($documentary);
    }
}