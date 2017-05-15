<?php

namespace DW\DWBundle\Tests\Manager;

use DW\DWBundle\Entity\Comment;
use DW\DWBundle\Tests\BaseWebTestCase;

class CommentManagerTest extends BaseWebTestCase
{
    private $commentManager;

    public function setUp()
    {
        parent::setUp();

        $this->commentManager = $this->getContainer()->get("documentary_wire.comment_manager");
    }

    public function testGetRecentComments()
    {
        $limit = 5;
        $comments = $this->commentManager->getRecentComments($limit);
        $this->assertNotNull($comments);
        $this->assertEquals($limit, count($comments));
    }
}