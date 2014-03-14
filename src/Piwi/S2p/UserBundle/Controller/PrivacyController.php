<?php

namespace Piwi\S2p\UserBundle\Controller;

use Piwi\S2p\UserBundle\Form\PrivacyFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PrivacyController extends Controller
{
    /**
     * @param $username
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction($username, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository('PiwiSystemUserBundle:User');

        /** @var $user \Piwi\System\UserBundle\Entity\User */
        if (!$user = $userRepository->findOneByUsername($username)) {
            throw $this->createNotFoundException('piwi.s2p.user.exception.user_not_found');
        }

        $form = $this->createForm(new PrivacyFormType(), $user, array(
            'show_legend' => false
        ));

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', 'piwi.s2p.user.privacy.edit.flashbag.success'
                );

                return $this->redirect(
                    $this->generateUrl(
                        'piwi_s2p_user_profile',
                        array('username' => $user->getUsername())
                    )
                );
            }
        }

        return $this->render(
            'PiwiS2pUserBundle:Privacy:edit.html.twig',
            array(
                'user' => $user,
                'form' => $form->createView()
            )
        );
    }
}
