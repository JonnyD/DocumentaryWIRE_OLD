<?php

namespace DW\DWBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="DW\DWBundle\Repository\Doctrine\ORM\CategoryRepository")
 * @ORM\Table(name="category")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Category
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
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
     * @JMS\Expose
     * @JMS\Type("string")
     *
	 * @Gedmo\Slug(fields={"name"}, updatable=false)
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
	 * @ORM\OneToMany(targetEntity="Documentary", mappedBy="category", cascade="persist")
     * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $documentaries;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Column(type="integer")
     */
    protected $count = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documentaries = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
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
     * @return Category
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
     * Add documentaries
     *
     * @param \DW\DWBundle\Entity\Documentary $documentaries
     * @return Category
     */
    public function addDocumentarie(\DW\DWBundle\Entity\Documentary $documentaries)
    {
        if (!$this->documentaries->contains($documentaries)) {
            $this->documentaries[] = $documentaries;
            $documentaries->addCategorie($this);
            $this->count++;
        }
    
        return $this;
    }

    /**
     * Remove documentaries
     *
     * @param \DW\DWBundle\Entity\Documentary $documentaries
     */
    public function removeDocumentarie(\DW\DWBundle\Entity\Documentary $documentaries)
    {
        $this->documentaries->removeElement($documentaries);
    }

    /**
     * Get documentaries
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDocumentaries()
    {
        return $this->documentaries;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Category
     */
    public function setCount($count)
    {
        $this->count = $count;
    
        return $this;
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
     * Increment count
     */
    public function incrementCount()
    {
        $this->count++;
    }

    /**
     * Decrement count
     */
    public function decrementCount()
    {
        $this->count--;
    }

    public function __toString()
    {
        return $this->name;
    }
}