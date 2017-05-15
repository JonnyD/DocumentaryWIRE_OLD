<?php

namespace DW\DWBundle\Manager;

use DW\DWBundle\Entity\Comment;
use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Event\CommentEvent;
use DW\DWBundle\Event\CommentEvents;
use DW\DWBundle\Repository\CommentRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CommentManager
{
    private $commentRepository;
    private $eventDispatcher;
	
	public function __construct(CommentRepository $commentRepository,
                                EventDispatcherInterface $eventDispatcher)
	{
        $this->commentRepository = $commentRepository;
        $this->eventDispatcher = $eventDispatcher;
	}

    public function getCommentById($id)
    {
        return $this->commentRepository->findCommentById($id);
    }

    public function addComment(Comment $comment)
    {
        $this->commentRepository->save($comment);

        $this->eventDispatcher->dispatch(
            CommentEvents::DOCUMENTARY_COMMENT_ADDED,
            new CommentEvent($comment)
        );
    }

    public function getAllComments()
    {
        return $this->commentRepository->findAll();
    }

    public function getCommentsByDocumentary(Documentary $documentary)
    {
        return $this->commentRepository->findCommentsByDocumentary($documentary);
    }

    public function getParentCommentsByDocumentaryOrderedByDate(Documentary $documentary)
    {
        return $this->commentRepository->findParentCommentsByDocumentaryOrderedByDate($documentary);
    }

    public function getParentCommentsByDocumentaryOrderedByPoints(Documentary $documentary)
    {
        return $this->commentRepository->findParentCommentsByDocumentaryOrderedByPoints($documentary);
    }

    public function addUserToComments(User $user)
    {
        $comments = $this->commentRepository->findCommentsByEmailWithNoUser($user->getEmail());
        foreach ($comments as $comment) {
            $comment->setUser($user);
            $this->commentRepository->save($comment, false);
        }
        $this->commentRepository->flush();
    }

    public function getCommentsWithUser()
    {
        return $this->commentRepository->findCommentsWithUser();
    }
}