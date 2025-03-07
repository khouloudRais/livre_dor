<?php
require_once '../classes/database.php';
require_once '../classes/messages.php';  // Assurez-vous que le bon chemin est utilisé


// Instancier la classe Messages
$livreOr = new Messages(new Database());  // Nous créons une instance de Messages en lui passant l'objet Database

// Définir la valeur de $query s'il existe dans l'URL, sinon une chaîne vide
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Nombre de messages par page
$limit = 2;

// Récupérer la page actuelle
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Calculer le point de départ des messages à afficher
$start = ($page - 1) * $limit;

// Calcul du nombre total de messages
if ($query) {
    $totalMessages = $livreOr->getTotalMessagesByKeyword($query);
} else {
    $totalMessages = $livreOr->getTotalMessages();
}

$totalPages = ceil($totalMessages / $limit);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livre d'Or</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
   
    <header>
        <h1>Livre d'Or</h1>
    </header>

    <nav>
        <ul>
            <li><a href="commentaires.php">Ajouter un message</a></li>
            <li><a href="connexion.php">Deconnexion</a></li>
            <li><a href="profil.php">Modifier ton profil</a></li>
        </ul>
    
        <form action="#" method="GET" class="search-form">
            <input type="text" name="query" placeholder="Entrez le mot clé..." value="<?php echo htmlspecialchars($query); ?>" class="search-input">
            <button type="submit" class="search-button">Rechercher</button>
        </form>
    </nav>

    <section class="livre-or">
        <?php
        // Afficher les messages avec pagination ou les résultats de la recherche
        if ($query) {
            $messages = $livreOr->searchMessagesByKeyword($query, $start, $limit);
        } else {
            $messages = $livreOr->getMessagesWithPagination($start, $limit);
        }

        // Affichage des messages
        foreach ($messages as $message) {
            echo "<div class='message'>";
            echo "<p><strong>" . $message['name'] . "</strong> <span class='date'>" . $message['date'] . "</span></p>";
            echo "<p>" . $message['message'] . "</p>";
            echo "</div>";
        }

        if (isset($_GET['message_added']) && $_GET['message_added'] == 'true') {
            echo "<p><strong>Votre message a été ajouté avec succès !</strong></p>";
        }

        // Affichage des liens de pagination
        echo "<div class='pagination'>";
        if ($page > 1) {
            echo "<a href='?page=" . ($page - 1) . "&query=" . urlencode($query) . "'>&laquo; Page précédente</a>";
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $page) {
                echo "<span class='current'>$i</span>";
            } else {
                echo "<a href='?page=$i&query=" . urlencode($query) . "'>$i</a>";
            }
        }

        if ($page < $totalPages) {
            echo "<a href='?page=" . ($page + 1) . "&query=" . urlencode($query) . "'>Page suivante &raquo;</a>";
        }
        echo "</div>";
        ?>
    </section>

    <footer>
        <div class="footer-container">
            <p>&copy; <?php echo date("Y"); ?> Livre d'Or. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
