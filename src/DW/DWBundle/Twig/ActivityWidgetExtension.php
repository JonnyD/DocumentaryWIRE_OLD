<?php

namespace DW\DWBundle\Twig;

use DW\DWBundle\Manager\ActivityManager;

class ActivityWidgetExtension extends \Twig_Extension
{
    private $activityManager;

    public function __construct(ActivityManager $activityManager) {
        $this->activityManager = $activityManager;
    }

    public function getFunctions()
    {
        return array(
            'activityWidget' => new \Twig_Function_Method($this, 'activityWidget')
        );
    }

    public function activityWidget($type)
    {
        if ($type == "comments") {
            $activity = $this->activityManager->getRecentCommentsForWidget(5);
        } else if ($type == "likes") {
            $activity = $this->activityManager->getRecentLikesForWidget(10);
        } else {
            $activity = $this->activityManager->getRecentActivityForWidget();
        }
        return $activity;
    }

    public function getName()
    {
        return 'activityWidgetExtension';
    }
}