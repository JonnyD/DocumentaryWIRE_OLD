<?php

namespace DW\DWBundle\Entity;

abstract class VoteType
{
    const UPVOTE = "upvote";
    const DOWNVOTE = "downvote";
    const NEUTRAL = "neutral";
}