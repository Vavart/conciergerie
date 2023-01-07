<?php

    include "../php/sql_connection.php";
    $id_commande = $_POST['id_commande'];

    // Check if an invoice is already created for the command

    // If yes just redirection, else creation
    $query = "SELECT id_facture FROM facture WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $result = $result->fetch_all(MYSQLI_ASSOC);


    // if there is no invoice, create one
    if (empty($result)) {
        // create a code for the invoice
        // get the code of the command and replace the C by an F

        $commande_code = $_POST['code_commande'];
        list($date, $cmd, $code) = explode("-", $commande_code);
        $invoice_code = str_replace("C", "F", $code);
        $code = $date."-".$cmd."-".$invoice_code;

        $query = "INSERT INTO facture (`id_facture`, `id_commande`, `facture_date`, `numero`) VALUES (0,'$id_commande',curdate(),'$code')";
        $result = $connect->query($query);

        // get the id of what we created
        $query = "SELECT id_facture FROM facture WHERE numero='$code'";
        $result = $connect->query($query);
        $id_facture = $result->fetch_all(MYSQLI_ASSOC);
        $id_facture = $id_facture[0]['id_facture'];

    } 
    // else we get the id of the invoice
    else {
        $id_facture = $result[0]['id_facture'];
    }

    header('Location: ../pages/invoice.php?id='.$id_facture);
    exit();
?>