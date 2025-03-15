<?php

namespace App\Service\Excel;

use App\Service\Excel\Handler\CompteAffaireHandler;
use App\Service\Excel\Handler\CompteEvenementHandler;
use App\Service\Excel\Handler\EnergieHandler;
use App\Service\Excel\Handler\FicheVenteHandler;
use App\Service\Excel\Handler\FicheTechniqueVoitureHandler;
use App\Service\Excel\Handler\LibelleCiviliteHandler;
use App\Service\Excel\Handler\OrigineEvenementHandler;
use App\Service\Excel\Handler\ProprioHandler;
use App\Service\Excel\Handler\TypeProspectHandler;
use App\Service\Excel\Handler\TypeVehiculeHandler;
use App\Service\Excel\Handler\TypeVenteHandler;
use App\Service\Excel\Handler\VoitureHandler;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Fabrique pour les gestionnaires d'entités
 */
class EntityHandlerFactory
{
    private EntityManagerInterface $entityManager;
    private array $handlers = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Récupère un gestionnaire d'entités
     * 
     * @param string $handlerClass La classe du gestionnaire
     * @return EntityHandlerInterface Le gestionnaire d'entités
     */
    public function getHandler(string $handlerClass): EntityHandlerInterface
    {
        if (!isset($this->handlers[$handlerClass])) {
            $handler = new $handlerClass();
            $handler->setEntityManager($this->entityManager);
            $this->handlers[$handlerClass] = $handler;
        }
        
        return $this->handlers[$handlerClass];
    }

    /**
     * Récupère le gestionnaire pour CompteAffaire
     * 
     * @return CompteAffaireHandler Le gestionnaire
     */
    public function getCompteAffaireHandler(): CompteAffaireHandler
    {
        return $this->getHandler(CompteAffaireHandler::class);
    }

    /**
     * Récupère le gestionnaire pour CompteEvenement
     * 
     * @return CompteEvenementHandler Le gestionnaire
     */
    public function getCompteEvenementHandler(): CompteEvenementHandler
    {
        return $this->getHandler(CompteEvenementHandler::class);
    }

    /**
     * Récupère le gestionnaire pour LibelleCivilite
     * 
     * @return LibelleCiviliteHandler Le gestionnaire
     */
    public function getLibelleCiviliteHandler(): LibelleCiviliteHandler
    {
        return $this->getHandler(LibelleCiviliteHandler::class);
    }

    /**
     * Récupère le gestionnaire pour TypeProspect
     * 
     * @return TypeProspectHandler Le gestionnaire
     */
    public function getTypeProspectHandler(): TypeProspectHandler
    {
        return $this->getHandler(TypeProspectHandler::class);
    }

    /**
     * Récupère le gestionnaire pour Energie
     * 
     * @return EnergieHandler Le gestionnaire
     */
    public function getEnergieHandler(): EnergieHandler
    {
        return $this->getHandler(EnergieHandler::class);
    }

    /**
     * Récupère le gestionnaire pour TypeVehicule
     * 
     * @return TypeVehiculeHandler Le gestionnaire
     */
    public function getTypeVehiculeHandler(): TypeVehiculeHandler
    {
        return $this->getHandler(TypeVehiculeHandler::class);
    }

    /**
     * Récupère le gestionnaire pour TypeVente
     * 
     * @return TypeVenteHandler Le gestionnaire
     */
    public function getTypeVenteHandler(): TypeVenteHandler
    {
        return $this->getHandler(TypeVenteHandler::class);
    }

    /**
     * Récupère le gestionnaire pour OrigineEvenement
     * 
     * @return OrigineEvenementHandler Le gestionnaire
     */
    public function getOrigineEvenementHandler(): OrigineEvenementHandler
    {
        return $this->getHandler(OrigineEvenementHandler::class);
    }

    /**
     * Récupère le gestionnaire pour Proprio
     * 
     * @return ProprioHandler Le gestionnaire
     */
    public function getProprioHandler(): ProprioHandler
    {
        return $this->getHandler(ProprioHandler::class);
    }

    /**
     * Récupère le gestionnaire pour Voiture
     * 
     * @return VoitureHandler Le gestionnaire
     */
    public function getVoitureHandler(): VoitureHandler
    {
        return $this->getHandler(VoitureHandler::class);
    }

    /**
     * Récupère le gestionnaire pour FicheVente
     * 
     * @return FicheVenteHandler Le gestionnaire
     */
    public function getFicheVenteHandler(): FicheVenteHandler
    {
        return $this->getHandler(FicheVenteHandler::class);
    }

    /**
     * Récupère le gestionnaire pour FicheTechniqueVoiture
     * 
     * @return FicheTechniqueVoitureHandler Le gestionnaire
     */
    public function getFicheTechniqueVoitureHandler(): FicheTechniqueVoitureHandler
    {
        return $this->getHandler(FicheTechniqueVoitureHandler::class);
    }
} 