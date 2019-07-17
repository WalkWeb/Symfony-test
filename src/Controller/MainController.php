<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\News;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'news' => $this->getDoctrine()->getRepository(News::class)->findAll(),
        ]);
    }
}
