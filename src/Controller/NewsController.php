<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\News;

class NewsController extends AbstractController
{
    /**
     * @Route("/")
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
     * @Route("/news/save")
     */
    public function save(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $news = new News();
        $news->setTitle('News title');
        $news->setText('News text');

        $entityManager->persist($news);
        $entityManager->flush();

        return new Response('news save as id ' . $news->getId());
    }
}
