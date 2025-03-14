<?php

namespace App\Repository;

use App\Entity\FicheVente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FicheVente>
 *
 * @method FicheVente|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheVente|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheVente[]    findAll()
 * @method FicheVente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheVenteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheVente::class);
    }
}
