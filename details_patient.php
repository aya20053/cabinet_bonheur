<?php
session_start();
ob_start();

include 'menu_admin.php';

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

// Récupérer l'ID du patient à afficher
if (!isset($_GET['id'])) {
    header("Location: patients.php");
    exit();
}
$id = $_GET['id'];

// Récupérer les informations du patient
$stmt = $pdo->prepare("SELECT * FROM femmes_enceintes WHERE id = :id");
$stmt->execute(['id' => $id]);
$patient = $stmt->fetch();

if (!$patient) {
    header("Location: patients.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Patient</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

             h1 { text-align: center; color: #3B1C32; margin-bottom: 20px; }


        .patient-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .patient-details label {
            font-weight: bold;
            color: #555;
        }

        .patient-details p {
            margin: 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .back-button {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .back-button a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #883C65;
            color: white;
            border-radius: 4px;
            font-size: 16px;
        }

        .back-button a:hover {
            background-color: #78566D;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-user"></i> Détails du Patient</h1>

        <div class="patient-details">
            <div>
                <label>Nom :</label>
                <p><?= htmlspecialchars($patient['nom']) ?></p>
            </div>
            <div>
                <label>Prénom :</label>
                <p><?= htmlspecialchars($patient['prenom']) ?></p>
            </div>
            <div>
                <label>Email :</label>
                <p><?= htmlspecialchars($patient['email']) ?></p>
            </div>
            <div>
                <label>Date de Naissance :</label>
                <p><?= htmlspecialchars($patient['date_naissance']) ?></p>
            </div>
            <div>
                <label>Téléphone :</label>
                <p><?= htmlspecialchars($patient['telephone']) ?></p>
            </div>
            <div>
                <label>Adresse :</label>
                <p><?= htmlspecialchars($patient['adresse']) ?></p>
            </div>
            <div>
                <label>Groupe Sanguin :</label>
                <p><?= htmlspecialchars($patient['groupe_sanguin']) ?></p>
            </div>
            <div>
                <label>Date de Dernières Règles :</label>
                <p><?= htmlspecialchars($patient['date_dernieres_regles']) ?></p>
            </div>
            <div>
                <label>Date Prévue d'Accouchement :</label>
                <p><?= htmlspecialchars($patient['date_prevue_accouchement']) ?></p>
            </div>
            <div>
                <label>Nombre de Grossesses Précédentes :</label>
                <p><?= htmlspecialchars($patient['nombre_grossesses_precedentes']) ?></p>
            </div>
            <div>
                <label>Antécédents Médicaux :</label>
                <p><?= htmlspecialchars($patient['antecedents_medicaux']) ?></p>
            </div>
            <div>
                <label>Allergies :</label>
                <p><?= htmlspecialchars($patient['allergies']) ?></p>
            </div>
        </div>

        <div class="back-button">
            <a href="patients.php"><i class="fas fa-arrow-left"></i> Retour à la liste des patients</a>
        </div>
    </div>
</body>
</html>