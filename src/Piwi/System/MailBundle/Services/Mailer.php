<?php

namespace Piwi\System\MailBundle\Services;

use Doctrine\Bundle\DoctrineBundle\Registry;

class Mailer
{
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    public function __construct(Registry $doctrine, \Swift_Mailer $mailer)
    {
        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
    }

    /**
     * @return \Swift_Mailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * Send new mail
     *
     * @param $subject
     * @param $htmlBody
     * @param $to
     */
    public function sendMail($subject, $htmlBody, $to)
    {
        $mail = $this->getDefaultMessage($subject);
        $mail->setBody($htmlBody, 'text/html');
        $mail->setTo($to);
        $this->mailer->send($mail);
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