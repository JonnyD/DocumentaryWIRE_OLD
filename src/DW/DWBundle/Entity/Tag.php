<?php

namespace DW\DWBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="DW\DWBundle\Repository\Doctrine\ORM\TagRepository")
 * @ORM\Table(name="tag")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Tag
{
    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="name", type="string", length=30)
     */
    private $name;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     *
     * @ORM\Column(name="slug", type="string", length=30)
     */
    private $slug;

    private $documentaries;

    /**
     * @ORM\Column(name="documentary_count", type="integer")
     */
    private $count;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->documentaries = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
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
     * Set documentaries
     *
     * @param ArrayCollection $documentaries
     */
    public function setDocumentaries($documentaries)
    {
        $this->documentaries = $documentaries;
    }

    /**
     * Has documentary
     *
     * @param Documentary $documentary
     * @return boolean
     */
    public function hasDocumentary(Documentary $documentary)
    {
        return $this->documentaries->contains($documentary);
    }

    /**
     * Add documentary
     *
     * @param Documentary $documentary
     */
    public function addDocumentary(Documentary $documentary)
    {
        if (!$this->hasDocumentary($documentary)) {
            $this->documentaries->add($documentary);
            $documentary->addTag($this);
        }
    }

    /**
     * Remove documentary
     *
     * @param Documentary $documentary
     */
    public function removeDocumentary(Documentary $documentary)
    {
        if ($this->hasDocumentary($documentary)) {
            $this->documentaries->removeElement($documentary);
            $documentary->removeTag($this);
        }
    }

    /**
     * Get documentaries
     *
     * @return ArrayCollection
     */
    public function getDocumentaries()
    {
        return $this->documentaries;
    }

    /**
     * Set count
     *
     * @param integer $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * Get count
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
}