<?php

namespace Piwi\S2p\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrivacyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('show_email', 'checkbox', array(
            'label' => 'piwi.s2p.user.privacy.edit.show_email',
            'widget_checkbox_label' => 'label',
            'horizontal_label_class' => 'col-md-6',
            'horizontal_input_wrapper_class' => 'col-md-6',
            'render_optional_text' => false,
            'required' => false
        ));
        $builder->add('show_birthday', 'checkbox', array(
            'label' => 'piwi.s2p.user.privacy.edit.show_birthday',
            'widget_checkbox_label' => 'label',
            'horizontal_label_class' => 'col-md-6',
            'horizontal_input_wrapper_class' => 'col-md-6',
            'render_optional_text' => false,
            'required' => false
        ));
        $builder->add('save', 'submit', array(
            'label' => 'piwi.s2p.user.privacy.edit.save',
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
        return 's2p_piwi_user_privacy';
    }
}