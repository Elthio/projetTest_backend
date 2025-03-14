<?php

namespace App\Repository;

use App\Entity\TypeVentes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeVentes>
 *
 * @method TypeVentes|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeVentes|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeVentes[]    findAll()
 * @method TypeVentes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeVentesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeVentes::class);
    }
}
