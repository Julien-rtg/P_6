<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\PhotoFigure;
use App\Entity\VideoFigure;
use App\Form\TrickType;
use App\Repository\FigureRepository;
use App\Repository\UtilisateurRepository;
use App\Service\FileUploader;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateTrickController extends AbstractController
{

    /**
     * @Route("/admin/create/trick", name="create_trick")
     */
    public function createTrick(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader, UtilisateurRepository $userRepository, FigureRepository $figureRepository): Response
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
            if ($this->checkDataForm($datas, $figureRepository, $figure, $form) === true) { // si pas d'erreur
                // ON INSERE EN PREMIER LA FIGURE POUR AVOIR L'ID DE LA FIGURE POUR LES PHOTOS ET VIDEOS
                $figure2->setNom($datas->getNom());
                $figure2->setGroupeFigure($datas->getGroupeFigure());
                $figure2->setDescription($datas->getDescription());
                $figure2->setDateCreation(new DateTime());
                $figure2->setDateModification(new DateTime());
                $figure2->setIdUtilisateur($user);

                $em = $doctrine->getManager();
                $em->persist($figure2);
                $em->flush();

                $files = $figure->getPhotoFigures();
                for($i = 0; $i<count($files); $i++){
                    $attachment = $files[$i]->getFile(); // This is the file
                    $files[$i]->setPath($fileUploader->upload($attachment));
                    if($i == 0){
                        $files[$i]->setPreview(1);
                    }
                    $files[$i]->setIdFigure($figure2);
                    $em->persist($files[$i]);
                    $em->flush();
                }
                // dd($files);

                $videos = $figure->getVideoFigures();
                foreach ($videos as $vid) {
                    preg_match('/src="([^"]+)"/', $vid->getPath(), $match);
                    $url = $match[1];
                    $vid->setPath($url);
                    $vid->setIdFigure($figure2);
                    $em->persist($vid);
                    $em->flush();
                }

                return $this->redirect('/?add=true');
            }
            
        }

        return $this->render('admin/create_trick.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function checkDataForm($datas, FigureRepository $figureRepository, $figure, $form){
        $error = false;
        $nom = $datas->getNom();
        $res = $figureRepository->findBy(['nom' => $nom]);

        if($res){
            $form->get('nom')->addError(new FormError(''));
            $error = true;
        }
        $videos = $figure->getVideoFigures();
        for($i = 0; $i < count($videos); $i++){
            preg_match('/src="([^"]+)"/', $videos[$i]->getPath(), $match);
            if(!$match){
                $index = strval($i);
                $form->get('videoFigures')->addError(new FormError($index));
                $error = true;
            }
        }

        $photos = $figure->getPhotoFigures();
        for($j = 0; $j < count($photos); $j++){
            $ext = $photos[$j]->getFile()->getMimeType();
            
            if($ext != 'image/jpeg' && $ext !='image/jpg' && $ext != 'image/png'){
                $index = strval($j);
                $form->get('photoFigures')->addError(new FormError($index));
                $error = true;
            }
        }

        if(!$error){
            return true; // si pas d'erreur on accède ici
        }else {
            return false;
        }
        
    }
}
