<?php

namespace Piwi\S2p\DashboardBundle\Controller;

use Piwi\S2p\EventBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        if ($this->get('security.context')->isGranted('ROLE_MEMBER')) {
            $public = false;
        } else {
            $public = true;
        }

        $events = $em->getRepository('PiwiS2pEventBundle:Event')->findLatestEvents(5, $public);
        $upcomingEvent = null;
        if (count($events) > 0) {
            $upcomingEvent = $events[0];
        }

        /** @var Event $nextEvents */
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

        $commentCount = null;
        if (!is_null($upcomingEvent)) {
            // Get comment thread
            $commentService = $this->get('piwi_s2p_comment.comment');
            $commentService->getThreadByEntityId('event', $upcomingEvent->getId());
            $commentCount = count($commentService->getComments());
        }

        $photos = $em->getRepository('PiwiS2pPhotoBundle:Photo')->findLatestPhotos(12, $public);

        $comments = $em->getRepository('PiwiS2pCommentBundle:Comment')->findLatestComments(10);

        return $this->render('PiwiS2pDashboardBundle:Default:index.html.twig', array(
            'upcomingEvent' => $upcomingEvent,
            'upcomingEventComments' => $commentCount,
            'events' => $nextEvents,
            'photos' => $photos,
            'comments' => $comments
        ));
    }
}
