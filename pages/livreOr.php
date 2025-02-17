<?php

class LivreOr {
    private $pdo;

    // Constructeur pour initialiser la connexion à la base de données
    public function __construct() {
        try {
            // Connexion à la base de données
            $this->pdo = new PDO('mysql:host=localhost;dbname=livreor', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Activer le mode d'erreur
        } catch (PDOException $e) {
            // Gestion des erreurs de connexion
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }


    




    public function displayMessagesWithPagination($start, $limit) {
        $sql = "SELECT * FROM messages ORDER BY date DESC LIMIT :start, :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        // Parcours des messages et affichage
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name = htmlspecialchars($row['name']);
            $message = htmlspecialchars($row['message']);
            $date = date('d/m/Y H:i', strtotime($row['date']));

            echo "<div class='message'>
                    <p><strong>$name</strong> <span class='date'>$date</span></p>
                    <p>$message</p>
                  </div>";
        }
    }

    // Retourne le nombre total de messages pour la pagination
    public function getTotalMessages() {
        $sql = "SELECT COUNT(*) FROM messages";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchColumn();
    }
}

?>
