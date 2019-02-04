<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/backend/user")
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
    public function new(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepo ): Response
    {
        $newUser = new User();
        $form = $this->createForm(UserType::class, $newUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // appel à la fontion custom createUsername() pour créer automatique le username
            $username = $this->createUsername($newUser, $userRepo);
            
            $newUser->setUsername($username);

            $file = $newUser->getPhoto();
            if(!is_null($newUser->getPhoto())){
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                    try {
                        $file->move(
                            $this->getParameter('users_directory'),
                            $fileName
                        );
                    } catch (FileException $e) {
                    // dump($e);
                    }
            $newUser->setPhoto($fileName);
            }

            // appel à la fonction custom createPassword() pour générer un mot de passa automatique
            $password = $this->createPassword(4);
            
            $mail = $this->mail($newUser, $password);
 
            $entityManager = $this->getDoctrine()->getManager();
            $hash = $encoder->encodePassword($newUser, $newUser->getPassword());
            $newUser->setPassword($hash);
            $entityManager->persist($newUser);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
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
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        $oldImage = $user->getPhoto();
        if(!empty($oldImage)) {
            $user->setPhoto(
                new File($this->getParameter('users_directory').'/'.$oldImage)
            );
        }

        $form = $this->createForm(UserType::class, $user);
        $oldPassword = $user->getPassword();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!is_null($user->getPhoto())){
                $file = $user->getPhoto();

                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('users_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    dump($e);
                }

                $user->setPhoto($fileName);
                if(!empty($oldImage)){
                    unlink(
                        $this->getParameter('users_directory') .'/'.$oldImage
                    );
                }
            } else {
                $user->setPhoto($oldImage);
            }

            $entityManager = $this->getDoctrine()->getManager();

            if(empty($user->getPassword()) || is_null($user->getPassword())){
                $encodedPassword = $oldPassword;

                } else {
                $encodedPassword = $encoder->encodePassword($user, $user->getPassword());
                }

            $user->setPassword($encodedPassword);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [
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
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

   /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    public function createUsername($newUser, $userRepo) 
    {
        // cette fonction sert à créer le username automatiquement
        $firstname = $newUser->getFirstName();
        $lastname = $newUser->getLastName();

        // on prend la 1e lettre du nom et du prénom et on y acolle un nombre.
        $firstLetter =  substr($firstname, 0, 1);
        $secondLetter = substr($lastname, 0, 1);
        $number = 1;
        $username = $firstLetter . $secondLetter . $number;
        $users = $userRepo->findAll();
        
        // pour éviter que le username soit identique pour 2 utilisateurs, on récupère tous les usernames en base
        // et on vérifie si le nouveau username existe déjà.
        // si oui, on incrémente le nombre tant qu'on en trouve un existant déjà en base.
        foreach ($users as $user) {
            $test[] = $user->getUsername();
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
            
            $numbers [] = $nb;
                        
            $password .= $nb;
        }
        return $password;
    }

    public function mail($newUser, $password)
    {
        // fonction créant le mail qui enverra le mot de passe au salarié, lui permettant de se connecter à son compte dans le restaurant et d'agir sur les stocks.
        $transport = (new \Swift_SmtpTransport('smtp.free.fr', 25))
        ->setUsername('inrestock@free.fr')
        ->setPassword('In2Restock7')
        ;

        $mailer = new \Swift_Mailer($transport);

        $firstname = $newUser->getFirstName();
        $lastname = $newUser->getLastName();

        
        $message = (new \Swift_Message('Nouveau salarié inRestock'))
        ->setFrom(['inrestock@free.fr' => 'restaurant X'])
        ->setTo(['inrestock@free.fr'])
        ->setBody(
            '<html>' .
            '   <body>' .
            '       <p>Bonjour et bienvenue '.$firstname.' '.$lastname.'</p>'.
            '       <p>Voici votre mot de passe : <span>'.$password.'</span>'.
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

