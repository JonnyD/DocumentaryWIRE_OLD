<?php

namespace DW\DWBundle\Entity;

abstract class DocumentaryFilter
{
    const DATE = "created";
    const TITLE = "title";
    const VIEWS = "views";
    const LIKES = "likes";
    const COMMENTS = "comments";
}