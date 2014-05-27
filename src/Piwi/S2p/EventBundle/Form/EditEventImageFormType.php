<?php

namespace Piwi\S2p\EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditEventImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('posterFile', 'file', array(
            'label' => 'piwi.s2p.event.event.add.poster'
        ));
        $builder->add('save', 'submit', array(
            'label' => 'piwi.s2p.event.event.edit_image.save',
            'attr' => array('class' => 'pull-right btn-primary')
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwi\S2p\EventBundle\Entity\Event'
        ));
    }

    public function getName()
    {
        return 'piwi_s2p_event_event_edit_image';
    }
}