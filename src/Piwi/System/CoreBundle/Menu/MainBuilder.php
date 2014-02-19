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

        $menu->addChild('Home',
            array(
                'icon' => 'home',
                'route' => 'homepage'
            )
        );

        return $menu;
    }
}