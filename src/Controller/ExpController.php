<?php

namespace App\Controller;

use App\Entity\Exp;
use App\Form\ExpType;
use App\Repository\ExpRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exp")
 */
class ExpController extends AbstractController
{
    /**
     * @Route("/", name="exp_index", methods={"GET"})
     */
    public function index(ExpRepository $expRepository): Response
    {
        return $this->render('exp/index.html.twig', [
            'exps' => $expRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="exp_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $exp = new Exp();
        $form = $this->createForm(ExpType::class, $exp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exp);
            $entityManager->flush();

            return $this->redirectToRoute('exp_index');
        }

        return $this->render('exp/new.html.twig', [
            'exp' => $exp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="exp_show", methods={"GET"})
     */
    public function show(Exp $exp): Response
    {
        return $this->render('exp/show.html.twig', [
            'exp' => $exp,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="exp_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Exp $exp): Response
    {
        $form = $this->createForm(ExpType::class, $exp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('exp_index');
        }

        return $this->render('exp/edit.html.twig', [
            'exp' => $exp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="exp_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Exp $exp): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exp->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('exp_index');
    }
}
