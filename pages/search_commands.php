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

    <?php include "../php/sql_connection.php" ?>

    <?php
    
    $query = "SELECT * FROM commande";
    $result = $connect->query($query);
    $commandes = $result->fetch_all(MYSQLI_ASSOC);
    
    ?>

</head>
<body>
    
    <div class="page">

        <div class="cont-header">
            <h1>Rechercher une commande</h1>
            <p class="caption">Cliquez sur la commande pour voir plus de détails à son sujet</p>
            <a href="index.html" class="menu">
                Retour à l'accueil
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
                        <th>Date</th>
                        <th>Nom du client</th>
                        <th>Reste à payer</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    
                    foreach ($commandes as $commande) { ?>
                        <tr data-id="<?= $commande['id_commande'] ?>">
                            <td><?= $commande['numero'] ?></td>
                            <td><?= $commande['cmd_date'] ?></td>

                            <!-- Get the name of the client -->
                            <?php
                            
                            // First get the id of the client who ordered
                            $id_commande = $commande['id_commande'];
                            $query = "SELECT id_client FROM liste_client_commande WHERE id_commande='$id_commande'";
                            $result = $connect->query($query);
                            $id_client = $result->fetch_all(MYSQLI_ASSOC);
                            $id_client = $id_client[0]['id_client'];

                            // Then, get the name and the surname of the client 
                            $query = "SELECT name, surname FROM client WHERE id_client='$id_client'";
                            $result = $connect->query($query);
                            $result = $result->fetch_all(MYSQLI_ASSOC);
                            $client_name = $result[0]['name'];
                            $client_surname = $result[0]['surname'];

                            ?>
                            <td><?= $client_name." ".$client_surname ?></td>

                            <!-- Get the amount left for the command -->
                            <!-- TO DO -->


                            <td><?= $commande['status'] ?></td>
                            <td><?= htmlspecialchars_decode($commande['note']) ?></td>    
                        </tr>
                    <?php }

                    ?>
                    

                </tbody>
            </table>
        </div>


    </div>

</body>
</html>