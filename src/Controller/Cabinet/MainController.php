<?php

declare(strict_types=1);

namespace App\Controller\Cabinet;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller\Cabinet
 * @Route("/cabinet")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="cabinet_main", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('cabinet/index.html.twig');
    }
}
