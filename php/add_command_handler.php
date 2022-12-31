<?php 

    include "sql_connection.php";

    // check if there is a client
    if (!isset($_POST['numero']) || $_POST['numero'] == "") {
        header('Location: ../pages/index.html?msg=error'); // error
    }
    
    // check if there is at least product
    if (!isset($_POST['product_name_1']) || $_POST['product_name_1'] == "") {
        header('Location: ../pages/index.html?msg=error'); // error
    }

    // check if there is a payment
    if (!isset($_POST['payment_amount_1']) || $_POST['payment_amount_1'] == "") {
        header('Location: ../pages/index.html?msg=error'); // error
    }

    // Create points and link it to the client
    $id_client = $_POST['numero'];
    $total_price = $_POST['total_price'];
    $points_amount = ceil($total_price / 10);
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
    $note = $_POST['command_note'];

    $query = "INSERT INTO commande (`id_commande`, `id_points`, `numero`, `cmd_date`, `status`, `delivery_price`, `service_price`, `note`) VALUES (0,'$id_points','$code', curdate(), '$status','$delivery_price','$service_price','$note')";
    $result = $connect->query($query);

    // Get the id of the command lately recorded
    $query = "SELECT id_commande FROM commande WHERE id_points='$id_points' AND cmd_date=curdate()";
    $result = $connect->query($query);
    $id_commande = $result->fetch_all(MYSQLI_ASSOC);
    $id_commande = $id_commande[0]['id_commande'];

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
            $id_paiement = $rows[0]['id_mode_paiement'];
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
        $query = "INSERT INTO liste_paiement_commande (`id_liste_paiement_commande`, `id_paiement`, `id_commande`) VALUES (0,'$id_paiement','$id_commande')";
        $result = $connect->query($query);
    }

     // link the client to the command too (command - client)
     $query = "INSERT INTO liste_client_comande (`id_liste_client_commande`, `id_client`, `id_commande`) VALUES (0,'$id_client','$id_commande')";
     $result = $connect->query($query);
?>