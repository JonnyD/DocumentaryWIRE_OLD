<?php

namespace DW\DWBundle\Repository;

interface UserRepository
{
    public function findLatestUsers($max = 3);
    public function findRecentlyActiveUsers($max = 3);
    public function findUsers($orderBy, $limit);
    public function findUsersWithFacebook();
    public function findUserByUsernameOrEmail($usernameOrEmail);
    public function findUserByEmailOrFacebookId($email, $facebookId);
    public function findUserByFacebookId($facebookId);
    public function findAll();
    public function find($id);
    public function findOneByUsername($username);
    public function findOneByEmail($email);
    public function findUserByResetKey($key);
    public function findUserByActivationKey($key);
}