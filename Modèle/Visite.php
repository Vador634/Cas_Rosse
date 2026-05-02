<?php
/**
 * Classe Visite
 * Représente une visite avec son état et sa durée
 */
class Visite {
    private $idVisite;
    private $etat; // 'p', 'a', 'r'
    private $dureeTotale;
    private $idStation;

    // Constructeur
    public function __construct($idVisite = null, $etat = null, 
                                $dureeTotale = null, $idStation = null) {
        $this->idVisite = $idVisite;
        $this->etat = $etat;
        $this->dureeTotale = $dureeTotale;
        $this->idStation = $idStation;
    }

    // Getters
    public function getIdVisite() {
        return $this->idVisite;
    }

    public function getEtat() {
        return $this->etat;
    }

    public function getDureeTotale() {
        return $this->dureeTotale;
    }

    public function getIdStation() {
        return $this->idStation;
    }

    // Setters
    public function setIdVisite($idVisite) {
        $this->idVisite = $idVisite;
    }

    public function setEtat($etat) {
        // Validation: l'état doit être p, a ou r
        if (in_array($etat, ['p', 'a', 'r'])) {
            $this->etat = $etat;
        }
    }

    public function setDureeTotale($dureeTotale) {
        $this->dureeTotale = $dureeTotale;
    }

    public function setIdStation($idStation) {
        $this->idStation = $idStation;
    }
}
?>
