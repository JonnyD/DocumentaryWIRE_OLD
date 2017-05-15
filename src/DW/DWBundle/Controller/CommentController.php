<?php

namespace DW\DWBundle\Controller;

use DW\DWBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use DW\DWBundle\Entity\Comment;
use DW\DWBundle\Entity\Vote;
use DW\DWBundle\Entity\VoteType;
use DW\DWBundle\Entity\User;

class CommentController extends Controller
{   	
	public function voteAction()
	{
        $commentManager = $this->get('documentary_wire.comment_manager');
        $voteManager = $this->get('documentary_wire.vote_manager');
        $userHelper = $this->get('documentary_wire.user_helper');

        $request = $this->container->get('request');
		$action = $request->get('action');
		$commentId = $request->get('commentId');
		
		$user = $userHelper->getLoggedInUser();
        if ($user != null) {
            $comment = $commentManager->getCommentById($commentId);
            $vote = $voteManager->getVoteByUserAndComment($user, $comment);
            $points = 0;
            if ($vote == null) {
                $points = $voteManager->createVote($user, $comment, $action);
            } else if ($vote != null) {
                $points = $voteManager->updateVote($vote, $action);
            }

            $headers = array(
                'Content-Type' => 'application/json'
            );
            $response = array(
                "code" => 100,
                "success" => true,
                "error" => "",
                "points" => $points
            );
            return new Response(json_encode($response), 200, $headers);
        }
	}

    public function newAction($documentarySlug)
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentary = $documentaryManager->getDocumentaryBySlug($documentarySlug);

        $comment = new Comment();
        $comment->setDocumentary($documentary);
        $comment->setCreated(new \DateTime());

        $form = $this->createForm(new CommentType(), $comment);

        return $this->render('DocumentaryWIREBundle:Comment:form.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }

}