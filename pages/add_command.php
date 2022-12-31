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

</head>
<body>

<div class="page">
    
    <div class="cont-header">
        <h1>Passer une nouvelle commande</h1>
        <a href="index.html" class="menu">
            Retour à l'accueil
        </a>
    </div>

    <form action="../php/add_command_handler.php" method="post">

        <div class="main-form">


            <!-- Client -->
            <h2 class="sec-title">Informations client</h2>
            <div class="section">
                
                <div class="sec">
                    <div class="cont-input">
                        <label for="numero">Numéro client</label>
                        <input type="text" name="numero" id="numero" class="locked" readonly>
                    </div>
                    <div class="cont-input">
                        <label for="code">Code client</label>
                        <input type="text" name="code" id="code" class="locked" readonly>
                    </div>
                </div>
                <div class="sec">
                    <div class="cont-input">
                        <label for="name_surname">Prénom / Nom</label>
                        <input type="text" name="name_surname" id="name_surname" class="locked" readonly>
                    </div>
                    <div class="cont-input">
                        <label for="mail">Mail</label>
                        <input type="email" name="mail" id="mail" class="locked" readonly>
                    </div>
                </div>

                <div class="cont-choose-client">
                    <a href="choose_client.php" class="choose-client">
                        Choisir un autre client
                    </a>
                </div>
            </div>
            
            <!-- Products -->
            <h2 class="sec-title">Produits</h2>

            <div class="section product-section">
                <!-- Products will be displayed here -->
            </div>

            <div class="sec col">
                <div class="cont-choose-product">
                    <a href="choose_product.php" class="choose-product">
                        Ajouter un produit
                    </a>
                </div>
            </div>


            <!-- Payment -->
            <h2 class="sec-title">Réglement</h2>
            <div class="section cont-payment">
                <!-- Payments will be there -->
            </div>

            <div class="sec col">
                <div class="cont-add-payment">
                    <button class="add-payment" type="button">
                        Ajouter un paiement
                    </button>
                    <input type="hidden" name="how_many_payments">
                </div>
            </div>

            <!-- Note -->
            <h2 class="sec-title">Note</h2>
            <div class="section cont-note">
                <textarea name="command_note" id="command_note" placeholder="Un item est cassé"></textarea>
            </div>

            <!-- Status -->
            <div class="sec col">
                <label for="status" class="subtitle">Statut :</label>

                <div class="cont-input radio">
                    <input type="radio" name="status" id="status" value="to_buy" checked>
                    <label for="stock">À acheter</label>
                </div>
                <div class="cont-input radio">
                    <input type="radio" name="status" id="status" value="bought">
                    <label for="available">Acheté</label>
                </div>
                <div class="cont-input radio">
                    <input type="radio" name="status" id="status" value="packed">
                    <label for="packed">Emballé</label>
                </div>
                <div class="cont-input radio">
                    <input type="radio" name="status" id="status" value="shipped">
                    <label for="dispatched">Expédiée</label>
                </div>
                <div class="cont-input radio">
                    <input type="radio" name="status" id="status" value="arrived">
                    <label for="arrived">Arrivée</label>
                </div>
                <div class="cont-input radio">
                    <input type="radio" name="status" id="status" value="delivered">
                    <label for="delivered">Livrée</label>
                </div>
                <div class="cont-input radio">
                    <input type="radio" name="status" id="status" value="done">
                    <label for="other">Terminée</label>
                </div>
            </div>


            <!-- Recap -->
            <h2 class="sec-title">Récapitulatif</h2>
            <div class="section recap">
                <div class="sec">
                    <div class="cont-input">
                        <label for="">Frais de livraison (en €)</label>
                        <input type="number" name="delivery_fee" id="delivery_fee" placeholder="0">
                    </div>
                    <div class="cont-input">
                        <label for="">Frais de service (en €)</label>
                        <input type="number" name="service_fee" id="service_fee" placeholder="0">
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
            <a href="index.html" class="cancel">
                Annuler
            </a>

            <button type="submit">
                Passer commande
            </button>
        </div>


    </form>
   
</div>
    

</body>
</html>