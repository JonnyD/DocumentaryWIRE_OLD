<?php

namespace DW\DWBundle\Manager;

use DW\DWBundle\Entity\Activity;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Event\ActivityEvent;
use DW\DWBundle\Event\ActivityEvents;
use DW\DWBundle\Repository\ActivityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ActivityManager
{
    private $activityRepository;
    private $eventDispatcher;

    public function __construct(ActivityRepository $activityRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->activityRepository = $activityRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getActivityItem($id)
    {
        return $this->activityRepository->find($id);
    }

    public function getRawActivity()
    {
        return $this->activityRepository->findRawActivity();
    }

    public function getAllActivity()
    {
        return $this->activityRepository->findAll();
    }

    public function updateActivityForMigration($id, $data)
    {
        $this->activityRepository->updateActivityForMigration($id, $data);
    }

    public function createActivity(User $user, $objectId, $type, $component, $data, $groupNumber)
    {
        $activity = new Activity();
        $activity->setUser($user);
        $activity->setObjectId($objectId);
        $activity->setType($type);
        $activity->setComponent($component);
        $activity->setCreated(new \DateTime());
        $activity->setData($data);
        $activity->setGroupNumber($groupNumber);
        return $activity;
    }

    public function addActivity(User $user, $objectId, $type, $component, $data)
    {
        $groupNumber = $this->calculateGroupNumber();
        $activity = $this->createActivity($user, $objectId, $type, $component, $data, $groupNumber);
        $this->activityRepository->save($activity);
        $this->cascadeGroupNumber($user, $type, $groupNumber);

        $this->eventDispatcher->dispatch(
            ActivityEvents::ACTIVITY_ADDED,
            new ActivityEvent($activity)
        );
    }

    public function removeActivity(Activity $activity)
    {
        $this->activityRepository->remove($activity);
    }

    public function cascadeGroupNumber($user, $type, $groupNumber)
    {
        $to = new \DateTime();
        if ($type == "like") {
            $from = new \DateTime();
            $from->sub(new \DateInterval('P3W'));
            $previousActivity = $this->activityRepository->findActivityByUserAndTypeBetweenDates($user, $type, $from, $to);

            if ($previousActivity != null) {
                foreach ($previousActivity as $activity) {
                    $activity->setGroupNumber($groupNumber);
                    $this->activityRepository->save($activity, false);
                }
                $this->activityRepository->flush();
            }
        } else if ($type == "joined") {
            $from = new \DateTime();
            $from->sub(new \DateInterval('P1W'));
            $previousActivityByDate = $this->activityRepository->findActivityByTypeBetweenDates($type, $from, $to);
            if ($previousActivityByDate != null) {
                foreach ($previousActivityByDate as $activity) {
                    $activity->setGroupNumber($groupNumber);
                    $this->activityRepository->save($activity, false);
                }
                $tempGroupNumber = $this->activityRepository->findPreviousGroupNumber($groupNumber);
                if ($tempGroupNumber != null) {
                    $tempActivity = $this->activityRepository->findActivityByGroupNumber($tempGroupNumber);
                    $previousGroupNumber = $this->activityRepository->findPreviousGroupNumber($tempGroupNumber);
                    $previousActivity = $this->activityRepository->findActivityByGroupNumber($previousGroupNumber);
                    if (count($previousActivity) <= 20) {
                        foreach ($tempActivity as $activity) {
                            $activity->setGroupNumber($previousGroupNumber);
                            $this->activityRepository->save($activity, false);
                        }
                    }
                }
                $this->activityRepository->flush();
            }
        } else if ($type == "added") {
            $from = new \DateTime();
            $from->sub(new \DateInterval('P1W'));
            $previousActivity = $this->activityRepository->findActivityByUserAndTypeBetweenDates($user, $type, $from, $to);

            if ($previousActivity != null) {
                foreach ($previousActivity as $activity) {
                    $activity->setGroupNumber($groupNumber);
                    $this->activityRepository->save($activity, false);
                }
                $this->activityRepository->flush();
            }
        }
    }

    public function calculateGroupNumber()
    {
        $lastActivity = $this->getLastActivity();
        $groupNumber = $lastActivity->getGroupNumber();
        return $groupNumber + 1;

        /*$date = new \DateTime();
        $date->sub(new \DateInterval('P3D'));
        var_dump($date);
        $userLastActivity = $this->activityRepository->findActivityByUserNewerThanDate($user, $date);
        if ($userLastActivity != null) {

        }
        var_dump($userLastActivity);
        if ($userLastActivity == null || empty($userLastActivity)) {
            $lastActivity = $this->getLastActivity();
            $groupNumber = $lastActivity->getGroupNumber();
            return $groupNumber + 1;
        }*/
    }

    public function getActivity(User $user, $objectId, $type)
    {
        return $this->activityRepository->findActivityByUserObjectType($user, $objectId, $type);
    }

    public function getRecentActivityForWidget()
    {
        $limit = $this->activityRepository->findAmountForRecentWidget();
        $activity = $this->activityRepository->findRecentActivity($limit);
        $activityArray = $this->convertActivityToArray($activity);
        return $activityArray;
    }

    public function getRecentCommentsForWidget($limit)
    {
        $activity = $this->activityRepository->findRecentCommentActivity($limit);
        $activityArray = $this->convertActivityToArray($activity);
        return $activityArray;
    }

    public function getRecentLikesForWidget($limit)
    {
        $activity = $this->activityRepository->findRecentLikeActivity($limit);
        $activityArray = $this->convertActivityToArray($activity);
        return $activityArray;
    }

    public function getActivityOrderedByDate()
    {
        return $this->activityRepository->findActivityOrderedByCreated();
    }

    public function getActivityOrderedByDateASC()
    {
        return $this->activityRepository->findActivityOrderedByCreatedASC();
    }

    public function getActivityByUser($user)
    {
        return $this->activityRepository->findActivityByUser($user);
    }

    public function getActivityById($id)
    {
        return $this->activityRepository->find($id);
    }

    public function getActivityByUserAndTypeBetweenDates($user, $type, $from, $to)
    {
        return $this->activityRepository->findActivityByUserAndTypeBetweenDates($user, $type, $from, $to);
    }

    public function getActivityByTypeBetweenDates($type, $from, $to)
    {
        return $this->activityRepository->findActivityByTypeBetweenDates($type, $from, $to);
    }

    private function convertActivityToArray($activity)
    {
        $activityArray = array();

        $previousGroupNumber = null;
        foreach ($activity as $activityItem) {
            $type = $activityItem->getType();
            $groupNumber = $activityItem->getGroupNumber();
            $user = $activityItem->getUser();
            $username = $user->getUsername();
            $name = $user->getDisplayName();
            $avatar = $user->getAvatar();
            $email = $user->getEmail();
            $data = $activityItem->getData();
            $created = $activityItem->getCreated();

            $activityArray[$groupNumber]['type'] = $type;
            $activityArray[$groupNumber]['created'] = $created;

            if ($type == "like") {
                if ($groupNumber != $previousGroupNumber) {
                    $activityArray[$groupNumber]['parent']['data'] = $data;
                    $activityArray[$groupNumber]['parent']['user']['name'] = $name;
                    $activityArray[$groupNumber]['parent']['user']['username'] = $username;
                    $activityArray[$groupNumber]['parent']['user']['avatar'] = $avatar;
                    $activityArray[$groupNumber]['parent']['user']['email'] = $email;
                } else {
                    $child['data'] = $data;
                    $child['user']['name'] = $name;
                    $child['user']['username'] = $username;
                    $child['user']['avatar'] = $avatar;
                    $child['user']['email'] = $email;
                    $activityArray[$groupNumber]['child'][] = $child;
                }
            } else if ($type == "comment") {
                $activityArray[$groupNumber]['parent']['user']['name'] = $name;
                $activityArray[$groupNumber]['parent']['user']['username'] = $username;
                $activityArray[$groupNumber]['parent']['user']['avatar'] = $avatar;
                $activityArray[$groupNumber]['parent']['user']['email'] = $email;
                $activityArray[$groupNumber]['parent']['data'] = $data;
            } else if ($type == "joined") {
                if ($groupNumber != $previousGroupNumber) {
                    $activityArray[$groupNumber]['parent']['user']['name'] = $name;
                    $activityArray[$groupNumber]['parent']['user']['username'] = $username;
                    $activityArray[$groupNumber]['parent']['user']['avatar'] = $avatar;
                    $activityArray[$groupNumber]['parent']['user']['email'] = $email;
                } else {
                    $child['user']['name'] = $name;
                    $child['user']['username'] = $username;
                    $child['user']['avatar'] = $avatar;
                    $child['user']['email'] = $email;
                    $activityArray[$groupNumber]['child'][] = $child;
                }
            } else if ($type == "added") {
                if ($groupNumber != $previousGroupNumber) {
                    $activityArray[$groupNumber]['parent']['data'] = $data;
                    $activityArray[$groupNumber]['parent']['user']['name'] = $name;
                    $activityArray[$groupNumber]['parent']['user']['username'] = $username;
                    $activityArray[$groupNumber]['parent']['user']['avatar'] = $avatar;
                    $activityArray[$groupNumber]['parent']['user']['email'] = $email;
                } else {
                    $child['data'] = $data;
                    $child['user']['name'] = $name;
                    $child['user']['username'] = $username;
                    $child['user']['avatar'] = $avatar;
                    $child['user']['email'] = $email;
                    $activityArray[$groupNumber]['child'][] = $child;
                }
            }

            $previousGroupNumber = $groupNumber;
        }

        return $activityArray;
    }

    public function getLastActivity()
    {
        $recentActivity = $this->activityRepository->findRecentActivity(11);
        $lastActivity = $recentActivity[0];
        return $lastActivity;
    }

    public function getActivityRepository()
    {
        return $this->activityRepository;
    }

    public function persistOnly($entity)
    {
        $this->activityRepository->save($entity, false);
    }

    public function flush()
    {
        $this->activityRepository->flush();
    }
}