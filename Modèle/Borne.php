<?php
/**
 * Classe Borne
 * Représente une borne avec ses caractéristiques et ses relations
 */
class Borne {
    private $idBorne;
    private $dateDerniereRevision;
    private $indiceCompteurUnites;
    private $codeTypeBorne;
    private $idStation;

    // Constructeur
    public function __construct($idBorne = null, $dateDerniereRevision = null, 
                                $indiceCompteurUnites = null, $codeTypeBorne = null, 
                                $idStation = null) {
        $this->idBorne = $idBorne;
        $this->dateDerniereRevision = $dateDerniereRevision;
        $this->indiceCompteurUnites = $indiceCompteurUnites;
        $this->codeTypeBorne = $codeTypeBorne;
        $this->idStation = $idStation;
    }

    // Getters
    public function getIdBorne() {
        return $this->idBorne;
    }

    public function getDateDerniereRevision() {
        return $this->dateDerniereRevision;
    }

    public function getIndiceCompteurUnites() {
        return $this->indiceCompteurUnites;
    }

    public function getCodeTypeBorne() {
        return $this->codeTypeBorne;
    }

    public function getIdStation() {
        return $this->idStation;
    }

    // Setters
    public function setIdBorne($idBorne) {
        $this->idBorne = $idBorne;
    }

    public function setDateDerniereRevision($dateDerniereRevision) {
        $this->dateDerniereRevision = $dateDerniereRevision;
    }

    public function setIndiceCompteurUnites($indiceCompteurUnites) {
        $this->indiceCompteurUnites = $indiceCompteurUnites;
    }

    public function setCodeTypeBorne($codeTypeBorne) {
        $this->codeTypeBorne = $codeTypeBorne;
    }

    public function setIdStation($idStation) {
        $this->idStation = $idStation;
    }
}
?>
