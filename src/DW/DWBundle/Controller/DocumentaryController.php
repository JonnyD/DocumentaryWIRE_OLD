<?php

namespace DW\DWBundle\Controller;

use DW\DWBundle\Entity\ActivityComponent;
use DW\DWBundle\Entity\ActivityType;
use DW\DWBundle\Entity\Category;
use DW\DWBundle\Entity\DocumentaryFilter;
use DW\DWBundle\Entity\DocumentaryStatus;
use DW\DWBundle\Entity\Order;
use DW\DWBundle\Entity\Status;
use DW\DWBundle\Form\AddDocumentary;
use DW\DWBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DW\DWBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;

class DocumentaryController extends Controller
{
	public function showAction($slug, $page, Request $request)
	{
        $sort = $request->query->get('sort');

        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentary = $documentaryManager->getDocumentaryBySlug($slug);

        if ($documentary == null) {
            $truncated = $documentaryManager->getDocumentaryByTruncatedSlug($slug);
            if ($truncated != null) {
                return $this->redirect($this->generateUrl('documentary_wire_show_documentary',
                    array('slug' => $truncated->getSlug())));
            }
        }

        if ($documentary == null) {
            throw $this->createNotFoundException("Documentary doesn't exist");
        }

        $embedCode = $documentaryManager->getEmbedCode(
            $documentary->getVideoSource(),
            $documentary->getVideoId(),
            620, 465, true);

        $commentManager = $this->get('documentary_wire.comment_manager');
        if ($sort == "date") {
            $comments = $commentManager->getParentCommentsByDocumentaryOrderedByDate($documentary);
        } else if ($sort == "points") {
            $comments = $commentManager->getParentCommentsByDocumentaryOrderedByPoints($documentary);
        } else {
            $comments = $commentManager->getParentCommentsByDocumentaryOrderedByPoints($documentary);
        }
        $commentCount = count($comments);
        $limitPerPage = 10;
        $pages = $commentCount / $limitPerPage;
        if ($page > $pages + 1) {
            throw $this->createNotFoundException("Page doesn't exist");
        }
        $previousPage = null;
        $nextPage = null;
        if ($page > 1) {
            $previousPage = $page - 1;
        }
        if ($page < $pages) {
            $nextPage = $page + 1;
        }
        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $comments = $paginateManager->getPaginator()->paginate(
            $comments,
            $page/*page number*/,
            $limitPerPage/*limit per page*/
        );
        $comments->setUsedRoute('documentary_wire_show_documentary_page');

        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $userHelper = $this->get('documentary_wire.user_helper');
                $loggedInUser = $userHelper->getLoggedInUser();

                $comment->setDocumentary($documentaryManager->merge($documentary));
                $comment->setCreated(new \DateTime());
                $comment->setStatus(Status::ACTIVE);
                $comment->setUser($loggedInUser);

                $commentManager = $this->get('documentary_wire.comment_manager');
                $commentManager->addComment($comment);

                $cacheHelper = $this->get('documentary_wire.cache_helper');
                $cacheHelper->deleteFromCache("documentary_slug_".$documentary->getSlug(), "comments");

                return $this->redirect($this->generateUrl('documentary_wire_show_documentary',
                    array('slug' => $documentary->getSlug())));
            }
        }

		return $this->render('DocumentaryWIREBundle:Documentary:show.html.twig', array(
            'documentary' => $documentary,
            'embedCode' => $embedCode,
            'comments' => $comments,
            'commentCount' => $commentCount,
            'commentForm' => $form->createView(),
            'previousPage' => $previousPage,
            'nextPage' => $nextPage
		));
	}

    public function browseAction($page, Request $request)
    {
        $orderBy = $request->query->get('orderBy');
        $order = $request->query->get('order');

        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getPublishedDocumentaryKeys($orderBy, $order);

        $totalCount = count($documentaries);
        $limitPerPage = 12;
        $pages = $totalCount / $limitPerPage;
        if ($page > $pages + 1) {
            throw $this->createNotFoundException("Page doesn't exist");
        }
        $previousPage = null;
        $nextPage = null;
        if ($page > 1) {
            $previousPage = $page - 1;
        }
        if ($page < $pages) {
            $nextPage = $page + 1;
        }
        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $documentaries = $paginateManager->getPaginator()->paginate(
            $documentaries,
            $page/*page number*/,
            $limitPerPage/*limit per page*/
        );
        $documentaries->setUsedRoute('documentary_wire_browse_page');

        return $this->render(
            'DocumentaryWIREBundle:Documentary:browse.html.twig', array(
                'documentaries' => $documentaries,
                'orderBy' => $orderBy,
                'order' => $order,
                'previousPage' => $previousPage,
                'nextPage' => $nextPage
            ));
    }

    public function showYearAction($year, Request $request)
    {
        $orderBy = $request->query->get('orderBy');
        $order = $request->query->get('order');

        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getPublishedDocumentaryKeysByYear($year, $orderBy, $order);

        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $documentaries = $paginateManager->paginate($documentaries, 12, $request);

        return $this->render(
            'DocumentaryWIREBundle:Documentary:year.html.twig', array(
            'year' => $year,
            'documentaries' => $documentaries,
            'orderBy' => $orderBy,
            'order' => $order
        ));
    }

    public function listAction()
    {
        $categoryManager = $this->get('documentary_wire.category_manager');
        $categories = $categoryManager->getCategoriesWithDocumentaries();

        $documentaryManager = $this->get('documentary_wire.documentary_manager');

        $list = array();
        foreach ($categories as $category) {
            $documentaries = $documentaryManager->getDocumentariesByCategory($category,
                DocumentaryStatus::PUBLISH, DocumentaryFilter::TITLE, Order::ASC, -1);
            $list[$category->getName()]['name'] = $category->getName();
            $list[$category->getName()]['documentaries'] = $documentaries;
        }

        return $this->render('DocumentaryWIREBundle:Documentary:documentary-list.html.twig', array(
            'list' => $list
        ));
    }

    public function listByCategoryAction(Category $category)
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getDocumentariesByCategoryFromCache($category,
            DocumentaryStatus::PUBLISH, DocumentaryFilter::TITLE, Order::ASC, -1, 'list');

        return $this->render('DocumentaryWIREBundle:Documentary:list.html.twig', array(
            'documentaries' => $documentaries
        ));
    }

    public function listYearsAction()
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $years = $documentaryManager->getYears();

        return $this->render('DocumentaryWIREBundle:Documentary:listYears.html.twig', array(
            'years' => $years
        ));
    }

    public function listDurationAction()
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $durations = $documentaryManager->getDurations();

        return $this->render('DocumentaryWIREBundle:Documentary:listDuration.html.twig', array(
            'durations' => $durations
        ));
    }

    public function showDurationAction($length, Request $request)
    {
        $orderBy = $request->query->get('orderBy');
        $order = $request->query->get('order');

        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getPublishedDocumentaryKeysByDuration($length, $orderBy, $order);

        $paginateManager = $this->get('documentary_wire.paginate_manager');
        $documentaries = $paginateManager->paginate($documentaries, 12, $request);

        return $this->render('DocumentaryWIREBundle:Documentary:showDuration.html.twig', array(
            'documentaries' => $documentaries,
            'length' => $length
        ));
    }

    public function showSliderAction()
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getDocumentaryKeys(DocumentaryStatus::PUBLISH,
            DocumentaryFilter::DATE, Order::DESC, -1, "slider");

        return $this->render(
            'DocumentaryWIREBundle:Documentary:slider.html.twig', array(
            'randomDocumentaries' => $documentaries
        ));
    }
    
    public function addAction()
    {
        $form = $this->createForm(new AddDocumentary());
    	
    	return $this->render('DocumentaryWIREBundle:Documentary:add.html.twig', array(
    	    'form' => $form->createView(),
    	));
    }
}
