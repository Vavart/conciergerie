<?php 

    include "sql_connection.php";

    // check if there is a client
    if (!isset($_POST['numero']) || $_POST['numero'] == "") {
        header('Location: ../pages/index.html?msg=error'); // error
        exit();
    }
    
    // check if there is at least product
    if (!isset($_POST['product_name_1']) || $_POST['product_name_1'] == "") {
        header('Location: ../pages/index.html?msg=error'); // error
        exit();
    }

    // check if there is a payment
    if (!isset($_POST['payment_amount_1']) || $_POST['payment_amount_1'] == "") {
        header('Location: ../pages/index.html?msg=error'); // error
        exit();
    }

    // Create points and link it to the client
    $id_client = $_POST['numero'];

    // Check if any points were used in the command
    if (isset($_POST['how_many_unspent_points'])) {
        $how_many_unspent_points = $_POST['how_many_unspent_points'];

        for ($i = 0; $i < $how_many_unspent_points; $i++) {

            $key_use_points = "use_points_".($i+1);

            // if the checkbox is checked get id and reason
            if (isset($_POST[$key_use_points])) {

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
            die();
        }
    }

    die();

    $total_price = $_POST['total_price'];
    $points_amount = $total_price;
    $exp_date = date('Y-m-d', strtotime('+1 year'));

    $query = "INSERT INTO points (`id_points`, `id_client`, `nb_points`, `exp_date`) VALUES (0,'$id_client','$points_amount','$exp_date')";
    $result = $connect->query($query);

    // Create the command (with the points ref)
    // Select the id of the points lately added
    $query = "SELECT id_points FROM points WHERE id_client='$id_client' AND exp_date='$exp_date' AND nb_points='$points_amount'";
    $result = $connect->query($query);
    $id_points = $result->fetch_all(MYSQLI_ASSOC);
    $id_points = $id_points[0]['id_points'];

    // create a code for the command
    $code_date = date("dmy"); // 311222
    $code_code = "CMD";

    $query_ref = "SELECT COUNT(*) FROM commande WHERE MONTH(cmd_date)=date('m')";
    $result_query_ref = $connect->query($query_ref);
    $rows = $result_query_ref->fetch_all(MYSQLI_ASSOC);
    $code_value = $rows[0]['COUNT(*)'] + 1;    
    $code_value = str_pad($code_value, 3, '0', STR_PAD_LEFT);
    $code = $code_date."-".$code_code."-C".$code_value;

    $status = $_POST['status'];
    $delivery_price = $_POST["delivery_fee"];
    $service_price = $_POST["service_fee"];
    $note = htmlspecialchars($_POST['command_note'], ENT_QUOTES);

    $query = "INSERT INTO commande (`id_commande`, `id_points`, `numero`, `cmd_date`, `status`, `delivery_price`, `service_price`, `note`) VALUES (0,'$id_points','$code', curdate(), '$status','$delivery_price','$service_price','$note')";
    $result = $connect->query($query);

    // Get the id of the command lately recorded
    $query = "SELECT id_commande FROM commande WHERE id_points='$id_points' AND cmd_date=curdate()";
    $result = $connect->query($query);
    $id_commande = $result->fetch_all(MYSQLI_ASSOC);
    $id_commande = $id_commande[0]['id_commande'];

    // Add products
    $how_many_products = $_POST['how_many_products'];
    for ($i = 0; $i < $how_many_products; $i++) {

        $product_id_key = 'product_id_'.($i+1);
        $product_quantity_key = 'product_quantity_'.($i+1);
        $product_sold_price_key = 'product_sold_price_'.($i+1);

        $product_id = $_POST[$product_id_key];
        $product_quantity = $_POST[$product_quantity_key];
        $product_sold_price = $_POST[$product_sold_price_key];

        $query = "INSERT INTO historique (`id_commande`, `id_produit`, `quantity`, `sold_price`) VALUES ('$id_commande','$product_id','$product_quantity','$product_sold_price')";
        $result = $connect->query($query);
    }

    // Create all payment methods and amount
    $how_many_payments = $_POST['how_many_payments'];
    for ($i = 0; $i < $how_many_payments; $i++) {

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

     // link the client to the command too (command - client)
     $query = "INSERT INTO liste_client_commande (`id_client`, `id_commande`) VALUES ('$id_client','$id_commande')";
     $result = $connect->query($query);

     header('Location: ../pages/command_sheet.php?id='.$id_commande);
     exit();
?>