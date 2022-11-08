<?php

namespace App\Controller;

use App\Repository\FigureRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks/details/{id}-{slug}", name="tricks", requirements={"id"="\d+"})
     */
    public function tricks(string $id, FigureRepository $figureRepository, UtilisateurRepository $userRepository): Response
    {
        $figure = $figureRepository->find($id);
        $matchingGroupeFigure = [1 => 'Grabs', 2 => 'Rotations', 3 => 'Flips'];
        $figure->stringGroupeFigure = $matchingGroupeFigure[$figure->getGroupeFigure()];
        
        foreach($figure->getCommentaires() as $com){ // on recupere l'utilisateur du commentaire
            $com->user = $userRepository->find($com->getIdUtilisateur()->getId());
            // dd($com->user->getNom());
        }

        return $this->render('public/tricks.html.twig', [
            'figure' => $figure
        ]);
    }
}
