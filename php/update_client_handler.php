<?php 

    include "sql_connection.php";

    /*
    
    Points conciergerie : 
    - add command : vérifier si le client a des points et les afficher en-dessous de ses informations
    - command 

    ---> Gestion backend 
    - Une commande génère des points
    - La fiche client peut se voir ajouter des points manuellement

    Les points s'ajoutent "comme avant" après chaque commande

    Le changement se passe pendant les commandes 
    - Possibilité d'ajout d'une utilisation de points
    Dans ce cas : si c'est défini, alors on ajoute un nouvel historique de l'utilisation des points

    Donc 3 cas pour le add command :
    - Historique existe et utilisation de points
    - Historique n'existe pas et utilisation de points
    - Pas d'utilisation de points
    Pour le update command : 
    Les 3 mêmes cas mais surtout, l'update des points en fonction des articles précédemment commandé
    Les points sont générés pour les réglements faits !
    (car ils sont inchangés)

    */

    // Gathering the fields values
    $id_client = $_POST['numero']; // useful to update
    $code = $_POST['code']; // useful to switch pages

    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $address = $_POST['address'];
    $mail = $_POST['mail'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $membership = $_POST['membership'];

    
    // Updating in the database
    $query = "UPDATE client SET `name`='$prenom',`surname`='$nom',`postal_address`='$address',`facebook_username`='$facebook',`instagram_username`='$instagram',`email`='$mail',`membership`='$membership',`next_discount`=0 WHERE id_client='$id_client'";

    $result = $connect->query($query);


    // For the phone number
    // Previous amount of phone numbers
    $query = "SELECT * FROM telephone WHERE id_client='$id_client'";
    $result = $connect->query($query);
    $phone_numbers = $result->fetch_all(MYSQLI_ASSOC);
    $how_many_phone_numbers_before = count($phone_numbers);
    $how_many_phone_numbers_now = $_POST['how_many_phone_numbers'];


    // If there are more phone numbers than before
    if ($how_many_phone_numbers_now > $how_many_phone_numbers_before) {

        for ($i = 0; $i < $how_many_phone_numbers_now; $i++) {

            $key = 'phone_number_'.($i+1);
            $phone_number = $_POST[$key];

            if ($i >= $how_many_phone_numbers_before) {
                $query = "INSERT INTO telephone (`id_telephone`, `id_client`, `numero`) VALUES (0,'$id_client','$phone_number')";
                $result = $connect->query($query);
            }

            else {
                $id_telephone = $phone_numbers[$i]['id_telephone'];
                $query = "UPDATE telephone SET `numero`=$phone_number WHERE id_telephone='$id_telephone'";
                $result = $connect->query($query);
            }
        }
    }

    // Same amount
    elseif ($how_many_phone_numbers_now == $how_many_phone_numbers_before) {
        for ($i = 0; $i < $how_many_phone_numbers_now; $i++) {

            $key = 'phone_number_'.($i+1);
            $phone_number = $_POST[$key];

            $id_telephone = $phone_numbers[$i]['id_telephone'];
            $query = "UPDATE telephone SET `numero`=$phone_number WHERE id_telephone='$id_telephone'";
            $result = $connect->query($query);
        }
    }

    // Lower than before
    else {
        for ($i = 0; $i < $how_many_phone_numbers_before; $i++) {

            $key = 'phone_number_'.($i+1);
            $phone_number = $_POST[$key];
            $id_telephone = $phone_numbers[$i]['id_telephone'];

            if ($i >= $how_many_phone_numbers_now) {
                $query = "DELETE FROM telephone WHERE id_telephone='$id_telephone'";
                $result = $connect->query($query);
            }

            else {
                $query = "UPDATE telephone SET `numero`=$phone_number WHERE id_telephone='$id_telephone'";
                $result = $connect->query($query);
            }
        }        
    }

    // For the points
    // Previous amount of points unspent
    $query = "SELECT * FROM points WHERE id_client='$id_client' AND id_cadre_depense_points IS NULL";
    $result = $connect->query($query);
    $points = $result->fetch_all(MYSQLI_ASSOC);
    $how_many_points_before = count($points);
    $how_many_points_now = $_POST['how_many_points'];
    
    for ($i = $how_many_points_before; $i < $how_many_points_now; $i++) {

        $key_points = 'points_unspent_'.($i+1);
        $key_date = 'exp_date_unspent_'.($i+1);
        $nb_points = $_POST[$key_points];
        $exp_date = $_POST[$key_date];

        $query = "INSERT INTO points (`id_points`, `id_client`, `nb_points`, `exp_date`) VALUES (0,'$id_client','$nb_points','$exp_date')";
        $result = $connect->query($query);
        
    }
    

    // Update the membership of the client after a possible adding of points

    // Select the new sum of unspent points
    $query = "SELECT SUM(nb_points) FROM points WHERE id_client='$id_client' AND id_cadre_depense_points IS NULL";
    $result = $connect->query($query);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    $sum_points_unspent = $result[0]['SUM(nb_points)'];

    if ($sum_points_unspent < 300) {
        $membership = "Silver";
    } else if ($sum_points_unspent < 700) {
        $membership = "Gold";
    } else {
        $membership = "Platinum";
    }

    // Update client's membership
    $query = "UPDATE client SET membership='$membership'";
    $result = $connect->query($query);

    header('Location: ../pages/client.php?id='.$code);
    exit();
?>