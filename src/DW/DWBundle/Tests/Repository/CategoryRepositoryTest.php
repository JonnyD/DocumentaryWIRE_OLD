<?php

namespace DW\DWBundle\Tests\Repository;

use DW\DWBundle\Tests\BaseWebTestCase;
use DW\DWBundle\Entity\Category;

class CategoryRepositoryTest extends BaseWebTestCase
{
    private $categoryRepository;

    public function setUp()
    {
        parent::setUp();

        $this->categoryRepository = $this->getEntityManager()->getRepository('DocumentaryWIREBundle:Category');
    }

    public function testFindAll()
    {
        $categories = $this->categoryRepository->findAll();
        $this->assertNotNull($categories);
        $this->assertTrue(is_array($categories));
        $this->assertEquals(27, count($categories));
    }

    public function testFindOneByName()
    {
        $category = $this->categoryRepository->findOneByName("Science");
        $this->assertNotNull($category);
        $this->assertEquals("Science", $category->getName());
        $this->assertEquals("science", $category->getSlug());
    }

    public function testFindOneBySlug()
    {
        $category = $this->categoryRepository->findOneBySlug("science");
        $this->assertNotNull($category);
        $this->assertEquals("Science", $category->getName());
        $this->assertEquals("science", $category->getSlug());
    }
}