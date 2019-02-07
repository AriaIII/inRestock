<?php

namespace App\Controller;

use App\Entity\Modification;
use App\Repository\ModificationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModificationController extends AbstractController
{
    /**
     * @Route("/modifications", name="all_modification")
     */
    public function findAll(ModificationRepository $repo)
    {
        $modifications = $repo->findAll();


        foreach ($modifications as $index => $currentValue) {
         $array[$index] = [
             'id' => $currentValue->getId(),
             'name' => $currentValue->getName(),

         ];
         }

    $jsonModification = \json_encode($array);
     $response = new Response($jsonModification);
     $response->headers->set('Content-Type', 'application/json');
     // $response->headers->set('Access-Control-Allow-Origin', '');
     return $response;
    }

}
