<?php

namespace App\Service\Excel\Handler;

use App\Entity\LibelleCivilite;
use App\Entity\Proprio;
use App\Entity\TypeProspect;
use App\Service\Excel\AbstractEntityHandler;

/**
 * Gestionnaire pour l'entité Proprio
 */
class ProprioHandler extends AbstractEntityHandler
{
    /**
     * Récupère ou crée un Proprio à partir des données
     * 
     * @param array $data Les données pour créer ou récupérer l'entité
     * @return Proprio|null L'entité créée ou récupérée
     */
    public function getOrCreateEntity(array $data): ?Proprio
    {
        $nom = $data['Nom'] ?? null;
        $prenom = $data['Prénom'] ?? null;
        $email = $data['Email'] ?? '';
        
        if (empty($nom) || empty($prenom)) {
            return null;
        }
        
        $proprio = $this->entityManager->getRepository(Proprio::class)
            ->findOneBy([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email
            ]);
        
        if (!$proprio) {
            $proprio = new Proprio();
            $proprio->setNom($nom);
            $proprio->setPrenom($prenom);
            $proprio->setEmail($email);
            $proprio->setNumEtNomVoie($data['N° et Nom de la voie'] ?? '');
            $proprio->setComplementAdresse($data['Complément adresse 1'] ?? null);
            $proprio->setCodePostal($data['Code postal'] ?? '');
            $proprio->setVille($data['Ville'] ?? '');
            $proprio->setTelephoneDomicile($data['Téléphone domicile'] ?? null);
            $proprio->setTelephonePortable($data['Téléphone portable'] ?? null);
            $proprio->setTelephoneJob($data['Téléphone job'] ?? null);
            
            $this->entityManager->persist($proprio);
        }
        
        return $proprio;
    }
    
    /**
     * Associe les entités liées au Proprio
     * 
     * @param Proprio $proprio L'entité Proprio
     * @param LibelleCivilite|null $libelleCivilite L'entité LibelleCivilite
     * @param TypeProspect|null $typeProspect L'entité TypeProspect
     */
    public function associateRelatedEntities(Proprio $proprio, ?LibelleCivilite $libelleCivilite, ?TypeProspect $typeProspect): void
    {
        $proprio->setLibelleCivilite($libelleCivilite);
        $proprio->setTypeProspect($typeProspect);
    }
} 