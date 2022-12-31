<!DOCTYPE html>
<html lang="fr">
<head>

    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher un produit - Conciergerie</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- JS -->
    <script src="../js/select_product.js" defer></script>

    <?php include "../php/sql_connection.php" ?>

    <?php

    $query = "SELECT * FROM produit";
    $result = $connect->query($query);
    $products = $result->fetch_all(MYSQLI_ASSOC);
    
    ?>

</head>
<body>
    
    <div class="page">

        <div class="cont-header">
            <h1>Rechercher un client</h1>
            <p class="caption">Cliquez sur le client souhaitant passer commande</p>
            <a href="add_command.php" class="menu">
                Retour à la commande
            </a>
        </div>

        <div class="cont-search">
            <div class="cont-input">
                <label for="search">Recherche</label>
                <input type="text" name="search" id="search" placeholder="Jean Moulin">
            </div>
        </div>


        <div class="cont-tab">
            <table>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nom du produit</th>
                        <th>Prix unitaire (en €)</th>
                        <th>Statut</th>
                        <th>Nombre en stock</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    
                    foreach ($products as $product) { ?>
                        <tr 
                        data-id="<?= $product['id_produit'] ?>" 
                        data-name="<?= $product['product_name'] ?>" 
                        data-price="<?= $product['unit_price'] ?>"
                        data-status="<?= $product['status'] ?>"
                        data-dispo="<?= $product['nb_dispo'] ?>"
                        >
                            <td><?= $product['id_produit'] ?></td>
                            <td><?= $product['product_name'] ?></td>
                            <td><?= $product['unit_price'] ?></td>
                            <td><?= $product['status'] ?></td>
                            <td><?= $product['nb_dispo'] ?></td>    
                        </tr>
                    <?php }

                    ?>
                    

                </tbody>
            </table>
        </div>


    </div>

</body>
</html>