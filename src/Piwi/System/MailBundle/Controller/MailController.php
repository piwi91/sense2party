<?php

namespace Piwi\System\MailBundle\Controller;

use Piwi\S2p\EventBundle\Entity\Event;
use Piwi\System\MailBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MailController extends Controller
{
    public function readAction($type, $username, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('PiwiSystemUserBundle:User')->findOneByUsername($username);
        if (is_null($user)) {
            throw $this->createNotFoundException('User not found');
        }

        switch ($type)
        {
            case "event":
                $event = $em->getRepository('PiwiS2pEventBundle:Event')->find($id);
                if (is_null($event)) {
                    throw $this->createNotFoundException('Event not found');
                }
                return $this->render('PiwiSystemMailBundle:Mail:event.html.twig', array(
                    'event' => $event,
                    'user' => $user
                ));
                break;
            case "mail":
                $mail = $em->getRepository('PiwiSystemMailBundle:Mail')->find($id);
                if (is_null($mail)) {
                    throw $this->createNotFoundException('E-mail not found');
                }
                return $this->render('PiwiSystemMailBundle:Mail:email.html.twig', array(
                    'mail' => $mail,
                    'user' => $user
                ));
                break;

        }

        throw $this->createNotFoundException('No mail found');
    }

    /**
     * @param \Piwi\S2p\EventBundle\Entity\Event $event
     */
    public function newEventMail(Event $event)
    {
        $mailUsers = $this->getUsers();
        $subject = "Nieuw evenement: " . $event->getTitle();

        /** @var $user \Piwi\System\UserBundle\Entity\User */
        foreach ($mailUsers as $user) {
            $htmlBody = $this->renderView('PiwiSystemMailBundle:Mail:event.html.twig', array(
                'event' => $event,
                'user' => $user
            ));
            $mail = $this->getDefaultMessage($subject);
            $mail->setBody($htmlBody, 'text/html');
            $mail->setTo($user->getEmail());
            $this->get('mailer')->send($mail);
        }

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
