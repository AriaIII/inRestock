<?php

namespace App\Controller\Backend;

use App\Entity\Modification;
use App\Form\ModificationType;
use App\Repository\ModificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/modification")
 */
class ModificationController extends AbstractController
{
    /**
     * @Route("/", name="modification_index", methods={"GET"})
     */
    public function index(ModificationRepository $modificationRepository): Response
    {
        return $this->render('backend/modification/index.html.twig', [
            'modifications' => $modificationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="modification_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $modification = new Modification();
        $form = $this->createForm(ModificationType::class, $modification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($modification);
            $entityManager->flush();

            return $this->redirectToRoute('modification_index');
        }

        return $this->render('backend/modification/new.html.twig', [
            'modification' => $modification,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="modification_show", methods={"GET"})
     */
    public function show(Modification $modification): Response
    {
        return $this->render('backend/modification/show.html.twig', [
            'modification' => $modification,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="modification_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Modification $modification): Response
    {
        $form = $this->createForm(ModificationType::class, $modification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('modification_index', [
                'id' => $modification->getId(),
            ]);
        }

        return $this->render('backend/modification/edit.html.twig', [
            'modification' => $modification,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="modification_delete")
     */
    public function delete(Request $request, Modification $modification): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($modification);
            $entityManager->flush();


        return $this->redirectToRoute('modification_index');
    }
}
