<?php

namespace Piwi\S2p\UserBundle\Controller;

use Piwi\S2p\UserBundle\Form\AdminUserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository('PiwiSystemUserBundle:User');

        $users = $userRepository->findAll();

        return $this->render(
            'PiwiS2pUserBundle:Admin:list.html.twig',
            array('users' => $users)
        );
    }

    public function editAction($username, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository('PiwiSystemUserBundle:User');

        /** @var $user \Piwi\System\UserBundle\Entity\User */
        if (!$user = $userRepository->findOneByUsername($username)) {
            throw $this->createNotFoundException('piwi.s2p.user.exception.user_not_found');
        }

        $form = $this->createForm(new AdminUserFormType(), $user, array(
            'show_legend' => false,
            'roles' => $this->container->getParameter('security.role_hierarchy.roles')
        ));

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', 'piwi.s2p.user.admin.edit.flashbag.success'
                );

                return $this->redirect(
                    $this->generateUrl(
                        'piwi_s2p_user_admin_list'
                    )
                );
            }
        }

        return $this->render(
            'PiwiS2pUserBundle:Admin:edit.html.twig',
            array(
                'user' => $user,
                'form' => $form->createView()
            )
        );
    }

    public function deleteAction($username)
    {
        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository('PiwiSystemUserBundle:User');

        /** @var $user \Piwi\System\UserBundle\Entity\User */
        if (!$user = $userRepository->findOneByUsername($username)) {
            throw $this->createNotFoundException('piwi.s2p.user.exception.user_not_found');
        }

        $em->remove($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'success', $this->get('translator')->trans(
                'piwi.s2p.user.admin.delete.flashbag.success'
            )
        );

        return $this->redirect(
            $this->generateUrl('piwi_s2p_photo_photo_index')
        );
    }
}