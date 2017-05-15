<?php

namespace DW\DWBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Type;

/**
 * @ORM\Entity(repositoryClass="DW\DWBundle\Repository\Doctrine\ORM\ActivityRepository")
 * @ORM\Table(name="activity", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="like_idx", columns={"user_id", "object_id", "type"})
 * })
 *
 * @ExclusionPolicy("all")
 */
class Activity implements \Serializable
{
    /**
     * @Expose
     * @Type("integer")
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Expose
     * @Type("DW\DWBundle\Entity\User")
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="activity", cascade={"merge"}, fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @Expose
     * @Type("string")
     *
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @Expose
     * @Type("string")
     *
     * @ORM\Column(type="string")
     */
    protected $component;

    /**
     * @Expose
     * @Type("integer")
     *
     * @ORM\Column(type="integer", name="object_id")
     */
    protected $objectId;

    /**
     * @Expose
     * @Type("array<string, string>")
     *
     * @ORM\Column(type="array")
     */
    protected $data;

    /**
     * @Expose
     * @Type("integer")
     *
     * @ORM\Column(name="group_number", type="integer")
     */
    protected $groupNumber;

    /**
     * @Expose
     * @Type("DateTime")
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
     * Set type
     *
     * @param string $type
     * @return Activity
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set component
     *
     * @param string $component
     */
    public function setComponent($component)
    {
        $this->component = $component;
    }

    /**
     * Get component
     *
     * @return string
     */
    public function getComponent()
    {
        return $this->component;
    }

    /**
     * Set data
     *
     * @param array
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Get data
     *
     * @return array $data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Activity
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
     * Set user
     *
     * @param \DW\DWBundle\Entity\User $user
     * @return Activity
     */
    public function setUser(\DW\DWBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
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
     * Set objectId
     *
     * @param integer $objectId
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;
    }

    /**
     * Get objectId
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set groupNumber
     *
     * @param integer $groupNumber
     */
    public function setGroupNumber($groupNumber)
    {
        $this->groupNumber = $groupNumber;
    }

    /**
     * Get groupNumber
     *
     * @return integer
     */
    public function getGroupNumber()
    {
        return $this->groupNumber;
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
}