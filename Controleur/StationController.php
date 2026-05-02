<?php
require_once 'DAO/StationDAO.php';
require_once 'Modèle/Station.php';

/**
 * Classe StationController
 * Gère la logique métier pour les stations
 * Exemple: récupérer les données, traiter les formulaires, etc.
 */
class StationController {
    private $stationDAO;

    // Constructeur - reçoit la connexion PDO
    public function __construct($pdo) {
        $this->stationDAO = new StationDAO($pdo);
    }

    /**
     * Action: Afficher toutes les stations
     * Retourne un tableau de stations
     */
    public function listAll() {
        try {
            return $this->stationDAO->getAll();
        } catch (Exception $e) {
            echo "Erreur lors de la récupération des stations: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Action: Afficher une station spécifique par son ID
     */
    public function getById($idStation) {
        try {
            $station = $this->stationDAO->getById($idStation);
            if ($station) {
                return $station;
            } else {
                echo "Station non trouvée";
                return null;
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Action: Créer une nouvelle station
     * Paramètres: $libelle = le libellé de l'emplacement
     */
    public function create($libelleEmplacement) {
        try {
            // Vérifier que le libellé n'est pas vide
            if (empty($libelleEmplacement)) {
                echo "Le libellé ne peut pas être vide";
                return false;
            }

            // Créer une nouvelle instance Station
            $station = new Station(null, $libelleEmplacement);
            
            // Sauvegarder en base de données
            if ($this->stationDAO->create($station)) {
                echo "Station créée avec succès";
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
     * Action: Modifier une station existante
     */
    public function update($idStation, $libelleEmplacement) {
        try {
            // Vérifier que les paramètres sont valides
            if (empty($idStation) || empty($libelleEmplacement)) {
                echo "Données invalides";
                return false;
            }

            // Créer l'objet Station avec les nouvelles données
            $station = new Station($idStation, $libelleEmplacement);
            
            // Mettre à jour en base de données
            if ($this->stationDAO->update($station)) {
                echo "Station modifiée avec succès";
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
     * Action: Supprimer une station
     */
    public function delete($idStation) {
        try {
            if (empty($idStation)) {
                echo "ID invalide";
                return false;
            }

            if ($this->stationDAO->delete($idStation)) {
                echo "Station supprimée avec succès";
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
}
?>
