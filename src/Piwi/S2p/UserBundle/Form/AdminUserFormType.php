<?php

namespace Piwi\S2p\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('firstName', 'text', array(
            'label' => 'piwi.s2p.user.admin.edit.firstname'
        ));
        $builder->add('lastName', 'text', array(
            'label' => 'piwi.s2p.user.admin.edit.lastname'
        ));
        $builder->add('birthday', 'birthday', array(
            'label' => 'piwi.s2p.user.admin.edit.birthday',
            'attr' => array('class' => 'col-md-1'),
            'widget' => 'single_text',
            'format' => 'dd-MM-yyyy',
        ));
        $builder->add('roles', 'choice', array(
            'label' => 'piwi.s2p.user.admin.edit.roles',
            'multiple' => true,
            'choices' => $this->refactorRoles($options['roles']),
            'attr' => array('style' => 'height:100%;')
        ));
        $builder->add('email', 'email', array(
            'label' => 'piwi.s2p.user.admin.edit.email',
        ));
        $builder->add('enabled', 'checkbox', array(
            'label' => 'piwi.s2p.user.admin.edit.enabled',
            'required' => false,
            'render_required_asterisk' => false,
            'render_optional_text' => false,
        ));
        $builder->add('locked', 'checkbox', array(
            'label' => 'piwi.s2p.user.admin.edit.locked',
            'required' => false,
            'render_required_asterisk' => false,
            'render_optional_text' => false,
        ));
        $builder->add('save', 'submit', array(
            'label' => 'piwi.s2p.user.admin.edit.save',
            'attr' => array('class' => 'pull-right btn-primary')
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwi\System\UserBundle\Entity\User',
            'roles' => null
        ));
    }

    public function getName()
    {
        return 's2p_piwi_admin_user';
    }

    private function refactorRoles($originRoles)
    {
        $roles = array();
        $rolesAdded = array();

        // Add herited roles
        foreach ($originRoles as $roleParent => $rolesHerit) {
            $tmpRoles = array_values($rolesHerit);
            $rolesAdded = array_merge($rolesAdded, $tmpRoles);
            $roles[$roleParent] = array_combine($tmpRoles, $tmpRoles);
        }
        // Add missing superparent roles
        $rolesParent = array_keys($originRoles);
        foreach ($rolesParent as $roleParent) {
            if (!in_array($roleParent, $rolesAdded)) {
                $roles['-----'][$roleParent] = $roleParent;
            }
        }

        return $roles;
    }
}