<?php

namespace Piwi\S2p\EventBundle\Twig;

class EventExtension extends \Twig_Extension
{
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getEventById', array($this, 'getEventById'))
        );
    }

    public function getEventById($id)
    {
        return $this->doctrine->getRepository('PiwiS2pEventBundle:Event')->find($id);
    }

    public function getName()
    {
        return 'event_extension';
    }
}