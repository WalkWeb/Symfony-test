<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\News;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class NewsController extends AbstractController
{
    /**
     * @Route("/", name="news_list")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('news/index.html.twig', [
            'news' => $this->getDoctrine()->getRepository(News::class)->findAll(),
        ]);
    }

    /**
     * @Route("/news/create", name="news_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $news = new News();

        $form = $this->createFormBuilder($news)
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('text', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Добавить новость',
                'attr' => ['class' => 'btn btn-primary mt-3'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $news = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($news);
            $entityManager->flush();
            return $this->redirectToRoute('news_list');
        }

        return $this->render('news/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/news/edit/{id}", name="news_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, int $id): Response
    {
        $news = $this->getDoctrine()->getRepository(News::class)->find($id);

        $form = $this->createFormBuilder($news)
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('text', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Отредактировать новость',
                'attr' => ['class' => 'btn btn-primary mt-3'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('news_list');
        }

        return $this->render('news/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/news/destroy/{id}", name="news_destroy")
     * @Method({"DELETE"})
     *
     * @param int $id
     */
    public function destroy(int $id): void
    {
        $news = $this->getDoctrine()->getRepository(News::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($news);
        $entityManager->flush();

        (new Response())->send();
    }

    /**
     * @Route("/news/{id}", name="news_show")
     * @Method({"GET"})
     *
     * @param $id
     * @return Response
     */
    public function show(int $id): Response
    {
        return $this->render('news/show.html.twig', [
            'news' => $this->getDoctrine()->getRepository(News::class)->find($id),
        ]);
    }

}
