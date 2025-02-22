<?php
ob_start();

include 'menu_admin.php';

// Connexion à la base de données
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "clinique_bonheur"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Gestion de l'ajout de médecins
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $specialite = trim($_POST['specialite']);

    // Vérifier que les champs ne sont pas vides
    if (!empty($nom) && !empty($prenom) && !empty($specialite)) {
        $sql = "INSERT INTO medecins (nom, prenom, specialite) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nom, $prenom, $specialite);
        
        if ($stmt->execute()) {
            $message = "Médecin ajouté avec succès.";
        } else {
            $message = "Erreur lors de l'ajout : " . $conn->error;
        }
        $stmt->close();
    } else {
        $message = "Tous les champs sont obligatoires.";
    }
}

// Gestion de la recherche
$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

// Récupérer les médecins de la base de données
$sql = "SELECT * FROM medecins WHERE nom LIKE ? OR prenom LIKE ? OR specialite LIKE ?";
$searchTerm = "%$search%";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Médecins</title>
    <head>
    <link rel="stylesheet" href="menu_admin.php"> <!-- Fichier spécifique au menu, assurez-vous que ce fichier est chargé en dernier -->
</head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Container */
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Headings */
        h2 {
            text-align: center;
            color: #3B1C32;
            margin-bottom: 20px;
            font-size: 30px;
        }

        /* Button Container */
        .button-container {
            text-align: center;
            margin-bottom: 20px;
        }

        button {
            background-color: #3B1C32;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 0 10px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #F4CCE9;
            color: #3B1C32;
        }

        /* Form Styles */
        form {
            margin-bottom: 30px;
        }

        form input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .btn {
            background-color: #F4CCE9;
            color: #3B1C32;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: rgb(125, 28, 74);
            color: white;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            color: rgb(59, 28, 50);
        }
/* Search Bar */
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
        /* Table Styles */
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
            background-color: #A64D79;
            color: white;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        /* Message Styles */
        .message {
            color: green;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 15px;
            }

           

            
        }
        
        
        /* Hide sections initially */
        .form-container, .table-container {
            display: none; /* Masquer par défaut */
        }

        @media (max-width: 600px) {
    /* Ajuster les éléments pour les petits écrans */
    .container {
        margin: 20px;
        padding: 15px;
    }

    /* Rendre les boutons plus larges */
    button {
        width: 100%;
        padding: 12px;
        font-size: 18px;
    }

    /* Redimensionner la barre de recherche */
    .search-bar {
        width: 100%;
        padding: 10px;
    }

    .search-bar input {
        width: 80%; /* Réduire la largeur de l'input pour un meilleur alignement avec l'icône */
    }

    .search-bar i {
        font-size: 20px; /* Augmenter la taille de l'icône pour une meilleure visibilité */
    }

    /* Redimensionner les champs de formulaire */
    form input[type="text"] {
        width: 100%;
        padding: 12px;
        font-size: 16px;
    }

    /* Augmenter la taille du texte et ajuster les marges */
    h2 {
        font-size: 24px;
        margin-bottom: 15px;
    }

    /* Changer l'apparence de la table */
    table th, table td {
        font-size: 14px;
        padding: 10px;
    }

    table {
        font-size: 14px;
        margin-top: 10px;
    }
}

    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-user-md"></i> Gestion des Médecins</h2>
    <!-- Message d'ajout -->
    <?php if (isset($message)): ?>
        <div class="message"><?= $message; ?></div>
    <?php endif; ?>

    <!-- Boutons d'action -->
    <div class="button-container">
        <button id="btn-ajouter" onclick="showForm()">
            <i class="fas fa-user-plus"></i> Ajouter Médecin
        </button>
        <button id="btn-afficher" onclick="showTable()">
            <i class="fas fa-users"></i> Afficher Médecins
        </button>
    </div>

    <!-- Formulaire d'ajout -->
    <div class="form-container" id="formContainer">
        <form method="post" action="">
            <label for="nom">Nom</label>
            <input type="text" name="nom" placeholder="Nom" required>

            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" placeholder="Prénom" required>

            <label for="specialite">Spécialité</label>
            <input type="text" name="specialite" placeholder="Spécialité" required>

            <button class="btn" type="submit" name="ajouter">
                <i class="fas fa-plus"></i> Ajouter
            </button>
        </form>
    </div>

    <!-- Barre de recherche -->
    <div class="search-container" id="searchContainer" style="display: none;">
        <div class="search-bar">
            <i class="fas fa-search"></i> <!-- Icône de recherche -->
            <input type="text" name="search" placeholder="Rechercher par nom, prénom, email, etc." value="<?= htmlspecialchars($search) ?>" autofocus>
            <button type="submit" style="display: none;"></button>
        </div>
    </div>

    <!-- Table des médecins -->
    <div class="table-container" id="tableContainer">
        <table>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Spécialité</th>
            </tr>
            <?php if ($results): ?>
                <?php while ($row = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['nom']); ?></td>
                    <td><?= htmlspecialchars($row['prenom']); ?></td>
                    <td><?= htmlspecialchars($row['specialite']); ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucun médecin trouvé.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<?php
// Fermer la connexion
$stmt->close();
$conn->close();
?>

<script>
    function showForm() {
        document.getElementById('formContainer').style.display = 'block';
        document.getElementById('tableContainer').style.display = 'none';
        document.getElementById('searchContainer').style.display = 'none'; // Masquer la barre de recherche
    }

    function showTable() {
        document.getElementById('formContainer').style.display = 'none';
        document.getElementById('tableContainer').style.display = 'block';
        document.getElementById('searchContainer').style.display = 'flex'; // Afficher la barre de recherche
    }
</script>

</body>
</html>