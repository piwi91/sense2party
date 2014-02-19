<?php

namespace Piwi\S2p\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    /**
     * @param $username
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexAction($username)
    {
        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository('PiwiSystemUserBundle:User');

        if (!$user = $userRepository->findOneByUsername($username)) {
            throw $this->createNotFoundException('piwi.s2p.user.exception.user_not_found');
        }

        return $this->render(
            'PiwiS2pUserBundle:Profile:index.html.twig',
            array('user' => $user)
        );
    }
}
