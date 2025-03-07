<?php
require_once 'classes/messages.php'; // Assure-toi du bon chemin
require_once 'classes/database.php'; // Si nécessaire, inclure la classe Database

// Instancier la classe Database
$database = new Database();
$livreOr = new Messages($database);

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
    $messages = $livreOr->searchMessagesByKeyword($query, $start, $limit);
} else {
    $totalMessages = $livreOr->getTotalMessages();
    $messages = $livreOr->getMessagesWithPagination($start, $limit);
}

$totalPages = ceil($totalMessages / $limit);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livre d'Or</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Livre d'Or</h1>
    </header>

    <main>
        <nav>
            <ul>
                <li><a href="pages/connexion.php">CONNEXION</a></li>
                <li><a href="pages/inscription.php">INSCRIPTION</a></li>
            </ul>
            <form action="index.php" method="GET" class="search-form">
                <input type="text" name="query" placeholder="Tapez le nom..." value="<?php echo htmlspecialchars($query); ?>" class="search-input">
                <button type="submit" class="search-button">Rechercher</button>
            </form>
        </nav>

        <section class="livre-or">
            <?php
            // Affichage des messages récupérés
            if (count($messages) > 0) {
                foreach ($messages as $message) {
                    echo "<div class='message'>";
                    echo "<p><strong>" . htmlspecialchars($message['name']) . "</strong> <span class='date'>" . $message['date'] . "</span></p>";
                    echo "<p>" . htmlspecialchars($message['message']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Aucun message trouvé.</p>";
            }

            // Affichage des liens de pagination
            echo "<div class='pagination'>";
            if ($page > 1) {
                echo "<a href='?page=" . ($page - 1) . "'>&laquo; Page précédente</a>";
            }

            for ($i = 1; $i <= $totalPages; $i++) {
                if ($i == $page) {
                    echo "<span class='current'>$i</span>";
                } else {
                    echo "<a href='?page=$i'>$i</a>";
                }
            }

            if ($page < $totalPages) {
                echo "<a href='?page=" . ($page + 1) . "'>Page suivante &raquo;</a>";
            }
            echo "</div>";
            ?>
        </section>
    </main>

    <footer>
        <div class="footer-container">
            <p>&copy; <?php echo date("Y"); ?> Livre d'Or. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
