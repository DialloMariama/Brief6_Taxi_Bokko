<?php

require_once('IUtilisateur.php');
// require('dataBase.php');


class Utilisateur implements IUtilisateur{
    private $nom;
    private $prenom;
    private $tel;
    private $email;
    private $password;
    

    public function setNom($nom){
        if (!preg_match('/^[a-zA-Z \']{2,}$/', $nom)) {
            throw new Exception("Le nom est invalide. Il doit contenir uniquement des lettres");
        }

        $this->nom= $nom;
    }
    public function getNom(){
        return $this->nom;
    }

    public function setPrenom($prenom){
        if (!preg_match('/^[a-zA-Z \']{3,}$/', $prenom)) {
            throw new Exception("Le prenom est invalide. Il doit contenir uniquement des lettres.");
        }
        $this->prenom= $prenom;
    }
    public function getPrenom(){        return $this->prenom;
    }

    public function setTel($tel){
        if (!preg_match("/^(70|75|76|77|78)[0-9]{7}$/", $tel)) {
            throw new Exception("Le numéro de téléphone doit contenir exactement 9 chiffres et respecter les operateurs existant par ex: 70.");
        }
        $this->tel= $tel;
    }
    public function getTel(){
        return $this->tel;
    }

    public function setEmail($email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            $this->email= $email;            
        }else{
            throw new Exception("Error Processing Request");

        }        
    }
    public function getEmail(){
        return $this->email;
    }

    public function setMotDetPasse($password){
        if (strlen($password) == 8 && is_string($password)) {

            $this->password= $password;
        }else{
            throw new Exception("Le mot de passe doit comporeter exactement 8 caractéres");
            
        }
       
    }
    public function getMotDetPasse(){
        return $this->password;
    }
    
    public function __construct($nom, $prenom, $tel, $email, $password){
        $this->setNom($nom);
        $this->setprenom($prenom);
        $this->settel($tel);
        $this->setEmail($email);
        $this->setMotDetPasse($password);

    }


    public function inscriptionUtilisateur($db) {
        $nom = $this->getNom();
        $prenom = $this->getPrenom();
        $tel = $this->getTel();
        $email = $this->getEmail();
        $password = $this->getMotDetPasse();

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $date_inscription = date("Y-m-d H:i:s");

        $sql = "INSERT INTO utilisateurs (nom, prenom, tel, email, password, date_inscription) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);
        $stmt->execute([$nom, $prenom, $tel, $email, $passwordHash, $date_inscription]);

        return true; 
    }
    public static function connexionUtilisateur($email, $password,$db) {
        
        //global $db;

        $sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilisateur && password_verify($password, $utilisateur['password'])) {
            $_SESSION['utilisateur'] = $utilisateur;
            echo "Coucou ";
            header('Location: ProfilUtilisateur.php'); 
        } else {
      
            $messageErreur = "Adresse e-mail ou mot de passe incorrect.";
            echo $messageErreur;
        }
    }
    public static function consulterListeUtilisateurs() {
        global $db; 

        $sql = "SELECT * FROM utilisateurs";
        $stmt = $db->query($sql);
        $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $utilisateurs;
    }
}

// $user = new Utilisateur("DIALLO","Mariama", 774958198,"maridialloisidk@gmail.com", "12345678");

// $user->inscriptionUtilisateur();


?>