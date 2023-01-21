<?php

namespace App\Controller;

use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function index(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {
        $em = $doctrine->getManager();

        $user = $this->getUser();
        $form = $this->createForm(UtilisateurType::class, $user);
        $originalAvatar = $em->getUnitOfWork()->getOriginalEntityData($user)['photo'];
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();


            if($datas->getFile()){
                $attachment = $datas->getFile(); // This is the file
                $user->setPhoto($fileUploader->upload($attachment));
            }
            $user->setFile(null);
            $em->persist($user);
            $em->flush();

            $this->addFlash('is-success', 'Utilisateur modifié');
            return $this->redirect('/account');
            
        }
        $user->setFile(null);

        return $this->render('public/account.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'avatar' => $originalAvatar
        ]);
    }

}
