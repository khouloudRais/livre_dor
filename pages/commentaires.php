<?php
require_once '../classes/database.php';
session_start();
$database = new Database();
$pdo = $database->getConnection();



// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    die("<p>Vous devez être connecté pour poster un commentaire.</p>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer le commentaire
    $message_recupere = htmlspecialchars($_POST['commentaire'], ENT_QUOTES, 'UTF-8');
    $username = $_SESSION['username']; // Récupérer le nom d'utilisateur depuis la session

    // Vérifier si le commentaire n'est pas vide
    if (!empty($message_recupere)) {
        $sql = "INSERT INTO messages (name, message) VALUES (:nom, :var_commentaire)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $username, PDO::PARAM_STR);
        $stmt->bindParam(':var_commentaire', $message_recupere, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "Message bien enregistré !";
            header("Location: livre_dor.php");
            exit();  
        } else {
            echo "Une erreur s'est produite lors de l'enregistrement.";
        }
    } else {
        echo "<p>Le commentaire ne peut pas être vide.</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulaire.css"> 
    <title>Ajouter un commentaire</title>
</head>
<body>
<header>
    <h1>Livre d'Or</h1>
   
    </header>
<nav>
        <ul>
            <li><a href="livre_dor.php">Accueil</a></li>
          
            <li><a href="profil.php">Modifier ton profil</a></li>
            <li><a href="connexion.php">Deconnexion</a></li>
        </ul>
    </nav>
    <h1>Ajouter un commentaire</h1>
    
    <form action="commentaires.php" method="POST">
        <label for="commentaire">Votre commentaire :</label><br>
        <textarea name="commentaire" id="commentaire" rows="5" cols="40" required></textarea><br><br>
        <button type="submit">Envoyer</button>
    </form>
</body>
<footer>
        <div class="footer-container">
            <p>&copy; <?php echo date("Y"); ?> Livre d'Or. Tous droits réservés.</p>
           
        </div>
    </footer>
</html>
