<?php
session_start(); 

require_once('Utilisateur.php'); 
require_once('dataBase.php'); 
date_default_timezone_set('Africa/Dakar');

$erreurs=[];

$messageErreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['suivant'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
     
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) == 8) {
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['password'] = $_POST['password'];
        } else {
            array_push($erreurs, "Entrez un email valide");
            array_push($erreurs, "Entrez un mot de passe valide de 8 caractères.");
        }
        if (!empty($erreurs)) {
            echo "<ul>";
            foreach ($erreurs as $erreur) {
                echo "<li style='color: red;'>$erreur</li>";
            }
            echo "</ul>";
        }
    }
 } 
 if (isset($_POST['inscription'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $tel = $_POST['tel'];
        

    
        $email = $_SESSION['email'];
        $password = $_SESSION['password'];
        var_dump($email);
        var_dump($password);


       
        $regex_nom = "/^[a-zA-Z ']{2,}$/";
        $regex_prenom = "/^[a-zA-Z ']{3,}$/";

       
        if (!preg_match($regex_nom, $_POST["nom"])) {
            $erreurs[] = "Le nom est invalide.";
        }
        if (!preg_match($regex_prenom, $_POST["prenom"])) {
            $erreurs[] = "Le prénom est invalide.";
        }
        if (!preg_match("/^(70|75|76|77|78)[0-9]{7}$/", $tel)) {
            array_push($erreurs, "Le numéro de téléphone doit contenir exactement 9 chiffres et respecter les operateurs existant par ex: 70.");
        }

        if (!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
            array_push($erreurs, "Entrez un email valide");
        }
        if (!empty($erreurs)) {
            echo "<ul>";
            foreach ($erreurs as $erreur) {
                echo "<li style='color: red;'>$erreur</li>";
            }
            echo "</ul>";
        } else {
            $email_exist = "SELECT COUNT(*) FROM utilisateurs WHERE email = ?";
            $stmt_email_exist = $db->prepare($email_exist);
            $stmt_email_exist->execute([$email]);
            $count_email_exist = $stmt_email_exist->fetchColumn();

            $tel_exist = "SELECT COUNT(*) FROM utilisateurs WHERE tel = ?";
            $stmt_tel_exist = $db->prepare($tel_exist);
            $stmt_tel_exist->execute([$tel]);
            $count_tel_exist = $stmt_tel_exist->fetchColumn();
            
            $date_inscription = date("Y-m-d H:i:s");


            if ($count_email_exist > 0) {
                // array_push($erreurs, "Cet e-mail est déjà enregistré.");
                echo "Cet e-mail est déjà enregistré.";
            }

            if ($count_tel_exist > 0) {
                array_push($erreurs, "Ce numéro de téléphone est déjà enregistré.");
            }

            if ($count_email_exist === 0 && $count_tel_exist === 0) {
    
            $nouvelUtilisateur = new Utilisateur($nom, $prenom, $tel, $email, $password);
            
            $inscriptionReussie = $nouvelUtilisateur->inscriptionUtilisateur($db);
    
            if ($inscriptionReussie) {
                header('location: connexion.php');
                echo "Inscription reussie";
                exit;
            } else {
                echo "Inscription echouée";
                
            }
    }
}
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


    <section>
        <form action="" method="post" name="formulaire1">
            <h2><strong>Inscription</strong></h2>
            <p>Votre chauffeur en un clic !</p>
            <button type="submit" class="up">Continuer avec Facebook</button>
            <hr class="hr1">
            <p class="p2">ou</p>
            <hr class="hr2">
            <label for="Email">EMAIL</label>
            <input type="email" name="email" id="email" placeholder="email" >
            <label for="Password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Mot de passe" >

            <div class="foot">
                <p class="P2"><strong><a href="connexion.php">J'ai déjà un compte</a></strong></p>
                <button type="submit" class="down" name="suivant">Suivant ➜</button>
            </div>
        </form>
        <form action="" method="post" name="formulaire2">
            <h2><strong>Inscription</strong></h2>
            <p>Finalisez votre inscription en renseignant les informations manquantes</p>
            <div class="name-fields">

                <div class="form-group">
                    <label for="Prenom">PRENOM</label>
                    <input type="text" name="prenom" id="prenom" placeholder="Prénom" >
                </div>
                <div class="form-group">
                    <label for="Nom">NOM</label>
                    <input type="text" name="nom" id="nom" placeholder="Nom" >
                </div>
            </div>
            <div id="tel-container">
                <div id="tel-text">+221</div>
                <div id="tel-input">
                    <label for="tel">TELEPHONE</label>
                    <input type="tel" name="tel" id="tel" placeholder="Téléphone">
                </div>
            </div>
            <label for="Email">EMAIL</label>
            <input type="email" name="email" id="email12" placeholder="email"readonly>
            <button type="submit" class="left" name="inscription">S'inscrire ➜</button>
        </form>


    </section>
</body>

</html>