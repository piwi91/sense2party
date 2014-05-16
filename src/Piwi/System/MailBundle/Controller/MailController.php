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

        $mail = $em->getRepository('PiwiSystemMailBundle:Mail')->find($id);
        if (is_null($mail)) {
            throw $this->createNotFoundException('Mail not found');
        }

        switch ($type)
        {
            case Mail::EVENT:
                return $this->render('PiwiSystemMailBundle:Mail:event.html.twig', array(
                    'event' => $mail,
                    'user' => $user
                ));
                break;
            case Mail::MAIL:
                return $this->render('PiwiSystemMailBundle:Mail:email.html.twig', array(
                    'mail' => $mail,
                    'user' => $user
                ));
                break;
        }

        throw $this->createNotFoundException(sprintf('Type %s not found', $type));
    }
}
