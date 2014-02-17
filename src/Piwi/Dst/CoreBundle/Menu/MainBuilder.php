<?php
namespace Piwi\Dst\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class MainBuilder extends ContainerAware
{
    public function menu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'navbar' => true,
        ));

        $menu->addChild('Home',
            array(
                'icon' => 'home',
                'route' => 'homepage'
            )
        );

        return $menu;
    }
}