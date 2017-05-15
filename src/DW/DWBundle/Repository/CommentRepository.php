<?php

namespace DW\DWBundle\Repository;

use DW\DWBundle\Entity\Documentary;
use DW\DWBundle\Entity\User;

interface CommentRepository
{
    public function findCommentById($id);
    public function findAll();
    public function findCommentsByDocumentary(Documentary $documentary);
    public function findParentCommentsByDocumentaryOrderedByPoints(Documentary $documentary);
    public function findParentCommentsByDocumentaryOrderedByDate(Documentary $documentary);
    public function findCommentsByUser(User $user);
    public function findCommentsByEmail($email);
    public function findCommentsByEmailWithNoUser($email);
    public function findCommentsWithUser();
}