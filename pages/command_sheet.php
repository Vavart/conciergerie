<!DOCTYPE html>
<html lang="fr">
<head>

    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer commande - Conciergerie</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- JS -->
    <script src="../js/command_storage.js" defer></script>
    <script src="../js/add_payment.js" defer></script>
    <script src="../js/command_recap.js" defer></script>
    <!-- Html2Pdf for the invoice -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script src="../js/invoice_pdf.js" defer></script>

    <!-- PHP -->
    <?php

    include "../php/sql_connection.php";
    
    // Get the id from the url
    $id_commande = $_REQUEST['id'];

    // Get the command
    $query = "SELECT * FROM commande WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $command = $result->fetch_all(MYSQLI_ASSOC);
    $command = $command[0];

    // The payments amounts and methods linked to the command
    $query = "SELECT id_paiement FROM liste_paiement_commande WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $id_paiements = $result->fetch_all(MYSQLI_ASSOC);
    
    // The articles linked to the command
    $query = "SELECT id_produit FROM historique WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $ids_article = $result->fetch_all(MYSQLI_ASSOC);

    // The client who ordered
    $query = "SELECT id_client FROM liste_client_commande WHERE id_commande='$id_commande'";
    $result = $connect->query($query);
    $id_client = $result->fetch_all(MYSQLI_ASSOC);
    $id_client = $id_client[0]['id_client'];
    
    // The unspent points of the client who ordered
    $query = "SELECT * FROM points WHERE id_client='$id_client' AND id_cadre_depense_points IS NULL";
    $result = $connect->query($query);
    $points = $result->fetch_all(MYSQLI_ASSOC);


    // for later
    $code_commande = $command['numero'];
    ?>

</head>
<body>

