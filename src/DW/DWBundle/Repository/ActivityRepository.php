<?php

namespace DW\DWBundle\Repository;

use DW\DWBundle\Entity\User;

interface ActivityRepository
{
    public function find($id);
    public function findActivityByUserAndTypeBetweenDates($user, $type, $from, $to);
    public function findActivityByTypeBetweenDates($type, $from, $to);
    public function updateActivityByTypeBetweenDates($type, $from, $to, $groupNumber);
    public function findRecentActivity($limit);
    public function findRecentCommentActivity($limit);
    public function findRecentLikeActivity($limit);
    public function findActivityByUser(User $user);
    public function findActivityOrderedByCreated();
    public function findActivityOrderedByCreatedASC();
    public function findAmountForRecentWidget();
    public function findRawActivity();
    public function findActivityByUserObjectType($user, $objectId, $type);
    public function findPreviousGroupNumber($groupNumber);
    public function findActivityByGroupNumber($groupNumber);
    public function updateActivityForMigration($id, $data);
}