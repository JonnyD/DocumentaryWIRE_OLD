<?php

namespace EliteFifa\Bundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use DW\DWBundle\Entity\Role;

class RoleFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userRole = new Role();
        $userRole->setName("user");
        $userRole->setRole("ROLE_USER");
        $manager->persist($userRole);
        $manager->flush();

        $adminRole = new Role();
        $adminRole->setName("admin");
        $adminRole->setRole("ROLE_ADMIN");
        $manager->persist($adminRole);
        $manager->flush();

        $this->addReference('userRole', $userRole);
        $this->addReference('adminRole', $adminRole);
    }

    public function getOrder()
    {
        return 1;
    }
}