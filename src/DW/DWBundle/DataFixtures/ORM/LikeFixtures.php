<?php

namespace DW\DWBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DW\DWBundle\Entity\Like;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LikeFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    private $manager;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $user1 = $this->getReference('user1');
        $user2 = $this->getReference('user2');
        $user3 = $this->getReference('user3');
        $user4 = $this->getReference('user4');
        $user5 = $this->getReference('user5');
        $user6 = $this->getReference('user6');
        $user7 = $this->getReference('user7');
        $user8 = $this->getReference('user8');
        $user9 = $this->getReference('user9');
        $user10 = $this->getReference('user10');
        $user11 = $this->getReference('user11');
        $user12 = $this->getReference('user12');
        $user13 = $this->getReference('user13');
        $user14 = $this->getReference('user14');
        $user15 = $this->getReference('user15');
        $user16 = $this->getReference('user16');

        $documentary1 = $this->getReference('documentary1');
        $documentary2 = $this->getReference('documentary2');
        $documentary3 = $this->getReference('documentary3');
        $documentary4 = $this->getReference('documentary4');
        $documentary5 = $this->getReference('documentary5');
        $documentary6 = $this->getReference('documentary6');
        $documentary7 = $this->getReference('documentary7');
        $documentary8 = $this->getReference('documentary8');
        $documentary9 = $this->getReference('documentary9');
        $documentary10 = $this->getReference('documentary10');
        $documentary11 = $this->getReference('documentary11');
        $documentary12 = $this->getReference('documentary12');
        $documentary13 = $this->getReference('documentary13');
        $documentary14 = $this->getReference('documentary14');
        $documentary15 = $this->getReference('documentary15');

        $this->createLike($user1, $documentary1);
        $this->createLike($user1, $documentary2);
        $this->createLike($user2, $documentary1);
        $this->createLike($user3, $documentary3);
        $this->createLike($user4, $documentary1);
        $this->createLike($user4, $documentary2);
        $this->createLike($user4, $documentary3);
        $this->createLike($user4, $documentary4);
        $this->createLike($user5, $documentary5);
        $this->createLike($user5, $documentary1);
        $this->createLike($user5, $documentary15);
        $this->createLike($user6, $documentary12);
        $this->createLike($user7, $documentary8);
        $this->createLike($user8, $documentary9);
        $this->createLike($user9, $documentary1);
        $this->createLike($user10, $documentary4);
        $this->createLike($user11, $documentary11);
        $this->createLike($user12, $documentary10);
        $this->createLike($user13, $documentary2);
        $this->createLike($user14, $documentary13);
        $this->createLike($user15, $documentary7);
        $this->createLike($user16, $documentary14);
        $this->createLike($user2, $documentary14);
        $this->createLike($user3, $documentary6);
        $this->createLike($user4, $documentary9);

        $manager->flush();
    }

    private function createLike($user, $documentary)
    {
        $like = new Like();
        $like->setUser($user);
        $like->setDocumentary($documentary);
        $this->manager->persist($like);
        return $like;
    }

    public function getOrder()
    {
        return 6;
    }
}