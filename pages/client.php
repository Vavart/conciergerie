<!DOCTYPE html>
<html lang="fr">
<head>

    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche client - Conciergerie</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- PHP -->
    <?php include "../php/sql_connection.php"; 
    
    $code = $_REQUEST['id'];

    // The client
    $query = "SELECT * FROM client WHERE code= '$code'";
    $result = $connect->query($query);
    $client = $result->fetch_all(MYSQLI_ASSOC);
    $client = $client[0];

    // The points of the clients
    $id_client = $client['id_client'];
    $query = "SELECT * FROM points WHERE id_client='$id_client'";
    $result = $connect->query($query);
    $points = $result->fetch_all(MYSQLI_ASSOC);
    
    if (empty($points)) {
        $points = [];
        $sum = 0;
    } else {
        // Get the sum of all points
        $query = "SELECT SUM(nb_points) FROM points WHERE id_client='$id_client'";
        $result = $connect->query($query);
        $result = $result->fetch_all(MYSQLI_ASSOC);
        $sum = $result[0]['SUM(nb_points)'];
    }
    // echo '<pre>',var_dump($sum),'</pre>';

    // The phone numbers of the client
    $query = "SELECT * FROM telephone WHERE id_client='$id_client'";
    $result = $connect->query($query);
    $phone_numbers = $result->fetch_all(MYSQLI_ASSOC);
    
    ?>

    <!-- JS -->
    <script src="../js/add_phone_number.js" defer ></script>
    <script src="../js/add_points.js" defer ></script>

