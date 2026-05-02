<?php
require_once 'DAO/TechnicienDAO.php';
require_once 'Modèle/Technicien.php';

/**
 * Classe TechnicienController
 * Gère la logique métier pour les techniciens
 */
class TechnicienController {
    private $technicienDAO;

    // Constructeur
    public function __construct($pdo) {
        $this->technicienDAO = new TechnicienDAO($pdo);
    }

    /**
     * Action: Afficher tous les techniciens
     */
    public function listAll() {
        try {
            return $this->technicienDAO->getAll();
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Action: Afficher un technicien spécifique
     */
    public function getByMatricule($matricule) {
        try {
            $technicien = $this->technicienDAO->getByMatricule($matricule);
            if ($technicien) {
                return $technicien;
            } else {
                echo "Technicien non trouvé";
                return null;
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Action: Créer un nouveau technicien
     * Paramètres:
     *   - $matricule: numéro unique du technicien (entier)
     *   - $nom: nom de famille
     *   - $prenom: prénom
     */
    public function create($matricule, $nom, $prenom) {
        try {
            // Validation des données
            if (empty($matricule) || empty($nom) || empty($prenom)) {
                echo "Tous les champs sont obligatoires";
                return false;
            }

            // Vérifier que le matricule est un nombre
            if (!is_numeric($matricule)) {
                echo "Le matricule doit être un nombre";
                return false;
            }

            // Vérifier la longueur des noms (minimum 2 caractères)
            if (strlen($nom) < 2 || strlen($prenom) < 2) {
                echo "Le nom et le prénom doivent contenir au moins 2 caractères";
                return false;
            }

            // Créer l'objet Technicien
            $technicien = new Technicien($matricule, $nom, $prenom);
            
            // Sauvegarder
            if ($this->technicienDAO->create($technicien)) {
                echo "Technicien créé avec succès";
                return true;
            } else {
                echo "Erreur lors de la création (matricule peut être en doublon)";
                return false;
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Action: Modifier un technicien existant
     */
    public function update($matricule, $nom, $prenom) {
        try {
            // Validation
            if (empty($matricule) || empty($nom) || empty($prenom)) {
                echo "Tous les champs sont obligatoires";
                return false;
            }

            if (!is_numeric($matricule)) {
                echo "Le matricule doit être un nombre";
                return false;
            }

            if (strlen($nom) < 2 || strlen($prenom) < 2) {
                echo "Le nom et le prénom doivent contenir au moins 2 caractères";
                return false;
            }

            // Créer l'objet
            $technicien = new Technicien($matricule, $nom, $prenom);
            
            // Mettre à jour
            if ($this->technicienDAO->update($technicien)) {
                echo "Technicien modifié avec succès";
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
     * Action: Supprimer un technicien
     */
    public function delete($matricule) {
        try {
            if (empty($matricule)) {
                echo "Matricule invalide";
                return false;
            }

            if (!is_numeric($matricule)) {
                echo "Le matricule doit être un nombre";
                return false;
            }

            if ($this->technicienDAO->delete($matricule)) {
                echo "Technicien supprimé avec succès";
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
     * Fonction utilitaire: compter le nombre total de techniciens
     */
    public function countAll() {
        try {
            $techniciens = $this->technicienDAO->getAll();
            return count($techniciens);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Action: Obtenir les techniciens triés par disponibilité (moins d'occupations d'abord)
     * Utile pour assigner une borne à un technicien libre
     * Retourne les techniciens du moins occupé au plus occupé
     */
    public function getTechniciensByDisponibilite() {
        try {
            return $this->technicienDAO->getTechniciensByDisponibilite();
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Action: Attribuer une borne à un technicien
     */
    public function attribuerBorne($matricule, $idBorne, $dureeTotale) {
        try {
            if (empty($matricule) || empty($idBorne) || !is_numeric($dureeTotale)) {
                echo "Le matricule, l'ID de la borne et la durée sont obligatoires";
                return false;
            }

            if ($this->technicienDAO->attribuerBorne($matricule, $idBorne, $dureeTotale)) {
                echo "Borne attribuée avec succès";
                return true;
            } else {
                echo "Erreur lors de l'attribution";
                return false;
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
}
?>
