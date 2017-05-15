<?php

namespace DW\DWBundle\Manager;

use Doctrine\ORM\EntityManager;
use DW\DWBundle\Repository\CategoryRepository;

class CategoryManager
{
	private $categoryRepository;

	public function __construct(CategoryRepository $categoryRepository)
	{
        $this->categoryRepository = $categoryRepository;
	}
	
	public function getAllCategories()
    {
		return $this->categoryRepository->findAllCategories();
	}

    public function getCategoriesWithDocumentaries()
    {
        return $this->categoryRepository->findCategoriesWithDocumentaries();
    }

    public function getCategoryByName($name)
    {
        return $this->categoryRepository->findOneByName($name);
    }

    public function getCategoryBySlug($slug)
    {
        return $this->categoryRepository->findOneBySlug($slug);
    }
}