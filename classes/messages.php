<?php

class Messages {

    private $pdo;

    // Constructeur qui accepte une instance de la classe Database
    public function __construct(Database $database) {
        $this->pdo = $database->getConnection();
    }

    // Récupérer les messages avec pagination
    public function getMessagesWithPagination($start, $limit) {
        $stmt = $this->pdo->prepare("SELECT * FROM messages LIMIT :start, :limit");
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = [
                'name' => htmlspecialchars($row['name']),
                'date' => date('d/m/Y H:i', strtotime($row['date'])),
                'message' => htmlspecialchars($row['message'])
            ];
        }
        return $messages;  // Retourne un tableau de messages
    }

    // Rechercher les messages par mot-clé avec pagination
    public function searchMessagesByKeyword($keyword, $start, $limit) {
        $stmt = $this->pdo->prepare("SELECT * FROM messages WHERE message LIKE :keyword OR name LIKE :keyword LIMIT :start, :limit");
        $keyword = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $messages = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = [
                'name' => htmlspecialchars($row['name']),
                'date' => date('d/m/Y H:i', strtotime($row['date'])),
                'message' => htmlspecialchars($row['message'])
            ];
        }
        return $messages;  // Retourne un tableau de messages
    }

    // Obtenir le nombre total de messages pour la pagination
    public function getTotalMessages() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM messages");
        return $stmt->fetchColumn();
    }

    // Obtenir le nombre total de messages correspondant à une recherche
    public function getTotalMessagesByKeyword($keyword) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM messages WHERE message LIKE :keyword OR name LIKE :keyword");
        $keyword = "%" . $keyword . "%";
        $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>
