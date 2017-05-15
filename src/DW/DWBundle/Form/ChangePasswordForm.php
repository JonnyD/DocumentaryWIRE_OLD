<?php

namespace DW\DWBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', 'text')
            ->add('newPassword', 'repeated', array(
                'first_name'  => 'new password',
                'second_name' => 'confirm password'))
            ->add('submit', 'submit');
    }

    public function getName()
    {
        return 'change_password_form';
    }
}