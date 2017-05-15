<?php

namespace DW\DWBundle\Tests\Entity;

use DW\DWBundle\Entity\Like;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Tests\TestHelper;

class LikeTest extends \PHPUnit_Framework_TestCase
{
    public function testUser()
    {
        $user = TestHelper::createUser("user1");

        $like = new Like();
        $like->setUser($user);

        $this->assertNotNull($like);
        $this->assertNotNull($like->getUser());
        $this->assertEquals("user1", $like->getUser()->getUsername());
    }

    public function testDocumentary()
    {
        $documentary = TestHelper::createDocumentary("Documentary 1");

        $like = new Like();
        $like->setDocumentary($documentary);

        $this->assertNotNull($like);
        $this->assertNotNull($like->getDocumentary());
        $this->assertEquals("Documentary 1", $like->getDocumentary()->getTitle());
    }
}