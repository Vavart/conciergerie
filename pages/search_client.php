<!DOCTYPE html>
<html lang="fr">
<head>

    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher un client - Conciergerie</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- JS -->
    <script src="../js/search_client.js" defer></script>

    <?php include "../php/sql_connection.php" ?>

    <?php
    
    $query = "SELECT * FROM client";
    $result = $connect->query($query);
    $clients = $result->fetch_all(MYSQLI_ASSOC);
    
    ?>

</head>
<body>
    
    <div class="page">

        <div class="cont-header">
            <h1>Rechercher un client</h1>
            <p class="caption">Cliquez sur le client pour voir plus de détails à son sujet</p>
            <a href="index.php" class="menu">
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
                        <th>Code</th>
                        <th>Nom du client</th>
                        <th>Email</th>
                        <th>Téléphone principal</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    
                    foreach ($clients as $client) { ?>
                        <tr data-id="<?= $client['code'] ?>">
                            <td><?= $client['id_client'] ?></td>
                            <td><?= $client['code'] ?></td>
                            <td><?= $client['name']." ".$client['surname'] ?></td>
                            <td><?= $client['email'] ?></td>

                            <!-- Get the first number of the client -->
                            <?php 
                                $id_client = $client['id_client'];
                                $query = "SELECT numero FROM telephone WHERE id_client='$id_client'";
                                $result = $connect->query($query);
                                $numero_tel = $result->fetch_all(MYSQLI_ASSOC);
                                $numero_tel = $numero_tel[0]['numero'];
                            ?>

                            <td><?= $numero_tel ?></td>    
                        </tr>
                    <?php }

                    ?>
                    

                </tbody>
            </table>
        </div>


    </div>

</body>
</html>