</head>
<body>
    
    <div class="page">

        <div class="cont-header">
            <h1>Fiche client : <?php echo $client['code'] ?></h1>
            <a href="search_client.php" class="menu">
                Retour à la recherche
            </a>
        </div>


        <form action="../php/update_client_handler.php" method="post">

            <div class="main-form">


                <h2 class="sec-title">Informations client</h2>

                <div class="section">
                    <div class="sec">
                        <div class="cont-input">
                            <label for="numero">Numero</label>
                            <input type="text" name="numero" id="numero" value=<?php echo $client['id_client'] ?> class="locked" readonly>
                        </div>
                        <div class="cont-input">
                            <label for="code">Code</label>
                            <input type="text" name="code" id="code" value=<?= $client['code'] ?> class="locked" readonly>
                        </div>
                        <div class="cont-input">
                            <label for="seniority">Ancienneté</label>
                            <input type="date" name="seniority" id="seniority" placeholder="17-SPR-0001" class="locked" readonly value=<?= $client['arrival_date'] ?>>
                        </div>
                    </div>
                    <div class="sec">
                        <div class="cont-input">
                            <label for="prenom">Prénom</label>
                            <input type="text" name="prenom" id="prenom" placeholder="Jean Michelle" value="<?= $client['name'] ?>">
                        </div>
                        <div class="cont-input">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom" placeholder="Moulin" value="<?= $client['surname'] ?>">
                        </div>
                    </div>

                    <div class="sec">
                        <div class="cont-input large">
                            <label for="address">Adresse postale</label>
                            <input type="text" name="address" id="address" placeholder="6 rue du Pastaga 72000 Le Mans" class="" value="<?= $client['postal_address'] ?>">
                        </div>
                    </div>

                    <div class="sec">
                        <div class="cont-input">
                            <label for="mail">Adresse mail</label>
                            <input type="email" name="mail" id="mail" class="" placeholder="jeanmi_300@yahoo.fr" value="<?= $client['email'] ?>">
                        </div>
                    </div>

                    <div class="sec">
                        <div class="cont-input">
                            <label for="facebook">Facebook</label>
                            <input type="text" name="facebook" id="facebook" placeholder="JM Moulin" value="<?= $client['facebook_username'] ?>">
                        </div>
                        <div class="cont-input">
                            <label for="facebook">Instagram</label>
                            <input type="text" name="instagram" id="instagram" placeholder="jmm20" value="<?= $client['instagram_username'] ?>">
                        </div>
                    </div>

                    <div class="sec col">

                        <div class="cont-numeros">


                            <?php 
                                $i = 0;
                                foreach ($phone_numbers as $phone_number) {

                                    if ($i == 0) { ?>
                                        <div class="cont-input">
                                            <label for="phone_number">Numéro(s) de téléphone</label>
                                            <input type="text" name="phone_number_1" id="phone_number" placeholder="+3364585956271" value="<?= $phone_number['numero'] ?>">
                                        </div>
                                        <?php } else { ?>
                                        <div class="cont-input">
                                            <div class="cont-input-btn">
                                                <input type="text" name="<?='phone_number_'.$i+1 ?>" id="phone_number" placeholder="+3364585956271" value="<?= $phone_number['numero'] ?>">
                                                <button class="delete-btn" type="button">X</button>
                                            </div>
                                        </div>
                                    <?php }

                                    $i++;
                                }
                            
                            ?>
                        </div>
                        
                        <div class="cont-add-phone-number">
                            <button type="button" class="add-phone-number">
                                Ajouter un numéro de téléphone
                            </button>

                            <!-- Hidden input to know how much phone numbers there are -->
                            <input type="hidden" name="how_many_phone_numbers" value=<?= count($phone_numbers) ?>>
                        </div>
                    </div>

                    <div class="sec membership">
                        <label for="membership" class="subtitle">Membership :</label>

                        <?php 
                            if ($client['membership'] == "silver") { ?>
                                <div class="cont-input radio">
                                    <label for="silver">Silver</label>
                                    <input type="radio" name="membership" id="membership" value="silver" checked>
                                </div>
                            <?php } else { ?>
                                <div class="cont-input radio">
                                    <label for="silver">Silver</label>
                                    <input type="radio" name="membership" id="membership" value="silver">
                                </div>
                           <?php }
                            if ($client['membership'] == "gold") { ?>
                                <div class="cont-input radio">
                                    <label for="gold">Gold</label>
                                    <input type="radio" name="membership" id="membership" value="gold" checked>
                                </div>
                            <?php } else { ?>
                                <div class="cont-input radio">
                                    <label for="gold">Gold</label>
                                    <input type="radio" name="membership" id="membership" value="gold">
                                </div>
                           <?php }
                            if ($client['membership'] == "platinum") { ?>
                                <div class="cont-input radio">
                                    <label for="platinum">Platinum</label>
                                    <input type="radio" name="membership" id="membership" value="platinum" checked>
                                </div>
                            <?php } else { ?>
                                <div class="cont-input radio">
                                    <label for="platinum">Platinum</label>
                                    <input type="radio" name="membership" id="membership" value="platinum">
                                </div>
                           <?php }
                            if ($client['membership'] == "ultimate") { ?>
                                <div class="cont-input radio">
                                    <label for="ultimate">Ultimate</label>
                                    <input type="radio" name="membership" id="membership" value="ultimate" checked>
                                </div>
                            <?php } else { ?>
                                <div class="cont-input radio">
                                    <label for="ultimate">Ultimate</label>
                                    <input type="radio" name="membership" id="membership" value="ultimate">
                                </div>
                           <?php }
                        ?>
                    </div>
                </div>

                <h2 class="sec-title">Points</h2>

                <div class="section">
                    <div class="sec">
                        <div class="cont-input">
                            <label for="total_points">Nombre de points totaux</label>
                            <input type="text" name="total_points" id="total_points" class="locked" readonly value="<?= $sum ?>">
                        </div>
                    </div>

                    <div class="cont-points">

                    <?php 

                        $i = 0;
                        foreach($points as $point) { ?>
                        <div class="sec">
                            <div class="cont-input">
                                <label for="total_points">Nombre de points</label>
                                <input type="text" name="<?='points_'.$i+1 ?>" id="points" class="locked" readonly value="<?= $point['nb_points'] ?>">
                            </div>
                            <div class="cont-input">
                                <label for="total_points">Date d'expiration</label>
                                <input type="date" name="<?='exp_date_'.$i+1 ?>" id="exp_date" value="<?= $point['exp_date'] ?>" class="locked" readonly>
                            </div>
                            <div class="cont-input">
                                <button class="delete-btn" type="button">
                                    X
                                </button>
                            </div>
                        </div>
                        <?php $i++; }
                    
                    ?>
                    </div>

                    <div class="sec col">
                        <div class="cont-add-points">
                            <button type="button" class="add-points">
                                Ajouter des points
                            </button>
        
                            <!-- Hidden input to know how much phone numbers there are -->
                            <input type="hidden" name="how_many_points" value=<?= count($points) ?>>
                        </div>
                    </div>
            </div>

            <div class="cont-btns">
                <a href="search_client.html" class="cancel">
                    Annuler
                </a>

                <button type="submit">
                    Confirmer les modifications
                </button>
            </div>
        </form>
    </div>

</body>
</html>