<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HistoriqueStockRepository;

class HistoriqueStockController extends AbstractController
{
    /**
     * @Route("backend/historique/stock", name="historique_stock")
     */
    public function index(HistoriqueStockRepository $historiqueStockRepo)
    {
        $historiqueStock = $historiqueStockRepo->findAll();

        return $this->render('backend/historique_stock/index.html.twig', [
            'historiqueStock' => $historiqueStock,
        ]);
    }
}
