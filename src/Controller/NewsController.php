<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\News;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Exception;

class NewsController extends AbstractController
{
    /**
     * @Route("/news/create", name="news_create", methods={"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     * @throws Exception
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
            $news->setCreated(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($news);
            $entityManager->flush();
            return $this->redirectToRoute('main');
        }

        return $this->render('news/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/news/edit/{slug}", name="news_edit", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param string $slug
     * @return Response
     */
    public function edit(Request $request, string $slug): Response
    {
        $news = $this->getDoctrine()->getRepository(News::class)->findOneBySlug($slug);

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
            return $this->redirectToRoute('main');
        }

        return $this->render('news/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/news/destroy/{slug}", name="news_destroy", methods={"DELETE"})
     *
     * @param string $slug
     */
    public function destroy(string $slug): void
    {
        $news = $this->getDoctrine()->getRepository(News::class)->findOneBySlug($slug);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($news);
        $entityManager->flush();

        (new Response())->send();
    }

    /**
     * @Route("/news/{slug}", name="news_show", methods={"GET"})
     *
     * @param string $slug
     * @return Response
     */
    public function show(string $slug): Response
    {
        return $this->render('news/show.html.twig', [
            'news' => $this->getDoctrine()->getRepository(News::class)->findOneBySlug($slug),
        ]);
    }
}
