<?php

namespace Piwi\S2p\PhotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Album
 *
 * @ORM\Table(name="album")
 * @ORM\Entity(repositoryClass="Piwi\S2p\PhotoBundle\Entity\AlbumRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Album
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
     * @var integer
     *
     * @ORM\Column(name="views", type="integer")
     */
    private $views = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="count", type="integer")
     */
    private $count = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var \Piwi\System\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Piwi\System\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"title", "id"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @var \Piwi\S2p\PhotoBundle\Entity\Photo
     *
     * @ORM\OneToMany(targetEntity="\Piwi\S2p\PhotoBundle\Entity\Photo", mappedBy="album")
     */
    private $photos;

    /**
     * @var \Piwi\S2p\PhotoBundle\Entity\Photo
     *
     * @ORM\OneToOne(targetEntity="\Piwi\S2p\PhotoBundle\Entity\Photo")
     */
    private $preview;

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
     * @return Album
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
     * @return Album
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
     * @ORM\PrePersist()
     * @param \DateTime $date
     * @return Album
     */
    public function setDate($date)
    {
        $this->date = new \DateTime('now');

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
     * Set views
     *
     * @param integer $views
     * @return Album
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Album
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
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
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param \Piwi\System\UserBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        if ($this->user) {
            $this->author = $user;
        }
    }

    /**
     * @return \Piwi\System\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Piwi\S2p\PhotoBundle\Entity\Photo $preview
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;
    }

    /**
     * @return \Piwi\S2p\PhotoBundle\Entity\Photo
     */
    public function getPreview()
    {
        if ($this->preview instanceof Photo) {
            return $this->preview->getFilename();
        }
        return null;
    }

    function __toString()
    {
        return $this->getTitle();
    }
}
