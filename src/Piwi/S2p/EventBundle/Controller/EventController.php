<?php

namespace Piwi\S2p\EventBundle\Controller;

use Piwi\S2p\EventBundle\Entity\Event;
use Piwi\S2p\EventBundle\Entity\EventAttendee;
use Piwi\S2p\EventBundle\Form\AddEventFormType;
use Piwi\S2p\EventBundle\Form\EditEventFormType;
use Piwi\System\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $eventRepository = $em->getRepository('PiwiS2pEventBundle:Event');

        if (!$event = $eventRepository->findOneBySlug($slug)) {
            throw $this->createNotFoundException('piwi.s2p.event.exception.event_not_found');
        }

        if ($user = $this->getUser()) {
            $attendee = $this->isAttendee($event, $user);
        } else {
            $attendee = null;
        }

        // Get comment thread
        $commentService = $this->get('piwi_s2p_comment.comment');
        $commentService->getThreadByEntityId('event', $event->getId());

        return $this->render(
            'PiwiS2pEventBundle:Event:index.html.twig',
            array(
                'event' => $event,
                'attendee' => $attendee,
                'thread' => $commentService->getThread(),
                'comments' => $commentService->getComments()
            )
        );
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $eventRepository = $em->getRepository('PiwiS2pEventBundle:Event');

        $events = $eventRepository->findAll();

        return $this->render(
            'PiwiS2pEventBundle:Event:list.html.twig',
            array('events' => $events)
        );
    }
    
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $event = new Event();
        $form = $this->createForm(new AddEventFormType(), $event, array(
            'show_legend' => false
        ));

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                if ($user = $this->getUser()) {
                    $event->setUser($user);
                } else {
                    $event->setUserName('Guest');
                }

                $em->persist($event);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', 'piwi.s2p.event.event.add.flashbag.success'
                );

                return $this->redirect(
                    $this->generateUrl(
                        'piwi_s2p_event_event_index',
                        array('slug' => $event->getSlug())
                    )
                );
            }
        }

        return $this->render(
            'PiwiS2pEventBundle:Event:add.html.twig',
            array(
                'event' => $event,
                'form' => $form->createView()
            )
        );
    }

    public function editAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $eventRepository = $em->getRepository('PiwiS2pEventBundle:Event');

        /** @var $event Event */
        if (!$event = $eventRepository->findOneBySlug($slug)) {
            throw $this->createNotFoundException('piwi.s2p.event.exception.event_not_found');
        }

        $form = $this->createForm(new EditEventFormType(), $event, array(
            'show_legend' => false
        ));

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em->persist($event);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success', 'piwi.s2p.event.event.edit.flashbag.success'
                );

                return $this->redirect(
                    $this->generateUrl(
                        'piwi_s2p_event_event_index',
                        array('slug' => $event->getSlug())
                    )
                );
            }
        }

        return $this->render(
            'PiwiS2pEventBundle:Event:edit.html.twig',
            array(
                'event' => $event,
                'form' => $form->createView()
            )
        );
    }

    public function deleteAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $eventRepository = $em->getRepository('PiwiS2pEventBundle:Event');

        /** @var $event Event */
        if (!$event = $eventRepository->findOneBySlug($slug)) {
            throw $this->createNotFoundException('piwi.s2p.event.exception.event_not_found');
        }

        $em->remove($event);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
            'success', 'piwi.s2p.event.event.delete.flashbag.success'
        );

        $this->redirect(
            $this->generateUrl('piwi_s2p_event_event_list')
        );
    }

    public function attendAction($slug, $status = EventAttendee::PRESENT)
    {
        $em = $this->getDoctrine()->getManager();

        $eventRepository = $em->getRepository('PiwiS2pEventBundle:Event');

        /** @var $event Event */
        if (!$event = $eventRepository->findOneBySlug($slug)) {
            throw $this->createNotFoundException('piwi.s2p.event.exception.event_not_found');
        }

        $user = $this->getUser();
        if (!$eventAttendee = $this->isAttendee($event, $user)) {
            $eventAttendee = new EventAttendee();
            $eventAttendee->setEvent($event);
            $eventAttendee->setUser($user);
        }
        $eventAttendee->setStatus($status);
        $em->persist($eventAttendee);
        $em->flush();

        return $this->redirect(
            $this->generateUrl('piwi_s2p_event_event_index', array('slug' => $event->getSlug()))
        );
    }

    public function cancelAttendAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $eventRepository = $em->getRepository('PiwiS2pEventBundle:Event');

        /** @var $event Event */
        if (!$event = $eventRepository->findOneBySlug($slug)) {
            throw $this->createNotFoundException('piwi.s2p.event.exception.event_not_found');
        }

        $user = $this->getUser();
        if ($eventAttendee = $this->isAttendee($event, $user)) {
            $em->remove($eventAttendee);
        }
        $em->flush();

        return $this->redirect(
            $this->generateUrl('piwi_s2p_event_event_index', array('slug' => $event->getSlug()))
        );
    }

    public function redirectAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $eventRepository = $em->getRepository('PiwiS2pEventBundle:Event');

        /** @var $event Event */
        if (!$event = $eventRepository->find($id)) {
            throw $this->createNotFoundException('piwi.s2p.event.exception.event_not_found');
        }

        return $this->redirect(
            $this->generateUrl('piwi_s2p_event_event_index', array('slug' => $event->getSlug()))
        );
    }

    protected function isAttendee(Event $event, User $user)
    {
        /** @var $eventAttendee EventAttendee */
        foreach ($event->getAttendees() as $eventAttendee) {
            if ($eventAttendee->getUser() == $user) {
                return $eventAttendee;
            }
        }
        return null;
    }
}
