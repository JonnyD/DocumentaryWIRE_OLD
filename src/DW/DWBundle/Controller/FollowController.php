<?php

namespace DW\DWBundle\Controller;

use DW\DWBundle\Entity\ActivityComponent;
use DW\DWBundle\Entity\ActivityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FollowController extends Controller
{
    public function ajaxFollowAction()
    {
        $request = $this->container->get('request');
        $actionType = $request->get('actionType');
        $userId = $request->get('userId');

        $userManager = $this->get('documentary_wire.user_manager');
        $user = $userManager->getUserById($userId);
        $loggedInUser = $userManager->getLoggedInUser();

        $followManager = $this->get('documentary_wire.follow_manager');
        $activityManager = $this->get('documentary_wire.activity_manager');
        if ($actionType === 'follow')
        {
            $data = array(
                "userId" => $user->getId(),
                "username" => $user->getUsername());
            $activityManager->addActivity($loggedInUser, $user->getId(),
                ActivityType::FOLLOW, ActivityComponent::USER, $data);
            $followManager->followUser($loggedInUser, $user);
        }
        else if ($actionType === 'unfollow')
        {
            $activityManager->removeActivity($loggedInUser, $user->getId(),
                ActivityType::FOLLOW);
            $followManager->unfollowUser($loggedInUser, $user);
        }

        //prepare the response, e.g.
        $response = array("code" => 100, "success" => true, "error" => "");
        //you can return result as JSON
        return new Response(json_encode($response));
    }
}
