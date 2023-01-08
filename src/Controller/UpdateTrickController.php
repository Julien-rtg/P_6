<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Figure;
use App\Entity\PhotoFigure;
use App\Entity\VideoFigure;
use App\Form\TrickType;
use App\Repository\CommentaireRepository;
use App\Repository\FigureRepository;
use App\Repository\PhotoFigureRepository;
use App\Repository\UtilisateurRepository;
use App\Service\FileUploader;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UpdateTrickController extends AbstractController
{


    /**
     * @Route("/admin/tricks/update/{id}-{slug}", name="tricks_update", requirements={"id"="\d+"})
     */
    public function index(string $id, FigureRepository $figureRepository, FileUploader $fileUploader, UtilisateurRepository $userRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $figure = $figureRepository->find($id);
        $em = $doctrine->getManager();
        
        $form = $this->createForm(TrickType::class, $figure);
        
        $originalData = $em->getUnitOfWork()->getOriginalEntityData($figure);
        $originalVideos = [];
        foreach($originalData['videoFigures'] as $ogVideo){
            $originalVideos[] = $ogVideo->getPath();
        }
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();
            if ($this->checkDataForm($datas, $figureRepository, $figure,$form, $originalVideos, $originalData) === true) {// si pas d'erreur
                $user = $userRepository->find(2); // on recup le user
                
                // Récupération de la liste des IDs de photos à supprimer
                $photosToHide = [];
                $videosToHide = [];
                $gettingValuePhotos[] = $request->request->get('photos_to_hide', []);
                for($h = 0; $h < 50; $h++){
                    if(isset($gettingValuePhotos[$h])){
                        $photosToHide[] = $gettingValuePhotos[$h];
                    }else {
                        $h = 50;
                    }
                }
                $gettingValueVideos[] = $request->request->get('videos_to_hide', []);
                for($v = 0; $v < 50; $v++){
                    if(isset($gettingValueVideos[$v])){
                        $videosToHide[] = $gettingValueVideos[$v];
                    }else {
                        $v = 50;
                    }
                }
                
                $datas->setDateModification(new DateTime());
                $datas->setIdUtilisateur($user);
                
                $files = $figure->getPhotoFigures();

                foreach($files as $file){
                    $file->setIdFigure($figure);
                    if (in_array($file->getId(), $photosToHide[0])) {
                        // Suppression de la photo de la collection de photos de la figure
                        $figure->getPhotoFigures()->removeElement($file);
                        // Suppression de la photo de la base de données
                        $em->remove($file);
                    }
                    if($file->getFile()){
                        $attachment = $file->getFile(); // This is the file
                        $file->setPath($fileUploader->upload($attachment));
                        $em->persist($file); // les données des images
                        $em->flush();
                    }
                }

                $videos = $figure->getVideoFigures();

                foreach ($videos as $vid) {
                    $vid->setIdFigure($figure); 
                    if (in_array($vid->getId(), $videosToHide[0])) { // on le supprime
                        // Suppression de la photo de la collection de photos de la figure
                        $figure->getVideoFigures()->removeElement($vid);
                        // Suppression de la video de la base de données
                        $em->remove($vid);
                    }
                    preg_match('/src="([^"]+)"/', $vid->getPath(), $match);
                    if($match){
                        $url = $match[1];
                        $vid->setPath($url);
                        $em->persist($vid);
                        $em->flush();
                    }
                }
                
                $em->persist($datas); // les données de la figures
                $em->flush();
                
                return $this->redirect($request->getUri()); // refresh
            }

        }
        return $this->render('admin/update_trick.html.twig', [
            'figure' => $figure,
            'form' => $form->createView(),
            'originalVideos' => $originalVideos
        ]);

    }

    private function checkDataForm($datas, FigureRepository $figureRepository, $figure, $form, $originalVideos, $originalData)
    {
        $error = false;
        $nom = $datas->getNom();
        $res = $figureRepository->findBy(['nom' => $nom]);

        // dd($originalData);
        
        if ($res) {
            if($originalData['nom'] != $nom){
                $form->get('nom')->addError(new FormError(''));
                $error = true;
            }
        }

        $videos = $figure->getVideoFigures();
        for ($i = 0; $i < count($videos); $i++) {
            preg_match('/src="([^"]+)"/', $videos[$i]->getPath(), $match);
            if (!$match) {
                // si jamais la video de la figure existe et qu'elle est différente de celle en base de données alors on throw l'erreur sinon la valeur est bonne
                if(isset($originalVideos[$i]) && $originalVideos[$i] != $videos[$i]->getPath()){
                    $index = strval($i);
                    $form->get('videoFigures')->addError(new FormError($index));
                    $error = true;
                }else if(!isset($originalVideos[$i])){
                    $index = strval($i);
                    $form->get('videoFigures')->addError(new FormError($index));
                    $error = true;
                }
            }
        }

        $photos = $figure->getPhotoFigures();
        for ($j = 0; $j < count($photos); $j++) {
            if($photos[$j] && $photos[$j]->getFile()){
                $ext = $photos[$j]->getFile()->getMimeType();
                if ($ext != 'image/jpeg' && $ext != 'image/jpg' && $ext != 'image/png') {
                    $index = strval($j);
                    $form->get('photoFigures')->addError(new FormError($index));
                    $error = true;
                }
            }
        }
        if (!$error) {
            return true; // si pas d'erreur on accède ici
        } else {
            return false;
        }
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

    /**
     * @Route("/tricks/deleteMainPicture", name="tricks_delete_main_picture")
     */
    public function deleteMainPicture(Request $request, PhotoFigureRepository $photoFigureRepository, ManagerRegistry $doctrine) : Response
    {
        $em = $doctrine->getManager();
        $id_figure = json_decode($request->getContent());

        $setPhotoPreviewToFalse = $photoFigureRepository->findBy(['id_figure' => $id_figure]);
        foreach($setPhotoPreviewToFalse as $photo){
            $photo->setPreview(0);
            $em->persist($photo); // les données de la figures
            $em->flush();
        }

        return new Response('Ok', 200);
    }

    /**
     * @Route("/tricks/deleteFigure", name="tricks_delete_figure")
     */
    public function deleteFigure(Request $request, FigureRepository $figureRepository, ManagerRegistry $doctrine) : Response
    {
        $em = $doctrine->getManager();
        $id_figure = json_decode($request->getContent());

        $figureToDelete = $figureRepository->findBy(['id' => $id_figure]);

        $em->remove($figureToDelete[0]);
        $em->flush();

        return new Response('Ok', 200);
    }

    /**
     * @Route("/tricks/deleteCom", name="tricks_delete_com")
     */
    public function deleteCom(Request $request, CommentaireRepository $commentaireRepository, ManagerRegistry $doctrine) : Response
    {
        $em = $doctrine->getManager();
        $id_com = json_decode($request->getContent());

        $comToDelete = $commentaireRepository->findOneBy(['id' => $id_com]);

        $em->remove($comToDelete);
        $em->flush();

        $this->addFlash('is-success', 'Commentaire supprimé');

        return new Response('Ok', 200);
    }



}
