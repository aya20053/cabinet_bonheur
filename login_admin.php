<?php
session_start(); // Démarrer la session

// Connexion à la base de données avec PDO
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

// Traitement du formulaire de connexion
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $stmt = $pdo->prepare("SELECT id, password, isadmin FROM femmes_enceintes WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                if ($user['isadmin'] == 1) {
                    // Si l'utilisateur est administrateur, démarrer la session
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['isadmin'] = true;
                    header("Location: admin_dashboard.php");
                    exit;
                } else {
                    $error = "Accès refusé. Vous n'êtes pas administrateur.";
                }
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        } else {
            $error = "Aucun compte trouvé avec cet email.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Cabinet de Bonheur</title>
        <link rel="icon" type="image/png" href="lgp.png">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-image: url('cover.jpg'); /* Remplacez par le chemin de votre image */
      background-size: cover;
      background-position: center;
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.8); /* Transparence améliorée */
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      backdrop-filter: blur(5px); /* Effet de flou */
    }

    h2 {
      text-align: center;
      font-size: 30px;
      margin-bottom: 20px;
    }

    .login-container label {
      display: block;
      margin-bottom: 8px;
      color: #555;
      font-size: 14px;
    }

    .login-container input[type="email"],
    .login-container input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
      box-sizing: border-box;
    }

    .login-container input:focus {
      border-color: #872341;
      outline: none;
    }

    .login-container button {
      width: 100%;
      padding: 12px;
      background-color: #872341;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      transition: background-color 0.3s ease;
      cursor: pointer;
    }

    .login-container button:hover {
      background-color: #B39188;
      color:#872341;
    }

    .login-container p {
      text-align: center;
      margin-top: 10px;
    }

    .login-container a {
      color: #872341;
      text-decoration: none;
    }

    .login-container a:hover {
      text-decoration: underline;
    }

    /* Style pour le bouton de retour */
    .back-button {
            display: flex;
            justify-content: flex-start; /* Aligner à gauche */
            margin-top: 20px;
        }
        .back-button a {
            width: 100%;
            padding: 12px;
            background-color: #872341;
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
            background-color: #B39188;
            color:#872341;
        }
        p a {
            color: #872341
        }
  </style>
</head>
<body>
    <div class="login-container">
        <form action="login_admin.php" method="POST">
            <h2><i class="fas fa-sign-in-alt"></i> Se connecter</h2>
            <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
            <label><i class="fas fa-envelope"></i> Email :</label>
            <input type="email" name="email" required>
            <label><i class="fas fa-lock"></i> Mot de passe :</label>
            <input type="password" name="password" required>
            <button type="submit"><i class="fas fa-sign-in-alt"></i> Se connecter</button>
            <div class="back-button">
          <a href="index.html"><i class="fas fa-arrow-left"></i> Retour à l'accueil</a>
        </div>
        </form>
    </div>
</body>
</html>