<?php

namespace DW\DWBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Eko\FeedBundle\Item\Writer\ItemInterface;
use Eko\FeedBundle\Item\Writer\RoutedItemInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="DW\DWBundle\Repository\Doctrine\ORM\DocumentaryRepository")
 * @ORM\Table(name="documentary", uniqueConstraints={
 *              @ORM\UniqueConstraint(name="slug_idx", columns={"slug"})})
 * @ORM\HasLifecycleCallbacks
 *
 * @JMS\ExclusionPolicy("all")
 */
class Documentary implements RoutedItemInterface
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
     * @JMS\Type("string")
     *
	 * @ORM\Column(type="string", nullable=false)
	 */
	protected $title;
	
	/**
     * @JMS\Expose
     * @JMS\Type("string")
     *
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $description;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="seo_title", type="string", nullable=true)
     */
    protected $seoTitle;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @Assert\Length(min=1, max=160)
     * @ORM\Column(name="seo_description", type="text", nullable=true)
     */
    protected $seoDescription;

	/**
     * @JMS\Expose
     * @JMS\Type("string")
     *
	 * @Gedmo\Slug(fields={"title"}, updatable=false)
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $slug;
	
	/**
     * @JMS\Expose
     * @JMS\Type("DateTime")
     *
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $created;
	
	/**
     * @JMS\Expose
     * @JMS\Type("DateTime")
     *
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $updated;
	
	/**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @Assert\Length(min=1, max=160)
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $excerpt;
	
	/**
     * @JMS\Expose
     * @JMS\Type("string")
     *
	 * @ORM\Column(type="string", nullable=false)
	 */
	protected $status = "publish";
	
	/**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $views = 0;
	
	/**
     * @JMS\Expose
     * @JMS\Type("string")
     *
	 * @ORM\Column(name="video_source", type="string", nullable=true)
	 */
	protected $videoSource;
	
	/**
     * @JMS\Expose
     * @JMS\Type("string")
     *
	 * @ORM\Column(name="video_id", type="text", nullable=true)
	 */
	protected $videoId;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="short_url", type="string", nullable=true)
     */
    protected $shortUrl;

	/**
     * @JMS\Expose
     * @JMS\Type("string")
     *
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $thumbnail;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $oldThumbnail;
	
	/**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $length;

	/**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $likeCount = 0;

	/**
	 * @ORM\OneToMany(targetEntity="Comment", mappedBy="documentary", cascade="persist")
	 */
	protected $comments;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Column(type="integer", name="comment_count")
     */
    protected $commentCount = 0;

	/**
     * @JMS\Expose
     * @JMS\Type("DW\DWBundle\Entity\Category")
     *
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="documentaries", cascade="persist")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 */
	protected $category;

    /**
     * @ORM\OneToMany(targetEntity="Like", mappedBy="documentary", cascade="persist")
     */
    protected $likes;

    /**
     * @ORM\OneToMany(targetEntity="Video", mappedBy="documentary", cascade="persist")
     */
    protected $videos;
	
	/**
	 * @Assert\File(maxSize="6000000")
	 */
	private $file;
	
	private $temp;

    /**
     * @JMS\Expose
     * @JMS\Type("boolean")
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $featured = false;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="featured_image", type="string", nullable=true)
     */
    private $featuredImage;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Column(name="featured_order", type="integer", nullable=true)
     */
    private $featuredOrder;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(name="documentary_tag",
     *      joinColumns={@ORM\JoinColumn(name="documentary_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    private $tags;

    /**
     * @JMS\Expose
     * @JMS\Type("DW\DWBundle\Entity\User")
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="addedDocumentaries", cascade="persist")
     * @ORM\JoinColumn(name="added_by", referencedColumnName="id")
     */
    private $addedBy;

    /**
	 * Constructor
	 */
	public function __construct()
	{
		$this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return Documentary
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Documentary
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set seoTitle
     *
     * @param string $seoTitle
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;
    }

    /**
     * Get seoTitle
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * Set seoDescription
     *
     * @param string $seoDescription
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;
    }

    /**
     * Get seoDescription
     *
     * @return string
     */
    public function getSeoDescription()
    {
        $tempSeoDescription = $this->seoDescription;
        if (strlen($this->seoDescription > 159)) {
            $tempSeoDescription = substr($this->seoDescription, 0, 159);
        }
        return $tempSeoDescription;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Documentary
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Documentary
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set excerpt
     *
     * @param string $excerpt
     * @return Documentary
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
    
        return $this;
    }

    /**
     * Get excerpt
     *
     * @return string 
     */
    public function getExcerpt()
    {
        $excerpt = $this->excerpt;
        if ($excerpt != null || !empty($excerpt)) {
            $excerpt = strip_tags($this->excerpt);
            $excerpt = substr($excerpt, 0, 160);
        } else {
            $description = strip_tags($this->description);
            $excerpt = substr($description, 0, 160);
        }
        return $excerpt;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Documentary
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return Documentary
     */
    public function setViews($views)
    {
        $this->views = $views;
    
        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Increment views
     */
    public function incrementViews()
    {
        $this->views++;
    }

    /**
     * Set videoSource
     *
     * @param string $videoSource
     * @return Documentary
     */
    public function setVideoSource($videoSource)
    {
        $this->videoSource = $videoSource;
    
        return $this;
    }

    /**
     * Get videoSource
     *
     * @return string 
     */
    public function getVideoSource()
    {
        return $this->videoSource;
    }

    /**
     * Set videoId
     *
     * @param string $videoId
     * @return Documentary
     */
    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
    
        return $this;
    }

    /**
     * Get videoId
     *
     * @return string 
     */
    public function getVideoId()
    {
        return $this->videoId;
    }

    /**
     * Set shortUrl
     *
     * @param string $shortUrl
     */
    public function setShortUrl($shortUrl)
    {
        $this->shortUrl = $shortUrl;
    }

    /**
     * Get shortUrl
     *
     * @return string
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
    }

    /**
     * Set thumbnail
     *
     * @param string $thumbnail
     * @return Documentary
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    
        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return string 
     */
    public function getThumbnail()
    {
        $path = $this->getUploadDir();
        if ($this->thumbnail == null) {
            $path = $path."nothumb.png";
        } else {
            $path = $path.$this->thumbnail;
        }
        return $path;
    }

    /**
     * Set oldThumbnail
     *
     * @param string $oldThumbnail
     */
    public function setOldThumbnail($oldThumbnail)
    {
        $this->oldThumbnail = $oldThumbnail;
    }

    /**
     * Get oldThumbnail
     *
     * @return string
     */
    public function getOldThumbnail()
    {
        return $this->oldThumbnail;
    }

    /**
     * Set length
     *
     * @param integer $length
     * @return Documentary
     */
    public function setLength($length)
    {
        $this->length = $length;
    
        return $this;
    }

    /**
     * Get length
     *
     * @return integer 
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Has comment
     *
     * @param Comment $comment
     * @return boolean
     */
    public function hasComment(Comment $comment = null)
    {
        return $this->comments->contains($comment);
    }

    /**
     * Add comments
     *
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        if (!$this->hasComment($comment)) {
            $this->comments->add($comment);
            $comment->setDocumentary($this);
        }
    }

    /**
     * Remove comments
     *
     * @param \DW\DWBundle\Entity\Comment $comments
     */
    public function removeComment(\DW\DWBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Set comments
     *
     * @param ArrayCollection
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Remove category
     */
    public function removeCategory()
    {
        $this->category = null;
    }

    /**
     * Set category
     *
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Has like
     *
     * @param Like $like
     * @return boolean
     */
    public function hasLike(Like $like)
    {
        return $this->likes->contains($like);
    }

    /**
     * Add like
     *
     * @param Like $like
     */
    public function addLike(Like $like)
    {
        if (!$this->hasLike($like)) {
            $this->likes->add($like);
            $like->setDocumentary($this);
        }
    }

    /**
     * Remove like
     *
     * @param Like $like
     */
    public function removeLike(Like $like)
    {
        if ($this->hasLike($like)) {
            $this->likes->removeElement($like);
        }
    }

    /**
     * Get likes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set likeCount
     *
     * @param integer $likeCount
     */
    public function setLikeCount($likeCount)
    {
        $this->likeCount = $likeCount;
    }

    /**
     * Get likeCount
     *
     * @return integer
     */
    public function getLikeCount()
    {
        return $this->likeCount;
    }

    /**
     * Increment likeCount
     */
    public function incrementLikeCount()
    {
        $this->likeCount++;
    }

    /**
     * Decrement likeCount
     */
    public function decrementLikeCount()
    {
        $this->likeCount--;
    }

    public function getFile()
    {
    	return $this->file;
    }
  
    public function getWebPath()
    {
    	return null === $this->thumbnail
    	? null
    	: $this->getUploadDir().'/'.$this->thumbnail;
    }
    
    protected function getUploadRootDir()
    {
    	// the absolute directory path where uploaded
    	// documents should be saved
    	return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    
    protected function getUploadDir()
    {
    	// get rid of the __DIR__ so it doesn't screw up
    	// when displaying uploaded doc/image in the view.
    	return 'cover/';
    }
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
    	$this->file = $file;
    	// check if we have an old image path
    	if (is_file($this->getAbsolutePath())) {
    		// store the old name to delete after the update
    		$this->temp = $this->getAbsolutePath();
    	} else {
    		$this->thumbnail = 'initial';
    	}
    }
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
    	if (null !== $this->getFile()) {
    		$this->thumbnail = $this->slug . '.' . $this->getFile()->guessExtension();
    	}
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
    	if (null === $this->getFile()) {
    		return;
    	}
    	
    	// check if we have an old image
    	if (isset($this->temp)) {
    		// delete the old image
    		unlink($this->temp);
    		// clear the temp image path
    		$this->temp = null;
    	}
    
    	// you must throw an exception here if the file cannot be moved
    	// so that the entity is not persisted to the database
    	// which the UploadedFile move() method doe
    	
    	$this->getFile()->move(
    			$this->getUploadRootDir(),
    			$this->slug.'.'.$this->getFile()->guessExtension()
    	);
    
    	$this->setFile(null);
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
    	$this->temp = $this->getAbsolutePath();
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
    	if (isset($this->temp)) {
    		unlink($this->temp);
    	}
    }
    
    public function getAbsolutePath()
    {
    	return null === $this->thumbnail
    	? null
    	: $this->getUploadRootDir().'/'.$this->slug.'.'.$this->thumbnail;
    }

    /**
     * Set commentCount
     *
     * @param integer $commentCount
     * @return Documentary
     */
    public function setCommentCount($commentCount)
    {
        $this->commentCount = $commentCount;
    
        return $this;
    }

    /**
     * Get commentCount
     *
     * @return integer 
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }

    /**
     * Increment commentCount
     */
    public function incrementCommentCount()
    {
        $this->commentCount++;
    }

    /**
     * Decrement commentCount
     */
    public function decrementCommentCount()
    {
        $this->commentCount--;
    }

    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(PreUpdateEventArgs $args)
    {
        if ($args->hasChangedField('category')) {
            $oldCategory = $args->getOldValue('category');
            $newcategory = $args->getNewValue('category');
        }
    }

    /**
     * @ORM\PrePersist
     */
    public function onPostPersist()
    {
        $this->category->incrementCount();
    }

    /**
     * @ORM\PreRemove
     */
    public function onPreRemove()
    {
        $this->category->decrementCount();
    }

    /**
     * Set videos
     *
     * @param ArrayCollection $videos
     */
    public function setVideos($videos)
    {
        $this->videos = $videos;
    }

    /**
     * Get videos
     *
     * @return ArrayCollection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Has video
     *
     * @param Video $video
     * @return boolean
     */
    public function hasVideo(Video $video)
    {
        return $this->videos->contains($video);
    }

    /**
     * Add video
     *
     * @param Video $video
     */
    public function addVideo(Video $video)
    {
        if (!$this->hasVideo($video)) {
            $this->videos->add($video);
            $video->setDocumentary($this);
        }
    }

    /**
     * Remove video
     *
     * @param Video $video
     */
    public function removeVideo(Video $video)
    {
        if ($this->hasVideo($video)) {
            $this->videos->removeElement($video);
            $video->removeDocumentary();
        }
    }

    /**
     * Set featured
     *
     * @param boolean $featured
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
    }

    /**
     * Get featured
     *
     * @return boolean
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Set featuredImage
     *
     * @param String $featuredImage
     */
    public function setFeaturedImage($featuredImage)
    {
        $this->featuredImage = $featuredImage;
    }

    /**
     * Get featuredImage
     *
     * @return String
     */
    public function getFeaturedImage()
    {
        $directory = 'cover/';
        if ($this->featuredImage == null) {
            return $directory.$this->thumbnail;
        }
        return $directory.$this->featuredImage;
    }

    /**
     * Set featuredOrder
     *
     * @param integer $featuredOrder
     */
    public function setFeaturedOrder($featuredOrder)
    {
        $this->featuredOrder = $featuredOrder;
    }

    /**
     * Get featuredOrder
     *
     * @return integer
     */
    public function getFeaturedOrder()
    {
        return $this->featuredOrder;
    }

    /**
     * Set year
     *
     * @param integer $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set tags
     *
     * @param ArrayCollection $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * Has Tag
     *
     * @param Tag $tag
     * @return boolean
     */
    public function hasTag(Tag $tag)
    {
        return $this->tags->contains($tag);
    }

    /**
     * Add tag
     *
     * @param Tag $tag
     */
    public function addTag(Tag $tag)
    {
        if (!$this->hasTag($tag)) {
            $this->tags->add($tag);
            $tag->addDocumentary($this);
        }
    }

    /**
     * Remove tag
     *
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        if ($this->hasTag($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeDocumentary($this);
        }
    }

    /**
     * Get tags
     *
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function getFeedItemTitle()
    {
        return $this->title;
    }

    /**
     * Set addedBy
     *
     * @param User $addedBy
     */
    public function setAddedBy($addedBy)
    {
        if ($this->addedBy == null) {
            $this->addedBy = $addedBy;
            $addedBy->addAddedDocumentary($this);
        }
    }

    /**
     * Get addedBy
     *
     * @return User
     */
    public function getAddedBy()
    {
        return $this->addedBy;
    }

    public function getFeedItemDescription()
    {
        return $this->getExcerpt();
    }

    public function getFeedItemPubDate()
    {
        return $this->created;
    }

    public function getFeedItemRouteName()
    {
        return 'documentary_wire_show_documentary';
    }

    public function getFeedItemRouteParameters()
    {
        return array("slug" => $this->slug);
    }

    public function getFeedItemUrlAnchor()
    {
        return "";
    }
}