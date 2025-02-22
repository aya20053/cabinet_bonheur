<?php
session_start();
include 'menu_admin.php';
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Database connection
$host = "localhostfinityfree.com";
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

// Fetch the list of doctors
$doctorsQuery = "SELECT id, prenom, nom FROM medecins";
$doctorsStmt = $pdo->prepare($doctorsQuery);
$doctorsStmt->execute();
$doctors = $doctorsStmt->fetchAll();

// Handle file upload and note addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $description = trim($_POST['description']);  // Get the note description
    $medecin_id = $_POST['medecin_id'];           // Get the selected doctor's ID

    // Check if the note already exists
    $checkQuery = "SELECT COUNT(*) FROM notes WHERE description = ?";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->execute([$description]);
    
    if ($checkStmt->fetchColumn() > 0) {
        echo "<p>Cette note existe déjà.</p>";
    } else {
        // Handle file upload
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['pdf_file']['tmp_name'];
            $fileName = $_FILES['pdf_file']['name'];
            $uploadFileDir = './uploaded_notes/';
            $dest_path = $uploadFileDir . basename($fileName);

            // Ensure the upload directory exists
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true); // Create directory if it doesn't exist
            }

            // Move the file to the upload directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Insert note into the database with the file path
                $stmt = $pdo->prepare("INSERT INTO notes (medecin_id, note, description, date_ajout) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$medecin_id, $fileName, $description]);
                echo "<p>Note ajoutée avec succès !</p>";
            } else {
                echo "<p>Erreur lors du téléchargement du fichier.</p>";
            }
        }
    }
}

// Handle search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$query = "
    SELECT n.*, m.prenom AS medecin_prenom, m.nom AS medecin_nom
    FROM notes n
    JOIN medecins m ON n.medecin_id = m.id
    WHERE n.description LIKE :search
    ORDER BY n.date_ajout DESC
";
$stmt = $pdo->prepare($query);
$stmt->execute(['search' => "%$search%"]);
$notes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes Médicales</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* General Styles */
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
        h1 {
            text-align: center;
            color:#3B1C32;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 20px;
            margin-top: 30px;
            color:#3B1C32;
        }

        /* Search Form */
        .search-form {
            margin-bottom: 20px;
        }

        .search-form input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        /* Form Styles */
        form {
            margin-bottom: 30px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            color:rgb(59, 28, 50);
        }

        form input[type="text"],
        form input[type="file"],
        form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .btn-container {
    text-align: center;
}

.btn1 {
            background-color: #3B1C32;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            text-align: center;

        }
        .btn1:hover {
            background-color: #F4CCE9;
            color: #3B1C32;
        }
        /* Button Styles */
        button {
            background-color: #F4CCE9;
            color: #3B1C32;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color:rgb(125, 28, 74);
            color: white;        }

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
            background-color:  #A64D79;
            color: white;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                margin: 20px;
                padding: 15px;
            }

            h1 {
                font-size: 20px;
            }

            h2 {
                font-size: 18px;
            }

            button {
                width: 100%;
            }
        }
         /* Hide sections initially */
         .hidden {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h1><i class="fas fa-file-medical"></i> Notes Médicales</h1>

    <!-- Buttons -->
    <div class="btn-container">
        <button class="btn1" onclick="showSection('add-note')"><i class="fas fa-plus"></i> Ajouter une note</button>
        <button class="btn1" onclick="showSection('notes-list')"><i class="fas fa-list"></i> Afficher les notes publiées</button>
    </div>

    <!-- Form to add notes -->
    <div id="add-note" class="hidden">
        <form method="post" action="" enctype="multipart/form-data">
            <label for="medecin_id">Choisir le Médecin :</label>
            <select name="medecin_id" required>
                <option value="">Sélectionnez un médecin</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?= htmlspecialchars($doctor['id']) ?>"><?= htmlspecialchars($doctor['prenom'] . ' ' . $doctor['nom']) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="description">Description de la note :</label>
            <input type="text" name="description" required>
            <label for="pdf_file">Ajouter un fichier (PDF seulement) :</label>
            <input type="file" name="pdf_file" accept="application/pdf" required>
            <button type="submit" name="submit"><i class="fas fa-save"></i> Ajouter la note</button>
        </form>
    </div>

    <!-- Notes table -->
    <div id="notes-list" class="hidden">
    
        <h2>Historique des Notes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Fichier</th>
                    <th>Date d'ajout</th>
                    <th>Médecin</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note): ?>
                    <tr>
                        <td><?= htmlspecialchars($note['id']) ?></td>
                        <td><?= htmlspecialchars($note['description']) ?></td>
                        <td><a href="./uploaded_notes/<?= htmlspecialchars($note['note']) ?>" target="_blank"><i class="fas fa-file-pdf"></i> <?= htmlspecialchars($note['note']) ?></a></td>
                        <td><?= htmlspecialchars($note['date_ajout']) ?></td>
                        <td><?= htmlspecialchars($note['medecin_prenom'] . ' ' . $note['medecin_nom']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function showSection(sectionId) {
        document.getElementById('add-note').style.display = 'none';
        document.getElementById('notes-list').style.display = 'none';

        document.getElementById(sectionId).style.display = 'block';
    }
</script>

</body>
</html>