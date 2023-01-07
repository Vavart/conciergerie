<?php 

    @include "sql_connection.php";

    // -- Gathering the fields values

    // Client
    $id_client = $_POST['numero'];

    // Id_commande
    $id_commande = $_POST['id_commande'];

    // variable to know if the client used points during the command (so he won't earn points for this command)
    $is_client_used_points = false;

    // Check if any points were used in the command
    if (isset($_POST['how_many_unspent_points'])) {
        $how_many_unspent_points = $_POST['how_many_unspent_points'];

        for ($i = 0; $i < $how_many_unspent_points; $i++) {

            $key_use_points = "use_points_".($i+1);

            // if the checkbox is checked get id and reason
            if (isset($_POST[$key_use_points])) {

                $is_client_used_points = true;

                $key_points_id = "id_points_unspent_".($i+1);
                $key_points_rule = "point_use_rule_".($i+1);

                $points_id = $_POST[$key_points_id];
                $points_rule = $_POST[$key_points_rule];

                // check if the client has an history, if not create one
                $query = "SELECT * FROM historique_points WHERE id_client='$id_client'";
                $result = $connect->query($query);
                $result = $result->fetch_all(MYSQLI_ASSOC);

                // If the client doesn't have an history, create one
                if (count($result) === 0) {
                    $query = "INSERT INTO historique_points (`id_historique_points`, `id_client`) VALUES (0,'$id_client')";
                    $result = $connect->query($query);
                }

                // Get the id of the history of the client
                $query = "SELECT id_historique_points FROM historique_points WHERE id_client='$id_client'";
                $result = $connect->query($query);
                $id_historique_points = $result->fetch_all(MYSQLI_ASSOC);
                $id_historique_points = $id_historique_points[0]['id_historique_points'];

                // create a cadre_depense_points linked to this history with the rule
                $query = "INSERT INTO cadre_depense_points (`id_cadre_depense_points`, `id_historique_points`, `motif_utilisation`) VALUES (0,'$id_historique_points','$points_rule')";
                $result = $connect->query($query);

                // select the id recently added to table
                $query = "SELECT id_cadre_depense_points FROM cadre_depense_points WHERE id_historique_points='$id_historique_points' AND motif_utilisation='$points_rule'";
                $result = $connect->query($query);
                $id_cadre_depense_points = $result->fetch_all(MYSQLI_ASSOC);
                $id_cadre_depense_points = $id_cadre_depense_points[0]['id_cadre_depense_points'];

                // update the foreign key 'id_cadre_depense_points' in the point section
                $query = "UPDATE `points` SET `id_cadre_depense_points`='$id_cadre_depense_points' WHERE id_points='$points_id'";
                $result = $connect->query($query);
                
            }
        }
    }

    // If the client used points in the command, he doesn't earn points
    if (!$is_client_used_points) {

        // It needs to be the rest_to_pay before less the rest_to_pay now
        // check if it's > 0 to insert (otherwise it's useless to add 0 points)
        
        // Rest_to_pay before = total_price  LESS all payment amounts linked to this command
        $total_price = $_POST['total_price'];

        $query = "SELECT * FROM liste_paiement_commande NATURAL JOIN paiement WHERE id_commande='$id_commande'";
        $result = $connect->query($query);
        $liste_paiement_commande = $result->fetch_all(MYSQLI_ASSOC);
        
        $total_payment_amount = 0;
        for ($i = 0; $i < count($liste_paiement_commande); $i++) {
            $payment_amount = $liste_paiement_commande[$i]['montant'];   
            $total_payment_amount += $payment_amount;
        }

        $rest_to_pay_before = $total_price - $total_payment_amount;
        
        $rest_to_pay = $_POST['rest_to_pay'];
        $points_amount = $rest_to_pay_before - $rest_to_pay;

        // if > 0 we add points
        if ($points_amount > 0) {       
            $exp_date = date('Y-m-d', strtotime('+1 year'));
        
            $query = "INSERT INTO points (`id_points`, `id_client`, `nb_points`, `exp_date`) VALUES (0,'$id_client','$points_amount','$exp_date')";
            $result = $connect->query($query);
        }
    }


    // Update the membership of the client 
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

    // Command
    // $id_commande = $_POST['id_commande']; already on top of the page
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
    $how_many_products = $_POST['how_many_products']; // new products

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
            
            // If not : insert if the product is not in the history linked to the command
            else {

                $query = "SELECT id_produit FROM historique WHERE id_commande='$id_commande' AND id_produit='$product_id'";
                $result = $connect->query($query);
                $result = $result->fetch_all(MYSQLI_ASSOC);

                if (empty($result)) {
                    $query = "INSERT INTO historique (`id_commande`, `id_produit`, `quantity`, `sold_price`) VALUES ('$id_commande','$product_id','$product_quantity','$product_sold_price')";
                    $result = $connect->query($query);
                }
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

    // Update the date of modification of the invoice linked to this command if there is one
    $query = "SELECT id_facture FROM facture WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $result = $result->fetch_all(MYSQLI_ASSOC);

    if (!empty($result)) {
        $id_facture = $result[0]['id_facture'];
        $query = "UPDATE `facture` SET `facture_date_maj`=curdate() WHERE id_facture='$id_facture'";
        $result = $connect->query($query);
    }

    header('Location: ../pages/command_sheet.php?id='.$id_commande);
    exit();
?>