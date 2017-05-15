<?php

namespace DW\DWBundle\Tests\Repository;

use DW\DWBundle\Tests\BaseWebTestCase;

class UserRepositoryTest extends BaseWebTestCase
{
    private $userRepository;

    public function setUp()
    {
        parent::setUp();

        $this->userRepository = $this->getEntityManager()->getRepository('DocumentaryWIREBundle:User');
    }

    public function testFindAll()
    {
        $users = $this->userRepository->findAll();
        $this->assertNotNull($users);
        $this->assertTrue(is_array($users));
        $this->assertEquals(16, count($users));
    }

    public function testFindOneById()
    {
        $user = $this->userRepository->find(1);
        $this->assertNotNull($user);
        $this->assertEquals(1, $user->getId());
        $this->assertEquals("user1", $user->getUsername());
        $this->assertEquals("user1@email.com", $user->getEmail());
    }

    public function testFindOneByUsername()
    {
        $user = $this->userRepository->findOneByUsername("user1");
        $this->assertNotNull($user);
        $this->assertEquals(1, $user->getId());
        $this->assertEquals("user1", $user->getUsername());
        $this->assertEquals("user1@email.com", $user->getEmail());
    }

    public function testFindOneByEmail()
    {
        $email = "my1@email.com";
        $user = $this->userRepository->findOneByEmail($email);
        $this->assertNotNull($user);
        $this->assertEquals(1, $user->getId());
        $this->assertEquals("user1", $user->getUsername());
        $this->assertEquals($email, $user->getEmail());
    }

    public function testFindRolesByUser()
    {
        $user = $this->userRepository->findOneByUsername("user1");
        $this->assertNotNull($user);
        $this->assertEquals("user1", $user->getUsername());

        $roles = $user->getRoles();
        $this->assertNotNull($roles);
        $this->assertTrue(is_array($roles));
        $this->assertEquals(1, count($roles));

        $role = $roles[0];
        $this->assertNotNull($role);
        $this->assertEquals("user", $role->getName());
        $this->assertEquals("ROLE_USER", $role->getRole());
    }

    public function testFindUsers()
    {
        $users = $this->userRepository->findUsers("registered", 16);
        $this->assertNotNull($users);
        $this->assertEquals(16, count($users));
    }
}