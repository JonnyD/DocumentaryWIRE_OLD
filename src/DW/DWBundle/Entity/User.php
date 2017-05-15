<?php

namespace DW\DWBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="DW\DWBundle\Repository\Doctrine\ORM\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user", indexes={
 *      @ORM\Index(name="username_idx", columns={"username", "username"}),
 *      @ORM\Index(name="email_idx", columns={"email", "email"})
 * })
 *
 * @JMS\ExclusionPolicy("all")
 */
class User implements UserInterface, \Serializable, EquatableInterface
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
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(groups={"registration"}, min=4, max=15)
     *
     * @JMS\Expose
     * @JMS\Type("string");
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $username;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="username_canonical", type="string", nullable=true, unique=true)
     */
    protected $usernameCanonical;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @Assert\Length(groups={"registration"}, min=6, max=40)
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="reset_key", type="string", length=255, nullable=true)
     */
    protected $resetKey;

    /**
     * @JMS\Expose
     * @JMS\Type("DateTime")
     *
     * @ORM\Column(name="reset_request_time", type="datetime", nullable=true)
     */
    protected $resetRequest;

    /**
     * @JMS\Expose
     * @JMS\Type("DateTime")
     *
     * @ORM\Column(name="last_reset_time", type="datetime", nullable=true)
     */
    protected $lastReset;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $email;

    /**
     * @JMS\Expose
     * @JMS\Type("DateTime")
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $activated;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="activation_key", type="string", length=255, nullable=true)
     */
    protected $activationKey;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="email_canonical", type="string", nullable=true, unique=true)
     */
    protected $emailCanonical;

    /**
     * @JMS\Expose
     * @JMS\Type("DateTime")
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $registered;

    /**
     * @JMS\Expose
     * @JMS\Type("DateTime")
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastActive;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(type="string")
     */
    protected $displayName;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    private $temp;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Column(type="integer")
     */
    protected $status = 1;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user", cascade="persist")
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="user", cascade="persist")
     */
    protected $votes;

    /**
     * @ORM\OneToMany(targetEntity="Follow", mappedBy="follower", cascade="persist")
     * @ORM\OrderBy({"created" = "DESC"})
     */
    protected $following;

    /**
     * @ORM\OneToMany(targetEntity="Follow", mappedBy="following", cascade="persist")
     * @ORM\OrderBy({"created" = "DESC"})
     */
    protected $followers;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="user", cascade={"persist", "merge"})
     */
    protected $activity;

    /**
     * @ORM\OneToMany(targetEntity="Like", mappedBy="user", cascade="persist")
     * @ORM\OrderBy({"created" = "DESC"})
     */
    protected $likes;

    /**
     * @ORM\ManyToMany(targetEntity="Documentary")
     * @ORM\JoinTable(name="Likes",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="documentary_id", referencedColumnName="id")}
     *      )
     */
    protected $likedDocumentaries;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebookId;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebookAccessToken;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="facebook_avatar_full", type="string", nullable=true)
     */
    protected $facebookAvatarFull;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="facebook_avatar_thumb", type="string", nullable=true)
     */
    protected $facebookAvatarThumb;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $facebookAvatar2;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="user_role")
     */
    protected $roles;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="salt",  type="string", length=255, nullable=true)
     */
    protected $salt;

    /**
     * @ORM\OneToMany(targetEntity="Documentary", mappedBy="addedBy")
     * @ORM\OrderBy({"created" = "DESC"})
     */
    protected $addedDocumentaries;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->activity = new ArrayCollection();
        $this->likedDocumentaries = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->addedDocumentaries = new ArrayCollection();
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
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set resetKey
     *
     * @param string $resetKey
     */
    public function setResetKey($resetKey)
    {
        $this->resetKey = $resetKey;
    }

    /**
     * Get resetKey
     *
     * @return string
     */
    public function getResetKey()
    {
        return $this->resetKey;
    }

    /**
     * Set resetRequest
     *
     * @param \Datetime $resetRequest
     */
    public function setResetRequest(\DateTime $resetRequest)
    {
        $this->resetRequest = $resetRequest;
    }

    /**
     * Get resetTime
     *
     * @return \DateTime
     */
    public function getResetRequest()
    {
        return $this->resetRequest;
    }

    /**
     * Set lastReset
     *
     * @param \DateTime $lastReset
     */
    public function setLastReset(\DateTime $lastReset)
    {
        $this->lastReset = $lastReset;
    }

    /**
     * Get lastReset
     *
     * @return \DateTime
     */
    public function getLastReset()
    {
        return $this->lastReset;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * Has comment
     *
     * @param Comment
     * @return boolean
     */
    public function hasComment(Comment $comment)
    {
        return $this->comments->contains($comment);
    }

    /**
     * Add comments
     *
     * @param \DW\DWBundle\Entity\Comment $comments
     * @return User
     */
    public function addComment(\DW\DWBundle\Entity\Comment $comments)
    {
        if (!$this->hasComment($comments)) {
            $this->comments[] = $comments;
            $comments->setUser($this);
        }

        return $this;
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
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add votes
     *
     * @param \DW\DWBundle\Entity\Vote $votes
     * @return User
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
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            ) = unserialize($serialized);
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get usernameCanonical
     *
     * @return string
     */
    public function getUsernameCanonical()
    {
        return $this->usernameCanonical;
    }

    /**
     * Set usernameCanonical
     *
     * @param string $usernameCanonical
     */
    public function setUsernameCanonical($usernameCanonical)
    {
        $this->usernameCanonical = $usernameCanonical;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get emailCanonical
     *
     * @return string
     */
    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    /**
     * Set emailCanonical
     *
     * @param string $emailCanonical
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;
    }

    /**
     * Get activated
     *
     * @return \DateTime
     */
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * Set activated
     *
     * @param \DateTime $activated
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;
    }

    /**
     * Get activationKey
     *
     * @return String
     */
    public function getActivationKey()
    {
        return $this->activationKey;
    }

    /**
     * Set activationKey
     *
     * @param String $activationKey
     */
    public function setActivationKey($activationKey)
    {
        $this->activationKey = $activationKey;
    }

    /**
     * Set registered
     *
     * @param \DateTime $registered
     * @return User
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;

        return $this;
    }

    /**
     * Get registered
     *
     * @return \DateTime
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return User
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return User
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
     * Set lastActive
     *
     * @param \DateTime $lastActive
     * @return User
     */
    public function setLastActive($lastActive)
    {
        $this->lastActive = $lastActive;

        return $this;
    }

    /**
     * Get lastActive
     *
     * @return \DateTime
     */
    public function getLastActive()
    {
        return $this->lastActive;
    }

    /**
     * Has following
     *
     * @param Follow $following
     * @return boolean
     */
    public function hasFollowing(Follow $following)
    {
        return $this->following->contains($following);
    }

    /**
     * Add following
     *
     * @param Follow $following
     * @return User
     */
    public function addFollowing(Follow $following)
    {
        if (!$this->hasFollowing($following)) {
            $this->following->add($following);
            $following->setFollower($this);
        }
    }

    /**
     * Remove following
     *
     * @param Follow $following
     */
    public function removeFollowing(Follow $following)
    {
        if ($this->hasFollowing($following)) {
            $this->following->removeElement($following);
            //TODO: remove
        }
    }

    /**
     * Get following
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowing()
    {
        return $this->following;
    }

    public function getFollowingUsersOnly()
    {
        $following = new ArrayCollection();
        foreach ($this->following as $follow) {
            $user = $follow->getFollowing();
            $following->add($user);
        }
        return $following;
    }

    /**
     * Has follower
     *
     * @param Follow $follower
     * @return boolean
     */
    public function hasFollower(Follow $follower)
    {
        return $this->followers->contains($follower);
    }

    /**
     * Add follower
     *
     * @param Follow $follower
     */
    public function addFollower(Follow $follower)
    {
        if (!$this->hasFollower($follower)) {
            $this->followers->add($follower);
            $follower->setFollowing($this);
        }
    }

    /**
     * Remove follower
     *
     * @param Follow $follower
     */
    public function removeFollower(Follow $follower)
    {
        if ($this->hasFollower($follower)) {
            $this->followers->removeElement($follower);
            //TODO: remove
        }
    }

    /**
     * Get followers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowers()
    {
        return $this->followers;
    }

    public function getFollowersUsersOnly()
    {
        $followers = new ArrayCollection();
        foreach ($this->followers as $follow) {
            $user = $follow->getFollower();
            $followers->add($user);
        }
        return $followers;
    }

    /**
     * Add activity
     *
     * @param \DW\DWBundle\Entity\Activity $activity
     * @return User
     */
    public function addActivity(\DW\DWBundle\Entity\Activity $activity)
    {
        $this->activity[] = $activity;

        return $this;
    }

    /**
     * Remove activity
     *
     * @param \DW\DWBundle\Entity\Activity $activity
     */
    public function removeActivity(\DW\DWBundle\Entity\Activity $activity)
    {
        $this->activity->removeElement($activity);
    }

    /**
     * Get activity
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivity()
    {
        return $this->activity;
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
            $like->setUser($this);
        }
    }

    /**
     * Remove like
     */
    public function removeLike(Like $like)
    {
        if ($this->hasLike($like)) {
            $this->likes->remove($like);
            $like->setUser(null);
        }
    }

    /**
     * Get likedDocumentaries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikedDocumentaries()
    {
        return $this->likedDocumentaries;
    }

    /**
     * Set avatar
     *
     * @param String $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * Get avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Get avatar directory
     */
    public function getAvatarDirectory()
    {
        return 'uploads/images/avatar/';
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
            $this->avatar = 'initial';
        }
    }

    /**
     * Get file
     *
     * @return UploadedFile $file
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        if ($this->avatar !== null) {
            return $this->getAvatarRootDir().'/'.$this->avatar;
        }
    }

    protected function getAvatarRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getAvatarDirectory();
    }

    public function preUpload()
    {
        if ($this->getFile() !== null) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->avatar = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    public function upload()
    {
        if ($this->getFile() === null) {
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

        $this->getFile()->move($this->getAvatarRootDir(), $this->avatar);
        $resized = $this->resizeImage($this->getAbsolutePath(), 200, 200);
        $this->setFile(null);
    }

    public function resizeImage($file, $w, $h)
    {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($src, $dst, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagejpeg($src, $dst, 90);
        return $dst;
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

    /**
     * Set facebookId
     *
     * @param integer $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * Get facebookId
     *
     * @return integer
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set facebookAccessToken
     *
     * @param String $facebookAccessToken
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * Get facebookAccessToken
     *
     * @return $facebookAccessToken
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * Set facebookAvatarFull
     *
     * @param String $facebookAvatarFull
     */
    public function setFacebookAvatarFull($facebookAvatarFull)
    {
        $this->facebookAvatarFull = $facebookAvatarFull;
    }

    /**
     * Get facebookAvatarFull
     *
     * @return String
     */
    public function getFacebookAvatarFull()
    {
        return $this->facebookAvatarFull;
    }

    /**
     * Set facebookAvatarThumb
     *
     * @param String $facebookAvatarThumb
     */
    public function setFacebookAvatarThumb($facebookAvatarThumb)
    {
        $this->facebookAvatarThumb = $facebookAvatarThumb;
    }

    /**
     * Get facebookAvatarThumb
     *
     * @return String
     */
    public function getFacebookAvatarThumb()
    {
        return $this->facebookAvatarThumb;
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     */
    function getGravatar( $email, $s = 80, $d = 'identicon', $r = 'g', $img = false, $atts = array() ) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    /**
     * Set facebook avatar 2
     */
    public function setFacebookAvatar2($facebookAvatar2)
    {
        $this->facebookAvatar2 = $facebookAvatar2;
    }

    /**
     * Get facebookAvatar2
     */
    public function getFacebookAvatar2()
    {
        return $this->facebookAvatar2;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        $roleNames = array();
        foreach ($this->roles as $role) {
            $roleNames[] = $role->getRole();
        }
        return $roleNames;
    }

    /**
     * Has role
     *
     * @param Role $role
     * @return bool
     */
    public function hasRole(Role $role)
    {
        return $this->roles->contains($role);
    }

    /**
     * Add role
     *
     * @param Role $role
     */
    public function addRole(Role $role)
    {
        if (!$this->hasRole($role)) {
            $this->roles->add($role);
        }
    }

    /**
     * Remove roles
     *
     * @param Role $role
     */
    public function removeRole(Role $role)
    {
        if ($this->hasRole($role)) {
            $this->roles->removeElement($role);
            $role->removeUser($this);
        }
    }

    /**
     * Has added
     *
     * @param Documentary $documentary
     * @return bool
     */
    public function hasAddedDocumentary(Documentary $documentary)
    {
        return $this->addedDocumentaries->contains($documentary);
    }

    /**
     * Add addedDocumentary
     *
     * @param Documentary $documentary
     */
    public function addAddedDocumentary(Documentary $documentary)
    {
        if (!$this->hasAddedDocumentary($documentary)) {
            $this->addedDocumentaries->add($documentary);
            $documentary->setAddedBy($this);
        }
    }

    /**
     * Remove addedDocumentary
     *
     * @param Documentary $documentary
     */
    public function removeAddedDocumentary(Documentary $documentary)
    {
        if ($this->hasAddedDocumentary($documentary)) {
            $this->addedDocumentaries->removeElement($documentary);
        }
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}