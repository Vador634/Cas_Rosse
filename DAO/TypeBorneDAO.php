<?php
require_once 'Modèle/TypeBorne.php';

/**
 * Classe TypeBorneDAO
 * Gère l'accès aux données de la table TypeBorne
 */
class TypeBorneDAO {
    private $pdo;

    // Constructeur
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Récupère tous les types de borne
     */
    public function getAll() {
        $sql = "SELECT * FROM TypeBorne";
        $stmt = $this->pdo->query($sql);
        $typesBorne = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $typeBorne = new TypeBorne(
                $row['codeTypeBorne'],
                $row['dureeRevision'],
                $row['nbJoursEntreRevisions'],
                $row['nbUnitesEntreRevisions']
            );
            $typesBorne[] = $typeBorne;
        }
        
        return $typesBorne;
    }

    /**
     * Récupère un type de borne par son code
     */
    public function getByCode($codeTypeBorne) {
        $sql = "SELECT * FROM TypeBorne WHERE codeTypeBorne = :code";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':code' => $codeTypeBorne]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new TypeBorne(
                $row['codeTypeBorne'],
                $row['dureeRevision'],
                $row['nbJoursEntreRevisions'],
                $row['nbUnitesEntreRevisions']
            );
        }
        return null;
    }

    /**
     * Crée un nouveau type de borne
     */
    public function create(TypeBorne $typeBorne) {
        $sql = "INSERT INTO TypeBorne (codeTypeBorne, dureeRevision, nbJoursEntreRevisions, nbUnitesEntreRevisions) 
                VALUES (:code, :duree, :jours, :unites)";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':code' => $typeBorne->getCodeTypeBorne(),
            ':duree' => $typeBorne->getDureeRevision(),
            ':jours' => $typeBorne->getNbJoursEntreRevisions(),
            ':unites' => $typeBorne->getNbUnitesEntreRevisions()
        ]);
    }

    /**
     * Met à jour un type de borne
     */
    public function update(TypeBorne $typeBorne) {
        $sql = "UPDATE TypeBorne SET dureeRevision = :duree, nbJoursEntreRevisions = :jours, 
                nbUnitesEntreRevisions = :unites WHERE codeTypeBorne = :code";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':code' => $typeBorne->getCodeTypeBorne(),
            ':duree' => $typeBorne->getDureeRevision(),
            ':jours' => $typeBorne->getNbJoursEntreRevisions(),
            ':unites' => $typeBorne->getNbUnitesEntreRevisions()
        ]);
    }

    /**
     * Supprime un type de borne
     */
    public function delete($codeTypeBorne) {
        $sql = "DELETE FROM TypeBorne WHERE codeTypeBorne = :code";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([':code' => $codeTypeBorne]);
    }
}
?>
