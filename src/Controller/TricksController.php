<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Figure;
use App\Entity\Utilisateur;
use App\Form\CommentaireType;
use App\Repository\FigureRepository;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks/details/{id}-{slug}", name="tricks", requirements={"id"="\d+"})
     */
    public function tricks(string $id, FigureRepository $figureRepository, UtilisateurRepository $userRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $commentaire = new Commentaire();
        $figure = $figureRepository->find($id);
        $matchingGroupeFigure = [1 => 'Grabs', 2 => 'Rotations', 3 => 'Flips'];
        $figure->stringGroupeFigure = $matchingGroupeFigure[$figure->getGroupeFigure()];
        
        $url = $request->getPathInfo();
        $pagination = $this->pagination($figure, $request);

        foreach($figure->getCommentaires() as $com){ // on recupere l'utilisateur du commentaire
            $com->user = $userRepository->find($com->getIdUtilisateur()->getId());
        }

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            
            $user = $userRepository->find(2); // on recup le user

            $datas->setDate(new DateTime());
            $datas->setIdFigure($figure);
            $datas->setIdUtilisateur($user);

            $em = $doctrine->getManager();
            $em->persist($datas);
            $em->flush();

            return $this->redirect($request->getUri()); // refresh
        }
        return $this->render('public/tricks.html.twig', [
            'figure' => $figure,
            'form' => $form->createView(),
            'firstCom' => $pagination[0],
            'lastCom' => $pagination[1],
            'pages' => $pagination[2],
            'currentPage' => $pagination[3],
            'url' => $url
        ]);
    }


    private function pagination(?figure $figure, Request $request){ // pagination des commentaires
        // dd($figure->getCommentaires());
        $currentPage = $request->query->get('com');
        if (!isset($currentPage)) { // ON RECUP LA PAGE
            $currentPage = '1';
        }
        $countCom = 0;
        foreach($figure->getCommentaires() as $com){ // on compte chaque commentaire
            $countCom++;
        }
        
        // On détermine le nombre d'articles par page
        $perPage = 4;
        // On calcule le nombre de pages total
        $pages = ceil($countCom / $perPage);
        $pages = intval($pages);

        $firstCom = ((int)$currentPage * $perPage) - $perPage;
        $lastCom = $firstCom + $perPage;
        // dd($lastCom);
        return [$firstCom, $lastCom, $pages, $currentPage];
    }

}
