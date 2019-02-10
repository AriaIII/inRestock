<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ModificationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ProductController extends AbstractController
{
     /**
     * @Route("post/{postId}/category/{categoryId}/product/{productId}", name="product_modification")
    * @ParamConverter("post", class="App:Post", options={"mapping": {"postId": "id"}})
    * @ParamConverter("category", class="App:Category", options={"mapping": {"categoryId": "id"}})
    * @ParamConverter("product", class="App:Product", options={"mapping": {"productId": "id"}})
    */
    public function modifications(Category $category, Post $post, Product $product, ModificationRepository $modifRepo)
    {
        $modifications = $modifRepo->findAll();

        return $this->render('product/modification.html.twig', [
            'category' => $category,
            'product' => $product,
            'modifications' => $modifications
        ]);
    }
}
