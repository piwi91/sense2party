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
     * @ORM\OneToMany(targetEntity="\Piwi\S2p\EventBundle\Entity\Event", mappedBy="user", cascade={"persist"})
     */
    protected $events;

    /**
     * @var \Piwi\S2p\EventBundle\Entity\EventAttendee
     *
     * @ORM\OneToMany(targetEntity="\Piwi\S2p\EventBundle\Entity\EventAttendee", mappedBy="user", cascade={"remove"})
     */
    private $attendedEvents;

    /**
     * @var \Piwi\S2p\PhotoBundle\Entity\Album
     *
     * @ORM\OneToMany(targetEntity="\Piwi\S2p\PhotoBundle\Entity\Album", mappedBy="user", cascade={"persist"})
     */
    private $albums;

    /**
     * @var \Piwi\S2p\PhotoBundle\Entity\Photo
     *
     * @ORM\OneToMany(targetEntity="\Piwi\S2p\PhotoBundle\Entity\Photo", mappedBy="user", cascade={"persist"})
     */
    private $photos;

    /**
     * @var \Piwi\S2p\CommentBundle\Entity\Comment
     *
     * @ORM\OneToMany(targetEntity="\Piwi\S2p\CommentBundle\Entity\Comment", mappedBy="author", cascade={"persist"})
     */
    private $comments;

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

    /**
     * @param \Piwi\S2p\PhotoBundle\Entity\Album $albums
     */
    public function setAlbums($albums)
    {
        $this->albums = $albums;
    }

    /**
     * @return \Piwi\S2p\PhotoBundle\Entity\Album
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * @param \Piwi\S2p\PhotoBundle\Entity\Photo $photos
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
    }

    /**
     * @return \Piwi\S2p\PhotoBundle\Entity\Photo
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param \Piwi\S2p\CommentBundle\Entity\Comment $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    public function countAttendedEvents()
    {
        return count($this->getAttendedEvents());
    }

    public function countMaybeAttendedEvents()
    {
        if ($this->countAttendedEvents() > 0) {
            $countMaybe = 0;
            /** @var $attendedEvent \Piwi\S2p\EventBundle\Entity\EventAttendee */
            foreach ($this->getAttendedEvents() as $attendedEvent) {
                if ($attendedEvent->getStatus() == $attendedEvent::MAYBE) {
                    $countMaybe++;
                }
            }
            return $countMaybe;
        }
        return 0;
    }

    /**
     * @return \Piwi\S2p\CommentBundle\Entity\Comment
     */
    public function getComments()
    {
        return $this->comments;
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

        if ($this->getAlbums()) {
            $_albums = array();
            /** @var $album \Piwi\S2p\PhotoBundle\Entity\Album */
            foreach ($this->getAlbums() as $album) {
                $album->setUser(null);
                $album->setAuthor($this);
                $_albums[] = $album;
            }
            $this->setAlbums($_albums);
        }

        if ($this->getPhotos()) {
            $_photos = array();
            /** @var $photo \Piwi\S2p\PhotoBundle\Entity\Photo */
            foreach ($this->getPhotos() as $photo) {
                $photo->setUser(null);
                $photo->setAuthor($this);
                $_photos[] = $photo;
            }
            $this->setPhotos($_photos);
        }
    }
}