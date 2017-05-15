<?php

namespace DW\DWBundle\Form;

use DW\DWBundle\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreateDocumentary extends AbstractType
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('seoTitle', 'text')
            ->add('slug', 'text', array('required' => false))
            ->add('description', 'textarea')
            ->add('seoDescription', 'textarea')
            ->add('excerpt', 'textarea')
            ->add('length', 'text')
            ->add('year', 'text')
            ->add('file')
            ->add('category', 'entity', array(
                'class' => 'DocumentaryWIREBundle:Category',
                'property' => 'name'
            ))
            ->add('status', 'choice', array(
                'choices'   => array('publish' => 'Publish', 'draft' => 'Draft')
            ))
            ->add('url', 'text', array('mapped' => false))
            ->add('shortUrl', 'text')
            ->add('save', 'submit');
    }

    private function getCategories()
    {
        $categories = $this->categoryRepository->findAll();
        return $categories;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DW\DWBundle\Entity\Documentary'
        ));
    }

    public function getName()
    {
        return 'create_documentary';
    }
}