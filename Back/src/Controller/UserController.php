<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(UserRepository $userRepo)
    {
        $users = $userRepo->findAll();
        
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }
}
