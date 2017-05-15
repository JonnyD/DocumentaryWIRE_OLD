<?php

namespace DW\DWBundle\Tests\Entity;

use DW\DWBundle\Entity\Comment;
use DW\DWBundle\Tests\TestHelper;

class CommentTest extends \PHPUnit_Framework_TestCase
{
    public function testCreated()
    {
        $currentDateTime = new \DateTime();
        $comment = new Comment();
        $comment->setCreated($currentDateTime);

        $this->assertNotNull($comment);
        $this->assertEquals($currentDateTime, $comment->getCreated());
    }

    public function testComment()
    {
        $comment = new Comment();
        $comment->setComment("This is a comment");

        $this->assertNotNull($comment);
        $this->assertEquals("This is a comment", $comment->getComment());
    }

    public function testStatus()
    {
        $comment = new Comment();
        $comment->setStatus("publish");

        $this->assertNotNull($comment);
        $this->assertEquals("publish", $comment->getStatus());
    }

    public function testParentId()
    {
        $comment = new Comment();
        $comment->setParentId(3);

        $this->assertNotNull($comment);
        $this->assertEquals(3, $comment->getParentId());
    }

    public function testUser()
    {
        $user = TestHelper::createUser("user1");
        $comment = TestHelper::createComment("comment1");
        $comment->setUser($user);

        $this->assertNotNull($user);
        $this->assertEquals("user1", $comment->getUser()->getUsername());
    }

    public function testUserBidirectional()
    {
        $user = TestHelper::createUser("user1");
        $comment = TestHelper::createComment("comment1");
        $comment->setUser($user);

        $user2 = $comment->getUser();
        $this->assertNotNull($user2);
        $this->assertEquals("user1", $user2->getUsername());

        $comment2 = $user2->getComments()[0];
        $this->assertNotNull($comment2);
        $this->assertEquals("comment1", $comment2->getComment());
    }

    public function testDocumentary()
    {
        $documentary = TestHelper::createDocumentary("documentary1");
        $comment = TestHelper::createComment("comment1");
        $comment->setDocumentary($documentary);

        $documentary2 = $comment->getDocumentary();
        $this->assertNotNull($documentary2);
        $this->assertEquals("documentary1", $documentary2->getTitle());
    }

    public function testDocumentaryBidirectional()
    {
        $documentary = TestHelper::createDocumentary("documentary1");
        $comment = TestHelper::createComment("comment1");
        $comment->setDocumentary($documentary);

        $documentary2 = $comment->getDocumentary();
        $this->assertNotNull($documentary2);
        $this->assertEquals("documentary1", $documentary2->getTitle());

        $comment2 = $documentary2->getComments()[0];
        $this->assertNotNull($comment2);
        $this->assertEquals("comment1", $comment2->getComment());
    }
}