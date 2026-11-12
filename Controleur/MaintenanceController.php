<?php
require_once __DIR__ . '/../DAO/TechnicienDAO.php';

/**
 * Classe MaintenanceController
 * Gère l'affectation automatique des bornes aux techniciens
 */
class MaintenanceController {
    private $technicienDAO;

    public function __construct($pdo) {
        $this->technicienDAO = new TechnicienDAO($pdo);
    }

    /**
     * Obtient le technicien le moins chargé et attribue la borne
     */
    public function assignBorneToLeastLoadedTechnician($idBorne, $dureeTotale) {
        if (empty($idBorne) || !is_numeric($dureeTotale)) {
            return false;
        }

        $technicien = $this->technicienDAO->getTechnicienLeMoinsCharge();
        if (!$technicien) {
            return false;
        }

        return $this->technicienDAO->attribuerBorne($technicien->getMatricule(), $idBorne, $dureeTotale);
    }
}
?>