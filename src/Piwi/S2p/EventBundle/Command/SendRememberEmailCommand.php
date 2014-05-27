<?php

namespace Piwi\S2p\EventBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendRememberEmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('s2p:event:send_remember_mail')
            ->setDescription('Send a remember mail for an event')
            ->addArgument('event', InputOption::VALUE_OPTIONAL, 'The event id')
            ->addOption(
                'cron',
                null,
                InputOption::VALUE_OPTIONAL,
                'The cronjob send e-mails to upcomming events. The optional value is an integer and defines the amount of days before an e-mail is send (default 1)',
                1
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cron = $input->getOption('cron');
        if (empty($cron)) { $cron = 0; }
        $event = $input->getArgument('event');
        if (empty($event)) { $event = 0; }

        if ($cron == 0 && $event == 0) {
            throw new \Exception('Neither cron or event is given');
        }

        $users = $this->getContainer()->get('piwi_system_user.manager')->getMembers();

        if ($event != 0) {
            $event = $this->getContainer()->get('doctrine')->getRepository('PiwiS2pEventBundle:Event')->find($event);
            $subject = "Herinnering evenement: " . $event->getTitle();
            /** @var $user \Piwi\System\UserBundle\Entity\User */
            foreach ($users as $user) {
                $htmlBody = $this->getContainer()->get('templating')->render(
                    'PiwiSystemMailBundle:Mail:remember_event.html.twig',
                    array(
                        'event' => $event,
                        'user' => $user
                    )
                );
                $this->getContainer()->get('piwi_system_mail.mailer')->sendMail($subject, $htmlBody, $user->getEmail());
                $output->writeln('Remember mail for event ' . $event->getTitle() . ' send to ' . $user->getEmail());
            }
        } else if ($cron != 0) {
            $tomorrow = new \DateTime('+ ' . $cron . ' days');
            /** @var $events \Piwi\S2p\EventBundle\Entity\Event[] */
            $events = $this->getContainer()->get('doctrine')->getRepository('PiwiS2pEventBundle:Event')
                ->findLatestEvents(999, false, $tomorrow->format('Y-m-d'), 0);
            foreach ($events as $event) {
                $subject = "Herinnering evenement: " . $event->getTitle();
                /** @var $user \Piwi\System\UserBundle\Entity\User */
                foreach ($users as $user) {
                    $htmlBody = $this->getContainer()->get('templating')->render(
                        'PiwiSystemMailBundle:Mail:remember_event.html.twig',
                        array(
                            'event' => $event,
                            'user' => $user
                        )
                    );
                    $this->getContainer()->get('piwi_system_mail.mailer')->sendMail($subject, $htmlBody, $user->getEmail());
                    $output->writeln('Remember mail for event ' . $event->getTitle() . ' send to ' . $user->getEmail());
                }
            }
        }
    }
}