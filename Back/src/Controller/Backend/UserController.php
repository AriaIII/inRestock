<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Service\FileUploader;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/backend/user", name="backend_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('backend/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepo, FileUploader $fileUploader, \Swift_Mailer $mailer ): Response
    {
        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid() && "ROLE_VISITOR" !== $this->getUser()->getRole()->getCode()) {

            // appel à la fontion createUsername() pour créer automatiquement le username
            $username = $this->createUsername($newUser, $userRepo);

            $newUser->setUsername($username);

            /*------------------------- UPLOAD IMAGE ---------------------------------
             on recupere l'image du formulaire et on la stocke */
            $file = $newUser->getPhoto();
            // on verifie si l'utilisateur a entré une image (si le champ image n'est pas nul)
            if(!is_null($file)){
                $fileName = $fileUploader->upload($file);
                $newUser->setPhoto($fileName);
            }
            // appel à la fonction createPassword() pour générer un mot de passe automatiquement
            $password = $this->createPassword(4);
            //appel a la fonction custom mail qui enverra le message au salarié
            $mail = $this->mail($newUser, $password, $mailer);

            $entityManager = $this->getDoctrine()->getManager();
            // encodage du mot de passe
            $hash = $encoder->encodePassword($newUser, $password);
            $newUser->setPassword($hash);
            
            $entityManager->persist($newUser);
            $entityManager->flush();    
            
            

            return $this->redirectToRoute('backend_user_index');
        }

        return $this->render('backend/user/new.html.twig', [
            'user' => $newUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('backend/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder, FileUploader $fileUploader): Response
    {
        $oldImage = $user->getPhoto();
        // incomprehension ici
        if(!empty($oldImage)) {
            $user->setPhoto(
                new File($this->getParameter('image_directory').'/'.$oldImage)
            );
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && "ROLE_VISITOR" !== $this->getUser()->getRole()->getCode()) {
            if(!is_null($user->getPhoto())){
                $file = $user->getPhoto();
                $fileName = $fileUploader->upload($file);
                $user->setPhoto($fileName);
                if(!empty($oldImage)){
                    unlink(
                        $this->getParameter('image_directory') .'/'.$oldImage
                    );
                }
            } else {
                $user->setPhoto($oldImage);
            }

            //je recup le manager doctrine
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('backend_user_index', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('backend/user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token')) && "ROLE_VISITOR" !== $this->getUser()->getRole()->getCode()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_user_index');
    }


    public function createUsername($newUser, $userRepo)
    {
        // cette fonction sert à créer le username automatiquement
        $firstname = $newUser->getFirstName();
        $lastname = $newUser->getLastName();

        // on prend la 1e lettre du nom et du prénom et on y accole un nombre.
        $firstLetter =  substr($firstname, 0, 1);
        $secondLetter = substr($lastname, 0, 1);
        $number = 1;
        $username = $firstLetter . $secondLetter . $number;
        $users = $userRepo->findAll();

        // pour éviter que le username soit identique pour 2 utilisateurs, on récupère tous les usernames en base
        // et on vérifie si le nouveau username existe déjà.
        // si oui, on incrémente le nombre tant qu'on en trouve un existant déjà en base.
        if (!empty($users)) {
            foreach ($users as $user) {
                $test[] = $user->getUsername();
            }
        } else {
            $test = [];
        }        

        while (array_search($username, $test))
        {
            $number += 1;
            $username = $firstLetter . $secondLetter . $number;
        }

        return $username;
    }

    public function createPassword($passwordLength)
    {
        // la fonction génère un mot de passe aléatoire du nombre de chiffres passé en argument
        $password = "";
        for($i = 1; $i <= $passwordLength; $i++)
        {
            $nb = rand(0, 9);

            $password .= $nb;
        }
        return $password;
    }

    public function mail($newUser, $password, $mailer)
    {
        $firstname = $newUser->getFirstName();
        $lastname = $newUser->getLastName();
        $email = $newUser->getEmail();


        $message = (new \Swift_Message('Nouveau salarié inRestock'))
        ->setFrom(['inrestock@free.fr' => 'restaurant X'])
        ->setTo([$email])
        ->setBody(
            '<html>' .
            '   <body>' .
            '       <p>Bonjour et bienvenue '.$firstname.' '.$lastname.'</p>'.
            '       <p>Voici votre mot de passe : <span>'.$password.'</span></p>'.
            '       <p>Il vous servira à vous connecter à la base de gestion des stocks de notre restaurant.</p>'.
            '       <p>Bien cordialement,</p>'.
            '       <p>La direction</p>'.
            '   </body>' .
            '</html>',
            'text/html')
        ->addPart('Bonjour et bienvenue '.$firstname.' '.$lastname.
            'Voici votre mot de passe : '.$password.
            'Il vous servira à vous connecter à la base de gestion des stocks de notre restaurant.',
            'text/plain')
        ;
        return $result = $mailer->send($message);
    }
}

