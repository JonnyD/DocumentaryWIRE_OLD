<?php

namespace DW\DWBundle\Manager;

use Doctrine\ORM\EntityManager;
use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\Like;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Event\LikeEvent;
use DW\DWBundle\Event\LikeEvents;
use DW\DWBundle\Helper\CacheHelper;
use DW\DWBundle\Repository\LikeRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LikeManager
{
    private $likeRepository;
    private $eventDispatcher;

    public function __construct(LikeRepository $likeRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->likeRepository = $likeRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createLike(User $user, Documentary $documentary)
    {
        $like = new Like();
        $like->setUser($user);
        $like->setDocumentary($this->entityManager->merge($documentary));
        $like->setCreated(new \DateTime());
        return $like;
    }

    public function getAllLikes()
    {
        return $this->likeRepository->findAll();
    }

    public function getLikeByUserAndDocumentary(User $user, Documentary $documentary)
    {
        return $this->likeRepository->findLikeByUserAndDocumentary($user, $documentary);
    }

    public function getLikedDocumentariesByUser(User $user)
    {
        return $this->likeRepository->findLikesByUser($user);
    }

    public function hasLiked(User $user, $documentarySlug)
    {
        $likedDocumentaries = $this->getLikedDocumentariesByUser($user);
        return array_key_exists($documentarySlug, $likedDocumentaries);
    }

    public function getLikesByDocumentary(Documentary $documentary)
    {
        return $this->likeRepository->findLikesByDocumentary($documentary);
    }

    public function likeDocumentary(User $user, Documentary $documentary)
    {
        $like = $this->createLike($user, $documentary);
        $this->likeRepository->save($like);
        $this->eventDispatcher->dispatch(
            LikeEvents::DOCUMENTARY_LIKED,
            new LikeEvent($like)
        );
        return $like;
    }

    public function unlikeDocumentaryByUserAndDocumentary(User $user, Documentary $documentary)
    {
        $this->likeRepository->deleteLikeByUserAndDocumentary($user, $documentary);
    }

    public function unlikeDocumentary(Like $like)
    {
        $this->likeRepository->remove($like);
    }
}