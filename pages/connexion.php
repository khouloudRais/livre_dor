<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'livreor';
    private $user = 'root';
    private $password = '';
    private $conn;

    public function connect() {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            exit;
        }
    }
}
?>
<?php
class User {
    private $db;
    private $conn;

    public function __construct($db) {
        $this->db = $db;
        $this->conn = $this->db->connect();
    }

    public function login($username, $password) {
        // Requête pour vérifier les identifiants de l'utilisateur
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Si l'utilisateur existe
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifie le mot de passe
            if (password_verify($password, $user['password'])) {
                // Si les identifiants sont corrects, démarrer une session et stocker des données
                session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];

                // Redirection vers une page sécurisée (par exemple, la page d'accueil ou tableau de bord)
                header("Location: livre_dor.php");
                exit;
            } else {
                // Si le mot de passe est incorrect
                return "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } else {
            // Si l'utilisateur n'existe pas
            return "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }
}
?>
<?php


// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données envoyées par le formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Crée une instance de la classe Database
    $db = new Database();

    // Crée une instance de la classe User
    $user = new User($db);

    // Appel de la méthode login pour tenter la connexion
    $message = $user->login($username, $password);

    // Si un message d'erreur est renvoyé, l'afficher
    if ($message) {
        echo $message;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de connexion</title>
    <link rel="stylesheet" href="../css/formulaire.css"> 
</head>
<body>

<header>
    <h1>Livre d'Or</h1>
   
    </header>

    <nav>
            <ul>
               
                <li><a href="../index.php">Acceuil</a></li>
                <li><a href="inscription.php">INSCRIPTION</a></li>
            </ul>
        </nav>
    <h2>Connexion</h2>

    <!-- Formulaire de connexion -->
    <form action="connexion.php" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>
        <br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br><br>

        <button type="submit">Se connecter</button>

        <p>Vous voulez vous inscrire ? <a href="inscription.php">S'inscrire</a></p>
    </form>

    <footer>
        <div class="footer-container">
            <p>&copy; <?php echo date("Y"); ?> Livre d'Or. Tous droits réservés.</p>
           
        </div>
    </footer>
</body>
</html>
