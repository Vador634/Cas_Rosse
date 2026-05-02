<?php
require_once 'DAO/BorneDAO.php';
require_once 'DAO/TypeBorneDAO.php';
require_once 'Modèle/Borne.php';
require_once 'Controleur/MaintenanceController.php';

/**
 * Classe BorneController
 * Gère la logique métier pour les bornes
 */
class BorneController {
    private $borneDAO;
    private $typeBorneDAO;
    private $maintenanceController;

    // Constructeur
    public function __construct($pdo) {
        $this->borneDAO = new BorneDAO($pdo);
        $this->typeBorneDAO = new TypeBorneDAO($pdo);
        $this->maintenanceController = new MaintenanceController($pdo);
    }

    /**
     * Action: Afficher toutes les bornes
     */
    public function listAll() {
        try {
            return $this->borneDAO->getAll();
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Action: Afficher une borne spécifique
     */
    public function getById($idBorne) {
        try {
            $borne = $this->borneDAO->getById($idBorne);
            if ($borne) {
                return $borne;
            } else {
                echo "Borne non trouvée";
                return null;
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Action: Créer une nouvelle borne
     * Paramètres:
     *   - $dateDerniereRevision: date au format YYYY-MM-DD
     *   - $indiceCompteurUnites: nombre entier
     *   - $codeTypeBorne: code du type de borne (clé étrangère)
     *   - $idStation: ID de la station (clé étrangère)
     */
    public function create($dateDerniereRevision, $indiceCompteurUnites, $codeTypeBorne, $idStation) {
        try {
            // Validation des données
            if (empty($dateDerniereRevision) || empty($indiceCompteurUnites) || 
                empty($codeTypeBorne) || empty($idStation)) {
                echo "Tous les champs sont obligatoires";
                return false;
            }

            // Vérifier le format de la date
            if (!$this->isValidDate($dateDerniereRevision)) {
                echo "Format de date invalide (utilisez YYYY-MM-DD)";
                return false;
            }

            // Vérifier que l'indice est un nombre
            if (!is_numeric($indiceCompteurUnites)) {
                echo "L'indice doit être un nombre";
                return false;
            }

            // Créer l'objet Borne
            $borne = new Borne(null, $dateDerniereRevision, $indiceCompteurUnites, $codeTypeBorne, $idStation);
            
            // Sauvegarder
            $idBorne = $this->borneDAO->create($borne);
            if (!$idBorne) {
                echo "Erreur lors de la création";
                return false;
            }

            // Affecter automatiquement la borne au technicien le moins chargé
            $typeBorne = $this->typeBorneDAO->getByCode($codeTypeBorne);
            $dureeTotale = $typeBorne ? $typeBorne->getDureeRevision() : 1;

            if ($this->maintenanceController->assignBorneToLeastLoadedTechnician($idBorne, $dureeTotale)) {
                echo "Borne créée et affectée automatiquement au technicien le moins chargé";
            } else {
                echo "Borne créée, mais aucun technicien n'a pu être affecté automatiquement";
            }
            return true;
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Action: Modifier une borne existante
     */
    public function update($idBorne, $dateDerniereRevision, $indiceCompteurUnites, $codeTypeBorne, $idStation) {
        try {
            // Validation
            if (empty($idBorne) || empty($dateDerniereRevision) || empty($indiceCompteurUnites) || 
                empty($codeTypeBorne) || empty($idStation)) {
                echo "Tous les champs sont obligatoires";
                return false;
            }

            if (!$this->isValidDate($dateDerniereRevision)) {
                echo "Format de date invalide";
                return false;
            }

            if (!is_numeric($indiceCompteurUnites)) {
                echo "L'indice doit être un nombre";
                return false;
            }

            // Créer l'objet
            $borne = new Borne($idBorne, $dateDerniereRevision, $indiceCompteurUnites, $codeTypeBorne, $idStation);
            
            // Mettre à jour
            if ($this->borneDAO->update($borne)) {
                echo "Borne modifiée avec succès";
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
     * Action: Supprimer une borne
     */
    public function delete($idBorne) {
        try {
            if (empty($idBorne)) {
                echo "ID invalide";
                return false;
            }

            if ($this->borneDAO->delete($idBorne)) {
                echo "Borne supprimée avec succès";
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
     * Fonction utilitaire: vérifier si une date est valide au format YYYY-MM-DD
     */
    private function isValidDate($date) {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
?>
