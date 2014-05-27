<?php

namespace Piwi\S2p\EventBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="Piwi\S2p\EventBundle\Entity\EventRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Event
{
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\Length(min = "5")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="venue", type="string", length=255)
     * @Assert\Length(min = "5")
     */
    private $venue;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     * @Assert\Url()
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="poster", type="string", length=255)
     */
    private $poster;

    /**
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="event_poster", fileNameProperty="poster")
     *
     * @var File $image
     */
    private $posterFile;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title", "id"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var \Piwi\System\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Piwi\System\UserBundle\Entity\User", inversedBy="events")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=255)
     */
    private $userName;

    /**
     * @var \Piwi\S2p\EventBundle\Entity\EventAttendee
     *
     * @ORM\OneToMany(targetEntity="\Piwi\S2p\EventBundle\Entity\EventAttendee", mappedBy="event", cascade={"persist", "remove"})
     */
    private $attendees;

    /**
     * @var bool
     *
     * @ORM\Column(name="public", type="boolean", options={"default" = 1})
     */
    private $public = true;

    public function __construct()
    {
        $this->attendees = new ArrayCollection();
    }

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
     * Set title
     *
     * @param string $title
     * @return Event
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Event
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Event
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set venue
     *
     * @param string $venue
     * @return Event
     */
    public function setVenue($venue)
    {
        $this->venue = $venue;

        return $this;
    }

    /**
     * Get venue
     *
     * @return string 
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return Event
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set poster
     *
     * @param string $poster
     * @return Event
     */
    public function setPoster($poster)
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * Get poster
     *
     * @return string 
     */
    public function getPoster()
    {
        return $this->poster;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param File $posterFile
     */
    public function setPosterFile($posterFile)
    {
        $this->posterFile = $posterFile;
    }

    /**
     * @return File
     */
    public function getPosterFile()
    {
        return $this->posterFile;
    }

    /**
     * @param \Piwi\System\UserBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        $this->setUserName($user);
    }

    /**
     * @return \Piwi\System\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param \Piwi\System\UserBundle\Entity\User $attendees
     */
    public function setAttendees($attendees)
    {
        $this->attendees = $attendees;
    }

    /**
     * @param \Piwi\System\UserBundle\Entity\User $attendees
     */
    public function addAttendees($attendees)
    {
        $this->attendees[] = $attendees;
    }

    /**
     * @param \Piwi\System\UserBundle\Entity\User $attendees
     */
    public function removeAttendees($attendees)
    {
        $this->attendees->removeElement($attendees);
    }

    /**
     * @param array $status
     * @return \Piwi\S2p\EventBundle\Entity\EventAttendee[]
     */
    public function getAttendees($status = array(EventAttendee::MAYBE, EventAttendee::PRESENT))
    {
        return array_filter($this->attendees->toArray(), function ($eventAttendee) use ($status) {
            if (in_array($eventAttendee->getStatus(), $status)) {
                return true;
            }
            return false;
        });
    }

    public function getAllAttendees()
    {
        return $this->attendees;
    }

    /**
     * @param \Piwi\System\UserBundle\Entity\User[] $users
     * @return \Piwi\System\UserBundle\Entity\User[]
     */
    public function getAllNotificationUsers($users = array())
    {
        // Get all user entities from the EventAttendee entities
        $offUsers = array_map(function($attendee) {
            /** @var $attendee EventAttendee */
            return $attendee->getUser();
        }, $this->getAttendees(array(EventAttendee::OFF)));
        // Filter the users so it contains only the users which aren't signed off for this event
        return array_filter($users, function($user) use ($offUsers) {
            if (in_array($user, $offUsers)) {
                return false;
            }
            return true;
        });
    }

    /**
     * @param boolean $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * @return boolean
     */
    public function getPublic()
    {
        return $this->public;
    }
}
