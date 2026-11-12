<?php
require_once __DIR__ . '/../DAO/TypeBorneDAO.php';
require_once 'Modele/TypeBorne.php';

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
     *   - $dureeRevision: durée d'une révision (en heures)
     *   - $nbJours: nombre de jours entre révisions
     *   - $nbUnites: nombre d'unités entre révisions
     */
    public function create($code, $dureeRevision, $nbJours, $nbUnites) {
        try {
            // Validation des données
            if (trim((string)$code) === '' || trim((string)$dureeRevision) === ''
                || trim((string)$nbJours) === '' || trim((string)$nbUnites) === '') {
                echo "Tous les champs sont obligatoires";
                return false;
            }

            if (strlen($code) > 10) {
                echo "Le code du type de borne ne doit pas dépasser 10 caractères";
                return false;
            }

            // Vérifier que les nombres sont valides
            if (!ctype_digit((string)$dureeRevision) || !ctype_digit((string)$nbJours) || !ctype_digit((string)$nbUnites)
                || (int)$dureeRevision < 1 || (int)$nbJours < 1 || (int)$nbUnites < 1) {
                echo "La durée et les intervalles de révision doivent être des entiers positifs";
                return false;
            }

            // Créer l'objet TypeBorne
            $typeBorne = new TypeBorne($code, $dureeRevision, $nbJours, $nbUnites);

            // Sauvegarder
            if ($this->typeBorneDAO->getByCode($code)) {
                echo "Le type de borne « $code » existe déjà";
                return false;
            }

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
            if (trim((string)$code) === '' || trim((string)$dureeRevision) === ''
                || trim((string)$nbJours) === '' || trim((string)$nbUnites) === '') {
                echo "Tous les champs sont obligatoires";
                return false;
            }

            if (strlen($code) > 10) {
                echo "Le code du type de borne ne doit pas dépasser 10 caractères";
                return false;
            }

            if (!ctype_digit((string)$dureeRevision) || !ctype_digit((string)$nbJours) || !ctype_digit((string)$nbUnites)
                || (int)$dureeRevision < 1 || (int)$nbJours < 1 || (int)$nbUnites < 1) {
                echo "La durée et les intervalles de révision doivent être des entiers positifs";
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
