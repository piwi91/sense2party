<?php

namespace Piwi\S2p\UserBundle\Controller;

use Piwi\S2p\UserBundle\Form\ProfileFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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

        $eventRepository = $em->getRepository('PiwiS2pEventBundle:Event');

        $events = $eventRepository->findAttendedEventsByUser($user);

        return $this->render(
            'PiwiS2pUserBundle:Profile:index.html.twig',
            array(
                'user' => $user,
                'events' => $events
            )
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository('PiwiSystemUserBundle:User');

        $users = $userRepository->findAll();
        // Loop trough users to check if the user has a role ROLE_MEMBER
        $_users = array();
        foreach ($users as $user) {
            if ($user->hasRole('ROLE_MEMBER')) {
                $_users[] = $user;
            }
        }
        // Replace $users variable by the filtered array
        $users = $_users;

        return $this->render(
            'PiwiS2pUserBundle:Profile:list.html.twig',
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

        $form = $this->createForm(new ProfileFormType(), $user, array(
            'show_legend' => false
        ));

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', 'piwi.s2p.user.profile.edit.flashbag.success'
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
            'PiwiS2pUserBundle:Profile:edit.html.twig',
            array(
                'user' => $user,
                'form' => $form->createView()
            )
        );
    }
}
