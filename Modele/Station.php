<?php
/**
 * Classe Station
 * Représente une station avec son emplacement
 */
class Station {
    private $idStation;
    private $libelleEmplacement;

    // Constructeur
    public function __construct($idStation = null, $libelleEmplacement = null) {
        $this->idStation = $idStation;
        $this->libelleEmplacement = $libelleEmplacement;
    }

    // Getters
    public function getIdStation() {
        return $this->idStation;
    }

    public function getLibelleEmplacement() {
        return $this->libelleEmplacement;
    }

    // Setters
    public function setIdStation($idStation) {
        $this->idStation = $idStation;
    }

    public function setLibelleEmplacement($libelleEmplacement) {
        $this->libelleEmplacement = $libelleEmplacement;
    }
}
?>
