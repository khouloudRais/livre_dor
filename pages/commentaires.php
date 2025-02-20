<?php

require_once '../database.php'; 


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données du formulaire
    $message = $_POST['message'];

    // Récupère l'ID de l'utilisateur connecté (assumé qu'il est stocké dans la session)
    $user_id = $_SESSION['user_id']; // Assure-toi que la session contient 'user_id'

    // Prépare la requête pour insérer le message dans la base de données
    $sql = "INSERT INTO messages (user_id, message) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $message]);

    // Redirige vers la page d'accueil (index.php) après l'ajout
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un message</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Livre d'Or</h1>
    </header>

    <nav>
        <ul>
            <li><a href="livre_dor.php">Accueil</a></li>
            <li><a href="connexion.php">Deconnexion</a></li>
            <li><a href="profil.php">Modifier ton profil</a></li>
        </ul>
    </nav>
    <h2>Ajouter ton commentaire</h2>

    <section class="form-section">
        <form action="commentaires.php" method="POST">
            <label for="message">Message :</label><br>
            <textarea id="message" name="message" rows="4" required></textarea><br><br>

            <div class="form-buttons">
                <input type="submit" value="Envoyer">
            </div>
        </form>
    </section>

    <footer>
        <div class="footer-container">
            <p>&copy; <?php echo date("Y"); ?> Livre d'Or. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
