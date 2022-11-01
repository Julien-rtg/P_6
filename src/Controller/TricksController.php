<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks/details/{id}-{slug}", name="tricks", requirements={"id"="\d+"})
     */
    public function tricks(string $id, FigureRepository $figureRepository): Response
    {
        $figure = $figureRepository->find($id);
        $matchingGroupeFigure = [1 => 'Grabs', 2 => 'Rotations', 3 => 'Flips'];
        $figure->stringGroupeFigure = $matchingGroupeFigure[$figure->getGroupeFigure()];

        return $this->render('public/tricks.html.twig', [
            'figure' => $figure
        ]);
    }
}
