<?php

namespace Piwi\System\MailBundle\Controller;

use Piwi\System\MailBundle\Entity\Mail;
use Piwi\System\MailBundle\Form\ComposeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ComposeController extends Controller
{
    public function composeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = new Mail();
        $form = $this->createForm(new ComposeFormType(), $entity, array(
            'show_legend' => false
        ));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $entity->setUser($this->getUser());
                $entity->setAuthor($this->getUser());

                $em->persist($entity);
                $em->flush();

                $this->newMail($entity);

                $this->get('session')->getFlashBag()->add(
                    'success', 'piwi.system.mail.compose.compose.flashbag.success'
                );

                return $this->redirect($this->generateUrl('homepage'));
            }
        }

        return $this->render(
            'PiwiSystemMailBundle:Compose:compose.html.twig',
            array (
                'form' => $form->createView()
            )
        );
    }

    /**
     * @param Mail $mailEntity
     */
    public function newMail(Mail $mailEntity)
    {
        $mailUsers = $this->getUsers();
        $subject = $mailEntity->getTitle();

        /** @var $user \Piwi\System\UserBundle\Entity\User */
        foreach ($mailUsers as $user) {
            $htmlBody = $this->renderView('PiwiSystemMailBundle:Mail:email.html.twig', array(
                'mail' => $mailEntity,
                'user' => $user
            ));
            $mail = $this->getDefaultMessage($subject);
            $mail->setBody($htmlBody, 'text/html');
            $mail->setTo($user->getEmail());
            $this->get('mailer')->send($mail);
        }

    }

    /**
     * @return array
     */
    protected function getUsers()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('PiwiSystemUserBundle:User')->findAll();
        $mailUsers = array();
        foreach ($users as $user) {
            if ($user->hasRole('ROLE_MEMBER')) {
                $mailUsers[] = $user;
            }
        }

        return $mailUsers;
    }

    /**
     * Get default message with subject
     *
     * @param $subject
     * @return \Swift_Mime_MimePart
     */
    protected function getDefaultMessage($subject)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('no_reply@sense2party.nl', 'Sense 2 Party');
        return $message;
    }
}