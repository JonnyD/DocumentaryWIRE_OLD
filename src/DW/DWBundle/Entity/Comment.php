<?php

namespace DW\DWBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="DW\DWBundle\Repository\Doctrine\ORM\CommentRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="comment")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Comment
{
	/**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
     * @JMS\Expose
     * @JMS\Type("DateTime")
     *
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/**
     * @JMS\Expose
     * @JMS\Type("string")
     *
	 * @ORM\Column(type="text")
	 */
	protected $comment;

	/**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
	 * @ORM\Column(type="integer")
	 */
	protected $status;

    /**
     * @JMS\Expose
     * @JMS\Type("DW\DWBundle\Entity\Comment")
     *
     * @ORM\ManyToOne(targetEntity="Comment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
	protected $parent;

    /**
     *
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="parent")
     **/
    private $children;

	/**
     * @JMS\Expose
     * @JMS\Type("DW\DWBundle\Entity\User")
     *
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="comments", cascade="persist", fetch="EAGER")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $user;

	/**
     * @JMS\Expose
     * @JMS\Type("DW\DWBundle\Entity\Documentary")
     *
	 * @ORM\ManyToOne(targetEntity="Documentary", inversedBy="comments", cascade="persist")
	 * @ORM\JoinColumn(name="documentary_id", referencedColumnName="id")
	 */
	protected $documentary;

	/**     *
	 * @ORM\OneToMany(targetEntity="Vote", mappedBy="comment", cascade="persist")
	 */
	protected $votes;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $points = 0;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $author;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $email;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $disqusParentId;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $disqusId;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Column(type="integer", name="old_comment_id", nullable=true)
     */
    protected $oldCommentId;

    /**
	 * Constructor
	 */
	public function __construct()
	{
		$this->votes = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     * @return Comment
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
     * Set comment
     *
     * @param string $comment
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Comment
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set parent
     *
     * @param Comment $parent
     * @return Comment
     */
    public function setParent(Comment $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent
     *
     * @return Comment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Has child
     *
     * @param Comment $comment
     */
    public function hasChild(Comment $comment)
    {
        return $this->children->contains($comment);
    }

    /**
     * Add child
     *
     * @param Comment $comment
     */
    public function addChild(Comment $comment)
    {
        if (!$this->hasChild($comment)) {
            $this->children->add($comment);
            $comment->setParent($this);
        }
    }

    /**
     * Remove child
     *
     * @param Comment $comment
     */
    public function removeChild(Comment $comment)
    {
        if ($this->hasChild($comment)) {
            $this->children->removeElement($comment);
            $comment->setParent(null);
        }
    }

    /**
     * Has children
     *
     * @return boolean
     */
    public function hasChildren()
    {
        return $this->children->count() > 0;
    }

    /**
     * Get children
     *
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set documentary
     *
     * @param Documentary $documentary
     */
    public function setDocumentary(Documentary $documentary = null)
    {
        if ($this->documentary == null) {
            $this->documentary = $documentary;
            $documentary->addComment($this);
        }
    }

    /**
     * Get documentary
     *
     * @return \DW\DWBundle\Entity\Documentary 
     */
    public function getDocumentary()
    {
        return $this->documentary;
    }

    /**
     * Set user
     *
     * @param \DW\DWBundle\Entity\User $user
     * @return Comment
     */
    public function setUser(\DW\DWBundle\Entity\User $user = null)
    {
        if ($this->user == null) {
            $this->user = $user;
            $user->addComment($this);
        }
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \DW\DWBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add votes
     *
     * @param \DW\DWBundle\Entity\Vote $votes
     * @return Comment
     */
    public function addVote(\DW\DWBundle\Entity\Vote $votes)
    {
        $this->votes[] = $votes;
    
        return $this;
    }

    /**
     * Remove votes
     *
     * @param \DW\DWBundle\Entity\Vote $votes
     */
    public function removeVote(\DW\DWBundle\Entity\Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * Get points
     *
     * @return integer
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set points
     *
     * @param integer $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * Add points
     *
     * @param integer $points
     */
    public function addPoints($points)
    {
        $this->points = $this->points + ($points);
    }

    /**
     * Remove points
     *
     * @param integer $points
     */
    public function removePoints($points)
    {
        $this->points -= $points;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->documentary->incrementCommentCount();
    }

    /**
     * @ORM\PreRemove
     */
    public function onPreRemove()
    {
        $this->documentary->decrementCommentCount();
    }

    /**
     * Set author
     *
     * @param String $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        if ($this->user != null) {
            return $this->user->getAvatar();
        }
        return "";
    }

    /**
     * Get author
     *
     * @return String
     */
    public function getAuthor()
    {
        if ($this->user != null) {
            return $this->user->getUsername();
        }
        return $this->author;
    }

    /**
     * Set email
     *
     * @param String $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set disqusParentId
     *
     * @param integer $disqusParentId
     */
    public function setDisqusParentId($disqusParentId)
    {
        $this->disqusParentId = $disqusParentId;
    }

    /**
     * Get disqusParentId
     *
     * @return integer
     */
    public function getDisqusParentId()
    {
        return $this->disqusParentId;
    }

    /**
     * Set disqusId
     *
     * @param integer $disqusId
     */
    public function setDisqusId($disqusId)
    {
        $this->disqusId = $disqusId;
    }

    /**
     * Get disqusId
     *
     * @return integer
     */
    public function getDisqusId()
    {
        return $this->disqusId;
    }

    /**
     * Set oldCommentId
     *
     * @param integer $oldCommentId
     */
    public function setOldCommentId($oldCommentId)
    {
        $this->oldCommentId = $oldCommentId;
    }

    /**
     * Get oldCommentId
     *
     * @return integer
     */
    public function getOldCommentId()
    {
        return $this->oldCommentId;
    }
}