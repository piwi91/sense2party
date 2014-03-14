<?php

namespace Piwi\S2p\CommentBundle\Services;

use FOS\CommentBundle\Model\CommentManagerInterface;
use Piwi\S2p\CommentBundle\Entity\ThreadManager;
use Symfony\Component\HttpFoundation\RequestStack;

class Comment
{
    /**
     * @var \FOS\CommentBundle\Model\ThreadManagerInterface|\Piwi\S2p\CommentBundle\Entity\ThreadManager
     */
    private $threadManager;

    /**
     * @var \FOS\CommentBundle\Model\CommentManagerInterface
     */
    private $commentManager;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    private $thread;

    private $comments;

    /**
     * @param ThreadManager $threadManager
     * @param CommentManagerInterface $commentManager
     * @param RequestStack $requestStack
     */
    public function __construct(
        ThreadManager $threadManager,
        CommentManagerInterface $commentManager,
        RequestStack $requestStack
    )
    {
        $this->threadManager = $threadManager;
        $this->commentManager = $commentManager;
        $this->requestStack = $requestStack;
    }

    public function getThreadByEntityId($entityName, $entityId)
    {
        $thread = $this->threadManager->findThreadByEntityId($entityName, $entityId);
        if (null === $thread) {
            $thread = $this->threadManager->createThread();
            $thread->setPermalink($this->requestStack->getMasterRequest()->getUri());
            $thread->setEntityName($entityName);
            $thread->setEntityId($entityId);

            // Add the thread
            $this->threadManager->saveThread($thread);
        }

        $comments = $this->commentManager->findCommentTreeByThread($thread);

        $this->thread = $thread;
        $this->comments = $comments;
    }

    /**
     * @param mixed $thread
     */
    public function setThread($thread)
    {
        $this->thread = $thread;
    }

    /**
     * @return mixed
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }
}