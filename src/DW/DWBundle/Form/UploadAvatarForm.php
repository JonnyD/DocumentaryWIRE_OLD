<?php

namespace DW\DWBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UploadAvatarForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file')
            ->add('submit', 'submit');
    }

    public function getName()
    {
        return 'registration';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'DW\DWBundle\Entity\User'
        );
    }
}