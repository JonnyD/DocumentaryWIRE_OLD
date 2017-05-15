<?php

namespace DW\DWBundle\Repository;

use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\User;

interface LikeRepository
{
    public function findAllLikes();
    public function deleteLikeByUserAndDocumentary(User $user, Documentary $documentary);
    public function findLikesByUser(User $user);
    public function findLikeByUserAndDocumentary(User $user, Documentary $documentary);
    public function findLikesByDocumentary(Documentary $documentary);
}