<?php

namespace DW\DWBundle\Controller;

use DW\DWBundle\Entity\Contact;
use DW\DWBundle\Entity\DocumentaryFilter;
use DW\DWBundle\Entity\DocumentaryStatus;
use DW\DWBundle\Entity\Order;
use DW\DWBundle\Manager\Managers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class SiteController extends Controller
{
    public function sitemapAction()
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $lastUpdatedDocumentary = $documentaryManager->getLastUpdatedDocumentary();
        $lastModifiedDocumentary = $lastUpdatedDocumentary->getUpdated()->format('Y-m-d\TH:i:sP');

        $rootNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="http://www.documentarywire.com/sitemap.xsl"?> <sitemapindex
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>');

        $loc = $url = $this->generateUrl('documentary_wire.sitemap_page', array(), true);
        $url = $rootNode->addChild('sitemap');
        $url->addChild('loc', $loc);
        $url->addChild('lastmod', $lastModifiedDocumentary);

        $loc = $url = $this->generateUrl('documentary_wire.sitemap_category', array(), true);
        $url = $rootNode->addChild('sitemap');
        $url->addChild('loc', $loc);
        $url->addChild('lastmod', $lastModifiedDocumentary);

        $loc = $url = $this->generateUrl('documentary_wire.sitemap_documentary', array(), true);
        $url = $rootNode->addChild('sitemap');
        $url->addChild('loc', $loc);
        $url->addChild('lastmod', $lastModifiedDocumentary);

        $response = new Response($rootNode->asXML());
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }

    public function sitemapPageAction()
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $lastUpdatedDocumentary = $documentaryManager->getLastUpdatedDocumentary();
        $lastModified = $lastUpdatedDocumentary->getUpdated()->format('Y-m-d\TH:i:sP');

        $rootNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="http://www.documentarywire.com/sitemap.xsl"?> <urlset
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        $loc = $this->generateUrl('documentary_wire_homepage', array(), true);
        $url = $rootNode->addChild('url');
        $url->addChild('loc', $loc);
        $url->addChild('lastmod', $lastModified);
        $url->addChild('changefreq', 'daily');
        $url->addChild('priority', '1.0');

        $loc = $this->generateUrl('documentary_wire_browse', array(), true);
        $url = $rootNode->addChild('url');
        $url->addChild('loc', $loc);
        $url->addChild('lastmod', $lastModified);
        $url->addChild('changefreq', 'weekly');
        $url->addChild('priority', '0.8');

        $loc = $this->generateUrl('documentary_wire_all', array(), true);
        $url = $rootNode->addChild('url');
        $url->addChild('loc', $loc);
        $url->addChild('lastmod', $lastModified);
        $url->addChild('changefreq', 'monthly');
        $url->addChild('priority', '0.4');

        $loc = $this->generateUrl('documentary_wire.contact', array(), true);
        $url = $rootNode->addChild('url');
        $url->addChild('loc', $loc);
        $url->addChild('lastmod', "");
        $url->addChild('changefreq', 'yearly');
        $url->addChild('priority', '0.2');

        $response = new Response($rootNode->asXML());
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }

    public function sitemapCategoryAction()
    {
        $categoryManager = $this->get('documentary_wire.category_manager');
        $categproes = $categoryManager->getCategoriesWithDocumentaries();

        $rootNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="http://www.documentarywire.com/sitemap.xsl"?> <urlset
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        $documentaryManager = $this->get('documentary_wire.documentary_manager');

        foreach ($categproes as $category) {
            $loc = $url = $this->generateUrl('documentary_wire_show_category', array('slug' => $category->getSlug()), true);

            $lastModifiedDocumentary = $documentaryManager->getLastUpdatedDocumentaryInCategory($category);
            $lastModified = $lastModifiedDocumentary->getUpdated()->format('Y-m-d\TH:i:sP');

            $url = $rootNode->addChild('url');
            $url->addChild('loc', $loc);
            $url->addChild('lastmod', $lastModified);
            $url->addChild('changefreq', 'weekly');
            $url->addChild('priority', '0.8');
        }

        $response = new Response($rootNode->asXML());
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }

    public function sitemapDocumentaryAction()
    {
        $documentaryManager = $this->get('documentary_wire.documentary_manager');
        $documentaries = $documentaryManager->getDocumentaries(DocumentaryStatus::PUBLISH, DocumentaryFilter::DATE,
            Order::DESC, -1);

        $rootNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="http://www.documentarywire.com/sitemap.xsl"?> <urlset
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        foreach ($documentaries as $documentary) {
            $loc = $url = $this->generateUrl('documentary_wire_show_documentary', array('slug' => $documentary->getSlug()), true);

            $url = $rootNode->addChild('url');
            $url->addChild('loc', $loc);
            $url->addChild('lastmod', $documentary->getUpdated()->format('Y-m-d\TH:i:sP'));
            $url->addChild('changefreq', 'daily');
            $url->addChild('priority', '0.8');
        }

        $response = new Response($rootNode->asXML());
        $response->headers->set('Content-Type', 'text/xml');
        return $response;
    }

    public function contactAction(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('subject', 'text')
            ->add('yourEmailAddress', 'text')
            ->add('message', 'textarea')
            ->add('captcha', 'captcha')
            ->add('submit', 'submit')
            ->getForm();

        if ($request->isMethod("POST")) {
            $form->handleRequest($request);
            $data = $form->getData();

            if ($form->isValid()) {
                $subject = $data["subject"];
                $email = $data["yourEmailAddress"];
                $message = $data["message"];

                $contact = new Contact();
                $contact->setSubject($subject);
                $contact->setEmail($email);
                $contact->setMessage($message);

                $siteManager = $this->get(Managers::SITE);
                $siteManager->persist($contact);

                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->get('contact-message');
                $flashBag->set('contact-message', "Sent! Thanks for your message, we'll get back to you as soon as possible.");

                return $this->redirect($this->generateUrl('documentary_wire.contact'));
            }
        }

        return $this->render('DocumentaryWIREBundle:Site:contact.html.twig', array(
            'form' => $form->createView()
        ));
    }
}