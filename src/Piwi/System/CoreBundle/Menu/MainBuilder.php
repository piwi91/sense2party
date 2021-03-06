<?php
namespace Piwi\System\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class MainBuilder extends ContainerAware
{
    public function menu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'navbar' => true
        ));
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('menu.home',
            array(
                'route' => 'homepage'
            )
        );
        $menu->addChild('menu.events',
            array(
                'route' => 'piwi_s2p_event_event_list'
            )
        );
        $menu->addChild('menu.photos',
            array(
                'route' => 'piwi_s2p_photo_photo_index'
            )
        );
        $menu->addChild('menu.members',
            array(
                'route' => 'piwi_s2p_user_profile_list'
            )
        );

        return $menu;
    }
}