<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="all_user")
     */
    public function user(UserRepository $repo)
    {
        // on récupère tout mes users
       $users = $repo->findAll();

        // on crée un tableau personnalisé et je stocke les valeurs associées aux clés
       foreach ($users as $index => $currentValue) {

        // à chaque tour de boucle on récupère l'entité Post
        $post = $currentValue->getPost();

        // on vérifie si elle existe, sinon je mets un message par défaut
        if(!$post){
            $currentPost = "Pas de poste";
        // si oui on récupère le nom du poste
        }else{
            $currentPost = $post->getName();
        }

        $array[$index] = [
            'id' => $currentValue->getId(),
            'username' => $currentValue->getUsername(),
            'firstname' => $currentValue->getFirstName(),
            'lastname' => $currentValue->getLastName(),
            'role' => $currentValue->getRole()->getName(),
            'poste' => $currentPost,
            'photo' => $currentValue->getPhoto(),

        ];
    }
        //On encode ce tableau en json
     $jsonUser = \json_encode($array);

        // On retourne une réponse
        $response = new Response($jsonUser);

        // On configure les headers
        $response->headers->set('Content-Type', 'application/json;charset=utf-8');
        // $response->headers->set('Access-Control-Allow-Origin', '');
        return $response;

    }

    /**
     * @Route("/user/{id}", name="user_by_id")
     */
    public function UserByOne(User $user){

        // A mon avis on recupera l'id du postes avec la request
        //$id = $request->request->get('id');
        //$user = findBy($id);

            $array = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'firstname' => $user->getFirstName(),
            'lastname' => $user->getLastName(),
            'role' => $user->getRole()->getName(),
            'poste' => $user->getPost()->getName(),
            'photo' => $user->getPhoto(),
             ];


       $jsonOneUser = \json_encode($array);
       $response = new Response($jsonOneUser);
       $response->headers->set('Content-Type', 'application/json');
       // $response->headers->set('Access-Control-Allow-Origin', '');
       return $response;


    }
}
