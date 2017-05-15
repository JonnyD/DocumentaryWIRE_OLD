<?php

namespace DW\DWBundle\Repository;

interface CategoryRepository
{
    public function findAllCategories();
    public function findCategoriesWithDocumentaries();
    public function findOneByName($name);
    public function findOneBySlug($slug);
}