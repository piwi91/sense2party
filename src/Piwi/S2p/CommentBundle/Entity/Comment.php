<?php
// src/MyProject/MyBundle/Entity/Comment.php

namespace Piwi\S2p\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Comment as BaseComment;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Comment extends BaseComment
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Thread of this comment
     *
     * @var Thread
     * @ORM\ManyToOne(targetEntity="Piwi\S2p\CommentBundle\Entity\Thread")
     */
    protected $thread;

    /**
     * Author of the comment
     *
     * @ORM\ManyToOne(targetEntity="Piwi\System\UserBundle\Entity\User")
     * @var \Piwi\System\UserBundle\Entity\User
     */
    protected $author;

    /**
     * @param \Piwi\System\UserBundle\Entity\User $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return \Piwi\System\UserBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getAuthorName()
    {
        if (null === $this->getAuthor()) {
            return 'Guest';
        }

        return $this->getAuthor()->getUsername();
    }
}