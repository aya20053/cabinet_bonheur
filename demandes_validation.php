<?php
session_start();
ob_start();

include 'menu_admin.php';

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "clinique_bonheur";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupérer les comptes non validés
$query = "SELECT * FROM femmes_enceintes WHERE est_valide = 0";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demandes de Validation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
   /* Styles globaux pour la page */
body {
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #3B1C32;
    margin-bottom: 20px;
}

.container {
    max-width: 98%;
    margin: auto;
    padding: 15px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

table {
    width: 90%; /* Réduire la largeur de la table */
    margin: auto;
    border-collapse: collapse;
}

table th, table td {
    padding: 8px; /* Réduire un peu le padding */
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #3B1C32;
    color: white;
    font-size: 16px; /* Réduire la taille de la police */
}

table td {
    font-size: 14px; /* Réduire un peu la taille de la police des données */
}

table tr:hover {
    background-color: #f5f5f5;
}

.btn-valider {
    display: inline-flex;
    align-items: center;
    background-color: #9DC08B;
    color: white;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.btn-valider i {
    margin-right: 5px;
}

.btn-valider:hover {
    background-color: #218838;
}

/* Responsive Styles for Mobile */
@media (max-width: 480px) {
    body {
        padding: 5px;
    }

    h1 {
        font-size: 1.5em;
        margin-bottom: 15px;
    }

    .container {
        width: 100%;
    }

    table th, table td {
        width: auto;
    }

    .btn-valider {
        padding: 8px 12px;
        font-size: 14px;
    }
}

/* Styles spéciales pour le menu admin (ce style ne change pas) */
.admin-menu {
    background-color: #333;
    color: white;
    padding: 10px;
    border-radius: 8px;
}

.admin-menu a {
    color: white;
    text-decoration: none;
    padding: 10px;
    display: block;
}

.admin-menu a:hover {
    background-color: #555;
}

    </style>
</head>
<body>
<div class="container">
<h1><i class="fas fa-user-check"></i> Demandes de Validation de Comptes</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['nom']) ?></td>
                    <td><?= htmlspecialchars($row['prenom']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>
                        <a class="btn-valider" href="valider_compte.php?id=<?= $row['id'] ?>">
                            <i class="fas fa-check"></i> Valider
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div></body>
</html>

<?php
$conn->close();
?>