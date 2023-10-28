<?php

class Database{
    
public function connexion(){
        try
    {
        $db = new PDO('mysql:host=localhost;dbname=taxibokko;charset=utf8', 'root', '',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],);
        return $db;
    }
    catch (Exception $e)
    {
            die('Erreur : ' . $e->getMessage());
    }
    }
}

$database = new Database();
$db = $database->connexion();