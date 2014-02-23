<?php

namespace Piwi\S2p\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function loginAction()
    {
        return $this->render(
            'PiwiS2pUserBundle::login.html.twig'
        );
    }

    public function loginFailedAction()
    {
        $this->get('session')->getFlashBag()->add(
            'error',
            'piwi.s2p.user.login.flashbag.error'
        );

        return $this->redirect(
            $this->generateUrl('piwi_s2p_user_login')
        );
    }
}
