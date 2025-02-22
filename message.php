<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabinet de Bonheur</title>
        <link rel="icon" type="image/png" href="lgp.png">
        
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #872341;
        }
        .message-container {
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .message-container h2 {
            color: #872341;
        }
        .message-container p {
            color: #555;
        }
        .back-button {
            margin-top: 20px;
        }
        .back-button a {
            color: #872341;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <h2>Inscription réussie</h2>
        <p>Votre compte a été créé, mais il doit être activé par un administrateur. Vous ne pourrez pas vous connecter tant que cela n'est pas fait.</p>
        <div class="back-button">
            <a href="index.html">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>