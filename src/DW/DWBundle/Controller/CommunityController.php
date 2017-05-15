<?php

namespace DW\DWBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommunityController extends Controller
{
    public function showAction(Request $request)
    {
        $activityManager = $this->get('documentary_wire.activity_manager');
        $activity = $activityManager->getActivityOrderedByDate();

        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $activity = $paginateManager->paginate($activity, 32, $request);

        return $this->render('DocumentaryWIREBundle:Community:show.html.twig', array(
            'activity' => $activity
        ));
    }
}