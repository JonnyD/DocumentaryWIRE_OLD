<?php

namespace DW\DWBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WidgetController extends Controller
{
    public function listCategoriesAction()
    {
        $categoryManager = $this->get('documentary_wire.category_manager');
        $categories = $categoryManager->getCategoriesWithDocumentaries();

        return $this->render('DocumentaryWIREBundle:Widget:categories.html.twig',
            array('categories' => $categories)
        );
    }

    public function listRecentCommentsAction($max = 5)
    {
        $commentManager = $this->get('documentary_wire.comment_manager');
        $comments = $commentManager->getRecentComments($max);

        return $this->render('DocumentaryWIREBundle:Widget:recentComments.html.twig',
            array('comments' => $comments)
        );
    }

    public function listRecentlyActiveUsersAction()
    {
        $userManager = $this->get('documentary_wire.user_manager');
        $users = $userManager->getActiveUsers();

        return $this->render(
            'DocumentaryWIREBundle:Widget:recentlyActiveUsers.html.twig',
            array('users' => $users)
        );
    }
}
