<?php

class Database {
    private $pdo;
    private $host = 'localhost';
    private $dbname = 'livreor';
    private $username = 'root';
    private $password = '';

    // Constructeur : se connecte à la base de données
    public function __construct() {
        try {
            // Créer la connexion PDO
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            // Définir l'attribut d'erreur de PDO pour afficher les exceptions
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // En cas d'erreur, on affiche un message et on arrête l'exécution
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    // Méthode pour récupérer l'objet PDO (pour pouvoir l'utiliser ailleurs)
    public function getConnection() {
        return $this->pdo;
    }

    // Méthode pour fermer la connexion (optionnelle, mais peut être utile)
    public function closeConnection() {
        $this->pdo = null;
    }
}
?>
