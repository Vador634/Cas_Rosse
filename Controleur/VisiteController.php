<?php
require_once __DIR__ . '/../DAO/VisiteDAO.php';
require_once 'Modele/Visite.php';

/**
 * Classe VisiteController
 * Gère la logique métier pour les visites
 */
class VisiteController {
    private $visiteDAO;

    // Constructeur
    public function __construct($pdo) {
        $this->visiteDAO = new VisiteDAO($pdo);
    }

    /**
     * Action: Afficher toutes les visites
     */
    public function listAll() {
        try {
            return $this->visiteDAO->getAll();
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Action: Afficher une visite spécifique
     */
    public function getById($idVisite) {
        try {
            $visite = $this->visiteDAO->getById($idVisite);
            if ($visite) {
                return $visite;
            } else {
                echo "Visite non trouvée";
                return null;
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Action: Créer une nouvelle visite
     * Paramètres:
     *   - $etat: état de la visite ('p' = planifiée, 'a' = en cours, 'r' = réalisée)
     *   - $dureeTotale: durée totale en minutes
     *   - $idStation: ID de la station concernée
     */
    public function create($etat, $dureeTotale, $idStation) {
        try {
            // Validation des données
            if (empty($etat) || empty($dureeTotale) || empty($idStation)) {
                echo "Tous les champs sont obligatoires";
                return false;
            }

            // Vérifier que l'état est valide (p, a ou r)
            if (!in_array($etat, ['p', 'a', 'r'])) {
                echo "L'état doit être 'p' (planifiée), 'a' (en cours) ou 'r' (réalisée)";
                return false;
            }

            // Vérifier que la durée est un nombre
            if (!is_numeric($dureeTotale)) {
                echo "La durée doit être un nombre";
                return false;
            }

            // Créer l'objet Visite
            $visite = new Visite(null, $etat, $dureeTotale, $idStation);
            
            // Sauvegarder
            if ($this->visiteDAO->create($visite)) {
                echo "Visite créée avec succès";
                return true;
            } else {
                echo "Erreur lors de la création";
                return false;
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Action: Modifier une visite existante
     */
    public function update($idVisite, $etat, $dureeTotale, $idStation) {
        try {
            // Validation
            if (empty($idVisite) || empty($etat) || empty($dureeTotale) || empty($idStation)) {
                echo "Tous les champs sont obligatoires";
                return false;
            }

            // Vérifier l'état
            if (!in_array($etat, ['p', 'a', 'r'])) {
                echo "L'état doit être 'p', 'a' ou 'r'";
                return false;
            }

            if (!is_numeric($dureeTotale)) {
                echo "La durée doit être un nombre";
                return false;
            }

            // Créer l'objet
            $visite = new Visite($idVisite, $etat, $dureeTotale, $idStation);
            
            // Mettre à jour
            if ($this->visiteDAO->update($visite)) {
                echo "Visite modifiée avec succès";
                return true;
            } else {
                echo "Erreur lors de la modification";
                return false;
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Action: Supprimer une visite
     */
    public function delete($idVisite) {
        try {
            if (empty($idVisite)) {
                echo "ID invalide";
                return false;
            }

            if ($this->visiteDAO->delete($idVisite)) {
                echo "Visite supprimée avec succès";
                return true;
            } else {
                echo "Erreur lors de la suppression";
                return false;
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Fonction utilitaire: obtenir le libellé de l'état
     */
    public function getEtatLibelle($etat) {
        $etats = [
            'p' => 'Planifiée',
            'a' => 'En cours',
            'r' => 'Réalisée'
        ];
        return isset($etats[$etat]) ? $etats[$etat] : 'Inconnu';
    }
}
?>
