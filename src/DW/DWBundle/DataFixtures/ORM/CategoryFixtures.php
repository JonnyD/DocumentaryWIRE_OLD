<?php

namespace DW\DWBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use DW\DWBundle\Entity\Category;

class CategoryFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
    	$this->container = $container;
    }
    
	public function load(ObjectManager $manager)
	{
        $category1 = $this->createCategory($manager, "911", "911");
        $category2 = $this->createCategory($manager, "Art & Artists", "art-artists");
        $category3 = $this->createCategory($manager, "Biography", "biography");
        $category4 = $this->createCategory($manager, "Comedy", "comedy");
        $category5 = $this->createCategory($manager, "Conspiracy", "conspiracy");
        $category6 = $this->createCategory($manager, "Drugs", "drugs");
        $category7 = $this->createCategory($manager, "Economics", "economics");
        $category8 = $this->createCategory($manager, "Environment", "environment");
        $category9 = $this->createCategory($manager, "Health", "health");
        $category10 = $this->createCategory($manager, "History", "history");
        $category11 = $this->createCategory($manager, "Law", "law");
        $category12 = $this->createCategory($manager, "Media", "media");
        $category13 = $this->createCategory($manager, "Miltary & Law", "military-law");
        $category14 = $this->createCategory($manager, "Mystery", "mystery");
        $category15 = $this->createCategory($manager, "Nature", "nature");
        $category16 = $this->createCategory($manager, "Performing Arts", "performing-arts");
        $category17 = $this->createCategory($manager, "Philosophy", "philosophy");
        $category18 = $this->createCategory($manager, "Politics", "politics");
        $category19 = $this->createCategory($manager, "Preview Only", "preview-only");
        $category20 = $this->createCategory($manager, "Psychology", "psychology");
        $category21 = $this->createCategory($manager, "Religion", "religion");
        $category22 = $this->createCategory($manager, "Science", "science");
        $category23 = $this->createCategory($manager, "Sexuality", "sexuality");
        $category24 = $this->createCategory($manager, "Society", "society");
        $category25 = $this->createCategory($manager, "Sports", "sports");
        $category26 = $this->createCategory($manager, "Technology", "technology");
        $category27 = $this->createCategory($manager, "Uncategorized", "uncategorized");

		$manager->flush();
	}

    private function createCategory($manager, $name, $slug)
    {
        $category = new Category();
        $category->setName($name);
        $category->setSlug($slug);
        $manager->persist($category);
        $this->addReference($slug, $category);
        return $category;
    }

	public function getOrder()
	{
		return 3;
	}
}