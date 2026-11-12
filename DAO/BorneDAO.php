<?php
require_once 'Modele/Borne.php';

/**
 * Classe BorneDAO
 * Gère l'accès aux données de la table Borne
 */
class BorneDAO {
    private $pdo;

    // Constructeur
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Récupère toutes les bornes
     */
    public function getAll() {
        $sql = "SELECT * FROM Borne";
        $stmt = $this->pdo->query($sql);
        $bornes = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $borne = new Borne(
                $row['idBorne'],
                $row['dateDerniereRevision'],
                $row['indiceCompteurUnites'],
                $row['codeTypeBorne'],
                $row['idStation']
            );
            $bornes[] = $borne;
        }
        
        return $bornes;
    }

    /**
     * Récupère une borne par son ID
     */
    public function getById($idBorne) {
        $sql = "SELECT * FROM Borne WHERE idBorne = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idBorne]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Borne(
                $row['idBorne'],
                $row['dateDerniereRevision'],
                $row['indiceCompteurUnites'],
                $row['codeTypeBorne'],
                $row['idStation']
            );
        }
        return null;
    }

    /**
     * Crée une nouvelle borne
     * Retourne l'ID inséré en cas de succès, false sinon.
     */
    public function create(Borne $borne) {
        $sql = "INSERT INTO Borne (dateDerniereRevision, indiceCompteurUnites, codeTypeBorne, idStation) 
                VALUES (:date, :indice, :code, :station)";
        $stmt = $this->pdo->prepare($sql);
        
        if ($stmt->execute([
            ':date' => $borne->getDateDerniereRevision(),
            ':indice' => $borne->getIndiceCompteurUnites(),
            ':code' => $borne->getCodeTypeBorne(),
            ':station' => $borne->getIdStation()
        ])) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }

    /**
     * Met à jour une borne
     */
    public function update(Borne $borne) {
        $sql = "UPDATE Borne SET dateDerniereRevision = :date, indiceCompteurUnites = :indice, 
                codeTypeBorne = :code, idStation = :station WHERE idBorne = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':id' => $borne->getIdBorne(),
            ':date' => $borne->getDateDerniereRevision(),
            ':indice' => $borne->getIndiceCompteurUnites(),
            ':code' => $borne->getCodeTypeBorne(),
            ':station' => $borne->getIdStation()
        ]);
    }

    /**
     * Supprime une borne
     */
    public function delete($idBorne) {
        $sql = "DELETE FROM Borne WHERE idBorne = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([':id' => $idBorne]);
    }
}
?>
