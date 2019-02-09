<?php

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/backend/", name="backend_index")
     */
    public function index()
    {
        return $this->render('backend/index/index.html.twig', [

        ]);
    }
}
