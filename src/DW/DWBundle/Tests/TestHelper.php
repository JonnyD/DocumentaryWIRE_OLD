<?php

namespace DW\DWBundle\Tests;

use DW\DWBundle\Entity\Category;
use DW\DWBundle\Entity\User;
use DW\DWBundle\Entity\Role;
use DW\DWBundle\Entity\Comment;
use DW\DWBundle\Entity\Documentary;

class TestHelper
{
    public static function createUser($username)
    {
        $user = new User();
        $user->setUsername($username);

        return $user;
    }

    public static function createRole($name, $roleType)
    {
        $role = new Role();
        $role->setName($name);
        $role->setRole($roleType);

        return $role;
    }

    public static function createDocumentary($title)
    {
        $documentary = new Documentary();
        $documentary->setTitle($title);

        return $documentary;
    }

    public static function createComment($message)
    {
        $comment = new Comment();
        $comment->setComment($message);

        return $comment;
    }

    public static function createCategory($name, $slug)
    {
        $category = new Category();
        $category->setName($name);
        $category->setSlug($slug);

        return $category;
    }
}