<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Repository\ProductRepository;
use App\Repository\SupplierRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SupplierController extends AbstractController
{
    /**
     * @Route("/fournisseurs", name="supplier")
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
   dump( $jsonSupplier);

    $response = new Response($jsonSupplier);
    $response->headers->set('Content-Type', 'application/json');
    // $response->headers->set('Access-Control-Allow-Origin', '');
    return $response;

    }

    /**
     * @Route("/fournisseurs/{id}", name="supplier_show")
     */
    public function supplierByOne(Supplier $supplier, ProductRepository $productRepo){
      
        // A mon avis on recupera l'id du fournisseur avec la request
        //$id = $request->request->get('id)
        //$supplier = findBy($id);
        foreach($supplier->getProducts() as $products){
            $produit[] = [
                "id" => $products->getId(),
                "name" => $products->getName(),
            ];
              dump($produit);         
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
                'products' => $produit

             ];
            

       $jsonOneSupplier = \json_encode($array);
       dump($jsonOneSupplier);

       $response = new Response($jsonOneSupplier);
       $response->headers->set('Content-Type', 'application/json');
       // $response->headers->set('Access-Control-Allow-Origin', '');
       return $response;


    }
}
