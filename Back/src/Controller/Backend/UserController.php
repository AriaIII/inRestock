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

            $firstname = $newUser->getFirstName();

            $lastname = $newUser->getLastName();

            $firstLetter =  substr($firstname, 0, 1);
            $secondLetter = substr($lastname, 0, 1);
            $number = 1;
            $username = $firstLetter . $secondLetter . $number;
            $users = $userRepo->findAll();
            dump($users);

            foreach ($users as $user) {
                $test[] = $user->getUsername();
            }

            while (array_search($username, $test))
            {
                $number += 1;
                $username = $firstLetter . $secondLetter . $number;
            }

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
                    dump($e);
                    }
            $newUser->setPhoto($fileName);
        }
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
}
