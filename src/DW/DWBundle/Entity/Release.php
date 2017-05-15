<?php

namespace DW\DWBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="DW\DWBundle\Repository\Doctrine\ORM\ReleaseRepository")
 * @ORM\Table(name="release")
 */
class Release
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    private $documentaries;

    public function __construct()
    {
        $this->amount = 0;
        $this->documentaries = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getDocumentaries()
    {
        return $this->documentaries;
    }

    public function setDocumentaries($documentaries)
    {
        $this->documentaries = $documentaries;
    }

    public function hasDocumentary(Documentary $documentary)
    {
        return $this->documentaries->contains($documentary);
    }

    public function addDocumentary(Documentary $documentary)
    {
        if (!$this->hasDocumentary($documentary)) {
            $this->documentaries->add($documentary);
            $documentary->setRelease($this);
        }
    }

    public function removeDocumentary(Documentary $documentary)
    {
        if ($this->hasDocumentary($documentary)) {
            $this->documentaries->removeElement($documentary);
        }
    }
}