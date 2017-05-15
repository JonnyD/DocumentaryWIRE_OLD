<?php

namespace DW\DWBundle\Tests\Manager;

use DW\DWBundle\Tests\BaseWebTestCase;

class UserManagerTest extends BaseWebTestCase
{
    private $userManager;

    public function setUp()
    {
        parent::setUp();

        $this->userManager = $this->getContainer()->get("documentary_wire.user_manager");
    }

    public function testGetLatestUsers()
    {
        $users = $this->userManager->getLatestUsers();
        $this->assertNotNull($users);
        $this->assertEquals(16, count($users));
    }

    public function testGetRecentlyActiveUsers()
    {
        $users = $this->userManager->getActiveUsers();
        $this->assertNotNull($users);
        $this->assertEquals(16, count($users));
    }

    public function testGetUserByUsername()
    {
        $username = "user2";
        $user = $this->userManager->getUserByUsername($username);
        $this->assertNotNull($user);
        $this->assertEquals($username, $user->getUsername());
    }
}