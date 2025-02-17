<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livre d'Or</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
  
    <header>
    <h1>Livre d'Or</h1>
   
    </header>

   
    <main>
    <nav>
            <ul>
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="commentaires.php">Ajouter un message</a></li>
                <li><a href="connexion.php">Deconnexion</a></li>
            </ul>
        </nav>
        <section class="livre-or">
           

            <?php
            require_once 'LivreOr.php';

           
            $livreOr = new LivreOr();

           
            $limit = 2;

        
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($page < 1) $page = 1;

           
            $start = ($page - 1) * $limit;

            
            $livreOr->displayMessagesWithPagination($start, $limit);

           
            if (isset($_GET['message_added']) && $_GET['message_added'] == 'true') {
                echo "<p><strong>Votre message a été ajouté avec succès !</strong></p>";
            }

            // Récupérer le nombre total de messages pour la pagination
            $totalMessages = $livreOr->getTotalMessages();
            $totalPages = ceil($totalMessages / $limit);

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
            <p>Développé par <a href="https://www.linkedin.com/in/khouloud-kechiche" target="_blank">Khouloud Rais</a></p>
        </div>
    </footer>

</body>
</html>

