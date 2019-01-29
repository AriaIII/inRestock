<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="all_categories")
     */
    public function findAll(CategoryRepository $repo)
    {
        $categories = $repo->findAll();


        foreach ($categories as $index => $currentValue) {
         $array[$index] = [
             'id' => $currentValue->getId(),
             'name' => $currentValue->getName(),           
             
         ];
         }
 
    $jsonCategories = \json_encode($array);
    dump($jsonCategories);
 
     $response = new Response($jsonCategories);
     $response->headers->set('Content-Type', 'application/json');
     // $response->headers->set('Access-Control-Allow-Origin', '');
     return $response;
    }

    
    /**
     * @Route("/category/{id}", name="category_by_one")
     */
    public function findOneCategory(Category $category)
    {
          // A mon avis on recupera l'id de la categorie avec la request
        //$id = $request->request->get('id')
        //$category = findBy($id);
          foreach($category->getProducts() as $products){
              $produit[] = [
                  "id" => $products->getId(),
                  "name" => $products->getName(),
                  "stock" => $products->getStock()->getStock()
              ];
              dump($produit);                           
          }


        $array = [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'products' => $produit
            
         ];
        

   $jsonOneCategory = \json_encode($array);
   dump($jsonOneCategory);

   $response = new Response($jsonOneCategory);
   $response->headers->set('Content-Type', 'application/json');
   // $response->headers->set('Access-Control-Allow-Origin', '');
   return $response;
    }

}
