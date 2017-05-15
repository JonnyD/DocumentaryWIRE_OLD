<?php

namespace DW\DWBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
        $builder
        	->add('source', 'entity', array(
        		'class' => 'DocumentaryWIREBundle:Source',
        		'property' => 'name',
        		'empty_value' => '--Source--'
        	))
            ->add('videoId', 'text');
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    		'data_class' => 'DW\DWBundle\Entity\Link'
    	));
    }

    public function getName()
    {
        return 'link';
    }
}