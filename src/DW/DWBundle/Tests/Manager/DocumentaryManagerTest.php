<?php

namespace DW\DWBundle\Tests\Manager;

use DW\DWBundle\Entity\DocumentaryStatus;
use DW\DWBundle\Entity\DocumentaryFilter;
use DW\DWBundle\Entity\Order;
use DW\DWBundle\Tests\BaseWebTestCase;

class DocumentaryManagerTest extends BaseWebTestCase
{
    private $documentaryManager;
    private $categoryManager;

    public function setUp()
    {
        parent::setUp();

        $this->documentaryManager = $this->getContainer()->get("documentary_wire.documentary_manager");
        $this->categoryManager = $this->getContainer()->get("documentary_wire.category_manager");
    }

    public function testGetDocumentaryBySlug()
    {
        $slug = "growing-change";
        $documentary = $this->documentaryManager->getDocumentaryBySlug($slug);

        $this->assertNotNull($documentary);
        $this->assertEquals($slug, $documentary->getSlug());
        $this->assertEquals("Growing Change: A Journey Inside Venezuela’s Food Revolution", $documentary->getTitle());
    }

    public function testGetAllDocumentaries()
    {
        $documentaries = $this->documentaryManager->getAllDocumentaries();

        $this->assertNotNull($documentaries);
        $this->assertEquals(15, count($documentaries));
    }

    public function testGetDocumentaries()
    {
        $documentaries = $this->documentaryManager->getDocumentaries(DocumentaryStatus::PUBLISH, DocumentaryFilter::DATE, Order::DESC, 0);

        $this->assertNotNull($documentaries);
        $this->assertEquals(15, count($documentaries));
    }

    public function testGetDocumentariesByCategory()
    {
        $name = "Environment";
        $category = $this->categoryManager->getCategoryByName($name);
        $this->assertNotNull($name);
        $this->assertEquals($name, $category->getName());

        $documentaries = $this->documentaryManager->getDocumentariesByCategory($category->getId(), DocumentaryStatus::PUBLISH, DocumentaryFilter::DATE, Order::DESC, 0);
        $this->assertNotNull($documentaries);
        $this->assertEquals(7, count($documentaries));
    }

    public function testCreateDocumentary()
    {
        $title = "documentary1";
        $slug = "documentary1";
        $description = "this is the description";
        $excerpt = "an excerpt";
        $status = DocumentaryStatus::PUBLISH;

        $documentary = $this->documentaryManager->createDocumentary($title, $slug, $description, $excerpt, $status);
        $this->assertNotNull($documentary);
        $this->assertEquals($title, $documentary->getTitle());
        $this->assertEquals($slug, $documentary->getSlug());
        $this->assertEquals($description, $documentary->getDescription());
        $this->assertEquals($excerpt, $documentary->getExcerpt());
        $this->assertEquals($status, $documentary->getStatus());
    }
}