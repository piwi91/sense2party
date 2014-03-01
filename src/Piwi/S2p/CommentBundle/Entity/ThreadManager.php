<?php

namespace Piwi\S2p\CommentBundle\Entity;

use FOS\CommentBundle\Entity\ThreadManager as BaseThreadManager;
use FOS\CommentBundle\Event\ThreadEvent;
use FOS\CommentBundle\Events;
use FOS\CommentBundle\Model\Thread;

class ThreadManager extends BaseThreadManager
{
    /**
     * @param $entityName
     * @param $entityId
     * @return null|object
     */
    public function findThreadByEntityId($entityName, $entityId)
    {
        return $this->repository->findOneBy(
            array (
                'entityName' => $entityName,
                'entityId' => $entityId
            )
        );
    }
}