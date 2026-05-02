<?php
function connexionPDO() {
    $login = "root";   
    $mdp = "";     
    $bd = "rosse_bdd";     
    $serveur = "localhost";

    try {
        $conn = new PDO(
            "mysql:host=$serveur;dbname=$bd;charset=utf8",
            $login,
            $mdp
        ); 
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Erreur de connexion PDO : " . $e->getMessage());
    }
}
