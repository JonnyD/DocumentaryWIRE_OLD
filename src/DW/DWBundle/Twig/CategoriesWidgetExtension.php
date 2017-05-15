<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Manager\CategoryManager;

class CategoriesWidgetExtension extends \Twig_Extension
{
    private $categoryManager;

    public function __construct(CategoryManager $categoryManager) {
        $this->categoryManager = $categoryManager;
    }

    public function getFunctions()
    {
        return array(
            'listCategories' => new \Twig_Function_Method($this, 'listCategories')
        );
    }

    public function listCategories()
    {
        $categories = $this->categoryManager->getCategoriesWithDocumentaries();
        return $categories;
    }

    public function getName()
    {
        return 'listCategoriesExtension';
    }
}