<?php

namespace Piwi\S2p\PhotoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditAlbumFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('title', 'text', array(
            'label' => 'piwi.s2p.photo.photo.edit.title2'
        ));
        $builder->add('description', 'textarea', array(
            'label' => 'piwi.s2p.photo.photo.edit.description',
            'attr' => array('rows' => 10)
        ));
        $builder->add('public', 'checkbox', array(
            'required' => false,
            'render_required_asterisk' => false,
            'render_optional_text' => false,
            'value' => true,
            'label' => 'piwi.s2p.photo.photo.edit.public'
        ));
        $builder->add('submit', 'submit', array(
            'label' => 'piwi.s2p.photo.photo.edit.submit',
            'attr' => array('class' => 'pull-right btn-primary')
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwi\S2p\PhotoBundle\Entity\Album'
        ));
    }

    public function getName()
    {
        return 'piwi_s2p_photo_photo_edit_album';
    }
}