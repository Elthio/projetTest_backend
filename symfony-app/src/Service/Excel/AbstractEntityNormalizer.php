<?php

namespace App\Service\Excel;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Classe abstraite pour la normalisation des entités de référence
 */
abstract class AbstractEntityNormalizer
{
    protected EntityManagerInterface $entityManager;
    protected ?SymfonyStyle $io = null;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Définit l'objet SymfonyStyle pour l'affichage des messages
     */
    public function setOutput(SymfonyStyle $io): void
    {
        $this->io = $io;
    }

    /**
     * Normalise une valeur selon les règles définies
     */
    abstract public function normaliserValeur(string $valeur): string;

    /**
     * Récupère le nom de la propriété à normaliser
     */
    abstract public function getProprieteNom(): string;

    /**
     * Récupère le nom de la méthode getter pour la propriété
     */
    public function getMethodeGetter(): string
    {
        return 'get' . ucfirst($this->getProprieteNom());
    }

    /**
     * Récupère le nom de la méthode setter pour la propriété
     */
    public function getMethodeSetter(): string
    {
        return 'set' . ucfirst($this->getProprieteNom());
    }

    /**
     * Récupère la classe de l'entité à normaliser
     */
    abstract public function getEntityClass(): string;

    /**
     * Récupère les classes des entités qui ont une relation avec l'entité à normaliser
     * 
     * @return array [
     *   'EntityClass' => [
     *     'propriete' => 'nomPropriete',
     *     'getter' => 'getMethode',
     *     'setter' => 'setMethode'
     *   ]
     * ]
     */
    abstract public function getRelatedEntities(): array;

