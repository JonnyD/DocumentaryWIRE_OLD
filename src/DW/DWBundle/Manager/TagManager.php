<?php

namespace DW\DWBundle\Manager;

use Doctrine\ORM\EntityManager;
use DW\DWBundle\Repository\Doctrine\ORM\TagRepository;

class TagManager
{
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function getAllTags()
    {
        return $this->tagRepository->findAll();
    }

    public function getTagBySlug($slug)
    {
        return $this->tagRepository->findOneBy(
            array('slug' => $slug)
        );
    }
}