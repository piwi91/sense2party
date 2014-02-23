<?php

namespace Piwi\S2p\EventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    public function listAction()
    {
        return $this->render('PiwiS2pEventBundle:Event:list.html.twig');
    }
}
