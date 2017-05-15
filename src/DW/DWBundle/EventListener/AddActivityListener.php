<?php

namespace DW\DWBundle\EventListener;

use DW\DWBundle\Entity\ActivityType;
use DW\DWBundle\Entity\ActivityComponent;
use DW\DWBundle\Event\CommentEvent;
use DW\DWBundle\Event\CommentEvents;
use DW\DWBundle\Event\DocumentaryEvent;
use DW\DWBundle\Event\DocumentaryEvents;
use DW\DWBundle\Event\LikeEvent;
use DW\DWBundle\Event\LikeEvents;
use DW\DWBundle\Event\UserEvent;
use DW\DWBundle\Event\UserEvents;
use DW\DWBundle\Manager\ActivityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddActivityListener implements EventSubscriberInterface
{
    private $activityManager;

    public static function getSubscribedEvents()
    {
        return array(
            DocumentaryEvents::NEW_DOCUMENTARY_ADDED => "onDocumentaryAdded",
            LikeEvents::DOCUMENTARY_LIKED => "onDocumentaryLiked",
            UserEvents::USER_CONFIRMED => "onUserConfirmed",
            CommentEvents::DOCUMENTARY_COMMENT_ADDED => "onCommentAdded"
        );
    }

    public function __construct(ActivityManager $activityManager)
    {
        $this->activityManager = $activityManager;
    }

    public function onDocumentaryAdded(DocumentaryEvent $documentaryEvent)
    {
        $documentary = $documentaryEvent->getDocumentary();

        $data = array(
            "documentaryId" => $documentary->getId(),
            "documentaryTitle" => $documentary->getTitle(),
            "documentaryExcerpt" => $documentary->getExcerpt(),
            "documentaryThumbnail" => $documentary->getThumbnail(),
            "documentarySlug" => $documentary->getSlug());

        $this->activityManager->addActivity($documentary->getAddedBy(), $documentary->getId(), ActivityType::ADDED,
            ActivityComponent::DOCUMENTARY, $data);
    }

    public function onDocumentaryLiked(LikeEvent $likeEvent)
    {
        $like = $likeEvent->getLike();
        $user = $like->getUser();
        $documentary = $like->getDocumentary();

        $activity = $this->activityManager->getActivity($user, $documentary->getId(),
            ActivityType::LIKE, ActivityComponent::DOCUMENTARY);

        if ($activity) {
            $activity->setCreated(new \DateTime());
            $this->activityManager->persist($activity);
        } else {
            $data = array(
                "documentaryId" => $documentary->getId(),
                "documentaryTitle" => $documentary->getTitle(),
                "documentaryExcerpt" => $documentary->getExcerpt(),
                "documentaryThumbnail" => $documentary->getThumbnail(),
                "documentarySlug" => $documentary->getSlug());

            $this->activityManager->addActivity($user, $documentary->getId(),
                ActivityType::LIKE, ActivityComponent::DOCUMENTARY, $data);
        }
    }

    public function onUserConfirmed(UserEvent $userEvent)
    {
        $user = $userEvent->getUser();
        $this->activityManager->addActivity($user, $user->getId(), ActivityType::JOINED,
            ActivityComponent::USER, array());
    }

    public function onCommentAdded(CommentEvent $commentEvent)
    {
        $comment = $commentEvent->getComment();
        $documentary = $comment->getDocumentary();
        $user = $comment->getUser();

        $data = array(
            "documentaryId" => $documentary->getId(),
            "documentaryTitle" => $documentary->getTitle(),
            "documentaryThumbnail" => $documentary->getThumbnail(),
            "documentarySlug" => $documentary->getSlug(),
            "comment" => $comment->getComment());

        $this->activityManager->addActivity($user, $comment->getId(),
            ActivityType::COMMENT, ActivityComponent::DOCUMENTARY, $data);
    }
}