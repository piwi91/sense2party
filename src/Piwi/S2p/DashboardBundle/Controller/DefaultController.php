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

        if ($this->getUser()) {
            $public = false;
        } else {
            $public = true;
        }
        $photos = $em->getRepository('PiwiS2pPhotoBundle:Photo')->findLatestPhotos(12, $public);

        $comments = $em->getRepository('PiwiS2pCommentBundle:Comment')->findLatestComments(10);

        // Get comment thread
        $commentService = $this->get('piwi_s2p_comment.comment');
        $commentService->getThreadByEntityId('event', $upcomingEvent->getId());

        return $this->render('PiwiS2pDashboardBundle:Default:index.html.twig', array(
            'upcomingEvent' => $upcomingEvent,
            'upcomingEventComments' => count($commentService->getComments()),
            'events' => $nextEvents,
            'photos' => $photos,
            'comments' => $comments
        ));
    }
}
