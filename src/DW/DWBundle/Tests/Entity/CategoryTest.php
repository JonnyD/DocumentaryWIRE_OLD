<?php

namespace DW\DWBundle\Tests\Entity;

use DW\DWBundle\Entity\Category;
use DW\DWBundle\Tests\TestHelper;

class CategoryTest extends \PHPUnit_Framework_TestCase
{
    public function testName()
    {
        $category = new Category();
        $category->setName("Science");

        $this->assertNotNull($category);
        $this->assertEquals("Science", $category->getName());
    }

    public function testSlug()
    {
        $category = new Category();
        $category->setSlug("science");

        $this->assertNotNull($category);
        $this->assertEquals("science", $category->getSlug());
    }

    public function testCount()
    {
        $category = new Category();
        $category->setName("Science");
        $category->setCount(10);

        $this->assertNotNull($category);
        $this->assertEquals(10, $category->getCount());
    }

    public function testDocumentaries()
    {
        $documentary1 = TestHelper::createDocumentary("documentary1");
        $documentary2 = TestHelper::createDocumentary("documentary2");
        $documentary3 = TestHelper::createDocumentary("documentary3");

        $category = new Category();
        $category->setName("Science");
        $category->addDocumentarie($documentary1);
        $category->addDocumentarie($documentary2);
        $category->addDocumentarie($documentary3);

        $documentaries = $category->getDocumentaries();
        $this->assertNotNull($documentaries);
        $this->assertEquals(3, $documentaries->count());
    }

    public function testDocumentariesBidirectional()
    {
        $documentary1 = TestHelper::createDocumentary("documentary1");
        $documentary2 = TestHelper::createDocumentary("documentary2");
        $documentary3 = TestHelper::createDocumentary("documentary3");

        $category = new Category();
        $category->setName("Science");
        $category->addDocumentarie($documentary1);
        $category->addDocumentarie($documentary2);
        $category->addDocumentarie($documentary3);

        $documentaries = $category->getDocumentaries();
        $this->assertNotNull($documentaries);
        $this->assertEquals(3, $documentaries->count());

        $category = $documentary1->getCategories()[0];
        $this->assertNotNull($category);
        $this->assertEquals("Science", $category->getName());
    }
}