<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use App\Repository\PhotoFigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController{

    /**
     * @Route("/", name="home")
     */
    public function home(FigureRepository $figureRepository): Response
    {
        // je récupère toutes mes figures avec les relations déjà faites
        $allFigure = $figureRepository->findAll();
        // dd($allFigure[0]->getIdUtilisateur()->getNom());

        return $this->render('public/home.html.twig', [
            'figures' => $allFigure
        ]);
    }

}