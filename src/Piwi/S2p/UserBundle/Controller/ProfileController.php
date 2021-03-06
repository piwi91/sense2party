<?php

namespace Piwi\S2p\UserBundle\Controller;

use Piwi\S2p\UserBundle\Form\ProfileFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends Controller
{
    /**
     * @param $username
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($username)
    {
        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository('PiwiSystemUserBundle:User');

        if (!$user = $userRepository->findOneByUsername($username)) {
            throw $this->createNotFoundException('piwi.s2p.user.exception.user_not_found');
        }

        if (!$this->getUser() && !$user->getShowProfile()) {
            throw new AccessDeniedException();
        } elseif ($this->getUser()) {
            if (!$this->getUser()->hasRole('ROLE_MEMBER') && !$user->getShowProfile()) {
                throw new AccessDeniedException();
            }
        }

        $events = $em->getRepository('PiwiS2pEventBundle:Event')
            ->findAttendedEventsByUser($user);
        $comments = $em->getRepository('PiwiS2pCommentBundle:Comment')
            ->findBy(array('author' => $user), array('createdAt' => 'DESC'));

        return $this->render(
            'PiwiS2pUserBundle:Profile:index.html.twig',
            array(
                'user' => $user,
                'events' => $events,
                'comments' => $comments
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

        $users = $userRepository->findBy(array(), array('firstName' => 'DESC','lastName' => 'DESC'));
        // Loop trough users to check if the user has a role ROLE_MEMBER
        $_users = array();
        /** @var $user \Piwi\System\UserBundle\Entity\User */
        foreach ($users as $user) {
            if ($user->hasRole('ROLE_MEMBER') && $user->getShowProfile()) {
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
