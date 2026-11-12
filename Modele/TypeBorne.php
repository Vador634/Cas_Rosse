<?php
/**
 * Classe TypeBorne
 * Représente un type de borne avec ses caractéristiques de révision
 */
class TypeBorne {
    private $codeTypeBorne;
    private $dureeRevision;
    private $nbJoursEntreRevisions;
    private $nbUnitesEntreRevisions;

    // Constructeur
    public function __construct($codeTypeBorne = null, $dureeRevision = null, 
                                $nbJoursEntreRevisions = null, $nbUnitesEntreRevisions = null) {
        $this->codeTypeBorne = $codeTypeBorne;
        $this->dureeRevision = $dureeRevision;
        $this->nbJoursEntreRevisions = $nbJoursEntreRevisions;
        $this->nbUnitesEntreRevisions = $nbUnitesEntreRevisions;
    }

    // Getters
    public function getCodeTypeBorne() {
        return $this->codeTypeBorne;
    }

    public function getDureeRevision() {
        return $this->dureeRevision;
    }

    public function getNbJoursEntreRevisions() {
        return $this->nbJoursEntreRevisions;
    }

    public function getNbUnitesEntreRevisions() {
        return $this->nbUnitesEntreRevisions;
    }

    // Setters
    public function setCodeTypeBorne($codeTypeBorne) {
        $this->codeTypeBorne = $codeTypeBorne;
    }

    public function setDureeRevision($dureeRevision) {
        $this->dureeRevision = $dureeRevision;
    }

    public function setNbJoursEntreRevisions($nbJoursEntreRevisions) {
        $this->nbJoursEntreRevisions = $nbJoursEntreRevisions;
    }

    public function setNbUnitesEntreRevisions($nbUnitesEntreRevisions) {
        $this->nbUnitesEntreRevisions = $nbUnitesEntreRevisions;
    }
}
?>