<div class="page">
    
    <div class="cont-header">
        <h1>Commande : <?= $code_commande ?></h1>
        <a href="search_commands.php" class="menu">
            Retour à la recherche
        </a>
    </div>

    <form action="../php/update_command_handler.php" method="post">
    <input type="hidden" name="id_commande" value="<?= $id_commande ?>">
    <input type="hidden" name="code_commande" value="<?= $code_commande ?>">
        <div class="main-form">

            <!-- Client -->
            <?php

                $query = "SELECT code, name, surname, email FROM client WHERE id_client='$id_client'";
                $result = $connect->query($query);
                $client = $result->fetch_all(MYSQLI_ASSOC);
                $client = $client[0];
            
            ?>
            <h2 class="sec-title">Informations client</h2>
            <div class="section">
                
                <div class="sec">
                    <div class="cont-input">
                        <label for="numero">Numéro client</label>
                        <input type="text" name="numero" id="numero" class="locked" readonly value="<?= $id_client ?>">
                    </div>
                    <div class="cont-input">
                        <label for="code">Code client</label>
                        <input type="text" name="code" id="code" class="locked" readonly value="<?= $client['code'] ?>">
                    </div>
                </div>
                <div class="sec">
                    <div class="cont-input">
                        <label for="name_surname">Prénom / Nom</label>
                        <input type="text" name="name_surname" id="name_surname" class="locked" readonly value="<?= $client['name'].' / '.$client['surname'] ?>">
                    </div>
                    <div class="cont-input">
                        <label for="mail">Mail</label>
                        <input type="email" name="mail" id="mail" class="locked" readonly value="<?= $client['email'] ?>">
                    </div>
                    <input type="hidden" name="next_discount" value="<?= $command['promotion_applied'] ?>">
                </div>
            </div>

            <!-- Points of the client -->
            <h2 class="sec-title">Points disponibles</h2>
            <?php

            $i = 0;
            foreach($points as $point) { ?>

                <div class="section">
                    <div class="sec">
                        <div class="cont-input">
                            <label for="">Nombre de points</label>
                            <input type="text" name="points_unspent_<?= $i ?>" id="" class="locked" readonly value="<?= $point['nb_points'] ?>">
                        </div>
                        <div class="cont-input">
                            <label for="">Date d'expiration</label>
                            <input type="date" name="exp_date_unspent_<?= $i ?>" class="locked" readonly value="<?= $point['exp_date'] ?>">
                        </div>
                    </div>

                    <div class="sec">
                        <div class="cont-input checkbox">
                            <label for="">Utiliser pour la commande</label>
                            <input type="checkbox" name="use_points_<?= $i ?>">
                        </div>

                        <div class="cont-input">
                            <label for="">Règle d'utilisation</label>
                            <input type="text" name="point_use_rule_<?= $i ?>" placeholder="20% de remise totale">
                        </div>

                        <input type="hidden" name="id_points_unspent_<?= $i ?>" value="<?= $point['id_points'] ?>">
                    </div>
                </div>

            <?php $i++; } ?>

            <!-- How many unspent points available -->
            <input type="hidden" name="how_many_unspent_points" value="<?= count($points) ?>">
            
            
            <!-- Products -->
            <h2 class="sec-title">Produits</h2>

            <div class="section product-section">
                <!-- Products will be displayed here -->
                <?php
                    // echo "<pre>";
                    // print_r($rest_to_pay_before);
                    // echo "</pre>";
                $i = 0;
                foreach($ids_article as $id_article) {
                    $id_article = $id_article['id_produit'];
                    $query  = "SELECT * FROM produit WHERE id_produit='$id_article'";
                    $result = $connect->query($query);
                    $product = $result->fetch_all(MYSQLI_ASSOC);
                    $product = $product[0];

                    $query = "SELECT quantity, sold_price FROM historique WHERE id_produit='$id_article' AND id_commande='$id_commande'";
                    $result = $connect->query($query);
                    $data_product = $result->fetch_all(MYSQLI_ASSOC);
                    $data_product = $data_product[0];

                    $i++;

                ?>

                    <div class="section">
                        <div class="sec">
                            <input type="hidden" name="product_id_<?= $i ?>" value="<?= $id_article ?>">
                            <div class="cont-input large">
                                <label for="">Nom du produit</label>
                                <input type="text" name="product_name_<?= $i ?>" class="locked" readonly value="<?= $product['product_name'] ?>">
                            </div>
                            <div class="cont-input">
                                <label for="">Status</label>
                                <input type="text" name="product_status_<?= $i ?>" class="locked" readonly value="<?= $product['status'] ?>">
                            </div>
                        </div>

                        <div class="sec">
                            <div class="cont-input">
                                <label for="">Prix unitaire (en €)</label>
                                <input type="number" name="product_price_<?= $i ?>" class="locked" readonly value="<?= $product['unit_price'] ?>">
                            </div>
                            <div class="cont-input">
                                <label for="">Prix vendu (en €)</label>
                                <input type="number" name="product_sold_price_<?= $i ?>" value="<?= $data_product['sold_price'] ?>">
                            </div>
                        </div>

                        <div class="sec">
                            <div class="cont-input">
                                <label for="">Nombre en stock</label>
                                <input type="number" name="product_stock_<?= $i ?>" class="locked" readonly value="<?= $product['nb_dispo'] ?>">
                            </div>
                            <div class="cont-input">
                                <label for="">Quantité</label>
                                <input type="number" name="product_quantity_<?= $i ?>" value="<?= $data_product['quantity'] ?>">
                            </div>
                            <div class="cont-input">
                                <button class="delete-btn" type="button" data-id="<?= $i ?>" data-order="<?= $i ?>">
                                    X
                                </button>
                            </div>
                        </div>

                    </div>



                <?php }

                ?>
            </div>

            <div class="sec col">
                <div class="cont-choose-product">
                    <a href="choose_product.php?for=sheet&id=<?= $id_commande ?>" class="choose-product">
                        Ajouter un produit
                    </a>
                    <input type="hidden" name="how_many_products" value="<?= count($ids_article) ?>">
                </div>
            </div>


            <!-- Payment -->
            <h2 class="sec-title">Réglement</h2>
            <div class="section cont-payment">
                <!-- Payments will be there -->

                <?php
                
                $i = 0;
                foreach($id_paiements as $id_paiement) {
                    $id_paiement = $id_paiement['id_paiement'];
                    $i++;

                    $query = "SELECT id_mode_paiement, montant, payment_date FROM paiement WHERE id_paiement ='$id_paiement'";
                    $result = $connect->query($query);
                    $rows = $result->fetch_all(MYSQLI_ASSOC);
                    $data_payment = $rows[0];

                    $id_mode_paiement = $data_payment['id_mode_paiement'];
                    $query = "SELECT mode FROM mode_paiement WHERE id_mode_paiement='$id_mode_paiement'";
                    $result = $connect->query($query);
                    $mode_paiement = $result->fetch_all(MYSQLI_ASSOC);
                    $mode_paiement = $mode_paiement[0]['mode'];

                ?>    
                
                    <div class="sec">
                        <input type="hidden" name="payment_id_<?= $i ?>" value="<?= $id_paiement ?>">
                        <div class="cont-input">
                            <label for="">Dépôt (en €)</label>
                            <input type="number" name="payment_amount_<?= $i ?>" class="locked" readonly value="<?= $data_payment['montant'] ?>" id="payment_amount">
                        </div>
                        <div class="cont-input">
                            <label for="">Mode de paiement</label>
                            <input type="text" name="payment_method_<?= $i ?>" class="locked" readonly value="<?= $mode_paiement ?>">
                        </div>
                        <div class="cont-input">
                            <label for="">Date du dépôt</label>
                            <input type="date" name="payment_date_<?= $i ?>" class="locked" readonly value="<?= $data_payment['payment_date'] ?>">
                        </div>
                    </div>

                <?php }

                ?>
            </div>

            <div class="sec col">
                <div class="cont-add-payment">
                    <button class="add-payment" type="button">
                        Ajouter un paiement
                    </button>
                    <input type="hidden" name="how_many_payments" value="<?= count($id_paiements) ?>">
                    <input type="hidden" name="how_many_payments_already_present" value="<?= count($id_paiements) ?>">
                </div>
            </div>

            <!-- Note -->
            <h2 class="sec-title">Note</h2>
            <div class="section cont-note">
                <textarea name="command_note" id="command_note"><?= $command['note'] ?></textarea>
            </div>

            <!-- Status -->
            <h2 class="sec-title">Statut</h2>
            <div class="sec col">

                <?php 
                    if ($command['status'] == "to_buy") { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="to_buy" checked>
                        <label for="stock">À acheter</label>
                    </div>
                    <?php } else { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="to_buy">
                        <label for="stock">À acheter</label>
                    </div>
                    <?php }
                    if ($command['status'] == "bought") { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="bought" checked>
                        <label for="available">Acheté</label>
                    </div>
                    <?php } else { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="bought">
                        <label for="available">Acheté</label>
                    </div>
                    <?php }
                    if ($command['status'] == "packed") { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="packed" checked>
                        <label for="packed">Emballé</label>
                    </div>
                    <?php } else { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="packed">
                        <label for="packed">Emballé</label>
                    </div>
                    <?php }
                    if ($command['status'] == "shipped") { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="shipped" checked>
                        <label for="dispatched">Expédiée</label>
                     </div>
                    <?php } else { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="shipped">
                        <label for="dispatched">Expédiée</label>
                    </div>
                    <?php }
                    if ($command['status'] == "arrived") { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="arrived" checked>
                        <label for="arrived">Arrivée</label>
                    </div>
                    <?php } else { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="arrived">
                        <label for="arrived">Arrivée</label>
                    </div>
                    <?php }
                    if ($command['status'] == "delivered") { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="delivered" checked>
                        <label for="delivered">Livrée</label>
                    </div>
                    <?php } else { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="delivered">
                        <label for="delivered">Livrée</label>
                    </div>
                    <?php }
                    if ($command['status'] == "done") { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="done" checked>
                        <label for="other">Terminée</label>
                    </div>
                    <?php } else { ?>
                    <div class="cont-input radio">
                        <input type="radio" name="status" id="status" value="done">
                        <label for="other">Terminée</label>
                    </div>
                    <?php }
                ?>
            </div>

            <!-- Recap -->
            <h2 class="sec-title">Récapitulatif</h2>
            <div class="section recap">
                <div class="sec">
                    <div class="cont-input">
                        <label for="">Frais de livraison (en €)</label>
                        <input type="number" name="delivery_fee" id="delivery_fee" value="<?= $command['delivery_price'] ?>">
                    </div>
                    <div class="cont-input">
                        <label for="">Frais de service (en €)</label>
                        <input type="number" name="service_fee" id="service_fee" value="<?= $command['service_price'] ?>">
                    </div>
                </div>

                <div class="sec">
                    <div class="cont-input">
                        <label for="">Montant total (en €)</label>
                        <input type="number" name="total_price" id="total_price" class="locked" readonly>
                    </div>
                    <div class="cont-input">
                        <label for="">Reste à payer (en €)</label>
                        <input type="number" name="rest_to_pay" id="rest_to_pay" class="locked" readonly>
                    </div>
                </div>

                <div class="sec">
                    <div class="cont-input">
                        <button type="button" class="compute-btn">
                            Calculer le coût final
                        </button>
                    </div>
                </div>
            </div>
            
        </div>


        <div class="cont-btns">
            <button type="submit" formaction="../php/delete_command_handler.php" class="delete-command-btn">Supprimer la commande</button>
            <button type="submit" formaction="../php/invoice_handler.php" class="invoice-btn">Générer une facture</button>
        </div>

        <div class="cont-btns">
            <a href="index.html" class="cancel">
                Annuler
            </a>

            <button type="submit">
                Mettre à jour la commande
            </button>
        </div>


    </form>
   
</div>
    

</body>
</html>