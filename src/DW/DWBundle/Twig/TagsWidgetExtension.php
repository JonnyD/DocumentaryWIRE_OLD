<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Manager\TagManager;

class TagsWidgetExtension extends \Twig_Extension
{
    private $tagManager;

    public function __construct(TagManager $tagManager) {
        $this->tagManager = $tagManager;
    }

    public function getFunctions()
    {
        return array(
            'listTags' => new \Twig_Function_Method($this, 'listTags')
        );
    }

    public function listTags()
    {
        $tags = $this->tagManager->getAllTags();
        return $tags;
    }

    public function getName()
    {
        return 'listTagsExtension';
    }
}