<?php

namespace App\Controller\Api;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
    * @Route("/api", name="api_")
 */
class RoleController extends AbstractController
{
    /**
     * @Route("/roles", name="all_roles")
     */
    public function roles(RoleRepository $repo)
    {
       $roles = $repo->findAll();

       foreach ($roles as $index => $currentValue) {
        $array[$index] = [
            'id' => $currentValue->getId(),
            'name' => $currentValue->getName(),

        ];
    }

    $jsonRole = \json_encode($array);
    $response = new Response($jsonRole);
    $response->headers->set('Content-Type', 'application/json;charset=utf-8');
    // $response->headers->set('Access-Control-Allow-Origin', '');
    return $response;

    }

}
