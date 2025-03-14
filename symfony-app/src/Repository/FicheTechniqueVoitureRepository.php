<?php

namespace App\Repository;

use App\Entity\FicheTechniqueVoiture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FicheTechniqueVoiture>
 *
 * @method FicheTechniqueVoiture|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheTechniqueVoiture|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheTechniqueVoiture[]    findAll()
 * @method FicheTechniqueVoiture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheTechniqueVoitureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheTechniqueVoiture::class);
    }
}
