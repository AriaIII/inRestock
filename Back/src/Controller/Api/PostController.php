<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
    * @Route("/api", name="api_")
 */
class PostController extends AbstractController
{
     /**
     * @Route("/posts", name="all_posts")
     */
    public function post(PostRepository $repo)
    {
       $post = $repo->findAll();

       foreach ($post as $index => $currentValue) {
        $array[$index] = [
            'id' => $currentValue->getId(),
            'name' => $currentValue->getName(),
            'picture' => $currentValue->getPhoto()

        ];
    }

   $jsonSupplier = \json_encode($array);
    $response = new Response($jsonSupplier);
    $response->headers->set('Content-Type', 'application/json');
    // $response->headers->set('Access-Control-Allow-Origin', '');
    return $response;

    }

    /**
     * @Route("/posts/change", name="post_change")
     */
    public function postChange(Request $request, UserRepository $userRepo, PostRepository $postRepo){
        $postList = $postRepo->findAll();
        $data = $request->getContent();
        $req = json_decode($data);   
        
        $newPost = $req->post;
        $postToSet = $postList[$newPost];
        
        $userId = $req->user;
        $user = $userRepo->findById($userId); 
        $user[0]->setPost($postToSet);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user[0]);
        $entityManager->flush();
        return new Response('OK NICE');

    }

}
