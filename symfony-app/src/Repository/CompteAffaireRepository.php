<?php

namespace App\Repository;

use App\Entity\CompteAffaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompteAffaire>
 *
 * @method CompteAffaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompteAffaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompteAffaire[]    findAll()
 * @method CompteAffaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteAffaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompteAffaire::class);
    }
}
