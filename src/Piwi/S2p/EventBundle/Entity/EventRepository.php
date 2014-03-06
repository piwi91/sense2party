<?php

namespace Piwi\S2p\EventBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Piwi\System\UserBundle\Entity\User;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends EntityRepository
{
    public function findAttendedEventsByUser(User $user)
    {
        $qb = $this->createQueryBuilder('event');
        $qb
            ->innerJoin('event.attendees', 'eventAttendee')
            ->where($qb->expr()->eq('eventAttendee.user', ':userId'))
            ->setParameter('userId', $user->getId());

        return $qb->getQuery()->getResult();
    }

    public function findLatestEvents($limit = 10)
    {
        $qb = $this->createQueryBuilder('event');

        $qb->where('event.date >= :date');
        $qb->orderBy('event.date', 'ASC');
        $qb->setParameter('date', date('Y-m-d'));
        $qb->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
