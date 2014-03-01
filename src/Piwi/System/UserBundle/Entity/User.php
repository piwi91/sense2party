<?php

namespace Piwi\System\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="first_name", type="string", length=255, nullable=false) */
    protected $firstName;

    /** @ORM\Column(name="last_name", type="string", length=255, nullable=false) */
    protected $lastName;

    /** @ORM\Column(name="locale", type="string", length=10, nullable=false) */
    protected $locale = "en_US";

    /** @ORM\Column(name="birthday", type="datetime", nullable=true) */
    protected $birthday;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebookId;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebookAccessToken;

    /** @ORM\Column(name="facebook_link", type="string", length=255, nullable=true) */
    protected $facebookLink;

    /** @ORM\Column(name="privacy_show_email", type="boolean") */
    protected $showEmail = true;

    /** @ORM\Column(name="privacy_show_birthday", type="boolean") */
    protected $showBirthday = true;

    /**
     * @var \Piwi\S2p\EventBundle\Entity\Event
     *
     * @ORM\OneToMany(targetEntity="\Piwi\S2p\EventBundle\Entity\Event", mappedBy="user")
     */
    protected $events;

    /**
     * @var \Piwi\S2p\EventBundle\Entity\EventAttendee
     *
     * @ORM\OneToMany(targetEntity="\Piwi\S2p\EventBundle\Entity\EventAttendee", mappedBy="user", cascade={"remove"})
     */
    private $attendedEvents;

    public function __construct()
    {
        parent::__construct();

        $this->events = new ArrayCollection();
        $this->attendedEvents = new ArrayCollection();
    }

    /**
     * @param mixed $facebookAccessToken
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param mixed $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $facebookLink
     */
    public function setFacebookLink($facebookLink)
    {
        $this->facebookLink = $facebookLink;
    }

    /**
     * @return mixed
     */
    public function getFacebookLink()
    {
        return $this->facebookLink;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $showBirthday
     */
    public function setShowBirthday($showBirthday)
    {
        $this->showBirthday = $showBirthday;
    }

    /**
     * @return mixed
     */
    public function getShowBirthday()
    {
        return $this->showBirthday;
    }

    /**
     * @param mixed $showEmail
     */
    public function setShowEmail($showEmail)
    {
        $this->showEmail = $showEmail;
    }

    /**
     * @return mixed
     */
    public function getShowEmail()
    {
        return $this->showEmail;
    }

    /**
     * @param \Piwi\S2p\EventBundle\Entity\Event $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }

    /**
     * @param \Piwi\S2p\EventBundle\Entity\Event $events
     */
    public function addEvents($events)
    {
        $this->events[] = $events;
    }

    /**
     * @param \Piwi\S2p\EventBundle\Entity\Event $events
     */
    public function removeEvents($events)
    {
        $this->events->removeElement($events);
    }

    /**
     * @return \Piwi\S2p\EventBundle\Entity\Event
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param \Piwi\System\UserBundle\Entity\User $attendedEvents
     */
    public function setAttendedEvents($attendedEvents)
    {
        $this->attendedEvents = $attendedEvents;
    }

    /**
     * @param \Piwi\System\UserBundle\Entity\User $attendedEvents
     */
    public function addAttendedEvents($attendedEvents)
    {
        $this->attendedEvents[] = $attendedEvents;
    }

    /**
     * @param \Piwi\System\UserBundle\Entity\User $attendedEvents
     */
    public function removeAttendedEvents($attendedEvents)
    {
        $this->attendedEvents->removeElement($attendedEvents);
    }

    /**
     * @return \Piwi\System\UserBundle\Entity\User
     */
    public function getAttendedEvents()
    {
        return $this->attendedEvents;
    }

    public function __toString()
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        // Delete relationships with this entity so we can delete it
        // Remove this user from the Event entity and set the user name again
        if ($this->getEvents()) {
            $_events = array();
            /** @var $event \Piwi\S2p\EventBundle\Entity\Event */
            foreach ($this->getEvents() as $event) {
                $event->setUser(null);
                $event->setUserName($this);
                $_events[] = $event;
            }
            $this->setEvents($_events);
        }
    }
}