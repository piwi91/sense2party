<?php
// src/MyProject/MyBundle/Entity/Thread.php

namespace Piwi\S2p\CommentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\CommentBundle\Entity\Thread as BaseThread;

/**
 * @ORM\Entity
 * @ORM\Table(name="thread")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Thread extends BaseThread
{
    /**
     * @var int $id
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @var string $entityName
     *
     * @ORM\Column(name="entity_name", type="string", length=255)
     */
    protected $entityName;

    /**
     * @var int $entityId
     *
     * @ORM\Column(name="entity_id", type="integer")
     */
    protected $entityId;

    /**
     * @param mixed $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        $this->id = $this->entityName . $this->entityId;
    }

    /**
     * @return mixed
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param int $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
        $this->id = $this->entityName . $this->entityId;
    }

    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->entityId;
    }
}