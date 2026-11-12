<?php
require_once 'Modele/Technicien.php';

/**
 * Classe TechnicienDAO
 * Gère l'accès aux données de la table Technicien
 */
class TechnicienDAO {
    private $pdo;

    // Constructeur
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Récupère tous les techniciens
     */
    public function getAll() {
        $sql = "SELECT * FROM Technicien";
        $stmt = $this->pdo->query($sql);
        $techniciens = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $technicien = new Technicien(
                $row['matricule'],
                $row['nom'],
                $row['prenom']
            );
            $techniciens[] = $technicien;
        }
        
        return $techniciens;
    }

    /**
     * Récupère un technicien par son matricule
     */
    public function getByMatricule($matricule) {
        $sql = "SELECT * FROM Technicien WHERE matricule = :matricule";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':matricule' => $matricule]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Technicien(
                $row['matricule'],
                $row['nom'],
                $row['prenom']
            );
        }
        return null;
    }

    /**
     * Crée un nouveau technicien
     */
    public function create(Technicien $technicien) {
        $sql = "INSERT INTO Technicien (matricule, nom, prenom) 
                VALUES (:matricule, :nom, :prenom)";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':matricule' => $technicien->getMatricule(),
            ':nom' => $technicien->getNom(),
            ':prenom' => $technicien->getPrenom()
        ]);
    }

    /**
     * Met à jour un technicien
     */
    public function update(Technicien $technicien) {
        $sql = "UPDATE Technicien SET nom = :nom, prenom = :prenom 
                WHERE matricule = :matricule";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':matricule' => $technicien->getMatricule(),
            ':nom' => $technicien->getNom(),
            ':prenom' => $technicien->getPrenom()
        ]);
    }

    /**
     * Supprime un technicien
     */
    public function delete($matricule) {
        $sql = "DELETE FROM Technicien WHERE matricule = :matricule";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([':matricule' => $matricule]);
    }

    /**
     * Récupère les techniciens triés par disponibilité (moins d'occupations d'abord)
     * Affiche d'abord le technicien qui a le moins de visites assignées
     */
    public function getTechniciensByDisponibilite() {
        $sql = "SELECT t.*, COUNT(tv.idVisite) as nbVisites, COALESCE(SUM(v.dureeTotale), 0) as totalHeures
                FROM Technicien t
                LEFT JOIN Technicien_Visite tv ON t.matricule = tv.matricule
                LEFT JOIN Visite v ON tv.idVisite = v.idVisite
                GROUP BY t.matricule
                ORDER BY totalHeures ASC, t.nom ASC";
        
        $stmt = $this->pdo->query($sql);
        $result = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $technicien = new Technicien(
                $row['matricule'],
                $row['nom'],
                $row['prenom']
            );
            $result[] = [
                'technicien' => $technicien,
                'nbVisites' => $row['nbVisites'],
                'totalHeures' => $row['totalHeures']
            ];
        }
        
        return $result;
    }

    /**
     * Récupère le technicien le moins chargé
     */
    public function getTechnicienLeMoinsCharge() {
        $sql = "SELECT t.*, COALESCE(SUM(v.dureeTotale), 0) as totalHeures, COUNT(tv.idVisite) as nbVisites
                FROM Technicien t
                LEFT JOIN Technicien_Visite tv ON t.matricule = tv.matricule
                LEFT JOIN Visite v ON tv.idVisite = v.idVisite
                GROUP BY t.matricule
                ORDER BY totalHeures ASC, nbVisites ASC, t.nom ASC
                LIMIT 1";

        $stmt = $this->pdo->query($sql);
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Technicien(
                $row['matricule'], 
                $row['nom'], 
                $row['prenom']
            );
        }

        return null;
    }

    /**
     * Attribue une borne à un technicien
     * Crée une visite planifiée, puis lie le technicien et la borne à cette visite.
     * Les trois insertions sont faites dans une transaction : en cas d'erreur,
     * aucune visite "orpheline" ne reste en base.
     */
    public function attribuerBorne($matricule, $idBorne, $dureeTotale) {
        // Récupérer l'idStation de la borne
        $stmt = $this->pdo->prepare("SELECT idStation FROM Borne WHERE idBorne = :idBorne");
        $stmt->execute([':idBorne' => $idBorne]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return false;

        $transactionLocale = !$this->pdo->inTransaction();
        if ($transactionLocale) $this->pdo->beginTransaction();

        try {
            // Créer une visite planifiée avec la durée
            $stmt = $this->pdo->prepare("INSERT INTO Visite (etat, dureeTotale, idStation) VALUES ('p', :duree, :idStation)");
            $stmt->execute([':duree' => $dureeTotale, ':idStation' => $row['idStation']]);
            $idVisite = $this->pdo->lastInsertId();

            // Lier la visite au technicien
            $stmt = $this->pdo->prepare("INSERT INTO Technicien_Visite (matricule, idVisite) VALUES (:matricule, :idVisite)");
            $stmt->execute([':matricule' => $matricule, ':idVisite' => $idVisite]);

            // Lier la visite à la borne
            $stmt = $this->pdo->prepare("INSERT INTO Visite_Borne (idVisite, idBorne) VALUES (:idVisite, :idBorne)");
            $stmt->execute([':idVisite' => $idVisite, ':idBorne' => $idBorne]);

            if ($transactionLocale) $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            if ($transactionLocale) $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Planning des visites : une ligne par visite avec technicien, borne et station.
     */
    public function getPlanning() {
        $sql = "SELECT v.idVisite, v.etat, v.dureeTotale, s.libelleEmplacement,
                       vb.idBorne, b.codeTypeBorne, t.matricule, t.nom, t.prenom
                FROM Visite v
                JOIN Station s ON s.idStation = v.idStation
                LEFT JOIN Visite_Borne vb ON vb.idVisite = v.idVisite
                LEFT JOIN Borne b ON b.idBorne = vb.idBorne
                LEFT JOIN Technicien_Visite tv ON tv.idVisite = v.idVisite
                LEFT JOIN Technicien t ON t.matricule = tv.matricule
                ORDER BY v.idVisite DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
