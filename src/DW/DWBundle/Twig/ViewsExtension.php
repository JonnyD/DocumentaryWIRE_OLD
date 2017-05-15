<?php

namespace DW\DWBundle\Twig;

class ViewsExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('views', array($this, 'viewsFilter')),
        );
    }

    public function viewsFilter($views)
    {
        $views = abs($views);

        $suffix = 'K,M,B';
        $suffix = explode(',', $suffix);

        if ($views < 1000) { // any number less than a Thousand
            $shorted = number_format($views);
        } elseif ($views < 1000000) { // any number less than a million
            $shorted = number_format($views/1000, 2).$suffix[0];
        } elseif ($views < 1000000000) { // any number less than a billion
            $shorted = number_format($views/1000000, 2).$suffix[1];
        } else { // at least a billion
            $shorted = number_format($views/1000000000, 2).$suffix[2];
        }

        return $shorted;
    }

    public function getName()
    {
        return 'viewsExtension';
    }
}