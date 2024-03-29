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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

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
        $slugger = new AsciiSlugger();
        $figure->slug = $slugger->slug($figure->getNom());

        $showPreview = false;
        foreach($figure->getPhotoFigures() as $fig){
            if($fig->getPreview()){
                $showPreview=true;
                $figureToPreview = $fig;
            }
        }
        $userConnected = $this->getUser();
        $url = $request->getPathInfo();
        $fullUrl = $request->getUri();

        foreach($figure->getCommentaires() as $com){ // on recupere l'utilisateur du commentaire
            $nom = $userRepository->find($com->getIdUtilisateur()->getId())->getNom();
            $prenom = $userRepository->find($com->getIdUtilisateur()->getId())->getPrenom();
            $com->user = $nom . ' ' . $prenom;
            $com->userPhoto = $userRepository->find($com->getIdUtilisateur()->getId())->getPhoto();
        }

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            if(!$userConnected->getNom() || !$userConnected->getPrenom()){
                $this->addFlash('is-danger', 'Veuillez renseigner votre nom et prénom avant d\'ajouter un commentaire');
            }else if (strlen(trim($datas->getContenu())) == 0){
                $this->addFlash('is-danger', 'Veuillez renseigner un commentaire');
            }else {
                $user = $userRepository->find($userConnected); // on recup le user

                $datas->setDate(new DateTime());
                $datas->setIdFigure($figure);
                $datas->setIdUtilisateur($user);

                $em = $doctrine->getManager();
                $em->persist($datas);
                $em->flush();

                $this->addFlash('is-success', 'Commentaire ajouté !');

                return $this->redirect($request->getUri()); // refresh
            }
        }

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {
            $reset = $request->request->get('reset');
            if($reset == 'true'){
                $lastCom = 10;
            }else {
                $lastCom = $request->request->get('lastCom');
                $lastCom = intval($lastCom)+5;
            }
            return new JsonResponse([
                'content' => $this->renderView('elements/comments.html.twig', [
                    'figure' => $figure,
                    'firstCom' => 0,
                    'lastCom' => $lastCom,
                    'fullUrl' => $fullUrl
                ])
            ]);
        } else { 
            return $this->render('public/tricks.html.twig', [
                'figure' => $figure,
                'form' => $form->createView(),
                'firstCom' => 0,
                'lastCom' => 10,
                'url' => $url,
                'fullUrl' => $fullUrl,
                'userConnected' => $userConnected,
                'showPreview' => $showPreview,
                'figureToPreview' => $figureToPreview ?? null
            ]);
        }
    }


}
