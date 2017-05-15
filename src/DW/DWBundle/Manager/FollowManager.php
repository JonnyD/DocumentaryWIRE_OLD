<?php
namespace DW\DWBundle\Manager;

use DW\DWBundle\Entity\Follow;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Repository\Doctrine\ORM\FollowRepository;

class FollowManager
{
    private $followRepository;

    public function __construct(FollowRepository $followRepository)
    {
        $this->followRepository = $followRepository;
    }

    public function getFollowByFollowerAndFollowing(User $follower, User $following)
    {
        return $this->followRepository->findOneBy(
            array('follower' => $follower,
            'following' => $following)
        );
    }

    public function getFollowById($id)
    {
        return $this->followRepository->find($id);
    }

    public function createFollow(User $follower, User $following)
    {
        $follow = new Follow();
        $follow->setFollower($follower);
        $follow->setFollowing($following);
        $follow->setCreated(new \DateTime());
        return $follow;
    }

    public function followUser(User $follower, User $following)
    {
        $follow = $this->createFollow($follower, $following);
        $this->followRepository->save($follow);
        return $follow;
    }

    public function unfollowUser(User $follower, User $following)
    {
        $follow = $this->getFollowByFollowerAndFollowing($follower, $following);
        $this->followRepository->remove($follow);
    }

    public function getFollowersByUser(User $user)
    {
        return $this->followRepository->findBy(
            array('following' => $user)
        );
    }

    public function getFollowingByUser(User $user)
    {
        return $this->followRepository->findBy(
            array('follower' => $user)
        );
    }


}