<?php

namespace DW\DWBundle\Tests\Manager;

use DW\DWBundle\Tests\BaseWebTestCase;

class CategoryManagerTest extends BaseWebTestCase
{
    private $categoryManager;

    public function setUp()
    {
        parent::setUp();

        $this->categoryManager = $this->getContainer()->get("documentary_wire.category_manager");
    }

    public function testGetAllCategories()
    {
        $categories = $this->categoryManager->getAllCategories();

        $this->assertNotNull($categories);
        $this->assertEquals(27, count($categories));
    }

    public function testGetCategoryByName()
    {
        $name = "Environment";
        $category = $this->categoryManager->getCategoryByName($name);
        $this->assertNotNull($category);
        $this->assertEquals($name, $category->getName());
    }

    public function testGetCategoryBySlug()
    {
        $slug = "environment";
        $category = $this->categoryManager->getCategoryBySlug($slug);
        $this->assertNotNull($category);
        $this->assertEquals($slug, $category->getSlug());
    }
}