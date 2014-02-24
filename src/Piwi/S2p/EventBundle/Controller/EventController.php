<?php

namespace Piwi\S2p\EventBundle\Controller;

use Piwi\S2p\EventBundle\Entity\Event;
use Piwi\S2p\EventBundle\Form\EventFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
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
        $form = $this->createForm(new EventFormType(), $event, array(
            'show_legend' => false
        ));

        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $em->persist($event);
                $em->flush();

//                $this->container->get('vich_uploader.storage')->upload($event);

                $this->get('session')->getFlashBag()->add(
                    'success', 'piwi.s2p.event.event.add.flashbag.success'
                );

                return $this->redirect(
                    $this->generateUrl(
                        'piwi_s2p_event_event_list'
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
}
