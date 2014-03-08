<?php

namespace Piwi\S2p\EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditEventFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('title', 'text', array(
            'label' => 'piwi.s2p.event.event.add.title2'
        ));
        $builder->add('date', 'date', array(
            'label' => 'piwi.s2p.event.event.add.date',
            'attr' => array('class' => 'col-md-1'),
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
        ));
        $builder->add('description', 'textarea', array(
            'label' => 'piwi.s2p.event.event.add.description',
            'attr' => array('rows' => 10)
        ));
        $builder->add('venue', 'text', array(
            'label' => 'piwi.s2p.event.event.add.venue'
        ));
        $builder->add('source', 'text', array(
            'label' => 'piwi.s2p.event.event.add.source',
            'required' => false,
            'render_required_asterisk' => false,
            'render_optional_text' => false
        ));
        $builder->add('public', 'checkbox', array(
            'required' => false,
            'render_required_asterisk' => false,
            'render_optional_text' => false,
            'value' => true,
            'label' => 'piwi.s2p.event.event.edit.public'
        ));
        $builder->add('save', 'submit', array(
            'label' => 'piwi.s2p.user.profile.edit.save',
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
        return 'piwi_s2p_event_event_edit';
    }
}