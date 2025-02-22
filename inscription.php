<?php
// Connexion à la base de données
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "clinique_bonheur"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Vérifier que les champs obligatoires ne sont pas vides
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        die("Tous les champs sont obligatoires.");
    }

    // Vérifier si l'email est déjà utilisé
    $sql_check = "SELECT id FROM femmes_enceintes WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        die("Cet email est déjà utilisé. Veuillez en choisir un autre.");
    }
    $stmt_check->close();

    // Insérer les données dans la base de données avec est_valide à 0
    $sql = "INSERT INTO femmes_enceintes (nom, prenom, email, password, est_valide) VALUES (?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nom, $prenom, $email, $password);

    if ($stmt->execute()) {
        // Rediriger vers message.php après une inscription réussie
        header('Location: message.php');
        exit(); // Terminer le script après la redirection
    } else {
        die("Erreur lors de l'inscription : " . $conn->error);
    }
 
    $stmt->close();
}

// Fermer la connexion
$conn->close();
?>