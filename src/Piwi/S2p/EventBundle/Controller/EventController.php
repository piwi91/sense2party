<?php

namespace Piwi\S2p\EventBundle\Controller;

use Piwi\S2p\EventBundle\Entity\Event;
use Piwi\S2p\EventBundle\Form\AddEventFormType;
use Piwi\S2p\EventBundle\Form\EditEventFormType;
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

        return $this->render(
            'PiwiS2pEventBundle:Event:index.html.twig',
            array('event' => $event)
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

    public function attendAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $eventRepository = $em->getRepository('PiwiS2pEventBundle:Event');

        /** @var $event Event */
        if (!$event = $eventRepository->findOneBySlug($slug)) {
            throw $this->createNotFoundException('piwi.s2p.event.exception.event_not_found');
        }

        if ($user = $this->getUser()) {
            if (!in_array($user, $event->getAttendees()->toArray())) {
                $event->addAttendees($user);
                $em->persist($event);
                $em->flush();
            }
        }

        return $this->redirect(
            $this->generateUrl('piwi_s2p_event_event_index', array('slug' => $event->getSlug()))
        );
    }
}
