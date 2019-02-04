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
     * @Route("/modification", name="all_modification")
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
    dump($jsonModification);

     $response = new Response($jsonModification);
     $response->headers->set('Content-Type', 'application/json');
     // $response->headers->set('Access-Control-Allow-Origin', '');
     return $response;
    }

     /**
     * @Route("/modification/{id}", name="modification_by_one")
     */
    public function modificationByOne(Modification $modification){

        // A mon avis on recupera l'id du postes avec la request
        //$id = $request->request->get('id');
        //$modification = findBy($id);

        $array = [
            'id' => $modification->getId(),
            'name' => $modification->getName(),
            ];


       $jsonOneModification = \json_encode($array);
       dump($jsonOneModification);

       $response = new Response($jsonOneModification);
       $response->headers->set('Content-Type', 'application/json');
       // $response->headers->set('Access-Control-Allow-Origin', '');
       return $response;
    }
}
