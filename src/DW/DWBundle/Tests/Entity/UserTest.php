<?php

namespace DW\DWBundle\Tests\Entity;

use DW\DWBundle\Entity\User;
use DW\DWBundle\Entity\Status;
use DW\DWBundle\Tests\TestHelper;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testUsername()
    {
        $user = new User();
        $user->setUsername("user1");

        $this->assertEquals("user1", $user->getUsername());
    }

    public function testPassword()
    {
        $user = new User();
        $user->setPassword("pass1");

        $this->assertEquals("pass1", $user->getPassword());
    }

    public function testEmail()
    {
        $user = new User();
        $user->setEmail("my1@email.com");

        $this->assertEquals("my1@email.com", $user->getEmail());
    }

    public function testRegistered()
    {
        $currentDateTime = new \DateTime();

        $user = new User();
        $user->setRegistered($currentDateTime);

        $this->assertEquals($currentDateTime, $user->getRegistered());
    }

    public function testLastActive()
    {
        $currentDateTime = new \DateTime();

        $user = new User();
        $user->setLastActive($currentDateTime);

        $this->assertEquals($currentDateTime, $user->getLastActive());
    }

    public function testDisplayName()
    {
        $user = new User();
        $user->setDisplayName("user1");

        $this->assertEquals("user1", $user->getDisplayName());
    }

    public function testStatus()
    {
        $user = new User();

        $user->setStatus(Status::ACTIVE);
        $this->assertEquals(1, $user->getStatus());

        $user->setStatus(Status::INACTIVE);
        $this->assertEquals(2, $user->getStatus());
    }

    public function testRoles()
    {
        $role = TestHelper::createRole("user", "ROLE_USER");
        $user = TestHelper::createUser("user1");
        $user->addRole($role);

        $this->assertEquals(1, count($user->getRoles()));
        $this->assertEquals("user", $user->getRoles()[0]->getName());
        $this->assertEquals("ROLE_USER", $user->getRoles()[0]->getRole());
    }

    public function testComments()
    {
        $comment1 = TestHelper::createComment("comment1");
        $comment2 = TestHelper::createComment("comment2");
        $comment3 = TestHelper::createComment("comment3");

        $user = TestHelper::createUser("user1");
        $user->addComment($comment1);
        $user->addComment($comment2);
        $user->addComment($comment3);

        $this->assertTrue($user->hasComment($comment1));
        $this->assertTrue($user->hasComment($comment2));
        $this->assertTrue($user->hasComment($comment3));

        $comments = $user->getComments();
        $this->assertNotNull($comments);
        $this->assertEquals(3, $comments->count());
    }

    public function testCommentsBidirectional()
    {
        $comment1 = TestHelper::createComment("comment1");
        $comment2 = TestHelper::createComment("comment2");

        $user = TestHelper::createUser("user");
        $user->addComment($comment1);
        $user->addComment($comment2);

        $comments = $user->getComments();
        $this->assertNotNull($comments);
        $this->assertEquals(2, $comments->count());

        $comment1 = $comments->get(0);
        $this->assertNotNull($comment1);
        $this->assertEquals("comment1", $comment1->getComment());

        $user = $comment1->getUser();
        $this->assertNotNull($user);
        $this->assertEquals("user", $user->getUsername());
    }

    public function testRemoveComments()
    {
        $comment1 = TestHelper::createComment("comment1");
        $comment2 = TestHelper::createComment("comment2");
        $comment3 = TestHelper::createComment("comment3");

        $user = TestHelper::createUser("user1");
        $user->addComment($comment1);
        $user->addComment($comment2);
        $user->addComment($comment3);

        $this->assertTrue($user->hasComment($comment1));
        $this->assertTrue($user->hasComment($comment2));
        $this->assertTrue($user->hasComment($comment3));

        $comments = $user->getComments();
        $this->assertNotNull($comments);
        $this->assertEquals(3, $comments->count());

        $user->removeComment($comment2);
        $this->assertFalse($user->hasComment($comment2));

        $comments = $user->getComments();
        $this->assertNotNull($comments);
        $this->assertEquals(2, $comments->count());
    }
}