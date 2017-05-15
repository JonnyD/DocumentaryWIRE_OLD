<?php

namespace DW\DWBundle\Twig;

class HourMinuteExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('hourMinute', array($this, 'hourMinuteFilter')),
        );
    }

    public function hourMinuteFilter($minutes)
    {
        if ($minutes < 1) {
            return;
        }
        $format = '%d:%d';
        $hours = floor($minutes / 60);
        $minutes = ($minutes % 60);

        if ($hours < 1) {
            $format = "%dm";
            return sprintf($format, $minutes);
        } else if ($hours > 0 && $minutes < 1) {
            $format = "%dh";
            return sprintf($format, $hours);
        } else if ($hours > 0 && $minutes > 0) {
            $format = "%dh %dm";
            return sprintf($format, $hours, $minutes);
        }

        return sprintf($format, $hours, $minutes);
    }

    public function getName()
    {
        return 'hourMinuteExtension';
    }
}