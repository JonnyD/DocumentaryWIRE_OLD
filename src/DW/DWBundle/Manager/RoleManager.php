<?php

namespace DW\DWBundle\Manager;

use Doctrine\ORM\EntityManager;
use DW\DWBundle\Repository\Doctrine\ORM\RoleRepository;

class RoleManager
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getRoleByName($name)
    {
        return $this->roleRepository->findOneByName($name);
    }
}