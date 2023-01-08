<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/invoice.css">

    <!-- PHP -->
    <?php
    
    include "../php/sql_connection.php";

    // Get the id from the url
    $id_facture = $_REQUEST['id'];

    // Get my invoice according the id in the url
    $query = "SELECT * FROM facture WHERE id_facture='$id_facture'";
    $result = $connect->query($query);
    $facture = $result->fetch_all(MYSQLI_ASSOC);
    $facture = $facture[0]; // becasue 1 invoice is linked to 1 command, it just can be updated

    // Get the command linked to it
    $id_commande = $facture['id_commande'];
    $query = "SELECT numero, cmd_date, delivery_price, service_price, promotion_applied FROM commande WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $result = $result->fetch_all(MYSQLI_ASSOC);
    $commande = $result[0]; // same note as invoice
    
    // Get the articles of the command
    $query = "SELECT * FROM historique NATURAL JOIN produit WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $articles = $result->fetch_all(MYSQLI_ASSOC);
    
    // Payments links to the commands with their methods
    $query = "SELECT * FROM liste_paiement_commande NATURAL JOIN paiement NATURAL JOIN mode_paiement WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $payments = $result->fetch_all(MYSQLI_ASSOC);

    // Compute the total price of the command, the total amount of payment and the rest_to_pay

    $total_price = 0;
    $total_payment_amount = 0;

    for ($o = 0; $o < count($articles); $o++) {
        $price = $articles[$o]['sold_price'] * $articles[$o]['quantity'];
        $total_price += $price;
    }
    for ($o = 0; $o < count($payments); $o++) {
        $amount = $payments[$o]['montant'];
        $total_payment_amount += $amount;
    }

    $rest_to_pay = $total_price - $total_payment_amount - $commande['promotion_applied'];
    
    // echo "<pre>";
    // print_r($payments);
    // echo "</pre>";

    // Get the client who ordered
    $query = "SELECT id_client FROM liste_client_commande WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $id_client = $result->fetch_all(MYSQLI_ASSOC);
    $id_client = $id_client[0]['id_client'];

    $query = "SELECT code, name, surname, postal_address, next_discount FROM client WHERE id_client='$id_client'";
    $result = $connect->query($query);
    $client = $result->fetch_all(MYSQLI_ASSOC);
    $client = $client[0];

    // Get the main phone of the client
    $query = "SELECT numero FROM telephone WHERE id_client='$id_client'";
    $result = $connect->query($query);
    $numero_tel = $result->fetch_all(MYSQLI_ASSOC);
    $numero_tel = $numero_tel[0]['numero'];
    
    ?>

</head>
<body>

    <div class="page invoice">

        <div class="cont-title">
            <h1>La conciergerie</h1>
            <h2>Facture</h2>
        </div>

        <div class="cont-infos-cmd-client">
            <div class="infos-cmd">
                <ul>
                    <li class="item-list">No de commande : <?= $commande['numero'] ?></li>
                    <li class="item-list">Date de commande : <?= $commande['cmd_date'] ?></li>
                    <li class="item-list">Facture numéro : <?= $facture['numero'] ?></li>
                    <li class="item-list">Date de facture : <?= $facture['facture_date'] ?></li>
                    <li class="item-list">Dernière mise à jour : <?= $facture['facture_date_maj'] ?></li>
                </ul>
            </div>

            <div class="infos-client">
                <ul>
                    <li class="item-list">No. client : <?= $client['code'] ?></li>
                    <li class="item-list">Client : <?= $client['name']." ".$client['surname'] ?></li>
                    <li class="item-list">Adresse : <?= $client['postal_address'] ?></li>
                    <li class="item-list">Téléphone principal : <?= $numero_tel ?></li>
                </ul>
            </div>
        </div>


        <div class="main-table-cmd">
            <table>
                <thead class="thead"> 
                    <th>No.</th>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix à l'unité</th>
                    <th>Prix total</th>
                </thead>

                <tbody>

                    <?php
                    
                        $i = 1;
                        foreach($articles as $article) { ?>
                        <tr>
                            <td><?= $i ?></td>
                            <?php
                                $is_on_sale = $article['unit_price'] - $article['sold_price'] > 0;

                                if ($is_on_sale) { ?>
                                    <td><?= $article['product_name']." : ".$article['unit_price']."€ -->".$article['sold_price']."€" ?></td>
                                <?php } else { ?>
                                    <td><?= $article['product_name']." : ".$article['unit_price']."€" ?></td>
                               <?php }
                            ?>

                            <td><?= $article['quantity'] ?></td>

                            <?php
                                if ($is_on_sale) { ?>
                                    <td><?= $article['unit_price']."€" ?></td>
                                <?php } else { ?>
                                    <td><?= $article['sold_price']."€" ?></td>
                               <?php }
                            ?>

                            <?php
                                if ($is_on_sale) { ?>
                                    <td><?= $article['quantity']*$article['sold_price']."€" ?></td>
                                <?php } else { ?>
                                    <td><?= $article['quantity']*$article['unit_price']."€" ?></td>
                               <?php }
                            ?>

                        </tr> 
                       <?php $i++; }
                    
                    ?>

                </tbody>
            </table>
        </div>

        <div class="payment-fees">
            <div class="payment-info">
                <ul>
                    <li class="item-list">Promotion à appliquer au projet achat : <?= $client['next_discount'] ?>€</li>
                    <?php foreach($payments as $payment) { ?>
                        <li class="item-list">Dépôt <?= $payment['mode']." ".$payment['payment_date']." : ".$payment['montant']."€" ?></li>
                    <?php } ?>
                </ul>
            </div>
    
            <div class="fees">
                <div class="fee-item">
                    <div class="left">
                        <span>Montant de la commande</span>
                    </div>
                    <div class="right">
                        <span><?= $total_price ?>€</span>
                    </div>
                </div>
                <div class="fee-item">
                    <div class="left">
                        <span>Frais de service</span>
                    </div>
                    <div class="right">
                        <span><?= $commande['service_price'] ?>€</span>
                    </div>
                </div>
                <div class="fee-item">
                    <div class="left">
                        <span>Frais de livraison</span>
                    </div>
                    <div class="right">
                        <span><?= $commande['delivery_price'] ?>€</span>
                    </div>
                </div>
                <div class="fee-item">
                    <div class="left">
                        <span>Promotion / Remise</span>
                    </div>
                    <div class="right">
                        <?php 
                            $discount = 0;
                            if (isset($commande['promotion_applied'])) $discount = $commande['promotion_applied'];
                        ?>
                        <span><?= $discount ?>€</span>
                    </div>
                </div>
                <div class="fee-item">
                    <div class="left">
                        <span>Dépôt</span>
                    </div>
                    <div class="right">
                        <span><?= $total_payment_amount ?>€</span>
                    </div>
                </div>
                <div class="fee-item invoice-amount">
                    <div class="left">
                        <span>Montant de la facture</span>
                    </div>
                    <div class="right">
                        <span><?= $rest_to_pay ?>€</span>
                    </div>
                </div>
            </div>
        </div>


    </div>

</body>
</html>