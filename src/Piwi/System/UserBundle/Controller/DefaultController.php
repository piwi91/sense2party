<?php

namespace Piwi\System\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PiwiSystemUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
