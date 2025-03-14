<?php

namespace App\Repository;

use App\Entity\CompteEvenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompteEvenement>
 *
 * @method CompteEvenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompteEvenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompteEvenement[]    findAll()
 * @method CompteEvenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteEvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompteEvenement::class);
    }
}
