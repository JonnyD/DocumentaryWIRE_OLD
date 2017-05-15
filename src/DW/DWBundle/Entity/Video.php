<?php

namespace DW\DWBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="DW\DWBundle\Repository\Doctrine\ORM\VideoRepository")
 * @ORM\Table(name="video")
 */
class Video
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Documentary", inversedBy="videos", cascade="persist")
     * @ORM\JoinColumn(name="documentary_id", referencedColumnName="id")
     */
    private $documentary;

    /**
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @ORM\Column(type="string")
     */
    private $source;

    /**
     * @ORM\Column(type="string")
     */
    private $videoId;

    public function getId()
    {
        return $this->id;
    }

    public function setDocumentary(Documentary $documentary)
    {
        $this->documentary = $documentary;
        $documentary->addVideo($this);
    }

    public function getDocumentary()
    {
        return $this->documentary;
    }

    public function removeDocumentary()
    {
        $this->documentary = null;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
    }

    public function getVideoId()
    {
        return $this->videoId;
    }
}