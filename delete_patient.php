<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id'])) {
    header("Location: login_admin.php");
    exit();
}

// Connexion à la base de données
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "clinique_bonheur";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer l'ID du patient à supprimer
if (!isset($_GET['id'])) {
    header("Location: patients.php");
    exit();
}
$id = $_GET['id'];

// Supprimer le patient
$stmt = $pdo->prepare("DELETE FROM femmes_enceintes WHERE id = :id");
$stmt->execute(['id' => $id]);

header("Location: patients.php");
exit();
?>