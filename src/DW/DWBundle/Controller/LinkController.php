<?php

namespace DW\DWBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use DW\DWBundle\Entity\Link;

class LinkController extends Controller
{
	public function addAction()
	{
		$link = new LinK();
		
		$form = $this->createForm('link', $link);
		 
		$request = $this->get('request');
		 
		if($request->getMethod() === 'POST')
		{
			$form->bind($request);
			 
			if($form->isValid())
			{	 
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($link);
				$em->flush();
			}
		}
		 
		return $this->render('DocumentaryWIREBundle:Link:add.html.twig', array(
				'form' => $form->createView(),
		));
	}
}
