<?php

namespace DW\DWBundle\Tests\Repository;

use DW\DWBundle\Tests\BaseWebTestCase;
use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\DocumentaryStatus;
use DW\DWBundle\Entity\Order;
use DW\DWBundle\Entity\DocumentaryFilter;

class DocumentaryRepositoryTest extends BaseWebTestCase
{
    private $documentaryRepository;
    private $categoryRepository;

    public function setUp()
    {
        parent::setUp();

        $this->documentaryRepository = $this->getEntityManager()->getRepository('DocumentaryWIREBundle:Documentary');
        $this->categoryRepository = $this->getEntityManager()->getRepository('DocumentaryWIREBundle:Category');
    }

    public function testFindAll()
    {
        $documentaries = $this->documentaryRepository->findAll();
        $this->assertNotNull($documentaries);
        $this->assertTrue(is_array($documentaries));
        $this->assertEquals(15, count($documentaries));
    }

    public function testFindOneById()
    {
        $documentary = $this->documentaryRepository->find(1);
        $this->assertNotNull($documentary);
        $this->assertEquals("The Pirate Bay Away From Keyboard", $documentary->getTitle());
    }

    public function testFindOneByTitle()
    {
        $title = "The Pirate Bay Away From Keyboard";
        $documentary = $this->documentaryRepository->findOneByTitle($title);
        $this->assertNotNull($documentary);
        $this->assertEquals($title, $documentary->getTitle());
    }

    public function testFindOneBySlug()
    {
        $slug = "growing-change";
        $documentary = $this->documentaryRepository->findOneBySlug($slug);
        $this->assertNotNull($documentary);
        $this->assertEquals("Growing Change: A Journey Inside Venezuela’s Food Revolution", $documentary->getTitle());
    }

    public function testFindPublishedDocumentariesOrderedByDate()
    {
        $documentaries = $this->getDocumentaries(DocumentaryStatus::PUBLISH, DocumentaryFilter::DATE, Order::DESC);
        $this->checkDocumentary("The Pirate Bay Away From Keyboard", $documentaries[0]);
        $this->checkDocumentary("The Mars Underground", $documentaries[1]);
        $this->checkDocumentary("The Plastic Cow", $documentaries[2]);
        $this->checkDocumentary("Greening the Island of the Gods", $documentaries[3]);
        $this->checkDocumentary("FRANKENSTEER", $documentaries[4]);
        $this->checkDocumentary("No Impact Man", $documentaries[5]);
        $this->checkDocumentary("The Big Fix", $documentaries[6]);
        $this->checkDocumentary("Growing Change: A Journey Inside Venezuela’s Food Revolution", $documentaries[7]);
    }

    public function testFindPublishedDocumentariesOrderedByViews()
    {
        $documentaries = $this->getDocumentaries(DocumentaryStatus::PUBLISH, DocumentaryFilter::VIEWS, Order::DESC);
        $this->checkDocumentary("We Got F*cked", $documentaries[0]);
        $this->checkDocumentary("The Big Fix", $documentaries[1]);
        $this->checkDocumentary("Slavery by Another Name", $documentaries[2]);
        $this->checkDocumentary("The Plastic Cow", $documentaries[3]);
        $this->checkDocumentary("2012 Crossing Over, A New Beginning", $documentaries[4]);
        $this->checkDocumentary("Lolita: Slave to Entertainment", $documentaries[5]);
        $this->checkDocumentary("The Mars Underground", $documentaries[6]);
        $this->checkDocumentary("FRANKENSTEER", $documentaries[7]);
        $this->checkDocumentary("No Impact Man", $documentaries[8]);
        $this->checkDocumentary("The Pirate Bay Away From Keyboard", $documentaries[9]);
        $this->checkDocumentary("Occupied Cascadia", $documentaries[10]);
        $this->checkDocumentary("Growing Change: A Journey Inside Venezuela’s Food Revolution", $documentaries[11]);
    }

    public function testFindPublishedDocumentariesOrderedByTitle()
    {
        $documentaries = $this->getDocumentaries(DocumentaryStatus::PUBLISH, DocumentaryFilter::TITLE, Order::ASC);
        $this->checkDocumentary("2012 Crossing Over, A New Beginning", $documentaries[0]);
        $this->checkDocumentary("FRANKENSTEER", $documentaries[1]);
        $this->checkDocumentary("Greening the Island of the Gods", $documentaries[2]);
        $this->checkDocumentary("Growing Change: A Journey Inside Venezuela’s Food Revolution", $documentaries[3]);
        $this->checkDocumentary("How To Start A Revolution", $documentaries[4]);
        $this->checkDocumentary("Lolita: Slave to Entertainment", $documentaries[5]);
        $this->checkDocumentary("No Impact Man", $documentaries[6]);
        $this->checkDocumentary("Occupied Cascadia", $documentaries[7]);
        $this->checkDocumentary("Slavery by Another Name", $documentaries[8]);
        $this->checkDocumentary("The Big Fix", $documentaries[9]);
        $this->checkDocumentary("The Electronic Storyteller: TV & the Cultivation of Values", $documentaries[10]);
        $this->checkDocumentary("The Mars Underground", $documentaries[11]);
        $this->checkDocumentary("The Pirate Bay Away From Keyboard", $documentaries[12]);
        $this->checkDocumentary("The Plastic Cow", $documentaries[13]);
    }

