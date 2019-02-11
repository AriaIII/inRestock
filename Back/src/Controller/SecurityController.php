<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login/{username}", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, User $user){
        
        $error = $authenticationUtils->getLastAuthenticationError();
          
        $username = $user->getUsername();

        return $this->render('security/login.html.twig', array(
            'username' => $username,
            'error' => $error,
        ));
    }
    /**
      * @Route("/logout", name="security_logout")
      */
     public function logout(){

     }
 }

