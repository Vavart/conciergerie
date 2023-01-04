<?php 

    @include "sql_connection.php";

    // -- Gathering the fields values

    // Client
    // $id_client = $_POST['numero'];

    // Command
    $id_commande = $_POST['id_commande'];
    $command_status = $_POST['status'];
    $command_delivery_price = $_POST['delivery_fee'];
    $command_service_price = $_POST['service_fee'];
    $command_note = htmlspecialchars($_POST['command_note'], ENT_QUOTES);

    $query = "UPDATE commande SET `status`='$command_status',`delivery_price`='$command_delivery_price',`service_price`='$command_service_price',`note`='$command_note' WHERE id_commande='$id_commande'";
    $result = $connect->query($query);

    // Products

    // get the previous list of products linked to the command
    $query = "SELECT * FROM historique WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $previous_products = $result->fetch_all(MYSQLI_ASSOC);

    // Delete the products who were before and not now 
    for ($i = 0; $i < count($previous_products); $i++) {
        $previous_product_id = $previous_products[$i]['id_produit'];
        $is_in_new_command = false;


        for ($j = 0; $j < $how_many_products; $j++) {
            $key_product_id = "product_id_".($i+1);
            $product_id = $_POST[$key_product_id];

            // If it is do nothing
            if ($previous_product_id == $product_id) {
                $is_in_new_command = true;
            }
        }

        // Test to remove it from the db
        if (!$is_in_new_command) {
            $query = "DELETE FROM historique WHERE id_commande='$id_commande' AND id_produit='$previous_product_id'";
            $result = $connect->query($query);
        }
    }

    $how_many_products = $_POST['how_many_products'];
    for ($i = 0; $i < $how_many_products; $i++) {

        // Get the useful info of the product (id, sold_price, quantity)
        $key_product_id = "product_id_".($i+1);
        $product_id = $_POST[$key_product_id];

        $key_product_sold_price = "product_sold_price_".($i+1);
        $product_sold_price = $_POST[$key_product_sold_price];

        $key_product_quantity = "product_quantity_".($i+1);
        $product_quantity = $_POST[$key_product_quantity];

        // Check if the product was in the previous command
        for ($j = 0; $j < count($previous_products); $j++) {

            // If it is : update
            if ($product_id == $previous_products[$j]['id_produit']) {
                
                $query = "UPDATE historique SET `quantity`='$product_quantity',`sold_price`='$product_sold_price' WHERE id_commande='$id_commande' AND id_produit='$product_id'";
                $result = $connect->query($query);
            } 
            
            // If not : insert
            else {
                $query = "INSERT INTO historique (`id_commande`, `id_produit`, `quantity`, `sold_price`) VALUES ('$id_commande','$product_id','$product_quantity','$product_sold_price')";
                $result = $connect->query($query);
            }
        }
    }
    


    // Payment amount & payment method
    // Create all payment methods and amount
    $how_many_payments = $_POST['how_many_payments'];
    $how_many_payments_already_present = $_POST['how_many_payments_already_present'];
    for ($i = $how_many_payments_already_present; $i < $how_many_payments; $i++) {

        // Payment method
        $key = 'payment_method_'.($i+1);
        $payment_method = $_POST[$key];

        // See if the method already exists
        $query = "SELECT id_mode_paiement FROM mode_paiement WHERE mode='$payment_method'";
        $result = $connect->query($query);
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        // if it already exists we select the id_paiement
        if (!empty($rows)) {
            $id_mode_paiement = $rows[0]['id_mode_paiement'];
        }

        // we add it to the db and get the id for later
        else {
            $query = "INSERT INTO mode_paiement (`id_mode_paiement`, `mode`) VALUES (0 ,'$payment_method')";
            $result = $connect->query($query);

            $query = "SELECT id_mode_paiement FROM mode_paiement WHERE mode='$payment_method'";
            $result = $connect->query($query);
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            $id_mode_paiement = $rows[0]['id_mode_paiement'];
        }

        // Amounts
        $key = 'payment_amount_'.($i+1);
        $payment_amount = $_POST[$key];

        $query = "INSERT INTO paiement (`id_paiement`, `id_mode_paiement`, `montant`, `payment_date`) VALUES (0,'$id_mode_paiement','$payment_amount',curdate())";
        $result = $connect->query($query);

        // get the id of the recent payment
        $query = "SELECT id_paiement FROM paiement WHERE id_mode_paiement='$id_mode_paiement' AND montant='$payment_amount' AND payment_date=curdate()";
        $result = $connect->query($query);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $id_paiement = $rows[0]['id_paiement'];

        // link them with the command (payment - command)
        $query = "INSERT INTO liste_paiement_commande (`id_paiement`, `id_commande`) VALUES ('$id_paiement','$id_commande')";
        $result = $connect->query($query);
    }


    header('Location: ../pages/command_sheet.php?id='.$id_commande);
    exit();
?>