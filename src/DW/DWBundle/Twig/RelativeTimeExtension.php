<?php

namespace DW\DWBundle\Twig;

class RelativeTimeExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('relativeTime', array($this, 'relativeTimeFilter')),
        );
    }

    public function relativeTimeFilter(\DateTime $dateTime)
    {
        $time = $dateTime->getTimestamp();

        $now = time();
        $diff = $now - $time;

        if ($diff < 60){
            return sprintf($diff > 1 ? '%s seconds ago' : '%s second ago', $diff);
        }

        $diff = floor($diff/60);

        if ($diff < 60){
            return sprintf($diff > 1 ? '%s minutes ago' : '%s minute ago', $diff);
        }

        $diff = floor($diff/60);

        if ($diff < 24){
            return sprintf($diff > 1 ? '%s hours ago' : '%s hour ago', $diff);
        }

        $diff = floor($diff/24);

        if ($diff < 7){
            return sprintf($diff > 1 ? '%s days ago' : '%s day ago', $diff);
        }

        if ($diff < 60)
        {
            $diff = floor($diff / 7);

            return sprintf($diff > 1 ? '%s weeks ago' : '%s week ago', $diff);
        }

        $diff = floor($diff/30);

        if ($diff < 12){
            return sprintf($diff > 1 ? '%s months ago' : '%s month ago', $diff);
        }

        $diff = date('Y', $now) - date('Y', $time);

        return sprintf($diff > 1 ? '%s years ago' : '%s year ago', $diff);
    }

    public function getName()
    {
        return 'relativeTimeExtension';
    }
}