<?php
    function connexion(){
        // Connexion à MySQL avec affichage des résultats en UTF-8
        // avec gestion des erreurs potentielles

        // Définition des paramètres 4 paramètres
            $serveur = "localhost" ;
            $bd = "KanjiSensei" ;
            $login = "root" ;
            $mdp = "root";

        try {
            $sql = new PDO('mysql:host='.$serveur.';dbname='.$bd, $login, $mdp,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")) ;
            return $sql;
        }

        catch(PDOException $e) {
             echo "Erreur de connexion à la base de données ".$e->getMessage() ;
             die();
        }

    }

?>
