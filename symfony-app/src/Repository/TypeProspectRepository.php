<?php

namespace App\Repository;

use App\Entity\TypeProspect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeProspect>
 *
 * @method TypeProspect|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeProspect|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeProspect[]    findAll()
 * @method TypeProspect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeProspectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeProspect::class);
    }
} 