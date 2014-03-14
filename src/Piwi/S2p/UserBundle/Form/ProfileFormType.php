<?php

namespace Piwi\S2p\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('firstName', 'text', array(
            'label' => 'piwi.s2p.user.profile.edit.firstname'
        ));
        $builder->add('lastName', 'text', array(
            'label' => 'piwi.s2p.user.profile.edit.lastname'
        ));
        $builder->add('birthday', 'birthday', array(
            'label' => 'piwi.s2p.user.profile.edit.birthday',
            'attr' => array('class' => 'col-md-1'),
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
        ));
        $builder->add('save', 'submit', array(
            'label' => 'piwi.s2p.user.profile.edit.save',
            'attr' => array('class' => 'pull-right btn-primary')
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwi\System\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 's2p_piwi_user_profile';
    }
}