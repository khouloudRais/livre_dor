<?php


class User {
    private $pdo;


    public function __construct($host, $dbname, $user, $password) {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            exit;
        }
    }

    // Méthode pour vérifier si un utilisateur existe déjà avec cet email
    public function checkEmailExists($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode pour inscrire un utilisateur
    public function registerUser($username, $password, $email) {
        // Hacher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Préparer la requête d'insertion dans la base de données
        $stmt = $this->pdo->prepare("INSERT INTO user (username, password, email) VALUES (:username, :password, :email)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }
}

?>
<?php


// Connexion à la base de données
require_once '../classes/database.php';

//$userClass = new User($host, $dbname, $username, $password);

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);

    // Vérification que les mots de passe correspondent
    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }

    // Vérification si l'email est déjà utilisé
    if ($userClass->checkEmailExists($email)) {
        echo "Cet email est déjà utilisé.";
        exit;
    }

    // Inscrire l'utilisateur dans la base de données
    if ($userClass->registerUser($username, $password, $email)) {
        echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header("Location: connexion.php");
    } else {
        echo "Une erreur s'est produite lors de l'inscription. Essayez à nouveau.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulaire.css"> 
    <title>Formulaire d'Inscription</title>
    <link rel="stylesheet" href="css/akila.css"> 
</head>
<body>
<header>
    <h1>Livre d'Or</h1>
   
    </header>

    <nav>
            <ul>
               
              
                <li><a href="connexion.php">CONNEXION</a></li>
                <li><a href="../index.php">Acceuil</a></li>
            </ul>
        </nav>
    <h2>Inscrivez-vous !</h2>
    
    <form action="inscription.php" method="POST">
        <label for="username">Nom :</label>
        <input type="text" id="username" name="username" required>
        <br><br>

        <label for="email">Adresse email :</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br><br>

        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br><br>

        <button type="submit">S'inscrire ! </button>
        
        <p>Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
    </form>

    <footer>
        <div class="footer-container">
            <p>&copy; <?php echo date("Y"); ?> Livre d'Or. Tous droits réservés.</p>
           
        </div>
    </footer>
</body>
</html>