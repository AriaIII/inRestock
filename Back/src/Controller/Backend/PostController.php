<?php

namespace App\Controller\Backend;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\FileUploader;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route("/backend/post", name="backend_")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post_index", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('backend/post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="post_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && "ROLE_VISITOR" !== $this->getUser()->getRole()->getCode()) {

            $file = $post->getPhoto();
            if(!is_null($post->getPhoto())){
                $fileName = $fileUploader->upload($file);

                $post->setPhoto($fileName);
        }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('backend_post_index');
        }

        return $this->render('backend/post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('backend/post/show.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Post $post, FileUploader $fileUploader): Response
    {
        $oldImage = $post->getPhoto();
        if(!empty($oldImage)) {
            $post->setPhoto(
                new File($this->getParameter('image_directory').'/'.$oldImage)
            );
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && "ROLE_VISITOR" !== $this->getUser()->getRole()->getCode()) {
            if(!is_null($post->getPhoto())){
                $file = $post->getPhoto();
                $fileName = $fileUploader->upload($file);
                $post->setPhoto($fileName);

                if(!empty($oldImage)){
                    unlink(
                        $this->getParameter('image_directory') .'/'.$oldImage
                    );
                }
            } else {
                $post->setPhoto($oldImage);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('backend_post_index', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('backend/post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token')) && "ROLE_VISITOR" !== $this->getUser()->getRole()->getCode()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_post_index');
    }
}
