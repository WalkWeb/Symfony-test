<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $news = [
            ['id' => 1, 'title' => 'Новость #1', 'text' => 'Содержимое новости'],
        ];

        return $this->render('news/index.html.twig', [
            'news' => $news,
        ]);
    }
}
