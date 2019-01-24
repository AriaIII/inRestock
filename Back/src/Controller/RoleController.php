<?php

namespace App\Controller;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
   dump($jsonRole);

    $response = new Response($jsonRole);
    $response->headers->set('Content-Type', 'application/json');
    // $response->headers->set('Access-Control-Allow-Origin', '');
    return $response;

    }

    /**
     * @Route("/roles/{id}", name="roles_by_id")
     */
    public function roleByOne(Role $role){
      
        // A mon avis on recupera l'id du postes avec la request
        //$id = $request->request->get('id)
        //$postes = findBy($id);
        foreach($role->getUsers() as $user){
            $users[] = [
                "id" => $user->getId(),
                "firstName" => $user->getFirstName(),
                "lastName" => $user->getLastName()
            ];
              dump($users);         
        }

            $array = [
                'id' => $role->getId(),
                'name' => $role->getName(),     
                'users' => $users
             ];
            

       $jsonOneRole = \json_encode($array);
       dump($jsonOneRole);

       $response = new Response($jsonOneRole);
       $response->headers->set('Content-Type', 'application/json');
       // $response->headers->set('Access-Control-Allow-Origin', '');
       return $response;


    }
}
