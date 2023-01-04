<?php

    @include "sql_connection.php";

    // Get all necessary informations

    // Command & Client
    $id_commande = $_POST['id_commande'];
    $id_client = $_POST['numero'];

    // id_points 
    $query = "SELECT id_points FROM commande WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $id_points = $result->fetch_all(MYSQLI_ASSOC);
    $id_points = $id_points[0]['id_points'];

    // delete liste_client_commande
    $query = "DELETE FROM `liste_client_commande` WHERE id_commande='$id_commande' AND id_client='$id_client'";
    $result = $connect->query($query);

    // delete historique
    $how_many_products = $_POST['how_many_products'];
    for ($i = 0; $i < $how_many_products; $i++) {
        $key_product_id = "product_id_".($i+1);
        $product_id = $_POST[$key_product_id];

        $query = "DELETE FROM historique WHERE id_commande='$id_commande' AND id_produit='$product_id'";
        $result = $connect->query($query);
    }
    
    // delete liste_paiement_commande and delete paiement
    $how_many_payments = $_POST['how_many_payments'];
    for ($i = 0; $i < $how_many_payments; $i++) {
        $key_payment_id = "payment_id_".($i+1);
        $payment_id = $_POST[$key_payment_id];

        // liste_paiement_command
        $query = "DELETE FROM liste_paiement_commande WHERE id_commande='$id_commande' AND id_paiement='$payment_id'";
        $result = $connect->query($query);

        // paiement
        $query = "DELETE FROM paiement WHERE id_paiement='$payment_id'";
        $result = $connect->query($query);
    }

    // delete commande
    $query = "DELETE FROM commande WHERE id_commande='$id_commande'";
    $result = $connect->query($query);

    // delete points
    $query = "DELETE FROM points WHERE id_points='$id_points'";
    $result = $connect->query($query);

    header('Location: ../pages/search_commands.php');
    exit();
?>