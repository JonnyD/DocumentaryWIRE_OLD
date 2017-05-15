<?php

namespace FL\FifaLeague\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Entity\Role;
use FOS\UserBundle\Doctrine\UserManager;

class UserFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
    	$this->container = $container;
    }
    
	public function load(ObjectManager $manager)
	{
        $user1 = $this->createUser($manager, "user1");
        $user2 = $this->createUser($manager, "user2");
        $user3 = $this->createUser($manager, "user3");
        $user4 = $this->createUser($manager, "user4");
        $user5 = $this->createUser($manager, "user5");
        $user6 = $this->createUser($manager, "user6");
        $user7 = $this->createUser($manager, "user7");
        $user8 = $this->createUser($manager, "user8");
        $user9 = $this->createUser($manager, "user9");
        $user10 = $this->createUser($manager, "user10");
        $user11 = $this->createUser($manager, "user11");
        $user12 = $this->createUser($manager, "user12");
        $user13 = $this->createUser($manager, "user13");
        $user14 = $this->createUser($manager, "user14");
        $user15 = $this->createUser($manager, "user15");
        $user16 = $this->createUser($manager, "user16");

        $manager->flush();

        $this->addReference("user1", $user1);
        $this->addReference("user2", $user2);
        $this->addReference("user3", $user3);
        $this->addReference("user4", $user4);
        $this->addReference("user5", $user5);
        $this->addReference("user6", $user6);
        $this->addReference("user7", $user7);
        $this->addReference("user8", $user8);
        $this->addReference("user9", $user9);
        $this->addReference("user10", $user10);
        $this->addReference("user11", $user11);
        $this->addReference("user12", $user12);
        $this->addReference("user13", $user13);
        $this->addReference("user14", $user14);
        $this->addReference("user15", $user15);
        $this->addReference("user16", $user16);
	}

    private function createUser($manager, $username)
    {
        $userRole = $this->getReference("userRole");

        $user = new User();
        $user->setUsername($username);
        $user->setDisplayName($username);
        $user->setEmail($username . "@email.com");
        $user->addRole($userRole);
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $encodedPass = $encoder->encodePassword('pass', $user->getSalt());
        $user->setPassword($encodedPass);
        $manager->persist($user);
        return $user;
    }
	
	public function getOrder()
	{
		return 2;
	}
}