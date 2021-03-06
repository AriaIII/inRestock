<?php

namespace App\Controller\Api;

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
/**
    * @Route("/api", name="api_")
 */
class HistoriqueController extends AbstractController
{
    /**
     * @Route("/history/add", name="historique", methods={"POST"})
     */
    public function line(Request $request, StockRepository $stockRepo, UserRepository $userRepo, ModificationRepository $modifRepo, \Swift_Mailer $mailer)
    {
        // On recupere l'integralité de la request et on la stocke
       $data = $request->getContent();
       
       
       // On utilise la fonction native de chez php : json_decode pour décoder le json
       $req = json_decode($data);
       
       // On récupère l'id de l'user courant
       $userId = $req->user;
       
       // On récupère le user associé à l'id du user courant
       $user = $userRepo->findById($userId);
       
       $userToSet = $user[0]->getFirstName();
       
       $variation = $req->variation;

       $modification = $req->modification;
       $modifToSet = $modifRepo->findById($modification);
       $modif = $modifToSet[0]->getName();
       
       // On récupère le stock associé au produit que l'on doit modifier :
        $product = $req->product;
        $stock = $stockRepo->findByProduct($product);
        $productToSet = $stock[0]->getProduct()->getName(); 

        $stockAlert = $stock[0]->getStockAlert();       
        //On récupère le stock courant
        $currentStock = $stock[0]->getStock();

        if ("ROLE_VISITOR" !== $this->getUser()->getRole()->getCode()) {
            // On lui ajoute la variation
            $newStock = $currentStock + $variation;
            
            //! Si la somme/soustraction du stock rentrée par l'utilisateur passe sous 0 alors on stoppe tout et on retourne un message d'erreur
            if ($newStock < 0){
                return $this->json(['stock' => 'Il n\'est pas possible d\'avoir un stock inférieur à 0']);
            }
            // on set le newStock et on le push en bdd
            $stock[0]->setStock($newStock);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stock[0]);
        
            ///ensuite on crée une nouvelle ligne HistoriqueStock et on la remplit avec les données récupérées plus haut
            $newLine = new HistoriqueStock();
            $newLine->setVariation($variation);
            $newLine->setProduct($productToSet);
            $newLine->setModification($modif);
            $newLine->setUser($userToSet);
            $newLine->setPost($user[0]->getPost()->getName());
            $newLine->setRole($user[0]->getRole()->getName());
            $newLine->setNewStock($newStock);
            $newLine->setCreatedAt(new \DateTime());
            $entityManager->persist($newLine);
            $entityManager->flush();
        
            if ($newStock < $stockAlert){
            
                $mail = $this->mail($productToSet, $stockAlert, $newStock, $mailer);
            }
        } else {
            $newStock = $currentStock;
        }

        return $this->json(['stock' => $newStock], 200);
        

    }

    public function mail($productToSet, $stockAlert,$newStock, $mailer){
    
         $message = (new \Swift_Message('Limite atteinte du produit : '.$productToSet))
         ->setFrom(['inrestock@free.fr' => 'restaurant X'])
         ->setTo(['inrestock@free.fr'])
         ->setBody(
             '<html>' .
             '   <body>' .
             '       <p>Bonjour,</p>'.
             '       <p>Le produit : <span>'.$productToSet.' </span> a atteint la limite du seuil d\'alerte : <span>'.$stockAlert.'</span></p>'.
             ' <p> Le nouveau stock est de : '.$newStock.'</p>'.
             '       <p>Pensez à commander !</p>'.
             '       <p>Bien cordialement,</p>'.
             '       <p>La direction</p>'.
             '   </body>' .
             '</html>',
             'text/html')
         ->addPart('Bonjour, '.
             'Le produit : '.$productToSet.' a atteint la limite du seuil d\'alerte : '.$stockAlert.
             'Pensez à commander !',
             'text/plain')
         ;
         return $result = $mailer->send($message);
    }
}
