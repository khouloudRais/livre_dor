<?php

class User {
    private $db;
    private $conn;

    public function __construct($db) {
        $this->db = $db;
        $this->conn = $this->db->connect();
    }

    public function login($username, $password) {
        // Requête pour vérifier les identifiants de l'utilisateur
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Si l'utilisateur existe
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifie le mot de passe
            if (password_verify($password, $user['password'])) {
                // Si les identifiants sont corrects, démarrer une session et stocker des données
                session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];

                // Redirection vers une page sécurisée (par exemple, la page d'accueil ou tableau de bord)
                header("Location: index.php");
                exit;
            } else {
                // Si le mot de passe est incorrect
                return "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } else {
            // Si l'utilisateur n'existe pas
            return "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}


