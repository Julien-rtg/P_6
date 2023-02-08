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
    public function createTrick(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader, FigureRepository $figureRepository): Response
    {
        $figure = new Figure();
        $figure2 = new Figure();
        
        $photo = new PhotoFigure();
        $video = new VideoFigure();

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
                $figure2->setIdUtilisateur($this->getUser());

                $em = $doctrine->getManager();
                $em->persist($figure2);
                $em->flush();

                $files = $figure->getPhotoFigures();
                $countFiles = count($files);
                for($i = 0; $i< $countFiles; $i++){
                    if($files[$i] && $files[$i]->getFile()){
                        $attachment = $files[$i]->getFile(); // This is the file
                        $files[$i]->setPath($fileUploader->upload($attachment));
                        if($i == 0){
                            $files[$i]->setPreview(1);
                        }
                        $files[$i]->setIdFigure($figure2);
                        $em->persist($files[$i]);
                    }
                }

                $videos = $figure->getVideoFigures();
                foreach ($videos as $vid) {
                    if($vid->getPath()){
                        preg_match('/src="([^"]+)"/', $vid->getPath(), $match);
                        $url = $match[1];
                        $vid->setPath($url);
                        $vid->setIdFigure($figure2);
                        $em->persist($vid);
                    }
                }
                
                $em->flush();

                $this->addFlash('is-success', 'Figure ajouté');
                return $this->redirect('/');
            }
            
        }

        return $this->render('admin/create_trick.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function checkDataForm(mixed $datas, FigureRepository $figureRepository, mixed $figure, mixed $myForm) : mixed{
        $errorForm = false;
        $nom = $datas->getNom();
        $res = $figureRepository->findBy(['nom' => $nom]);

        if($res){
            $myForm->get('nom')->addError(new FormError(''));
            $errorForm = true;
        }
        $videos = $figure->getVideoFigures();
        $countVideos = count($videos);
        for($k = 0; $k < $countVideos; $k++){
            if($videos[$k]->getPath()){
                preg_match('/src="([^"]+)"/', $videos[$k]->getPath(), $match);
                if(!$match){
                    $index = strval($k);
                    $myForm->get('videoFigures')->addError(new FormError($index));
                    $errorForm = true;
                }
            }
        }
        

        $photos = $figure->getPhotoFigures();
        $countPhotos = count($photos);
        for($i = 0; $i < $countPhotos; $i++){
            if ($photos[$i] && $photos[$i]->getFile()) {
                $extension = $photos[$i]->getFile()->getMimeType();
                
                if($extension != 'image/jpeg' && $extension !='image/jpg' && $extension != 'image/png'){
                    $index = strval($i);
                    $myForm->get('photoFigures')->addError(new FormError($index));
                    $errorForm = true;
                }

            }
        }

        if(!$errorForm){
            return true; // si pas d'erreur on accède ici
        }else {
            return false;
        }
        
    }
}
