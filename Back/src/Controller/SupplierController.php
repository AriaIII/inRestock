<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Repository\SupplierRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SupplierController extends AbstractController
{
    /**
     * @Route("/suppliers", name="supplier_index")
     */
    public function index(SupplierRepository $supplierRepo)
    {
        $suppliers = $supplierRepo->findAll();

        return $this->render('supplier/index.html.twig', [
            "suppliers" => $suppliers     
        ]);
    }

    /**
     * @Route("/supplier/{id}", name="supplier_show")
     */
    public function show(Supplier $supplier)
    {
        return $this->render('supplier/show.html.twig', [
            "supplier" => $supplier     
        ]);
    }
}
