<?php

namespace Piwi\S2p\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('PiwiS2pEventBundle:Event')->findLatestEvents(5);
        $upcomingEvent = $events[0];

        $nextEvents = array();
        foreach ($events as $event) {
            if ($upcomingEvent !== $event) {
                $nextEvents[] = $event;
            }
        }

        // Make sure we got 4 row in the array
        if (count($nextEvents) < 4) {
            for ($i = count($nextEvents); $i < 4; $i++) {
                $nextEvents[] = null;
            }
        }

        $photos = $em->getRepository('PiwiS2pPhotoBundle:Photo')->findLatestPhotos(12);

        $comments = $em->getRepository('PiwiS2pCommentBundle:Comment')->findLatestComments(10);

        return $this->render('PiwiS2pDashboardBundle:Default:index.html.twig', array(
            'upcomingEvent' => $upcomingEvent,
            'events' => $nextEvents,
            'photos' => $photos,
            'comments' => $comments
        ));
    }
}
