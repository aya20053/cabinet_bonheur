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
    $messageId = $_POST['message_id']; // Get the ID of the original message
    $reply = trim($_POST['reply']); // Get the reply message

    // Update the existing message's repondre column
    $stmt = $pdo->prepare("UPDATE messages SET repondre = :repondre WHERE id = :message_id");
    $stmt->execute([
        'repondre' => $reply, // Store the reply in the repondre column
        'message_id' => $messageId // Specify which message to update
    ]);

    // Redirect back to the messages page
    header("Location:messagerie_admin.php?success=1"); // Optionally pass a success flag
    exit();
}
?>