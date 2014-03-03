<?php

namespace Piwi\S2p\PhotoBundle\EventListener;

use Oneup\UploaderBundle\Event\PostPersistEvent;
use Piwi\S2p\PhotoBundle\Entity\Photo;

class UploadListener
{
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function onUpload(PostPersistEvent $event)
    {
//        $em = $this->doctrine->getManager();
//
//        /**
//         * @var $gaufretteFile \Gaufrette\File
//         */
//        $gaufretteFile = $event->getFile();
//
//        $photo = new Photo();
//        $photo->setTitle($gaufretteFile->getName());
//        $photo->setUser();
//        $photo->setAlbum();
//
//        $em->persist($photo);
    }
}