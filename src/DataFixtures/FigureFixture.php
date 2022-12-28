<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\PhotoFigure;
use App\Entity\Utilisateur;
use App\Entity\VideoFigure;
use App\Repository\UtilisateurRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FigureFixture extends Fixture
{
    protected $userRepository;

    public function __construct(UtilisateurRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {

        $user = $this->userRepository->find(2);
        for ($i = 1; $i < 20; $i++) {
            $figure = new Figure();
            $video = new VideoFigure();
            $photo = new PhotoFigure();
            $figure->setNom('Figure'.$i);
            $figure->setGroupeFigure(1);
            $figure->setDescription('Ok, I guess it');
            $figure->setIdUtilisateur($user);
            $figure->addVideoFigure(
                $video->setPath('https://www.youtube.com/embed/daGKFdYt5Qw'),
                $video->setIdFigure($figure)
            );
            $figure->addPhotoFigure(
                $photo->setPath('home.jpg'),
                $photo->setIdFigure($figure),
                $photo->setPreview(1)
            );
            $figure->setDateCreation(new DateTime());
            $figure->setDateModification(new DateTime());

            $manager->persist($figure);
        }
        // add more products

        $manager->flush();
    }
}
