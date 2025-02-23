<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Database connection
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
    die("Connection failed: " . $e->getMessage());
}

// Process the reply
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $femmeId = $_POST['femme_id'];
    $messageId = $_POST['message_id'];
    $reply = trim($_POST['reply']);

    // Insert the reply into the messages table
    $stmt = $pdo->prepare("INSERT INTO messages (message, date_envoi, statut, femme_id, medecin_id) VALUES (:message, NOW(), 'Envoyé', :femme_id, :medecin_id)");
    $stmt->execute([
        'message' => $reply,
        'femme_id' => $femmeId,
        'medecin_id' => $_SESSION['user_id'] // Assuming the logged-in user's ID is the doctor
    ]);

    header("Location: messagerie_admin.php"); // Redirect back to the messages page
    exit();
}
?>