<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CategoryController extends AbstractController
{
    /**
     * @Route("post/{id}/category", name="category_index")
     */
    public function index(CategoryRepository $categoryRepository, Post $post, EntityManagerInterface $em)
    {
        $postId = $post->getId();
        $user = $this->getUser();
        $user->setPost($post);
        $em->persist($user);
        $em->flush();

        
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'postId' => $postId
        ]);
    }

    /**
     * @Route("post/{postId}/category/{categoryId}", name="category_show")
    * @ParamConverter("post", class="App:Post", options={"mapping": {"postId": "id"}})
    * @ParamConverter("category", class="App:Category", options={"mapping": {"categoryId": "id"}})
    */
    public function show(Category $category, Post $post)
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'postId' => $post->getId(),
        ]);
    }

}
