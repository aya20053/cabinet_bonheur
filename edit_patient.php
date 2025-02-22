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

// Récupérer l'ID du patient à modifier
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

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $date_naissance = trim($_POST['date_naissance']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);
    $groupe_sanguin = trim($_POST['groupe_sanguin']);
    $date_dernieres_regles = trim($_POST['date_dernieres_regles']);
    $date_prevue_accouchement = trim($_POST['date_prevue_accouchement']);
    $nombre_grossesses_precedentes = trim($_POST['nombre_grossesses_precedentes']);
    $antecedents_medicaux = trim($_POST['antecedents_medicaux']);
    $allergies = trim($_POST['allergies']);

    // Hacher le mot de passe s'il est modifié
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
    } else {
        // Conserver l'ancien mot de passe s'il n'est pas modifié
        $password = $patient['password'];
    }

    // Mettre à jour les informations du patient dans la base de données
    $stmt = $pdo->prepare("
        UPDATE femmes_enceintes
        SET nom = :nom,
            prenom = :prenom,
            email = :email,
            password = :password,
            date_naissance = :date_naissance,
            telephone = :telephone,
            adresse = :adresse,
            groupe_sanguin = :groupe_sanguin,
            date_dernieres_regles = :date_dernieres_regles,
            date_prevue_accouchement = :date_prevue_accouchement,
            nombre_grossesses_precedentes = :nombre_grossesses_precedentes,
            antecedents_medicaux = :antecedents_medicaux,
            allergies = :allergies
        WHERE id = :id
    ");
    $stmt->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'password' => $password,
        'date_naissance' => $date_naissance,
        'telephone' => $telephone,
        'adresse' => $adresse,
        'groupe_sanguin' => $groupe_sanguin,
        'date_dernieres_regles' => $date_dernieres_regles,
        'date_prevue_accouchement' => $date_prevue_accouchement,
        'nombre_grossesses_precedentes' => $nombre_grossesses_precedentes,
        'antecedents_medicaux' => $antecedents_medicaux,
        'allergies' => $allergies,
        'id' => $id
    ]);

    // Rediriger vers la liste des patients après la mise à jour
    header("Location: patients.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Patient</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color:#883C65;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="email"],
        input[type="password"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #883C65;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #78566D;
        }

        .back-button {
            display: flex;
            justify-content: flex-start; /* Aligner à gauche */
            margin-top: 20px;
        }
        .back-button a {
            width: 100%;
            padding: 12px;
            background-color: #883C65;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
            text-decoration: none;
        }
        .back-button a:hover {
            background-color: #78566D;
         
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-user-edit"></i> Modifier le Profil du Patient</h1>
        <form method="POST">
            <!-- Nouveaux champs ajoutés -->
            <label for="nom"><i class="fas fa-user"></i> Nom :</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($patient['nom']) ?>" required>

            <label for="prenom"><i class="fas fa-user-alt"></i> Prénom :</label>
            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($patient['prenom']) ?>" required>

            <label for="email"><i class="fas fa-envelope"></i> Email :</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($patient['email']) ?>" required>

            <label for="password"><i class="fas fa-lock"></i> Mot de passe :</label>
            <input type="password" id="password" name="password" placeholder="Laisser vide pour ne pas modifier">

            <!-- Champs existants -->
            <label>Date de Naissance :</label>
            <input type="date" name="date_naissance" value="<?= htmlspecialchars($patient['date_naissance']) ?>" required>

            <label>Téléphone :</label>
            <input type="text" name="telephone" value="<?= htmlspecialchars($patient['telephone']) ?>" required>

            <label>Adresse :</label>
            <textarea name="adresse" required><?= htmlspecialchars($patient['adresse']) ?></textarea>

            <label>Groupe Sanguin :</label>
            <input type="text" name="groupe_sanguin" value="<?= htmlspecialchars($patient['groupe_sanguin']) ?>" required>

            <label>Date de Dernières Règles :</label>
            <input type="date" name="date_dernieres_regles" value="<?= htmlspecialchars($patient['date_dernieres_regles']) ?>" required>

            <label>Date Prévue d'Accouchement :</label>
            <input type="date" name="date_prevue_accouchement" value="<?= htmlspecialchars($patient['date_prevue_accouchement']) ?>" required>

            <label>Nombre de Grossesses Précédentes :</label>
            <input type="number" name="nombre_grossesses_precedentes" value="<?= htmlspecialchars($patient['nombre_grossesses_precedentes']) ?>" required>

            <label>Antécédents Médicaux :</label>
            <textarea name="antecedents_medicaux" required><?= htmlspecialchars($patient['antecedents_medicaux']) ?></textarea>

            <label>Allergies :</label>
            <textarea name="allergies" required><?= htmlspecialchars($patient['allergies']) ?></textarea>

            <button type="submit"><i class="fas fa-sync-alt"></i> Mettre à jour</button>

<div class="back-button">
    <a href="patients.php"><i class="fas fa-arrow-left"></i> Annuler</a>
</div>

        </form>
    </div>
</body>
</html>