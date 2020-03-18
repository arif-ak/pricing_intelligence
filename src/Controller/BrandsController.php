<?php

namespace App\Controller;

use App\Entity\Brands;
use App\Form\BrandsType;
use App\Repository\BrandsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\WebCrawler;

/**
 * @Route("/brands")
 */
class BrandsController extends AbstractController
{
    /**
     * @Route("/", name="brands_index", methods={"GET"})
     */
    public function index(BrandsRepository $brandsRepository): Response
    {
        return $this->render('brands/index.html.twig', [
            'brands' => $brandsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="brands_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $brand = new Brands();
        $form = $this->createForm(BrandsType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($brand);
            $entityManager->flush();

            return $this->redirectToRoute('brands_index');
        }

        return $this->render('brands/new.html.twig', [
            'brand' => $brand,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="brands_show", methods={"GET"})
     */
    public function show(Brands $brand): Response
    {
        return $this->render('brands/show.html.twig', [
            'brand' => $brand,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="brands_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Brands $brand): Response
    {
        $form = $this->createForm(BrandsType::class, $brand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('brands_index');
        }

        return $this->render('brands/edit.html.twig', [
            'brand' => $brand,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="brands_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Brands $brand): Response
    {
        if ($this->isCsrfTokenValid('delete'.$brand->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($brand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('brands_index');
    }

    
    /**
     * @Route("/{id}/crawl", name="brand_crawl_data")
     */
    public function crawlData(WebCrawler $webCrawler, Brands $brand)
    {
        $response = $webCrawler->crawlBrands($brand);

        if($response->getContent() == 'success')
        {
            $this->addFlash('success', 'Data retrieved! Check product listing page to view data');
            return $this->redirectToRoute('website_show',['id' => $brand->getWebsite()->getId()]);
        } else {
            $this->addFlash('error', $response->getContent());
            return $this->redirectToRoute('website_show',['id' => $brand->getWebsite()->getId()]);
        }

    }
}
