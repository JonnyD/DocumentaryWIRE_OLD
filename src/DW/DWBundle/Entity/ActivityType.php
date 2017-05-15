<?php

namespace DW\DWBundle\Entity;

abstract class ActivityType
{
    const LIKE = "like";
    const COMMENT = "comment";
    const FOLLOW = "follow";
    const JOINED = "joined";
    const ADDED = "added";
}