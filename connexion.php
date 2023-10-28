<?php
session_start();
require_once('Utilisateur.php');
date_default_timezone_set('Africa/Dakar');

// require_once('bd.php');
require_once('dataBase.php'); 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

   Utilisateur::connexionUtilisateur($email, $password,$db);
    


}
?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>E-Taxibokko</title>
</head>
<body>
    
<form action="" method="post">
            <h2><strong>Connexion</strong></h2>
            <p>Votre chauffeur en un clic !</p>
            <label for="Email">EMAIL</label>
            <input type="email" name="email" id="email" placeholder="email">
            <label for="Password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Mot de passe" >

            <button type="submit" class="down" name="connexion">Se connecter</button>
            <?php
                if (isset($erreur)) {
                    echo "<p style='color: red;'>$erreur</p>";
                }
            ?>
        </form>
</body>
</html>
