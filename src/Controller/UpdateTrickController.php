<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Figure;
use App\Entity\PhotoFigure;
use App\Entity\VideoFigure;
use App\Form\TrickType;
use App\Repository\FigureRepository;
use App\Repository\PhotoFigureRepository;
use App\Repository\UtilisateurRepository;
use App\Service\FileUploader;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateTrickController extends AbstractController
{
    /**
     * @Route("/tricks/update/{id}-{slug}", name="tricks_update", requirements={"id"="\d+"})
     */
    public function index(string $id, FigureRepository $figureRepository, FileUploader $fileUploader, UtilisateurRepository $userRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $figure = $figureRepository->find($id);

        $url = $request->getPathInfo();
        $fullUrl = $request->getUri();
        $form = $this->createForm(TrickType::class, $figure);

        $countPhotoFigure = count($figure->getPhotoFigures());
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            $em = $doctrine->getManager();
            // dd($datas);
            $user = $userRepository->find(2); // on recup le user

            $datas->setDateModification(new DateTime());
            $datas->setIdUtilisateur($user);

            $files = $figure->getPhotoFigures();
            for ($i = 0; $i < $countPhotoFigure; $i++) {
                // dd($files[$i]->getIdFigure());
                if($files[$i]->getFile()){
                    // dd($files[$i]);
                    $attachment = $files[$i]->getFile(); // This is the file
                    $files[$i]->setPath($fileUploader->upload($attachment));
                    $files[$i]->setIdFigure($figure);
                    $em->persist($files[$i]); // les données des images
                    $em->flush();
                }
            }

            $em->persist($datas); // les données de la figures
            $em->flush();

            return $this->redirect($request->getUri()); // refresh
        }

        return $this->render('admin/update_trick.html.twig', [
            'figure' => $figure,
            'form' => $form->createView(),
            'url' => $url,
            'fullUrl' => $fullUrl
        ]);

    }

    /**
     * @Route("/tricks/editMainPicture/{id}", name="tricks_edit_main_picture", requirements={"id"="\d+"})
     */
    public function editMainPicture(string $id, Request $request, PhotoFigureRepository $photoFigureRepository, ManagerRegistry $doctrine) : Response
    {
        $em = $doctrine->getManager();
        $id_figure = json_decode($request->getContent());

        $setPhotoPreviewToFalse = $photoFigureRepository->findBy(['id_figure' => $id_figure]);
        foreach($setPhotoPreviewToFalse as $photo){
            $photo->setPreview(0);
            $em->persist($photo); // les données de la figures
            $em->flush();
        }

        $setPhotoPreview = $photoFigureRepository->find($id);
        $setPhotoPreview->setPreview(1);
        $em->persist($setPhotoPreview); // les données de la figures
        $em->flush();

        return new Response('Ok', 200);
    }

}
