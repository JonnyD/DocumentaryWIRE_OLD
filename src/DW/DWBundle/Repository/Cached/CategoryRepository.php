<?php

namespace DW\DWBundle\Repository\Cached;

use DW\DWBundle\Cache\CategoryCache;
use DW\DWBundle\Helper\CacheHelper;
use DW\DWBundle\Repository\CategoryRepository as CategoryRepositoryInterface;
use DW\DWBundle\Repository\Doctrine\ORM\CategoryRepository as CategoryRepositoryDoctrine;

class CategoryRepository extends CustomRepository implements CategoryRepositoryInterface
{
    private $categoryRepository;
    private $cacheHelper;

    public function __construct(CategoryRepositoryDoctrine $categoryRepository, CacheHelper $cacheHelper)
    {
        parent::__construct($categoryRepository);

        $this->categoryRepository = $categoryRepository;
        $this->cacheHelper = $cacheHelper;
    }

    public function findAllCategories()
    {
        $key = CategoryCache::KEY_LIST;
        $name = CategoryCache::CACHE_CATEGORY;
        $categories = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Category>");
        if ($categories == null) {
            $categories = $this->categoryRepository->findAllCategories();
            $this->cacheHelper->saveToCache($key, $name, $categories);
        }
        return $categories;
    }

    public function findCategoriesWithDocumentaries()
    {
        $key = CategoryCache::KEY_LIST_WITH_DOCUMENTARIES;
        $name = CategoryCache::CACHE_CATEGORY;
        $categories = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Category>");
        if ($categories == null) {
            $categories = $this->categoryRepository->findCategoriesWithDocumentaries();
            $this->cacheHelper->saveToCache($key, $name, $categories);
        }
        return $categories;
    }

    public function findOneByName($name)
    {
        $key = CategoryCache::KEY_NAME."_".$name;
        $cacheName = CategoryCache::CACHE_CATEGORY;
        $category = $this->cacheHelper->getFromCache($key, $cacheName, "DW\DWBundle\Entity\Category");
        if ($category == null) {
            $category = $this->categoryRepository->findOneByName($name);
            $this->cacheHelper->saveToCache($key, $cacheName, $category);
        }
        return $category;
    }

    public function findOneBySlug($slug)
    {
        $key = CategoryCache::KEY_SLUG."_".$slug;
        $cacheName = CategoryCache::CACHE_CATEGORY;
        $category = $this->cacheHelper->getFromCache($key, $cacheName, "DW\DWBundle\Entity\Category");
        if ($category == null) {
            $category = $this->categoryRepository->findOneBySlug($slug);
            $this->cacheHelper->saveToCache($key, $cacheName, $category);
        }
        return $category;
    }
}