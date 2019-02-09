<?php

namespace App\Controller\Api;

use App\Entity\Supplier;
use App\Repository\ProductRepository;
use App\Repository\SupplierRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
    * @Route("/api", name="api_")
 */
class SupplierController extends AbstractController
{
    /**
     * @Route("/suppliers", name="all_suppliers")
     */
    public function supplier(SupplierRepository $repo)
    {
       $suppliers = $repo->findAll();

       foreach ($suppliers as $index => $currentValue) {
        $array[$index] = [
            'id' => $currentValue->getId(),
            'name' => $currentValue->getName(),

        ];
        }

   $jsonSupplier = \json_encode($array);
    $response = new Response($jsonSupplier);
    $response->headers->set('Content-Type', 'application/json');
    // $response->headers->set('Access-Control-Allow-Origin', '');
    return $response;

    }

    /**
     * @Route("/supplier/{id}", name="supplier_by_id")
     */
    public function supplierByOne(Supplier $supplier){

        // A mon avis on recupera l'id du fournisseur avec la request
        //$id = $request->request->get('id')/
        //$supplier = findBy($id);

            foreach($supplier->getProducts() as $products){

                    $productList[] = [
                        "id" => $products->getId(),
                        "name" => $products->getName(),
                    ];

                }

                if(!isset($productList)){
                    $productList = "Pas de produits"; // on defini un message d'erreur s'il n'y a pas de produits
                }

        $array = [
            'id' => $supplier->getId(),
            'name' => $supplier->getName(),
            'society_name' => $supplier->getSocietyName(),
            'telephone' => $supplier->getPhone(),
            'mail' => $supplier->getMail(),
            'adress' => $supplier->getAdress(),
            'postcode' => $supplier->getPostcode(),
            'town' => $supplier->getTown(),
            'products' => $productList,

             ];


       $jsonOneSupplier = \json_encode($array);
       $response = new Response($jsonOneSupplier);
       $response->headers->set('Content-Type', 'application/json');
       // $response->headers->set('Access-Control-Allow-Origin', '');
       return $response;


    }
}
