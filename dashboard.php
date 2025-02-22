<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "clinique_bonheur";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

$email = trim($_POST['email']);
$password = $_POST['password'];

// Vérifier si l'utilisateur existe
$sql = "SELECT * FROM femmes_enceintes WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    
    // Vérifier si le mot de passe est correct
    if (password_verify($password, $user['password'])) {
        // Vérifier si le compte est validé et si l'utilisateur n'est pas un administrateur
        if ($user['est_valide'] == 1 && $user['isadmin'] == 0) {
            $_SESSION['user_id'] = $user['id'];
            // Rediriger vers la page de profil
            header("Location: profil.php");
            exit();
        } elseif ($user['isadmin'] == 1) {
            echo "Vous ne pouvez pas vous connecter en tant qu'administrateur avec ce compte.";
        } else {
            echo "Votre compte n'est pas encore validé par un administrateur.";
        }
    } else {
        echo "Mot de passe incorrect.";
    }
} else {
    echo "Utilisateur non trouvé.";
}

$stmt->close();
$conn->close();
?>