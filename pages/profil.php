<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php'); // Rediriger si non connecté
    exit();
}

// Connexion à la base de données
require_once '../classes/database.php';

// Créer une instance de la classe Database pour récupérer l'objet PDO
$database = new Database();
$pdo = $database->getConnection(); // Connexion à la base de données via PDO

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = :id");
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si l'utilisateur existe
if (!$user) {
    echo "Utilisateur introuvable.";
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Vérifier si l'email est déjà utilisé par un autre utilisateur
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email AND id != :id");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo "Cet email est déjà utilisé.";
    } else {
        // Mettre à jour les informations de l'utilisateur
        $stmt = $pdo->prepare("UPDATE user SET username = :username, email = :email WHERE id = :id");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $user_id);

        if ($stmt->execute()) {
            $_SESSION['profil'] = 'Votre profil a été mis à jour avec succès.';
            // Rediriger pour éviter le renvoi de données via POST
            header('Location: profil.php');
            exit();
        } else {
            echo "Une erreur est survenue lors de la mise à jour du profil.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Profil</title>
    <link rel="stylesheet" href="../css/profil.css">
</head>
<body>

    <header>
        <h1>Livre d'or</h1>
      
    </header>

    <main>
        
    <nav>
            <ul>
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="commentaires.php">Ajouter un message</a></li>
                <li><a href="connexion.php">Deconnexion</a></li>
              
            </ul>
        </nav>
        <form action="profil.php" method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <br><br>

            <label for="email">Adresse email :</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <br><br>

            <button type="submit">Mettre à jour le profil</button>
        </form>
    </main>

    <footer>
        <div class="footer-container">
            <p>&copy; <?php echo date("Y"); ?> Livre d'Or. Tous droits réservés.</p>
        </div>
    </footer>

</body>
</html>
