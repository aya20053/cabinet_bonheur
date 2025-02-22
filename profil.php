<?php
ob_start();
session_start();
include 'menu.php';
// Connexion à la base de données
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "clinique_bonheur";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Récupérer l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Récupérer la valeur de `profil_complet`, `nom` et `prenom` depuis la base de données
$sql = "SELECT profil_complet, nom, prenom FROM femmes_enceintes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['profil_complet'] = $row['profil_complet']; // Mettre à jour la session
    $_SESSION['nom'] = $row['nom']; // Stocker le nom dans la session
    $_SESSION['prenom'] = $row['prenom']; // Stocker le prénom dans la session
} else {
    $_SESSION['profil_complet'] = 0; // Valeur par défaut si l'utilisateur n'est pas trouvé
    $_SESSION['nom'] = ''; // Valeur par défaut pour le nom
    $_SESSION['prenom'] = ''; // Valeur par défaut pour le prénom
}

$profilComplet = $_SESSION['profil_complet']; // Utiliser la valeur de la session
$nom = $_SESSION['nom']; // Récupérer le nom depuis la session
$prenom = $_SESSION['prenom']; // Récupérer le prénom depuis la session

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
     <title>Cabinet de Bonheur</title>
        <link rel="icon" type="image/png" href="lgp.png">
        
    <style>
        body {
           
        }
        .alert {
            margin-top: 70px;
            background: #7C4C53;
            padding: 15px;
            color: white;
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .welcome-message {
             margin-top: 220px;
            font-size: 30px;
            font-weight: bold;
            color: #872341;
            margin-bottom: -200px;
            text-align: right;
        }
        .alert a {
            color: white;
        }
    </style>
</head>
<body>
    <div class="welcome-message">
        Bienvenue, <?= htmlspecialchars($prenom) ?> <?= htmlspecialchars($nom) ?> !....
    </div>

    <?php if ($profilComplet === 0): ?>
        <div class="alert">
            ⚠️ Votre dossier est incomplet. Veuillez <a href="completer_profil.php">le compléter ici</a>.
        </div>
    <?php endif; ?>
</body>
</html>