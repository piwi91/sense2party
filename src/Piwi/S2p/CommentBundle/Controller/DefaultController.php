<?php

namespace Piwi\S2p\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PiwiS2pCommentBundle:Default:index.html.twig', array('name' => $name));
    }
}
