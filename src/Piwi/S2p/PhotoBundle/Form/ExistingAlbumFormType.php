<?php

namespace Piwi\S2p\PhotoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ExistingAlbumFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('album', 'entity', array(
            'class' => 'Piwi\S2p\PhotoBundle\Entity\Album',
            'label' => 'piwi.s2p.photo.photo.add.step1.album'
        ));
        $builder->add('next', 'submit', array(
            'label' => 'piwi.s2p.photo.photo.add.step1.next',
            'attr' => array('class' => 'pull-right btn-primary')
        ));
    }

    public function getName()
    {
        return 'piwi_s2p_photo_photo_choose_album';
    }
}