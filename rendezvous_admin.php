<?php
session_start();
ob_start();

include 'menu_admin.php';

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id'])) {
    header("Location: login_admin.php"); // Rediriger vers la page de connexion
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

// Traitement de la recherche dynamique
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $query = "
        SELECT rendezvous.*, femmes_enceintes.nom AS patient_nom, femmes_enceintes.prenom AS patient_prenom
        FROM rendezvous
        LEFT JOIN femmes_enceintes ON rendezvous.patient_id = femmes_enceintes.id
        WHERE rendezvous.id LIKE :search OR
              rendezvous.date LIKE :search OR
              rendezvous.heure LIKE :search OR
              rendezvous.statut LIKE :search OR
              femmes_enceintes.nom LIKE :search OR
              femmes_enceintes.prenom LIKE :search
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['search' => "%$search%"]);
    $rendezvous = $stmt->fetchAll();

    // Retourner les résultats au format JSON
    header('Content-Type: application/json');
    echo json_encode($rendezvous);
    exit();
}

// Récupérer tous les rendez-vous par défaut
$query = "
    SELECT rendezvous.*, femmes_enceintes.nom AS patient_nom, femmes_enceintes.prenom AS patient_prenom
    FROM rendezvous
    LEFT JOIN femmes_enceintes ON rendezvous.patient_id = femmes_enceintes.id
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$rendezvous = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Rendez-vous</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    body {
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
  
}

.container {
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    width: 80%; /* Pour un centrage fluide */
}

h1 {
    text-align: center;
    color:#3B1C32;
    margin-bottom: 20px;
}

.search-form {
    margin-bottom: 20px;
    display: flex;
    justify-content: center; /* Centrer horizontalement */
    align-items: center; /* Centrer verticalement */
    width: 50%;
    margin-left: auto;
    margin-right: auto;
    border: 1px solid #ccc;
    border-radius: 25px;
    padding: 10px 15px;
    background: #fff;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.search-form i {
    font-size: 18px;
    color: #872341;
    padding: 8px;
}

.search-form input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: none;
    outline: none;
    background: transparent;
}

table th, table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color:#3B1C32;
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
    background-color: #48A6A7;
}

.action-buttons .delete {
    background-color: #e74c3c;
}

.action-buttons a:hover {
    opacity: 0.9;
}

/* Centrer le tableau */
#rendezvous-table {
    width: 100%; /* Faire en sorte que le tableau prenne toute la largeur disponible */
    margin-top: 20px;
}

table {
    margin: 0 auto; /* Centrer le tableau horizontalement */
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

    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-calendar-alt"></i> Gestion des Rendez-vous</h1>

        <!-- Champ de recherche dynamique -->
      <div class="search-form">
    <i class="fas fa-search"></i> <!-- Icône de recherche -->
    <input type="text" id="search" placeholder="Rechercher par date, heure, patient, statut, etc.">
</div>


        <!-- Tableau des rendez-vous -->
        <table id="rendezvous-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Patient</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rendezvous as $rdv): ?>
                    <tr>
                        <td><?= htmlspecialchars($rdv['id']) ?></td>
                        <td><?= htmlspecialchars($rdv['date']) ?></td>
                        <td><?= htmlspecialchars($rdv['heure']) ?></td>
                        <td><?= htmlspecialchars($rdv['patient_nom']) ?> <?= htmlspecialchars($rdv['patient_prenom']) ?></td>
                        <td><?= htmlspecialchars($rdv['statut']) ?></td>
                        <td class="action-buttons">
    <!-- Bouton Modifier le statut -->
    <a href="#" class="edit" onclick="updateStatus(<?= $rdv['id'] ?>, '<?= $rdv['statut'] === 'En attente' ? 'Confirmé' : 'En attente' ?>')">
        <i class="fas fa-edit"></i> <?= $rdv['statut'] === 'En attente' ? 'Confirmer' : 'Revenir à En attente' ?>
    </a>

    <!-- Bouton Supprimer -->
    <a href="delete_rendezvous.php?id=<?= $rdv['id'] ?>" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?');">
        <i class="fas fa-trash"></i> Supprimer
    </a>
</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
  <script> function updateStatus(id, newStatus) {
    fetch(`update_status.php?id=${id}&status=${newStatus}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualiser la page ou modifier l'état directement dans le tableau
                location.reload(); // Recharger la page pour voir les changements
            } else {
                alert('Erreur lors de la mise à jour du statut.');
            }
        })
        .catch(error => console.error('Erreur lors de la mise à jour :', error));
}</script> 
    <script>
        // Fonction pour effectuer la recherche dynamique
        function searchRendezVous(query) {
            fetch(`rendezvous_admin.php?search=${query}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.querySelector("#rendezvous-table tbody");
                    tbody.innerHTML = ""; // Vider le tableau actuel

                    // Ajouter les nouveaux résultats
                    data.forEach(rdv => {
                        const row = `
                            <tr>
                                <td>${rdv.id}</td>
                                <td>${rdv.date}</td>
                                <td>${rdv.heure}</td>
                                <td>${rdv.patient_nom} ${rdv.patient_prenom}</td>
                                <td>${rdv.statut}</td>
                                <td class="action-buttons">
                                    <a href="edit_rendezvous.php?id=${rdv.id}" class="edit">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <a href="delete_rendezvous.php?id=${rdv.id}" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?');">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                })
                .catch(error => console.error('Erreur lors de la recherche :', error));
        }

        // Écouter les changements dans le champ de recherche
        document.getElementById('search').addEventListener('input', function (e) {
            const query = e.target.value.trim();
            if (query.length >= 2) { // Rechercher uniquement si 2 caractères ou plus
                searchRendezVous(query);
            } else if (query.length === 0) {
                // Recharger tous les rendez-vous si le champ est vide
                searchRendezVous('');
            }
        });
    </script>
</body>
</html>