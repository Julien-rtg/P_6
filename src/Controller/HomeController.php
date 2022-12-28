<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use App\Repository\PhotoFigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class HomeController extends AbstractController{

    /**
     * @Route("/", name="home")
     */
    public function home(FigureRepository $figureRepository, Request $request): Response
    {
        $addTrick = $request->query->get('add');
        $deleteTrick = $request->query->get('delete');
        // je récupère toutes mes figures avec les relations déjà faites
        $allFigure = $figureRepository->findAll();
        $slugger = new AsciiSlugger();
        // on boucle sur toutes les figure pour rajouter le slug
        foreach($allFigure as $figure){
            $figure->slug = $slugger->slug($figure->getNom());
        }

        return $this->render('public/home.html.twig', [
            'figures' => $allFigure,
            'addTrick' => $addTrick,
            'deleteTrick' => $deleteTrick
        ]);
    }

}