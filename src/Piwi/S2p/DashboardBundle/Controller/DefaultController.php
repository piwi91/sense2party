<?php

namespace Piwi\S2p\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PiwiS2pDashboardBundle:Default:index.html.twig', array(

        ));
    }
}
