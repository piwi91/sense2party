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
 * @ORM\Table()
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
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
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
     */
    private $venue;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255)
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
     * @var \Piwi\System\UserBundle\Entity\User
     *
     * @ORM\ManyToMany(targetEntity="\Piwi\System\UserBundle\Entity\User", inversedBy="attendedEvents")
     */
    private $attendees;

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
     * @return \Piwi\System\UserBundle\Entity\User
     */
    public function getAttendees()
    {
        return $this->attendees;
    }
}
