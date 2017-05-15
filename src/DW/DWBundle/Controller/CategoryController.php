<?php

namespace DW\DWBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DW\DWBundle\Manager\CategoryManager;
use Symfony\Component\HttpFoundation\Request;
use DW\DWBundle\Entity\Order;

class CategoryController extends Controller
{   	
    public function listAction()
    {
    	$categoryManager = $this->get('documentary_wire.category_manager');
    	$categories = $categoryManager->getAllCategories();
    	
    	return $this->render(
            'DocumentaryWIREBundle:Category:list.html.twig',
            array('categories' => $categories)
    	);
    }
    
    public function showAction($slug, $page, Request $request)
    {
        $orderBy = $request->query->get('orderBy');
        $order = $request->query->get('order');

        $categoryManager = $this->get('documentary_wire.category_manager');
    	$category = $categoryManager->getCategoryBySlug($slug);

        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getPublishedDocumentaryKeysByCategory($category, $orderBy, $order);

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
        $documentaries->setUsedRoute('documentary_wire_show_category_page');
    	 
    	return $this->render(
            'DocumentaryWIREBundle:Category:show.html.twig', array(
                'category' => $category,
                'documentaries' => $documentaries,
                'orderBy' => $orderBy,
                'order' => $order,
                'previousPage' => $previousPage,
                'nextPage' => $nextPage
            )
    	);
    }
}
