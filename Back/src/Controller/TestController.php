<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    

    /**
     * @Route("/test", name="test")
     */
    public function test(ProductRepository $repo){

        $jsonProduct = $repo->findAll();
        dump($jsonProduct);
        // foreach ($jsonProduct as $product) {
        // $product = [
        //     "id" => $jsonProduct->getId(),
        //     "name" => $jsonProduct->getName(),
        //     "supplier" => $jsonProduct->getSupplier(),
        //     "category" => $jsonProduct->getCategory()
        //     ];
        // }

        foreach ($jsonProduct as $index => $currentValue) {
            $array[$index] = [
                'id' => $currentValue->getId(),
                'name' => $currentValue->getName(),
                'supplier' => $currentValue->getSupplier(),
                'category' => $currentValue->getCategory()->getName(),
                'stock' => $currentValue->getStock()
                
            ];
            }
       $jsonTest = \json_encode($array);
       dump($jsonTest);

        $response = new Response($jsonTest);
        $response->headers->set('Content-Type', 'application/json');
        // $response->headers->set('Access-Control-Allow-Origin', '');
        return $response;
 
    }

}
