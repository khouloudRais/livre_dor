<?php
// Connexion à la base de données
require_once '../database.php'; // Assure-toi que ce fichier contient la connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données du formulaire
    $name = $_POST['name'];
    $message = $_POST['message'];

    // Prépare la requête pour insérer le message dans la base de données
    $sql = "INSERT INTO messages (name, message) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $message]);

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
        </ul>
    </nav>

    <section class="form-section">
        <form action="ajouter.php" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required><br><br>

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
            <p>Développé par <a href="https://www.linkedin.com/in/khouloud-kechiche" target="_blank">Khouloud Rais</a></p>
        </div>
    </footer>

</body>
</html>






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
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="connexion.php">Deconnexion</a></li>
        </ul>
    </nav>

   
    <section class="form-section">
      
        <form action="message.php" method="POST">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" required><br><br>

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
            <p>Développé par <a href="https://www.linkedin.com/in/khouloud-kechiche" target="_blank">Khouloud Rais</a></p>
        </div>
    </footer>

</body>

</html>
