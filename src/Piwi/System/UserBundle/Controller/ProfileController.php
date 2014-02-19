<?php

namespace Piwi\System\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    public function indexAction()
    {
        return $this->render('PiwiSystemUserBundle:Profile:index.html.twig');
    }
}