    /**
     * Nettoie les données de l'entité
     */
    public function nettoyer(): array
    {
        $stats = [
            'total' => 0,
            'normalises' => 0,
            'supprimes' => 0,
            'relations_mises_a_jour' => 0
        ];

        // 1. Récupérer toutes les entités
        $entites = $this->entityManager->getRepository($this->getEntityClass())->findAll();
        $stats['total'] = count($entites);
        
        if ($this->io) {
            $this->io->info(sprintf('Nombre total d\'entités trouvées : %d', $stats['total']));
        }

        // 2. Créer un tableau pour stocker les entités normalisées
        $entitesNormalisees = [];
        $mapEntites = [];

        // 3. Parcourir toutes les entités et les normaliser
        foreach ($entites as $entite) {
            $getter = $this->getMethodeGetter();
            $setter = $this->getMethodeSetter();
            
            $valeurOriginale = $entite->$getter();
            $valeurNormalisee = $this->normaliserValeur($valeurOriginale);
            
            if (!isset($entitesNormalisees[$valeurNormalisee])) {
                $entitesNormalisees[$valeurNormalisee] = $entite;
                
                if ($this->io) {
                    $this->io->text(sprintf('Entité conservée : %s => %s', $valeurOriginale, $valeurNormalisee));
                }
                
                // Mettre à jour le nom si nécessaire
                if ($valeurOriginale !== $valeurNormalisee) {
                    $entite->$setter($valeurNormalisee);
                    $this->entityManager->persist($entite);
                    $stats['normalises']++;
                    
                    if ($this->io) {
                        $this->io->text(sprintf('  - Valeur mise à jour : %s => %s', $valeurOriginale, $valeurNormalisee));
                    }
                }
            }
            
            // Stocker la correspondance entre l'entité originale et l'entité normalisée
            $mapEntites[$this->getEntityId($entite)] = $entitesNormalisees[$valeurNormalisee];
        }

        if ($this->io) {
            $this->io->info(sprintf('Nombre d\'entités après normalisation : %d', count($entitesNormalisees)));
        }

        // 4. Mettre à jour les relations
        $relatedEntities = $this->getRelatedEntities();
        
        foreach ($relatedEntities as $entityClass => $relation) {
            $entities = $this->entityManager->getRepository($entityClass)->findAll();
            $compteurMisesAJour = 0;
            
            foreach ($entities as $entity) {
                $getter = $relation['getter'];
                $setter = $relation['setter'];
                
                $entiteActuelle = $entity->$getter();
                
                if ($entiteActuelle && isset($mapEntites[$this->getEntityId($entiteActuelle)])) {
                    $entiteNormalisee = $mapEntites[$this->getEntityId($entiteActuelle)];
                    
                    if ($this->getEntityId($entiteActuelle) !== $this->getEntityId($entiteNormalisee)) {
                        $entity->$setter($entiteNormalisee);
                        $this->entityManager->persist($entity);
                        $compteurMisesAJour++;
                    }
                }
            }
            
            $stats['relations_mises_a_jour'] += $compteurMisesAJour;
            
            if ($this->io) {
                $this->io->info(sprintf('Nombre de %s mis à jour : %d', (new \ReflectionClass($entityClass))->getShortName(), $compteurMisesAJour));
            }
        }

        // 5. Supprimer les entités redondantes
        $compteurSuppression = 0;
        
        foreach ($entites as $entite) {
            $getter = $this->getMethodeGetter();
            $valeurNormalisee = $this->normaliserValeur($entite->$getter());
            $entiteNormalisee = $entitesNormalisees[$valeurNormalisee];
            
            if ($this->getEntityId($entite) !== $this->getEntityId($entiteNormalisee)) {
                // Vérifier qu'il n'y a plus de relations avec cette entité
                $peutSupprimer = true;
                
                foreach ($relatedEntities as $entityClass => $relation) {
                    $entitesLiees = $this->entityManager->getRepository($entityClass)
                        ->findBy([$relation['propriete'] => $entite]);
                    
                    if (count($entitesLiees) > 0) {
                        $peutSupprimer = false;
                        
                        if ($this->io) {
                            $this->io->warning(sprintf(
                                'Impossible de supprimer l\'entité %s (ID: %d) car elle est encore utilisée par %d %s',
                                $entite->$getter(),
                                $this->getEntityId($entite),
                                count($entitesLiees),
                                (new \ReflectionClass($entityClass))->getShortName()
                            ));
                        }
                        
                        break;
                    }
                }
                
                if ($peutSupprimer) {
                    $this->entityManager->remove($entite);
                    $compteurSuppression++;
                    
                    if ($this->io) {
                        $this->io->text(sprintf(
                            'Suppression de l\'entité redondante : %s (ID: %d)',
                            $entite->$getter(),
                            $this->getEntityId($entite)
                        ));
                    }
                }
            }
        }
        
        $stats['supprimes'] = $compteurSuppression;
        
        if ($this->io) {
            $this->io->info(sprintf('Nombre d\'entités supprimées : %d', $compteurSuppression));
        }

        // 6. Enregistrer les modifications
        $this->entityManager->flush();

        return $stats;
    }

    /**
     * Récupère l'identifiant d'une entité
     */
    protected function getEntityId($entity)
    {
        $class = new \ReflectionClass($entity);
        
        foreach ($class->getProperties() as $property) {
            $docComment = $property->getDocComment();
            
            if ($docComment && strpos($docComment, '@ORM\Id') !== false) {
                $property->setAccessible(true);
                return $property->getValue($entity);
            }
        }
        
        // Méthode alternative si la réflexion ne fonctionne pas
        if (method_exists($entity, 'getId')) {
            return $entity->getId();
        }
        
        // Essayer de deviner le nom de la méthode getter pour l'ID
        $className = (new \ReflectionClass($entity))->getShortName();
        $idGetterMethod = 'getId' . $className;
        
        if (method_exists($entity, $idGetterMethod)) {
            return $entity->$idGetterMethod();
        }
        
        throw new \Exception('Impossible de déterminer l\'identifiant de l\'entité');
    }
} 