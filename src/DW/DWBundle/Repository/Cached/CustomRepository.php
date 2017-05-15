<?php

namespace DW\DWBundle\Repository\Cached;

use DW\DWBundle\Repository\Repository;

class CustomRepository implements Repository
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function merge($entity)
    {
        return $this->repository->merge($entity);
    }

    public function save($entity, $sync = true)
    {
        $this->repository->save($entity, $sync);
    }

    public function flush()
    {
        $this->repository->flush();
    }
}