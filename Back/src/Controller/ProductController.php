<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{

    /**
     * @Route("/produit", name="all_produit")
     */
    public function produit(ProductRepository $repo){

        $product = $repo->findAll();


        foreach ($product as $index => $currentValue) {
            $array[$index] = [
                'id' => $currentValue->getId(),
                'name' => $currentValue->getName(),
                'category' => $currentValue->getCategory()->getName(),
                'stock' => $currentValue->getStock()->getStock(),
                'stock_alert' => $currentValue->getStock()->getStockAlert(),
                // 'packaging' => $currentValue->getStock()->getPackaging()


            ];
            }

       $jsonProduct = \json_encode($array);

        $response = new Response($jsonProduct);
        $response->headers->set('Content-Type', 'application/json');
        // $response->headers->set('Access-Control-Allow-Origin', '');
        return $response;

    }

    /**
     * @Route("/produit/{id}", name="produit_by_one")
     */
    public function produitByOne(Product $product){

        // A mon avis on recupera l'id du postes avec la request
        //$id = $request->request->get('id');
        //$user = findBy($id);

        $array = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'category' => $product->getCategory()->getName(),
            'stock' => $product->getStock()->getStock(),
            'stock_alert' => $product->getStock()->getStockAlert(),
            ];


       $jsonOneProduct = \json_encode($array);
       dump($jsonOneProduct);

       $response = new Response($jsonOneProduct);
       $response->headers->set('Content-Type', 'application/json');
       // $response->headers->set('Access-Control-Allow-Origin', '');
       return $response;
    }

}
