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

// Gestion de la recherche
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Construction de la requête SQL
$query = "SELECT * FROM femmes_enceintes WHERE isadmin = 0";
$params = [];

if (!empty($search)) {
    $query .= " AND (nom LIKE :search OR prenom LIKE :search OR email LIKE :search OR telephone LIKE :search)";
    $params[':search'] = "%$search%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$patients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Patients</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
       body {
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 90%;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    text-align: center;
    color: #3B1C32;
    margin-bottom: 20px;
}

.search-container {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.search-bar {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 25px;
    padding: 10px 15px;
    background: #fff;
    width: 50%;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.search-bar i {
    font-size: 18px;
    color: #872341;
    padding: 8px;
}

.search-bar input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: none;
    outline: none;
    background: transparent;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #3B1C32;
    color: white;
}

table tr:hover {
    background-color: #f5f5f5;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.action-buttons a {
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 4px;
    color: white;
    font-size: 14px;
}

.action-buttons .edit {
    background-color: #9DC08B;
}

.action-buttons .delete {
    background-color: #e74c3c;
}

.action-buttons .details {
    background-color: #48A6A7;
}

.action-buttons a:hover {
    opacity: 0.9;
}

/* Responsive Styles */
@media (max-width: 480px) {
    .container {
        width: 100%;
        margin: 10px 0;
    }

    table {
        border: none;
    }

    table th, table td {
        display: block;
        text-align: right;
        padding: 10px;
    }

    table td {
        border-bottom: 1px solid #ddd;
        display: block;
        text-align: left;
    }

    table th {
        display: none;
    }

    table td::before {
        content: attr(data-label);
        font-weight: bold;
        display: block;
        color: #3B1C32;
    }

    .action-buttons a {
        width: 100%;
        text-align: center;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-users"></i> Liste des Patients</h1>
        <div class="search-container">
            <form method="GET" action="" class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Rechercher par nom, prénom, email, etc." value="<?= htmlspecialchars($search) ?>" autofocus>
                <button type="submit" style="display: none;"></button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($patients)): ?>
                    <tr><td colspan="6" style="text-align: center;">Aucun patient trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach ($patients as $patient): ?>
                        <tr>
                            <td><?= htmlspecialchars($patient['id']) ?></td>
                            <td><?= htmlspecialchars($patient['nom']) ?></td>
                            <td><?= htmlspecialchars($patient['prenom']) ?></td>
                            <td><?= htmlspecialchars($patient['email']) ?></td>
                            <td><?= htmlspecialchars($patient['telephone']) ?></td>
                            <td class="action-buttons">
                                <a href="details_patient.php?id=<?= $patient['id'] ?>" class="details"><i class="fas fa-eye"></i> Détails</a>
                                <a href="edit_patient.php?id=<?= $patient['id'] ?>" class="edit"><i class="fas fa-edit"></i> Modifier</a>
                                <a href="delete_patient.php?id=<?= $patient['id'] ?>" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?');"><i class="fas fa-trash"></i> Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
