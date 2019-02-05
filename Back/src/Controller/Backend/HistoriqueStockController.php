<?php

namespace App\Controller\Backend;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HistoriqueStockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

class HistoriqueStockController extends AbstractController
{
    /**
     * @Route("backend/historique/stock", name="historique_stock")
     */
    public function index(HistoriqueStockRepository $historiqueStockRepo, EntityManagerInterface $em, Request $request, PaginatorInterface $paginator)
    {

        // $historiqueStock = $historiqueStockRepo->findAll();

        //A SEPARER
        $dql   = "SELECT h FROM App:HistoriqueStock h
                  JOIN  h.stock s
                  JOIN s.product p
                  JOIN h.user u
                  JOIN h.modification m
                ";
        $query = $em->createQuery($dql);

        $historiqueStock = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
            );


        return $this->render('backend/historique_stock/index.html.twig', [
            'historiqueStock' => $historiqueStock,
        ]);
    }
}
