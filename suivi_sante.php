<?php
session_start();
include 'menu.php';
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

// Requête SQL pour récupérer les notes et les noms des médecins
$query = "SELECT n.id, m.nom AS medecin, n.note, n.description, n.date_ajout
          FROM notes n
          JOIN medecins m ON n.medecin_id = m.id";

// Exécution de la requête
$stmt = $pdo->query($query);

// Vérifiez si la requête a renvoyé des résultats
$notes = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi Santé</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-image: url('1.jpg');
            background-size: cover;
            background-position: center;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 30px auto;
            background-color: white;
        }
        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-bar input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 15px;
        }
        .card-header {
            font-weight: bold;
            background-color: #872341;
            color: white;
            padding: 10px;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 15px;
        }
        .card-footer {
            font-size: 14px;
            color: #666;
            padding: 10px;
            background: #f1f1f1;
            border-radius: 0 0 10px 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #B39188;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .btn:hover {
            background-color: rgb(184, 149, 139);
        }
        .text-center {
            text-align: center;
        }
        h2{ text-align: center;
            color: #872341;
            margin-top: 40px;
        }
                .search-bar {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ccc;
    border-radius: 25px;
    padding: 10px 15px;
    background: #fff;
    width: 50%;
    margin: 0 auto;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.search-bar i {
    font-size: 18px;
    color: #872341;
    padding: 8px;
    border-radius: 50%;
    margin-right: 10px;
    transition: all 0.3s ease;
}



.search-bar input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: none;
    outline: none;
    background: transparent;
}
  @media screen and (max-width: 768px) {
    body {
        background-repeat: no-repeat; /* Empêcher la répétition */
        background-position: center top; /* Garder l'image à son origine */
            background-image: url('ee.jpg');
            background-size: cover ; /* L'image sera contenue dans la fenêtre */
            overflow: hidden;
}}
    </style>
</head>
<body>
<div class="container">    
    <h2 ><i class="fas fa-notes-medical"></i> Suivi des Notes Médicales</h2>

    <div class="search-bar">
      <i class="fas fa-search"></i>

            <input type="text" id="search" class="form-control" placeholder="Rechercher par nom de médecin ou description">
        </div>
    <div id="notes-container">
        <?php
        if (!empty($notes)) {
            foreach ($notes as $row) {
                echo '<div class="card">
                        <div class="card-header">Médecin : <span>' . htmlspecialchars($row['medecin']) . '</span></div>
                        <div class="card-body">
                            <p>📝 "' . nl2br(htmlspecialchars($row['description'])) . '"</p>
                            <p><a href="./uploaded_notes/' . htmlspecialchars($row['note']) . '" target="_blank" class="btn">Voir le PDF</a></p>
                        </div>
                        <div class="card-footer">Ajoutée le ' . htmlspecialchars($row['date_ajout']) . '</div>
                      </div>';
            }
        } else {
            echo '<p class="text-center">Aucune note trouvée.</p>';
        }
        ?>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#search').on('input', function() {
        let searchTerm = $(this).val();
        $.ajax({
            url: 'fetch_notes.php',
            method: 'GET',
            data: { search: searchTerm },
            success: function(data) {
                $('#notes-container').html(data);
            }
        });
    });
});
</script>
</body>
</html>
