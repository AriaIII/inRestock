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
     * @Route("/products", name="all_produit")
     */
    public function produit(ProductRepository $repo){

        $product = $repo->findAll();


        foreach ($product as $index => $currentValue) {
            // à chaque tour de boucle je recupere l'entité Stock
            $stock = $currentValue->getStock();
            // je verifie si elle existe, si non je mets un message par defaut
            if (!$stock) {
                $currentStock = 'Pas de stock';
                $currentStockAlert = 'Pas d\'alerte';
                $currentPackaging = 'Pas de packaging';
            //si oui je recupere le nom des champs associés
            }else{
                $currentStock = $stock->getStock();
                $currentStockAlert = $stock->getStockAlert();
                $currentPackaging = $stock->getPackaging();
            }

            $array[$index] = [
                'id' => $currentValue->getId(),
                'name' => $currentValue->getName(),
                'category' => $currentValue->getCategory()->getName(),
                'stock' => $currentStock,
                'stock_alert' => $currentStockAlert,
                'packaging' => $currentPackaging


            ];
            }

       $jsonProduct = \json_encode($array);

        $response = new Response($jsonProduct);
        $response->headers->set('Content-Type', 'application/json');
        // $response->headers->set('Access-Control-Allow-Origin', '');
        return $response;

    }

    /**
     * @Route("/product/{id}", name="produit_by_one")
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
