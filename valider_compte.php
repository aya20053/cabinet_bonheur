<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "clinique_bonheur";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Mettre à jour le compte pour le valider
    $sql = "UPDATE femmes_enceintes SET est_valide = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirection vers la page des demandes de validation
        header("Location: demandes_validation.php?success=1");
        exit(); // Assurez-vous d'arrêter le script après la redirection
    } else {
        echo "Erreur lors de la validation du compte : " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>