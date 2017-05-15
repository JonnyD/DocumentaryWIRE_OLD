<?php

namespace DW\DWBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DW\DWBundle\Entity\Comment;
use DW\DWBundle\Entity\User;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="DW\DWBundle\Repository\Doctrine\ORM\VoteRepository")
 * @ORM\Table(name="vote", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="vote_idx", columns={"user_id", "comment_id"})}))
 *
 * @JMS\ExclusionPolicy("all")
 */
class Vote
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
     * @JMS\Type("DW\DWBundle\Entity\Comment")
     *
	 * @ORM\ManyToOne(targetEntity="Comment", inversedBy="votes", cascade="persist")
	 * @ORM\JoinColumn(name="comment_id", referencedColumnName="id")
	 */
	private $comment;
	
	/**
     * @JMS\Expose
     * @JMS\Type("DW\DWBundle\Entity\User")
     *
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="votes", cascade="persist")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
	 */
	private $user;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $value;

    /**
     * @JMS\Expose
     * @JMS\Type("DateTime")
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;
	
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
     * Set comment
     *
     * @param Comment $comment
     * @return Vote
     */
    public function setComment(Comment $comment = null)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set user
     *
     * @param User $user
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value
     *
     * @param integer $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Documentary
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
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

    /**
     * Get voteType
     *
     * @return string
     */
    public function getVoteType()
    {
        if ($this->value == 1) {
            return VoteType::UPVOTE;
        } else if ($this->value == -1) {
            return VoteType::DOWNVOTE;
        } else if($this->value == 0) {
            return VoteType::NEUTRAL;
        }
    }
}