<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
     /**
     * @Route("/postes", name="all_posts")
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
   dump( $jsonSupplier);

    $response = new Response($jsonSupplier);
    $response->headers->set('Content-Type', 'application/json');
    // $response->headers->set('Access-Control-Allow-Origin', '');
    return $response;

    }

    /**
     * @Route("/postes/change", name="post_change")
     */
    public function postChange(Request $request, UserRepository $userRepo, PostRepository $postRepo){
        $postList = $postRepo->findAll();
        $data = $request->getContent();
        $req = json_decode($data);

        dd($postList);
        $newPost = $req->post;
        $postToSet = $postList[$newPost];



        $userId = $req->user;
        $user = $userRepo->findById($userId);
        dd($user);
        $user[0]->setPost($postToSet);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user[0]);
        $entityManager->flush();
        return new Response('OK NICE');

    }

}
