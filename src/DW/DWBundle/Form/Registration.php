<?php

namespace DW\DWBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class Registration extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text')
            ->add('email', 'text')
            ->add('password', 'repeated', array(
                'first_name'  => 'password',
                'second_name' => 'confirm',
                'type'        => 'password'))
            ->add('submit', 'submit');
    }

    public function getName()
    {
        return 'registration';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'DW\DWBundle\Entity\User',
            'validation_groups' => array('registration')
        );
    }
}