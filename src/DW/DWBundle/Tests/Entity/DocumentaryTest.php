<?php

namespace DW\DWBundle\Tests\Entity;

use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\DocumentaryStatus;
use DW\DWBundle\Tests\TestHelper;

class DocumentaryTest extends \PHPUnit_Framework_TestCase
{
    public function testTitle()
    {
        $documentary = new Documentary();
        $documentary->setTitle("Zeitgeist");

        $this->assertNotNull($documentary);
        $this->assertEquals("Zeitgeist", $documentary->getTitle());
    }

    public function testDescription()
    {
        $documentary = new Documentary();
        $documentary->setDescription("This is the description");

        $this->assertNotNull($documentary);
        $this->assertEquals("This is the description", $documentary->getDescription());
    }

    public function testSlug()
    {
        $documentary = new Documentary();
        $documentary->setSlug("this-is-a-slug");

        $this->assertNotNull($documentary);
        $this->assertEquals("this-is-a-slug", $documentary->getSlug());
    }

    public function testCreated()
    {
        $currentDateTime = new \DateTime();

        $documentary = new Documentary();
        $documentary->setCreated($currentDateTime);

        $this->assertNotNull($documentary);
        $this->assertEquals($currentDateTime, $documentary->getCreated());
    }

    public function testUpdated()
    {
        $currentDateTime = new \DateTime();

        $documentary = new Documentary();
        $documentary->setUpdated($currentDateTime);

        $this->assertNotNull($documentary);
        $this->assertEquals($currentDateTime, $documentary->getUpdated());
    }

    public function testExcerpted()
    {
        $documentary = new Documentary();
        $documentary->setExcerpt("This is a excerpt");

        $this->assertNotNUll($documentary);
        $this->assertEquals("This is a excerpt", $documentary->getExcerpt());
    }

    public function testStatus()
    {
        $documentary = new Documentary();
        $this->assertNotNull($documentary);
        $this->assertEquals("publish", $documentary->getStatus());

        $documentary->setStatus(DocumentaryStatus::PUBLISH);
        $this->assertEquals("publish", $documentary->getStatus());

        $documentary->setStatus(DocumentaryStatus::DRAFT);
        $this->assertEquals("draft", $documentary->getStatus());

        $documentary->setStatus(DocumentaryStatus::PENDING);
        $this->assertEquals("pending", $documentary->getStatus());
    }

    public function testViews()
    {
        $documentary = new Documentary();
        $documentary->setViews(889);

        $this->assertNotNull($documentary);
        $this->assertEquals(889, $documentary->getViews());
    }

    public function testVideoSource()
    {
        $documentary = new Documentary();
        $documentary->setVideoSource("youtube");

        $this->assertNotNull($documentary);
        $this->assertEquals("youtube", $documentary->getVideoSource());
    }

    public function testVideoId()
    {
        $documentary = new Documentary();
        $documentary->setVideoId("aajshgj");

        $this->assertNotNull($documentary);
        $this->assertEquals("aajshgj", $documentary->getVideoId());
    }

    public function testThumbnail()
    {
        $documentary = new Documentary();
        $documentary->setThumbnail("this-is-a-thumbnail.jpg");

        $this->assertNotNull($documentary);
        $this->assertEquals("uploads/images/documentary/this-is-a-thumbnail.jpg", $documentary->getThumbnail());
    }

    public function testLength()
    {

    }

    public function testLikes()
    {

    }

    public function testComments()
    {
        $documentary = new Documentary();
        $documentary->setTitle("documentary1");

        $comment1 = TestHelper::createComment("comment1");
        $comment2 = TestHelper::createComment("comment2");
        $comment3 = TestHelper::createComment("comment3");

        $documentary->addComment($comment1);
        $documentary->addComment($comment2);
        $documentary->addComment($comment3);

        $this->assertTrue($documentary->hasComment($comment1));
        $this->assertTrue($documentary->hasComment($comment2));
        $this->assertTrue($documentary->hasComment($comment3));

        $this->assertNotNull($documentary);
        $comments = $documentary->getComments();
        $this->assertNotNull($comments);
        $this->assertEquals(3, $comments->count());
    }

    public function testCommentsBidirectional()
    {
        $documentary = new Documentary();
        $documentary->setTitle("documentary1");

        $comment1 = TestHelper::createComment("comment1");
        $comment2 = TestHelper::createComment("comment2");
        $comment3 = TestHelper::createComment("comment3");

        $documentary->addComment($comment1);
        $documentary->addComment($comment2);
        $documentary->addComment($comment3);

        $this->assertNotNull($documentary);
        $this->assertNotNull($comment1);

        $documentary = $comment1->getDocumentary();
        $this->assertNotNull($documentary);
        $this->assertEquals("documentary1", $documentary->getTitle());
    }

    public function testRemoveComment()
    {
        $documentary = new Documentary();
        $documentary->setTitle("documentary1");

        $comment1 = TestHelper::createComment("comment1");
        $comment2 = TestHelper::createComment("comment2");
        $comment3 = TestHelper::createComment("comment3");

        $documentary->addComment($comment1);
        $documentary->addComment($comment2);
        $documentary->addComment($comment3);

        $this->assertNotNull($documentary);
        $comments = $documentary->getComments();
        $this->assertNotNull($comments);
        $this->assertEquals(3, $comments->count());

        $documentary->removeComment($comment2);
        $this->assertEquals(2, $documentary->getComments()->count());
    }

    public function testCommentCount()
    {
        $documentary = new Documentary();
        $documentary->setCommentCount(3);

        $this->assertNotNull($documentary);
        $this->assertEquals(3, $documentary->getCommentCount());
    }

    public function testCategories()
    {
        $documentary = new Documentary();

        $category1 = TestHelper::createCategory("Technology", "technology");
        $category2 = TestHelper::createCategory("Science", "science");

        $documentary->addCategorie($category1);
        $documentary->addCategorie($category2);

        $this->assertTrue($documentary->hasCategory($category1));
        $this->assertTrue($documentary->hasCategory($category2));

        $categories = $documentary->getCategories();
        $this->assertNotNull($categories);
        $this->assertEquals(2, $categories->count());
    }

    public function testCategoriesBidirectional()
    {
        $documentary = new Documentary();
        $documentary->setTitle("Zeitgeist");

        $category1 = TestHelper::createCategory("Technology", "technology");
        $category2 = TestHelper::createCategory("Science", "science");

        $documentary->addCategorie($category1);
        $documentary->addCategorie($category2);

        $this->assertTrue($documentary->hasCategory($category1));
        $this->assertTrue($documentary->hasCategory($category2));

        $documentaries = $category1->getDocumentaries();
        $this->assertNotNull($documentaries);
        $this->assertEquals(1, $documentaries->count());
        $this->assertEquals("Zeitgeist", $documentaries->get(0)->getTitle());
    }
}