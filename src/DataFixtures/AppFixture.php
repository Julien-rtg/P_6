<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use App\Entity\Figure;
use App\Entity\PhotoFigure;
use App\Entity\Utilisateur;
use App\Entity\VideoFigure;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixture extends Fixture
{
    protected $userRepository;
    protected $userPasswordHasher;

    public const FIG_REF = 'fig-ref_%s';
    public const USER_REF = 'user_%s';

    public function __construct(UtilisateurRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->userLoad($manager);
        $this->figureLoad($manager);
        $this->photoFigureLoad($manager);
        $this->commentLoad($manager);


        // $manager->flush();
    }

    public function userLoad($manager)
    {
        $dataUsers = [
            [
                'nom' => 'Wisozk',
                'prenom' => 'Alexis',
                'email' => 'admin@admin.fr',
                'mdp' => 'mdp123',
                'photo' => 'error.png',
                'role' => ["ROLE_ADMIN"],
                'login' => 'admin',
                'is_verified' => 1
            ],
            [
                'nom' => 'Tuaoq',
                'prenom' => 'Pierre',
                'email' => 'test@email1.fr',
                'mdp' => 'mdp123',
                'photo' => 'avatar.png',
                'role' => ["ROLE_USER"],
                'login' => 'test1',
                'is_verified' => 1
            ],
            [
                'nom' => 'Anjz',
                'prenom' => 'Paul',
                'email' => 'test@email2.fr',
                'mdp' => 'mdp123',
                'photo' => 'avatar.png',
                'role' => ["ROLE_USER"],
                'login' => 'test2',
                'is_verified' => 1
            ],
        ];

        for ($i = 0; $i < count($dataUsers); $i++) {
            $user = new Utilisateur();
            $user->setNom($dataUsers[$i]['nom']);
            $user->setPrenom($dataUsers[$i]['prenom']);
            $user->setEmail($dataUsers[$i]['email']);
            $user->setMdp($this->userPasswordHasher->hashPassword(
                $user,
                $dataUsers[$i]['mdp']
            ));
            $user->setPhoto($dataUsers[$i]['photo']);
            $user->setRole($dataUsers[$i]['role']);
            $user->setLogin($dataUsers[$i]['login']);
            $user->setIsVerified($dataUsers[$i]['is_verified']);
            $manager->persist($user);
            $this->addReference(sprintf(self::USER_REF, $i), $user);
        }
        $manager->flush();
    }

    public function figureLoad($manager)
    {
        $dataFigures = [
            [
                'nom' => 'Indy grab',
                'description' =>
                'saisie de la carre frontside de la planche,
                entre les deux pieds, avec la main arrière ',
                'groupe_figure' => 1,
                'id_utilisateur_id' => 2,
                'date_creation' => '2023-01-31 10:53:43',
                'date_modification' => '2023-01-31 10:53:43',
            ],
            [
                'nom' => 'Japan air grab',
                'description' =>
                'saisie de l\'avant de la planche,avec la main
                avant, du côté de la carre frontside.',
                'groupe_figure' => 1,
                'id_utilisateur_id' => 1,
                'date_creation' => '2023-01-31 10:53:43',
                'date_modification' => '2023-01-31 10:53:43',
            ],
            [
                'nom' => 'Mute grab',
                'description' =>
                'saisie de la carre frontside de la planche
                entre les deux pieds avec la main avant',
                'groupe_figure' => 1,
                'id_utilisateur_id' => 3,
                'date_creation' => '2023-01-31 10:53:43',
                'date_modification' => '2023-01-31 10:53:43',
            ],
            [
                'nom' => 'Nose grab',
                'description' => 'saisie de la partie 
            avant de la planche, avec la main avant',
                'groupe_figure' => 1,
                'id_utilisateur_id' => 1,
                'date_creation' => '2023-01-31 10:53:43',
                'date_modification' => '2023-01-31 10:53:43',
            ],
            [
                'nom' => 'Sad ou melancholie',
                'description' =>
                'saisie de la carre backside de la planche, entre les deux pieds, 
            avec la main avant. Le rider est en position goofy.',
                'groupe_figure' => 2,
                'id_utilisateur_id' => 2,
                'date_creation' => '2023-01-31 10:53:43',
                'date_modification' => '2023-01-31 10:53:43',
            ],
            [
                'nom' => 'Seat belt',
                'description' => 'saisie du carre frontside à l\'arrière avec la main avant',
                'groupe_figure' => 1,
                'id_utilisateur_id' => 2,
                'date_creation' => '2023-01-31 10:53:43',
                'date_modification' => '2023-01-31 10:53:43',
            ],
            [
                'nom' => 'Stalefish',
                'description' =>
                'saisie de la carre backside de la planche entre les deux pieds avec la main arrière,
                sur cette image le rider est en position regular (son pied gauche est à l\'avant).',
                'groupe_figure' => 1,
                'id_utilisateur_id' => 3,
                'date_creation' => '2023-01-31 10:53:43',
                'date_modification' => '2023-01-31 10:53:43',
            ],
            [
                'nom' => 'Tail grab',
                'description' =>
                'saisie de la partie arrière de la planche, avec la main arrière. 
            Le rider est ici en position goofy.',
                'groupe_figure' => 1,
                'id_utilisateur_id' => 2,
                'date_creation' => '2023-01-31 10:53:43',
                'date_modification' => '2023-01-31 10:53:43',
            ],
            [
                'nom' => 'Truck driver',
                'description' =>
                'saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)',
                'groupe_figure' => 1,
                'id_utilisateur_id' => 3,
                'date_creation' => '2023-01-31 10:53:43',
                'date_modification' => '2023-01-31 10:53:43',
            ],
            [
                'nom' => 'Tail grab avec front flip',
                'description' =>
                'Le rider en position regular, effectue un front flip en saisissant la partie arrière de la planche,
                avec la main arrière.',
                'groupe_figure' => 1,
                'id_utilisateur_id' => 1,
                'date_creation' => '2023-01-31 10:53:43',
                'date_modification' => '2023-01-31 10:53:43',
            ],
        ];

        for ($i = 0; $i < count($dataFigures); $i++) {
            $userRandom = rand(0, 2);
            $figure = new Figure();
            $figure->setNom($dataFigures[$i]['nom']);
            $figure->setDescription($dataFigures[$i]['description']);
            $figure->setGroupeFigure($dataFigures[$i]['groupe_figure']);
            $figure->setIdUtilisateur($this->getReference('user_' . $userRandom));
            $figure->setDateCreation(new DateTime());
            $figure->setDateModification(new DateTime());
            $manager->persist($figure);
            $this->addReference(sprintf(self::FIG_REF, $i), $figure);
        }
        $manager->flush();
    }

    public function photoFigureLoad($manager)
    {
        $dataPhotoFigures = [
            [
                'path' => '360.jpg',
                'id_figure_id' => 0,
                'preview' => 1,
            ],
            [
                'path' => 'backside.jpg',
                'id_figure_id' => 0,
                'preview' => 1,
            ],
            [
                'path' => 'front_flip.jpg',
                'id_figure_id' => 0,
                'preview' => 1,
            ],
            [
                'path' => 'fs_japan.jpg',
                'id_figure_id' => 0,
                'preview' => 1,
            ],
            [
                'path' => 'indy.jpg',
                'id_figure_id' => 0,
                'preview' => 1,
            ],
            [
                'path' => 'indy2.jpg',
                'id_figure_id' => 0,
                'preview' => 1,
            ],
            [
                'path' => 'japan_air.jpg',
                'id_figure_id' => 0,
                'preview' => 1,
            ],
            [
                'path' => 'mute.jpg',
                'id_figure_id' => 0,
                'preview' => 1,
            ],
            [
                'path' => 'nose_grab.jpg',
                'id_figure_id' => 0,
                'preview' => 1,
            ],
            [
                'path' => 'one_foot.jpg',
                'id_figure_id' => 0,
                'preview' => 1,
            ],
        ];

        for ($i = 0; $i < count($dataPhotoFigures); $i++) {
            $photoFigure = new PhotoFigure();
            $photoFigure->setPath($dataPhotoFigures[$i]['path']);
            $photoFigure->setPreview($dataPhotoFigures[$i]['preview']);
            $photoFigure->setIdFigure($this->getReference('fig-ref_' . $i));
            $manager->persist($photoFigure);
        }
        $manager->flush();
    }

    public function videoFigureLoad($manager)
    {
        $dataVideoFigures = [
            [
                'path' => 'https://www.youtube.com/embed/aXrY6a15EKE',
                'id_figure_id' => 0,
            ],
            [
                'path' => 'https://www.youtube.com/embed/aXrY6a15EKE',
                'id_figure_id' => 0,
            ],
            [
                'path' => 'https://www.youtube.com/embed/t705_V-RDcQ',
                'id_figure_id' => 0,
            ],
            [
                'path' => 'https://www.youtube.com/embed/t705_V-RDcQ',
                'id_figure_id' => 0,
            ],
            [
                'path' => 'https://www.youtube.com/embed/t705_V-RDcQ',
                'id_figure_id' => 0,
            ],
            [
                'path' => 'https://www.youtube.com/embed/I9t_ez_utno',
                'id_figure_id' => 0,
            ],
            [
                'path' => 'japan_air.jpg',
                'id_figure_id' => 0,
            ],
            [
                'path' => 'https://www.youtube.com/embed/I9t_ez_utno',
                'id_figure_id' => 0,
            ],
            [
                'path' => 'https://www.youtube.com/embed/JCjmmlvVnc8',
                'id_figure_id' => 0,
            ],
            [
                'path' => 'https://www.youtube.com/embed/JCjmmlvVnc8',
                'id_figure_id' => 0,
            ],
        ];

        for ($i = 0; $i < count($dataVideoFigures); $i++) {
            $videoFigure = new VideoFigure();
            $videoFigure->setPath($dataVideoFigures[$i]['path']);
            $videoFigure->setIdFigure($this->getReference('fig-ref_' . $i));
            $manager->persist($videoFigure);
        }
        $manager->flush();
    }

    public function commentLoad($manager)
    {
        for ($i = 0; $i < 30; $i++) {

            $authorRefRandom = rand(0, 2);
            $figRandom = rand(0, 9);
            $comment = new Commentaire('');
            $comment->setContenu('Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l\'imprimerie depuis les années 1500');
            $comment->setDate(new \DateTime());
            $comment->setIdUtilisateur($this->getReference('user_' . $authorRefRandom));
            $comment->setIdFigure($this->getReference('fig-ref_' . $figRandom));
            $manager->persist($comment);
        }


        $manager->flush();
    }
}
