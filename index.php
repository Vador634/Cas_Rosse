<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'Config/config.php';
require_once 'Controleur/BorneController.php';
require_once 'Controleur/TechnicienController.php';
require_once 'Controleur/StationController.php';
require_once 'Controleur/TypeBorneController.php';

$pdo = connexionPDO();
$borneController = new BorneController($pdo);
$technicienController = new TechnicienController($pdo);
$stationController = new StationController($pdo);
$typeBorneController = new TypeBorneController($pdo);

$message = '';

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'creerStation':
            ob_start();
            $stationController->create($_POST['libelleEmplacement']);
            $message = ob_get_clean();
            break;

        case 'creerTypeBorne':
            ob_start();
            $typeBorneController->create(
                $_POST['codeTypeBorne'],
                $_POST['dureeRevision'],
                $_POST['nbJoursEntreRevisions'],
                $_POST['nbUnitesEntreRevisions']
            );
            $message = ob_get_clean();
            break;

        case 'creerBorne':
            ob_start();
            $borneController->create(
                $_POST['dateDerniereRevision'],
                $_POST['indiceCompteurUnites'],
                $_POST['codeTypeBorne'],
                $_POST['idStation']
            );
            $message = ob_get_clean();
            break;

        case 'creerTechnicien':
            ob_start();
            $technicienController->create(
                $_POST['matricule'],
                $_POST['nom'],
                $_POST['prenom']
            );
            $message = ob_get_clean();
            break;

        case 'attribuerBorne':
            ob_start();
            $technicienController->attribuerBorne(
                $_POST['matricule'],
                $_POST['idBorne'],
                $_POST['dureeTotale']
            );
            $message = ob_get_clean();
            break;
    }
}

// Récupération des données pour l'affichage
$bornes = $borneController->listAll();
$stations = $stationController->listAll();
$typesBorne = $typeBorneController->listAll();
$techniciensDispo = $technicienController->getTechniciensByDisponibilite();

require_once 'Vue/home.php';
