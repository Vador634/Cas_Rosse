<?php
/**
 * Classe Technicien
 * Représente un technicien avec ses informations
 */
class Technicien {
    private $matricule;
    private $nom;
    private $prenom;

    // Constructeur
    public function __construct($matricule = null, $nom = null, $prenom = null) {
        $this->matricule = $matricule;
        $this->nom = $nom;
        $this->prenom = $prenom;
    }

    // Getters
    public function getMatricule() {
        return $this->matricule;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    // Setters
    public function setMatricule($matricule) {
        $this->matricule = $matricule;
    }

    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    // Méthode utile pour afficher le nom complet
    public function getNomComplet() {
        return $this->prenom . " " . $this->nom;
    }
}
?>
