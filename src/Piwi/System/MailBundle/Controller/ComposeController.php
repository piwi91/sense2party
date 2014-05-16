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
     * Send e-mail to users
     *
     * @param Mail $mailEntity
     */
    protected  function newMail(Mail $mailEntity)
    {
        $mailUsers = $this->get('piwi_system_user.manager')->getMembers();
        $subject = $mailEntity->getTitle();

        /** @var $user \Piwi\System\UserBundle\Entity\User */
        foreach ($mailUsers as $user) {
            $htmlBody = $this->renderView('PiwiSystemMailBundle:Mail:email.html.twig', array(
                'mail' => $mailEntity,
                'user' => $user
            ));
            $this->get('piwi_system_mail.mailer')->sendMail($subject, $htmlBody, $user->getEmail());
        }

    }
}