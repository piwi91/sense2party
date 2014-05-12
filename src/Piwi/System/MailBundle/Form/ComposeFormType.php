<?php

namespace Piwi\System\MailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ComposeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('title', 'text', array(
            'label' => 'piwi.system.mail.compose.compose.title2'
        ));
        $builder->add('text', 'textarea', array(
            'label' => 'piwi.system.mail.compose.compose.text',
            'attr' => array('rows' => 10)
        ));
        $builder->add('save', 'submit', array(
            'label' => 'piwi.system.mail.compose.compose.send',
            'attr' => array('class' => 'pull-right btn-primary')
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwi\System\MailBundle\Entity\Mail'
        ));
    }

    public function getName()
    {
        return 'piwi_system_mail_compose';
    }
}