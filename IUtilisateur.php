<?php
interface IUtilisateur{
    public function inscriptionUtilisateur($db);
    public static function connexionUtilisateur($email, $password, $db);
    public static function consulterListeUtilisateurs();


}