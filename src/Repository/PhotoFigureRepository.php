<?php

namespace App\Repository;

use App\Entity\PhotoFigure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PhotoFigure>
 *
 * @method PhotoFigure|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhotoFigure|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhotoFigure[]    findAll()
 * @method PhotoFigure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoFigureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhotoFigure::class);
    }

    public function save(PhotoFigure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PhotoFigure $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
