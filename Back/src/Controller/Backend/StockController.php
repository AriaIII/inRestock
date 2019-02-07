<?php

namespace App\Controller\Backend;

use App\Entity\Stock;
use App\Entity\Product;
use App\Form\StockType;
use App\Repository\StockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backend/stock")
 */
class StockController extends AbstractController
{


    /**
     * @Route("/new/product/{id}", name="stock_new", methods={"GET","POST"})
     */
    public function new(Request $request, Product $product): Response
    {
        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $stock->setProduct($product);
            $entityManager->persist($stock);
            $entityManager->flush();

            $productId = $stock->getProduct()->getId();

            return $this->redirectToRoute('product_show', [
                'id' => $productId,
            ]);
        }

        return $this->render('backend/stock/new.html.twig', [
            'stock' => $stock,
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stock_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Stock $stock): Response
    {
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);
        $product = $stock->getProduct();
        

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $productId = $stock->getProduct()->getId();

            return $this->redirectToRoute('product_show', [
                'id' => $productId,
            ]);
        }

        return $this->render('backend/stock/edit.html.twig', [
            'stock' => $stock,
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stock_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Stock $stock): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        $productId = $stock->getProduct()->getId();


        return $this->redirectToRoute('product_show', [
            'id' => $productId,
        ]);
    }
}
