<?php

namespace Piwi\System\MailBundle\Controller;

use Piwi\S2p\EventBundle\Entity\Event;
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
        }

        throw $this->createNotFoundException('No mail found');
    }

    /**
     * @param \Piwi\S2p\EventBundle\Entity\Event $event
     */
    public function newEventMail(Event $event)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('PiwiSystemUserBundle:User')->findAll();
        $mailUsers = array();
        foreach ($users as $user) {
            if ($user->hasRole('ROLE_MEMBER')) {
                $mailUsers[] = $user;
            }
        }

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
     * Get default message with subject
     *
     * @param $subject
     * @return \Swift_Mime_MimePart
     */
    protected function getDefaultMessage($subject)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('no_reply@sense2party.nl');
        return $message;
    }
}
