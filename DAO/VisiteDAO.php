<?php
require_once 'Modele/Visite.php';

/**
 * Classe VisiteDAO
 * Gère l'accès aux données de la table Visite
 */
class VisiteDAO {
    private $pdo;

    // Constructeur
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Récupère toutes les visites
     */
    public function getAll() {
        $sql = "SELECT * FROM Visite";
        $stmt = $this->pdo->query($sql);
        $visites = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $visite = new Visite(
                $row['idVisite'],
                $row['etat'],
                $row['dureeTotale'],
                $row['idStation']
            );
            $visites[] = $visite;
        }
        
        return $visites;
    }

    /**
     * Récupère une visite par son ID
     */
    public function getById($idVisite) {
        $sql = "SELECT * FROM Visite WHERE idVisite = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idVisite]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Visite(
                $row['idVisite'],
                $row['etat'],
                $row['dureeTotale'],
                $row['idStation']
            );
        }
        return null;
    }

    /**
     * Crée une nouvelle visite
     */
    public function create(Visite $visite) {
        $sql = "INSERT INTO Visite (etat, dureeTotale, idStation) 
                VALUES (:etat, :duree, :station)";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':etat' => $visite->getEtat(),
            ':duree' => $visite->getDureeTotale(),
            ':station' => $visite->getIdStation()
        ]);
    }

    /**
     * Met à jour une visite
     */
    public function update(Visite $visite) {
        $sql = "UPDATE Visite SET etat = :etat, dureeTotale = :duree, idStation = :station 
                WHERE idVisite = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':id' => $visite->getIdVisite(),
            ':etat' => $visite->getEtat(),
            ':duree' => $visite->getDureeTotale(),
            ':station' => $visite->getIdStation()
        ]);
    }

    /**
     * Supprime une visite
     */
    public function delete($idVisite) {
        $sql = "DELETE FROM Visite WHERE idVisite = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([':id' => $idVisite]);
    }
}
?>
