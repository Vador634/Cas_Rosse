<?php
/**
 * Point d'entrée de l'application ROSSE.
 *
 * - Traite les formulaires (POST) puis redirige vers la page (pattern
 *   Post/Redirect/Get) : un rafraîchissement ne renvoie pas le formulaire.
 * - Chaque formulaire est protégé par un jeton CSRF stocké en session.
 * - Récupère les données et affiche la vue Vue/home.php.
 */
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/Config/config.php';
require_once __DIR__ . '/Controleur/BorneController.php';
require_once __DIR__ . '/Controleur/TechnicienController.php';
require_once __DIR__ . '/Controleur/StationController.php';
require_once __DIR__ . '/Controleur/TypeBorneController.php';

$pdo = connexionPDO();
$borneController = new BorneController($pdo);
$technicienController = new TechnicienController($pdo);
$stationController = new StationController($pdo);
$typeBorneController = new TypeBorneController($pdo);

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $champ = function ($nom) {
        return trim((string)($_POST[$nom] ?? ''));
    };
    $ok = false;

    ob_start();
    if (!hash_equals($_SESSION['csrf'], $_POST['csrf'] ?? '')) {
        echo "Session expirée, merci de renvoyer le formulaire.";
    } else {
        switch ($action) {
            case 'creerStation':
                $ok = $stationController->create($champ('libelleEmplacement'));
                break;

            case 'creerTypeBorne':
                $ok = $typeBorneController->create(
                    $champ('codeTypeBorne'),
                    $champ('dureeRevision'),
                    $champ('nbJoursEntreRevisions'),
                    $champ('nbUnitesEntreRevisions')
                );
                break;

            case 'creerBorne':
                $ok = $borneController->create(
                    $champ('dateDerniereRevision'),
                    $champ('indiceCompteurUnites'),
                    $champ('codeTypeBorne'),
                    $champ('idStation')
                );
                break;

            case 'creerTechnicien':
                $ok = $technicienController->create($champ('matricule'), $champ('nom'), $champ('prenom'));
                break;

            case 'attribuerBorne':
                $ok = $technicienController->attribuerBorne(
                    $champ('matricule'),
                    $champ('idBorne'),
                    $champ('dureeTotale')
                );
                break;

            default:
                echo "Action inconnue.";
        }
    }

    $_SESSION['flash'] = ['ok' => (bool)$ok, 'texte' => ob_get_clean()];
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

// Message de la dernière action (affiché une seule fois)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$csrf = $_SESSION['csrf'];

// Récupération des données pour l'affichage
$bornes = $borneController->listAll();
$stations = $stationController->listAll();
$typesBorne = $typeBorneController->listAll();
$techniciensDispo = $technicienController->getTechniciensByDisponibilite();
$planning = $technicienController->getPlanning();

require_once __DIR__ . '/Vue/home.php';
