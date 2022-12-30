<?php 

    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $db = 'conciergerie';

    // On établit la connexion
    $connect = mysqli_connect($servername, $username, $password, $db);

    // On vérifie la connexion
    if(!$connect){
        die('Erreur : ' .mysqli_connect_error());
    }
    // echo 'Connexion réussie';

?>