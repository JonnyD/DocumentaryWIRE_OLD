<?php

namespace DW\DWBundle\Controller;

use DW\DWBundle\Entity\ActivityComponent;
use DW\DWBundle\Entity\ActivityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class LikeController extends Controller
{

    public function likeAction()
    {
        $request = $this->container->get('request');
        $actionType = $request->get('actionType');
        $documentaryId = $request->get('documentaryId');

        $securityContext = $this->container->get('security.context');
        if(!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')
            && !$securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            // authenticated REMEMBERED, FULLY will imply REMEMBERED (NON anonymous)
        }
        $token = $securityContext->getToken();
        $user = $token->getUser();

        if ($user != null) {
            $documentaryManager = $this->get('documentary_wire.documentary_manager');
            $documentary = $documentaryManager->getDocumentaryById($documentaryId);

            if ($documentary) {
                $likeManager = $this->get('documentary_wire.like_manager');
                $hasLiked = $likeManager->hasLiked($user, $documentary->getSlug());

                $activityManager = $this->get('documentary_wire.activity_manager');
                $activity = $activityManager->getActivity($user, $documentary->getId(),
                    ActivityType::LIKE, ActivityComponent::DOCUMENTARY);

                if ($actionType === 'like')
                {
                    if (!$hasLiked) {
                        $like = $likeManager->likeDocumentary($user, $documentary);
                    }
                }
                else if ($actionType === 'unlike')
                {
                    if ($hasLiked) {
                        $like = $likeManager->getLikeByUserAndDocumentary($user, $documentary);
                        $likeManager->unlikeDocumentary($like);
                        $activityManager->removeActivity($activity);
                    }
                }
            }
        }

        $headers = array(
            'Content-Type' => 'application/json'
        );
        $response = array("code" => 100, "success" => true, "error" => "");
        return new Response(json_encode($response), 200, $headers);
    }

}