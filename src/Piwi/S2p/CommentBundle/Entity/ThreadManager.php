<?php

namespace Piwi\S2p\CommentBundle\Entity;

use FOS\CommentBundle\Entity\ThreadManager as BaseThreadManager;

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