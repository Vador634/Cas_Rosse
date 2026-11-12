<?php
/**
 * Configuration de la connexion à la base de données.
 *
 * Les valeurs par défaut correspondent à une installation locale
 * (XAMPP / WAMP / Laragon). Elles peuvent être surchargées par des
 * variables d'environnement sans modifier ce fichier :
 *   ROSSE_DB_HOST, ROSSE_DB_NAME, ROSSE_DB_USER, ROSSE_DB_PASS
 *   ROSSE_DB_DSN (DSN PDO complet, prioritaire sur les autres)
 */
function connexionPDO() {
    $serveur = getenv('ROSSE_DB_HOST') ?: 'localhost';
    $bd      = getenv('ROSSE_DB_NAME') ?: 'rosse_bdd';
    $login   = getenv('ROSSE_DB_USER') ?: 'root';
    $mdp     = getenv('ROSSE_DB_PASS') ?: '';
    $dsn     = getenv('ROSSE_DB_DSN') ?: "mysql:host=$serveur;dbname=$bd;charset=utf8mb4";

    try {
        $conn = new PDO($dsn, $login, $mdp);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    } catch (PDOException $e) {
        http_response_code(500);
        die("Connexion à la base impossible. Vérifiez que la base « $bd » existe (script Rosse_Bdd.sql) "
            . "et les identifiants dans Config/config.php.<br><small>" . htmlspecialchars($e->getMessage()) . "</small>");
    }
}