    public function testFindPublishedDocumentariesOrderedByCommentCount()
    {
        $documentaries = $this->getDocumentaries(DocumentaryStatus::PUBLISH, DocumentaryFilter::COMMENTS, Order::DESC);
        $this->checkDocumentary("The Pirate Bay Away From Keyboard", $documentaries[0]);
        $this->checkDocumentary("The Mars Underground", $documentaries[1]);
        $this->checkDocumentary("The Plastic Cow", $documentaries[2]);
        $this->checkDocumentary("Greening the Island of the Gods", $documentaries[3]);
        $this->checkDocumentary("FRANKENSTEER", $documentaries[4]);
        $this->checkDocumentary("No Impact Man", $documentaries[5]);
        $this->checkDocumentary("The Big Fix", $documentaries[6]);
        $this->checkDocumentary("Growing Change: A Journey Inside Venezuela’s Food Revolution", $documentaries[7]);
        $this->checkDocumentary("Lolita: Slave to Entertainment", $documentaries[8]);
    }

    public function testFindPublishedDocumentariesByCategoryOrderedByDate()
    {
        $documentaries = $this->getDocumentariesByCategory("Environment", 7, DocumentaryStatus::PUBLISH, DocumentaryFilter::DATE, Order::DESC);
        $this->checkDocumentary("The Big Fix", $documentaries[0]);
        $this->checkDocumentary("Growing Change: A Journey Inside Venezuela’s Food Revolution", $documentaries[1]);
        $this->checkDocumentary("No Impact Man", $documentaries[2]);
        $this->checkDocumentary("Greening the Island of the Gods", $documentaries[3]);
        $this->checkDocumentary("The Plastic Cow", $documentaries[4]);
        $this->checkDocumentary("FRANKENSTEER", $documentaries[5]);
    }

    public function testFindPublishedDocumentariesByCategoryOrderedByTitle()
    {
        $documentaries = $this->getDocumentariesByCategory("Environment", 7, DocumentaryStatus::PUBLISH, DocumentaryFilter::TITLE, Order::ASC);
        $this->checkDocumentary("FRANKENSTEER", $documentaries[0]);
        $this->checkDocumentary("Greening the Island of the Gods", $documentaries[1]);
        $this->checkDocumentary("Growing Change: A Journey Inside Venezuela’s Food Revolution", $documentaries[2]);
        $this->checkDocumentary("No Impact Man", $documentaries[3]);
        $this->checkDocumentary("The Big Fix", $documentaries[4]);
        $this->checkDocumentary("The Plastic Cow", $documentaries[5]);
    }

    public function testFindPublishedDocumentariesByCategoryOrderedByViews()
    {
        $documentaries = $this->getDocumentariesByCategory("Environment", 7, DocumentaryStatus::PUBLISH, DocumentaryFilter::VIEWS, Order::DESC);
        $this->checkDocumentary("The Big Fix", $documentaries[0]);
        $this->checkDocumentary("The Plastic Cow", $documentaries[1]);
        $this->checkDocumentary("FRANKENSTEER", $documentaries[2]);
        $this->checkDocumentary("No Impact Man", $documentaries[3]);
        $this->checkDocumentary("Growing Change: A Journey Inside Venezuela’s Food Revolution", $documentaries[4]);
        $this->checkDocumentary("Greening the Island of the Gods", $documentaries[5]);
    }

    public function testFindPublishedDocumentariesByCategoryOrderedByCommentCount()
    {
        $documentaries = $this->getDocumentariesByCategory("Environment", 7, DocumentaryStatus::PUBLISH, DocumentaryFilter::COMMENTS, Order::DESC);
        $this->checkDocumentary("Greening the Island of the Gods", $documentaries[0]);
        $this->checkDocumentary("The Big Fix", $documentaries[1]);
        $this->checkDocumentary("The Plastic Cow", $documentaries[2]);
        $this->checkDocumentary("FRANKENSTEER", $documentaries[3]);
        $this->checkDocumentary("Growing Change: A Journey Inside Venezuela’s Food Revolution", $documentaries[4]);
        $this->checkDocumentary("No Impact Man", $documentaries[5]);
    }

    private function getDocumentariesByCategory($categoryName, $amountExpected, $status, $filter, $order)
    {
        $category = $this->getCategory($categoryName);
        $documentaries = $this->documentaryRepository->findDocumentariesByCategory($category->getId(), $status, $filter, $order, 0);
        $this->assertNotNull($documentaries);
        $this->assertEquals($amountExpected, count($documentaries));
        return $documentaries;
    }

    private function getCategory($categoryName)
    {
        $category = $this->categoryRepository->findOneByName($categoryName);
        $this->assertNotNull($category);
        $this->assertEquals($categoryName, $category->getName());
        return $category;
    }

    private function checkDocumentary($title, $documentary)
    {
        $this->assertNotNull($documentary);
        $this->assertEquals($title, $documentary->getTitle());
    }

    private function getDocumentaries($status, $filter, $order)
    {
        $documentaries = $this->documentaryRepository->findDocumentaries($status, $filter, $order, 0);
        $this->assertNotNull($documentaries);
        $this->assertEquals(15, count($documentaries));
        return $documentaries;
    }
}