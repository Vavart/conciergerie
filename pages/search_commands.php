<!DOCTYPE html>
<html lang="fr">
<head>

    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher une commande - Conciergerie</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- JS -->
    <script src="../js/search_command.js" defer></script>
    <script src="../js/csv_download.js" defer></script>

    <?php include "../php/sql_connection.php" ?>

    <?php
    
    $query = "SELECT * FROM commande";
    $result = $connect->query($query);
    $commandes = $result->fetch_all(MYSQLI_ASSOC);

    $command_status_matching = array(
        'to_buy' => 'À acheter',
        'bought' => 'Achetée',
        'packed' => 'Emballée',
        'shipped' => 'Expédiée',
        'arrived' => 'Arrivée',
        'delivered' => 'Livrée',
        'done' => 'Terminée'
    );   
    ?>

</head>
<body>
    
    <div class="page">

        <div class="cont-header">
            <h1>Rechercher une commande</h1>
            <p class="caption">Cliquez sur la commande pour voir plus de détails à son sujet</p>
            <a href="index.php" class="menu">
                Retour à l'accueil
            </a>
        </div>

        <div class="cont-search tableur">
            <div class="cont-input">
                <label for="search">Recherche</label>
                <input type="text" name="search" id="search" placeholder="010123-CMD-C002">
            </div>
            <div class="cont-input">
                <button class="export-csv">
                    Exporter les commandes au format tableur
                </button>
            </div>
        </div>


        <div class="cont-tab">
            <table>
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>N° Client</th>
                        <th>Nom du client</th>
                        <th>Reste à payer</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    
                    foreach ($commandes as $commande) { 
                        $id_commande = $commande['id_commande'];             
                            
                        // First get the id of the client who ordered
                        $query = "SELECT id_client FROM liste_client_commande WHERE id_commande='$id_commande'";
                        $result = $connect->query($query);
                        $id_client = $result->fetch_all(MYSQLI_ASSOC);
                        $id_client = $id_client[0]['id_client'];

                        // Then, get the name and the surname of the client 
                        $query = "SELECT name, surname, code FROM client WHERE id_client='$id_client'";
                        $result = $connect->query($query);
                        $result = $result->fetch_all(MYSQLI_ASSOC);
                        $client_name = $result[0]['name'];
                        $client_surname = $result[0]['surname'];
                        $client_code = $result[0]['code'];

                        // Get the amount left for the command

                        // Total price
                        $query = "SELECT * FROM historique WHERE id_commande='$id_commande'";
                        $result = $connect->query($query);
                        $products = $result->fetch_all(MYSQLI_ASSOC);

                        $total_price = 0;
                        for ($i = 0; $i < count($products); $i++) {
                            $price = $products[$i]['sold_price'] * $products[$i]['quantity'];
                            $total_price += $price;
                        }

                        // Add delivery and serice fee
                        $total_price += $commande['delivery_price'];
                        $total_price += $commande['service_price'];

                        // Total amounts
                        $query = "SELECT montant FROM liste_paiement_commande NATURAL JOIN paiement WHERE id_commande='$id_commande'";
                        $result = $connect->query($query);
                        $amounts = $result->fetch_all(MYSQLI_ASSOC);

                        $total_amount = 0;
                        for ($i = 0; $i < count($amounts); $i++) {
                            $amount = $amounts[$i]['montant'];
                            $total_amount += $amount;
                        }

                        $rest_to_pay = $total_price - $total_amount;


                        // make $products a string in order to use it in js
                        // but first need the name of the product
                        
                        $query = "SELECT product_name, quantity, sold_price FROM produit NATURAL JOIN historique WHERE id_commande='$id_commande' ";
                        $result = $connect->query($query);
                        $products = $result->fetch_all(MYSQLI_ASSOC);


                        
                        $str_products = "";
                        foreach($products as $product) {
                            $str_product = $product['quantity']."x ".$product['product_name']." : ".$product['sold_price']*$product['quantity']."€";
                            $str_products .= $str_product." / ";
                        }

                        // check if the command has points linked
                        $query = "SELECT id_points FROM commande WHERE id_commande='$id_commande'";
                        $result = $connect->query($query);
                        $id_points = $result->fetch_all(MYSQLI_ASSOC);
                        $id_points = $id_points[0]['id_points'];

                        // get the amount of points id id_points is != null
                        if ($id_points != NULL) {
                            $query = "SELECT nb_points FROM points WHERE id_points='$id_points'";
                            $result = $connect->query($query);
                            $nb_points = $result->fetch_all(MYSQLI_ASSOC);
                            $nb_points = $nb_points[0]['nb_points'];
                        } else {
                            $nb_points = 0;
                        }

                        // If the arrival date of the command is 0000-00-00 then we'll print a "Non arrivée" message
                        if ($commande['cmd_arrival_date'] == "0000-00-00") {
                            $date_arrival = "Non arrivée";
                        } else {
                            $date_arrival = $commande['cmd_arrival_date'];
                        }

                        ?>
    
                        <tr 

                        data-id="<?= $id_commande ?>"
                        data-cmd-code = "<?= $commande['numero'] ?>"
                        data-client-code = "<?= $client_code ?>"
                        data-cmd-date = "<?= $commande['cmd_date'] ?>"
                        data-items = "<?= $str_products ?>"
                        data-cmd-total-price = "<?= $total_price ?>"
                        data-delivery-price = "<?= $commande['delivery_price'] ?>"
                        data-service-price = "<?= $commande['service_price'] ?>"
                        data-total-deposits-amounts = "<?= $total_amount ?>"
                        data-rest-to-pay = "<?= $rest_to_pay ?>"
                        data-points = "<?= $nb_points ?>"
                        data-status = "<?= $commande['status'] ?>"
                        data-cmd-arrival-date = "<?= $date_arrival ?>"
                        data-note = "<?= $commande['note'] ?>"

                        >
                            <td><?= $commande['numero'] ?></td>
                            <td><?= $client_code ?></td>
                            <td><?= $client_name." ".$client_surname ?></td>
                            <td><?= $rest_to_pay ?>€</td>
                            <td><?= $command_status_matching[$commande['status']] ?></td>
                        </tr>

                    <?php }

                    ?>
                    

                </tbody>
            </table>
        </div>


    </div>

</body>
</html>