<?php
// Connexion à la base de données
$host = 'localhost'; // Adresse du serveur MySQL
$dbname = 'clinique_bonheur'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur MySQL
$password = ''; // Mot de passe MySQL

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}
try {
    // Connexion à la base de données avec PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les données du formulaire
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Préparer la requête SQL
    $sql = "INSERT INTO contact (name, email, message) VALUES (:name, :email, :message)";
    $stmt = $conn->prepare($sql);

    // Lier les paramètres
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);

    // Exécuter la requête
    $stmt->execute();

    // Rediriger l'utilisateur après l'envoi
} catch (PDOException $e) {
    // En cas d'erreur, afficher un message
    echo "Erreur : " . $e->getMessage();
}

// Fermer la connexion
$conn = null;
?>