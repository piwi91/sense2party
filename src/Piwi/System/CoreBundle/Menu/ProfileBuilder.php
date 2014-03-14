<?php
namespace Piwi\System\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class ProfileBuilder extends ContainerAware
{
    public function menu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'navbar' => true
        ));
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('menu.profile.profile',
            array(
                'route' => 'piwi_s2p_user_profile'
            )
        );
        $menu->addChild('menu.profile.privacy',
            array(
                'route' => 'piwi_s2p_user_profile'
            )
        );

        return $menu;
    }
}