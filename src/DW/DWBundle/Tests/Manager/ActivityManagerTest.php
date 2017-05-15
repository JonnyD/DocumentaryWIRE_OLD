<?php

namespace DW\DWBundle\Tests\Manager;

use DW\DWBundle\Tests\BaseWebTestCase;

class ActivityManagerTest extends BaseWebTestCase
{
    private $acitivityManager;

    public function setUp()
    {
        parent::setUp();

        $this->acitivityManager = $this->getContainer()->get("documentary_wire.activity_manager");
    }

    public function testCascadeGroupNumber()
    {
        $user = null;
        $type = "joined";
        $groupNumber = 10;


    }
}