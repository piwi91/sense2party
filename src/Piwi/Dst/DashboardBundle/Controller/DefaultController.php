<?php

namespace Piwi\Dst\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PiwiDstDashboardBundle:Default:index.html.twig', array(

        ));
    }
}
