<?php

namespace DW\DWBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use DW\DWBundle\Entity\Comment;

class CommentFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
    	$this->container = $container;
    }
    
	public function load(ObjectManager $manager)
	{
        $user1 = $this->getReference("user1");
        $user2 = $this->getReference("user2");
        $user3 = $this->getReference("user3");
        $user4 = $this->getReference("user4");

        $documentary1 = $this->getReference("documentary1");
        $documentary2 = $this->getReference("documentary2");
        $documentary3 = $this->getReference("documentary3");

        $this->createComment($manager, "comment1", $user1, $documentary1, new \DateTime(date('2013-06-1 21:00:00')));
        $this->createComment($manager, "comment2", $user1, $documentary1, new \DateTime(date('2012-04-3 21:00:00')));
        $this->createComment($manager, "comment3", $user1, $documentary3, new \DateTime(date('2013-01-4 21:00:00')));
        $this->createComment($manager, "comment4", $user1, $documentary3, new \DateTime(date('2012-10-5 21:00:00')));
        $this->createComment($manager, "comment5", $user2, $documentary1, new \DateTime(date('2011-11-6 21:00:00')));
        $this->createComment($manager, "comment6", $user3, $documentary2, new \DateTime(date('2011-11-3 21:00:00')));
        $this->createComment($manager, "comment7", $user3, $documentary2, new \DateTime(date('2012-01-2 21:00:00')));
        $this->createComment($manager, "comment8", $user4, $documentary1, new \DateTime(date('2012-03-1 21:00:00')));
        $this->createComment($manager, "comment9", $user4, $documentary2, new \DateTime(date('2013-09-4 21:00:00')));
        $this->createComment($manager, "comment10", $user4, $documentary3, new \DateTime(date('2011-06-3 21:00:00')));
		
		$manager->flush();
	}

    private function createComment($manager, $message, $user, $documentary, $created)
    {
        $comment = new Comment();
        $comment->setComment($message);
        $comment->setUser($user);
        $comment->setCreated($created);
        $comment->setStatus("publish");
        $comment->setDocumentary($documentary);
        $manager->persist($comment);
        return $comment;
    }

	public function getOrder()
	{
		return 6;
	}
}