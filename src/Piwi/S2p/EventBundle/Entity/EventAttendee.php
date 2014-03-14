<?php

namespace Piwi\S2p\EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventAttendee
 *
 * @ORM\Table(name="event_attendee")
 * @ORM\Entity(repositoryClass="Piwi\S2p\EventBundle\Entity\EventAttendeeRepository")
 */
class EventAttendee
{
    const PRESENT = 'present';
    const MAYBE = 'maybe';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var \Piwi\S2p\EventBundle\Entity\Event
     *
     * @ORM\ManyToOne(targetEntity="\Piwi\S2p\EventBundle\Entity\Event", inversedBy="attendees")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;

    /**
     * @var \Piwi\System\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Piwi\System\UserBundle\Entity\User", inversedBy="attendedEvents")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return EventAttendee
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \Piwi\S2p\EventBundle\Entity\Event $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return \Piwi\S2p\EventBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param \Piwi\System\UserBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Piwi\System\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
