<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compléter Profil</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-image: url('cover.jpg'); /* Remplacez par le chemin de votre image */
      background-size: cover;
      background-position: center;
      overflow: hidden; /* Empêche le défilement de la page */
    }

    form {
      max-width: 700px;
      width: 90%; /* Ajustement pour les petits écrans */
      max-height: 80vh; /* Limite la hauteur du formulaire */
      padding: 30px;
      background-color: rgba(255, 255, 255, 0.4); /* Transparence */
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(5px); /* Effet de flou */
      overflow-y: auto; /* Ajoute un défilement vertical si nécessaire */
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: 600;
      color: #333;
    }

    input[type="text"],
    input[type="date"],
    input[type="number"],
    textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 25px;
      border: 1px solid #ced4da;
      border-radius: 5px;
      box-sizing: border-box;
      background-color: rgba(255, 255, 255, 0.8); /* Légère transparence pour les champs */
    }

    input:focus,
    textarea:focus {
      border-color: #35b4c6;
      outline: none;
    }

    button {
      display: inline-block;
      padding: 12px 25px;
      background-color: #35b4c6;
      color: #ffffff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #529ba4;
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>
  <form action="maj_profil.php" method="POST">
  <legend>
  <h1><i class="fas fa-user-edit"></i> Compléter le Profil</h1>
  </legend>
    <label>Date de Naissance :</label>
    <input type="date" name="date_naissance" required>

    <label>Téléphone :</label>
    <input type="text" name="telephone" required>

    <label>Adresse :</label>
    <textarea name="adresse" required></textarea>

    <label>Groupe Sanguin :</label>
    <input type="text" name="groupe_sanguin" required>

    <label>Date de Dernières Règles :</label>
    <input type="date" name="date_dernieres_regles" required>

    <label>Date Prévue d'Accouchement :</label>
    <input type="date" name="date_prevue_accouchement" required>

    <label>Nombre de Grossesses Précédentes :</label>
    <input type="number" name="nombre_grossesses_precedentes" required>

    <label>Antécédents Médicaux :</label>
    <textarea name="antecedents_medicaux" required></textarea>

    <label>Allergies :</label>
    <textarea name="allergies" required></textarea>

    <button type="submit">Mettre à jour</button>
  </form>
</body>
</html>