<?php

namespace App\Repository;

use App\Entity\VideoFigure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VideoFigure>
 *
 * @method VideoFigure|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoFigure|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoFigure[]    findAll()
 * @method VideoFigure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoFigureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoFigure::class);
    }

    public function save(VideoFigure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(VideoFigure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
