<?php
require_once 'DAO/TypeBorneDAO.php';
require_once 'Modèle/TypeBorne.php';

/**
 * Classe TypeBorneController
 * Gère la logique métier pour les types de borne
 */
class TypeBorneController {
    private $typeBorneDAO;

    // Constructeur
    public function __construct($pdo) {
        $this->typeBorneDAO = new TypeBorneDAO($pdo);
    }

    /**
     * Action: Afficher tous les types de borne
     */
    public function listAll() {
        try {
            return $this->typeBorneDAO->getAll();
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return [];
        }
    }

    /**
     * Action: Afficher un type de borne par son code
     */
    public function getByCode($codeTypeBorne) {
        try {
            $typeBorne = $this->typeBorneDAO->getByCode($codeTypeBorne);
            if ($typeBorne) {
                return $typeBorne;
            } else {
                echo "Type de borne non trouvé";
                return null;
            }
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Action: Créer un nouveau type de borne
     * Paramètres: 
     *   - $code: code unique du type
     *   - $dureeRevision: durée (en jours)
     *   - $nbJours: nombre de jours entre révisions
     *   - $nbUnites: nombre d'unités entre révisions
     */
    public function create($code, $dureeRevision, $nbJours, $nbUnites) {
        try {
            // Validation des données
            if (empty($code) || empty($dureeRevision) || empty($nbJours) || empty($nbUnites)) {
                echo "Tous les champs sont obligatoires";
                return false;
            }

            // Vérifier que les nombres sont valides
            if (!is_numeric($dureeRevision) || !is_numeric($nbJours) || !is_numeric($nbUnites)) {
                echo "Les valeurs doivent être des nombres";
                return false;
            }

            // Créer l'objet TypeBorne
            $typeBorne = new TypeBorne($code, $dureeRevision, $nbJours, $nbUnites);
            
            // Sauvegarder
            if ($this->typeBorneDAO->create($typeBorne)) {
                echo "Type de borne créé avec succès";
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
     * Action: Modifier un type de borne
     */
    public function update($code, $dureeRevision, $nbJours, $nbUnites) {
        try {
            // Validation
            if (empty($code) || empty($dureeRevision) || empty($nbJours) || empty($nbUnites)) {
                echo "Tous les champs sont obligatoires";
                return false;
            }

            if (!is_numeric($dureeRevision) || !is_numeric($nbJours) || !is_numeric($nbUnites)) {
                echo "Les valeurs doivent être des nombres";
                return false;
            }

            // Créer l'objet
            $typeBorne = new TypeBorne($code, $dureeRevision, $nbJours, $nbUnites);
            
            // Mettre à jour
            if ($this->typeBorneDAO->update($typeBorne)) {
                echo "Type de borne modifié avec succès";
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
     * Action: Supprimer un type de borne
     */
    public function delete($codeTypeBorne) {
        try {
            if (empty($codeTypeBorne)) {
                echo "Code invalide";
                return false;
            }

            if ($this->typeBorneDAO->delete($codeTypeBorne)) {
                echo "Type de borne supprimé avec succès";
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
