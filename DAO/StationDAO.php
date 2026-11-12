<?php
require_once 'Modele/Station.php';

/**
 * Classe StationDAO
 * Gère l'accès aux données de la table Station
 */
class StationDAO {
    private $pdo;

    // Constructeur qui reçoit la connexion PDO
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Récupère toutes les stations
     */
    public function getAll() {
        $sql = "SELECT * FROM Station";
        $stmt = $this->pdo->query($sql);
        $stations = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $station = new Station($row['idStation'], $row['libelleEmplacement']);
            $stations[] = $station;
        }
        
        return $stations;
    }

    /**
     * Récupère une station par son ID
     */
    public function getById($idStation) {
        $sql = "SELECT * FROM Station WHERE idStation = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idStation]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Station($row['idStation'], $row['libelleEmplacement']);
        }
        return null;
    }

    /**
     * Crée une nouvelle station
     */
    public function create(Station $station) {
        $sql = "INSERT INTO Station (libelleEmplacement) VALUES (:libelle)";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':libelle' => $station->getLibelleEmplacement()
        ]);
    }

    /**
     * Met à jour une station
     */
    public function update(Station $station) {
        $sql = "UPDATE Station SET libelleEmplacement = :libelle WHERE idStation = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':id' => $station->getIdStation(),
            ':libelle' => $station->getLibelleEmplacement()
        ]);
    }

    /**
     * Supprime une station
     */
    public function delete($idStation) {
        $sql = "DELETE FROM Station WHERE idStation = :id";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([':id' => $idStation]);
    }
}
?>
