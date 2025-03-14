<?php

namespace App\Repository;

use App\Entity\Proprio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Proprio>
 *
 * @method Proprio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proprio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proprio[]    findAll()
 * @method Proprio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProprioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proprio::class);
    }
}
