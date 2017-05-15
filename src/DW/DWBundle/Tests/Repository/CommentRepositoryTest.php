<?php

namespace DW\DWBundle\Tests\Repository;

use DW\DWBundle\Tests\BaseWebTestCase;
use DW\DWBundle\Entity\Comment;
use DW\DWBundle\Entity\CommentStatus;
use DW\DWBundle\Entity\Order;

class CommentRepositoryTest extends BaseWebTestCase
{
    private $commentRepository;
    private $userRepository;
    private $documentaryRepository;

    public function setUp()
    {
        parent::setUp();

        $this->commentRepository = $this->getEntityManager()->getRepository('DocumentaryWIREBundle:Comment');
        $this->userRepository = $this->getEntityManager()->getRepository('DocumentaryWIREBundle:User');
        $this->documentaryRepository = $this->getEntityManager()->getRepository('DocumentaryWIREBundle:Documentary');
    }

    public function testFindAll()
    {
        $comments = $this->commentRepository->findAll();

        $this->assertNotNull($comments);
        $this->assertTrue(is_array($comments));
        $this->assertEquals(10, count($comments));
    }

    public function testFindAllOrderedByDate()
    {
        $comments = $this->getComments(CommentStatus::PUBLISH, "created", Order::DESC);

        $this->checkComment("comment9", $comments[0]);
        $this->checkComment("comment1", $comments[1]);
        $this->checkComment("comment3", $comments[2]);
        $this->checkComment("comment4", $comments[3]);
        $this->checkComment("comment2", $comments[4]);
        $this->checkComment("comment8", $comments[5]);
        $this->checkComment("comment7", $comments[6]);
        $this->checkComment("comment5", $comments[7]);
        $this->checkComment("comment6", $comments[8]);
        $this->checkComment("comment10", $comments[9]);
    }

    public function testFindByUser()
    {
        $user = $this->userRepository->findOneByUsername("user1");
        $comments = $this->commentRepository->findByUser($user);

        $this->assertNotNull($comments);
        $this->assertTrue(is_array($comments));
        $this->assertEquals(4, count($comments));
    }

    public function testFindByDocumentary()
    {
        $documentary = $this->documentaryRepository->findOneByTitle("The Mars Underground");
        $comments = $this->commentRepository->findByDocumentary($documentary);

        $this->assertNotNull($comments);
        $this->assertTrue(is_array($comments));
        $this->assertEquals(3, count($comments));
    }

    private function getComments($status, $orderBy, $order)
    {
        $comments = $this->commentRepository->findComments($status, $orderBy, $order, 0);
        $this->assertNotNull($comments);
        $this->assertEquals(10, count($comments));
        return $comments;
    }

    private function checkComment($message, $comment)
    {
        $this->assertNotNull($comment);
        $this->assertEquals($message, $comment->getComment());
    }
}