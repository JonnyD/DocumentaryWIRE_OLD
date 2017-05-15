<?php

namespace DW\DWBundle\Repository\Doctrine\ORM;

use Doctrine\ORM\EntityRepository;
use DW\DWBundle\Repository\Repository;

abstract class CustomRepository extends EntityRepository implements Repository
{
    public function merge($entity)
    {
        return $this->_em->merge($entity);
    }

    public function save($entity, $sync = true)
    {
        $this->_em->persist($entity);
        if ($sync) {
            $this->_em->flush();
        }
    }

    public function flush()
    {
        $this->_em->flush();
    }
}