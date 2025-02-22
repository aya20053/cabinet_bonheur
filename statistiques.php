<?php
session_start();
include 'menu_admin.php'; // Include your navigation menu

// Database connection parameters
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "clinique_bonheur";

// Connect to the database
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the total number of patients
$sql_patients = "SELECT COUNT(*) AS total FROM femmes_enceintes";
$result_patients = $conn->query($sql_patients);
$total_patients = $result_patients->fetch_assoc()['total'];

// Query to get the total number of doctors
$sql_medecins = "SELECT COUNT(*) AS total FROM medecins";
$result_medecins = $conn->query($sql_medecins);
$total_medecins = $result_medecins->fetch_assoc()['total'];

// Query to get the total number of medical notes
$sql_notes = "SELECT COUNT(*) AS total FROM notes";
$result_notes = $conn->query($sql_notes);
$total_notes = $result_notes->fetch_assoc()['total'];

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #3B1C32;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }

        .content {
            padding: 20px;
        }

        .stats-list {
            list-style-type: none;
            padding: 0;
        }

        .stats-list li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        canvas {
            display: block;
            margin: 20px auto;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">Statistiques</div>
    <div class="content">
        <p>Voici quelques statistiques importantes :</p>
        <ul class="stats-list">
            <li>Total des patients : <strong><?php echo $total_patients; ?></strong></li>
            <li>Total des médecins : <strong><?php echo $total_medecins; ?></strong></li>
            <li>Total des notes médicales : <strong><?php echo $total_notes; ?></strong></li>
        </ul>
    </div>
    <div>
        <h4>Graphiques Statistiques</h4>
        <p>Les graphiques suivants illustrent les données collectées :</p>
        <canvas id="myChart" width="400" height="200"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Patients', 'Médecins', 'Notes Médicales'],
            datasets: [{
                data: [<?php echo $total_patients; ?>, <?php echo $total_medecins; ?>, <?php echo $total_notes; ?>],
                backgroundColor: ['rgba(59, 28, 50, 0.6)', 'rgba(153, 102, 255, 0.6)', 'rgba(153, 29, 118, 0.7)'],
                borderColor: ['rgb(59, 28, 50)', 'rgb(89, 42, 184)', 'rgb(153, 29, 118)'],
                borderWidth: 1
            }]
        }
    });
</script>
</body>
</html>
