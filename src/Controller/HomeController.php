<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController{

    /**
     * @Route("/", name="home")
     */
    public function home(FigureRepository $figureRepository): Response
    {
        $allFigure = $figureRepository->findAll();
        dd($allFigure);
        return $this->render('public/home.html.twig');
    }

}