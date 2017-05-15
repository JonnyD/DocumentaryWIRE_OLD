<?php

namespace DW\DWBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="DW\DWBundle\Repository\Doctrine\ORM\FollowRepository")
 * @ORM\Table(name="follow", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="follow_idx", columns={"follower_id", "following_id"})})
 *
 * @JMS\ExclusionPolicy("all")
 */
class Follow
{
    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @JMS\Expose
     * @JMS\Type("DW\DWBundle\Entity\User")
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="following", cascade="persist")
     * @ORM\JoinColumn(name="follower_id", referencedColumnName="id")
     */
    private $follower;

    /**
     * @JMS\Expose
     * @JMS\Type("DW\DWBundle\Entity\User")
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followers", cascade="persist")
     * @ORM\JoinColumn(name="following_id", referencedColumnName="id")
     */
    private $following;

    /**
     * @JMS\Expose
     * @JMS\Type("DateTime")
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * Set follower
     *
     * @param User $follower
     */
    public function setFollower(User $follower)
    {
        if ($this->follower == null) {
            $this->follower = $follower;
            $follower->addFollowing($this);
        }
    }

    /**
     * Get follower
     *
     * @return User
     */
    public function getFollower()
    {
        return $this->follower;
    }

    /**
     * Set following
     *
     * @param User $following
     */
    public function setFollowing(User $following)
    {
        if ($this->following == null) {
            $this->following = $following;
            $following->addFollower($this);
        }
    }

    /**
     * Get following
     *
     * @return User
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

}