<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\PhotoFigure;
use App\Entity\VideoFigure;
use App\Form\TrickType;
use App\Repository\UtilisateurRepository;
use App\Service\FileUploader;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateTrickController extends AbstractController
{

    /**
     * @Route("/create/trick", name="create_trick")
     */
    public function createTrick(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader, UtilisateurRepository $userRepository): Response
    {
        $figure = new Figure();
        $figure2 = new Figure();
        
        $photo = new PhotoFigure();
        $video = new VideoFigure();

        $user = $userRepository->find(2); // on recup le user

        $figure->getPhotoFigures()->add($photo);
        $figure->getVideoFigures()->add($video);

        $form = $this->createForm(TrickType::class, $figure);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            // dd($datas);
            // ON INSERE EN PREMIER LA FIGURE POUR AVOIR L'ID DE LA FIGURE POUR LES PHOTOS ET VIDEOS
            // $figure2->setNom($datas->getNom());
            // $figure2->setGroupeFigure($datas->getGroupeFigure());
            // $figure2->setDescription($datas->getDescription());
            // $figure2->setDateCreation(new DateTime());
            // $figure2->setDateModification(new DateTime());
            // $figure2->setIdUtilisateur($user);

            // $em = $doctrine->getManager();
            // $em->persist($figure2);
            // $em->flush();
            
            // $files = $figure->getPhotoFigures();
            // foreach ($files as $file) {
            //     $attachment = $file->getFile(); // This is the file
            //     $photo->setPath($fileUploader->upload($attachment));
            // }
            // $photo->setIdFigure($figure2);
            // $em->persist($photo);
            // $em->flush();

            $videos = $figure->getVideoFigures();
            foreach ($videos as $vid) {
                preg_match('/src="([^"]+)"/', $vid->getPath(), $match);
                $url = $match[1];
                $video->setPath($url);
                $video->setIdFigure($figure2);
            }
            // dd($videos);
            // $em->persist($video);
            // $em->flush();

            return $this->redirect($request->getUri()); // refresh
        }

        return $this->render('admin/create_trick.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
