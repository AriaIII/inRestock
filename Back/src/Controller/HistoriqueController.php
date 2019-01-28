<?php

namespace App\Controller;

use App\Entity\HistoriqueStock;
use App\Repository\UserRepository;
use App\Repository\StockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoriqueController extends AbstractController
{
    /**
     * @Route("/historique/add", name="historique")
     */
    public function line(Request $request, StockRepository $stockRepo, UserRepository $userRepo)
    {
        //je recupère les données envoyées en post :
       $product = $request->request->get('product');
       // je recup l'id de l'user courrant
       $userId = $request->request->get('user');
       //je recupere l'user associé a l'id de l'user courrant
       $user = $userRepo->findById($userId);
       $variation = $request->request->get('variation');
       $modification = $request->request->get('modification');

       // Je récupère le stock associé au produit que l'on doit modifier :
        $stock = $stockRepo->findByProduct($product);
        dump($stock);
       //je recupere le stock courrant
        $currentStock = $stock->getStock();
        // je lui rajoute la variation
        $newStock = $currentStock + $variation;
        // je set le newStock et je le push en bdd
        $stock->setStock($newStock);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($stock);

        //ensuite je crée une nouvelle ligne HistoriqueStock et je la rempli avec les données recupérer plus haut
        $newLine = new HistoriqueStock();
        $newLine->setVariation($variation);
        $newLine->setStock($newStock);
        $newLine->setModification($modification);
        $newLine->setPost($user->getPost->getName());
        $newLine->setRole($user->getRole->getName());
        $newLine->setCreatedAt(new \DateTime());
        $entityManager->persist($newLine);
        $entityManager->flush();

        return $this->json($data = ["message" => "Opération réussie"], $status = 200);




    }
}
