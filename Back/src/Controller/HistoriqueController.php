<?php

namespace App\Controller;

use PhpParser\JsonDecoder;
use App\Entity\HistoriqueStock;
use App\Repository\UserRepository;
use App\Repository\StockRepository;
use App\Repository\ModificationRepository;
use JMS\SerializerBundle\JMSSerializerBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HistoriqueController extends AbstractController
{
    /**
     * @Route("/historique/add", name="historique", methods={"POST"})
     */
    public function line(Request $request, StockRepository $stockRepo, UserRepository $userRepo, ModificationRepository $modifRepo)
    {
        $data = $request->getContent();
        $req = json_decode($data);
        //je recupère les données envoyées en post :
       $product = $req->product;


       // je recup l'id de l'user courrant
       $userId = $req->user;
       //je recupere l'user associé a l'id de l'user courrant
       $user = $userRepo->findById($userId);

       $variation = $req->variation;

       $modification = $req->modification;
       $modifList = $modifRepo->findAll();
       $modifToSet = $modifList[$modification];


       // Je récupère le stock associé au produit que l'on doit modifier :
        $stock = $stockRepo->findByProduct($product);



       //je recupere le stock courrant
        $currentStock = $stock[0]->getStock();
        // je lui rajoute la variation
        $newStock = $currentStock + $variation;
        // je set le newStock et je le push en bdd
        $stock[0]->setStock($newStock);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($stock[0]);

        //ensuite je crée une nouvelle ligne HistoriqueStock et je la rempli avec les données recupérer plus haut
        $newLine = new HistoriqueStock();
        $newLine->setVariation($variation);
        $newLine->setStock($stock[0]);
        $newLine->setModification($modifToSet);
        $newLine->setUser($user[0]);
        // $newLine->setPost($user->getPost->getName());
        // $newLine->setRole($user->getRole->getName());
        $newLine->setCreatedAt(new \DateTime());
        $entityManager->persist($newLine);
        $entityManager->flush();

        return new Response('OK SUPER');




    }
}
