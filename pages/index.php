<!DOCTYPE html>
<html lang="fr">
<head>

    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conciergerie</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/style.css">

    <?php 
    
    $notification = '';
    if (isset($_REQUEST['msg'])) {
        if ($_REQUEST['msg'] == 'error') {
            $notification = "Une erreur s'est produite, veuillez réessayer";
        }
    }

    ?>

</head>
<body>

<div class="page">
    
    <h1>Que souhaitez-vous faire ?</h1>
    <p class="notification"><?= $notification ?></p>
    
    <main>
        <div class="cat client">
    
            <div class="big-pic">
                <div class="pic">
                    <img src="../assets/client.svg" alt="client pictograph">
                </div>
                <h2>Client</h2>
            </div>
    
            <div class="cont-actions">
                <a href="add_client.html" class="action">
                    <div class="pic">
                        <img src="../assets/add_client.svg" alt="add client pictograph">
                    </div>
                    <p>Ajouter un nouveau client</p>
                </a>
                <a href="search_client.php" class="action">
                    <div class="pic">
                        <img src="../assets/search.svg" alt="search pictograph">
                    </div>
                    <p>Rechercher un client existant</p>
                </a>
            </div>
            
        </div>
    
        <div class="cat article">
    
            <div class="big-pic">
                <div class="pic">
                    <img src="../assets/article.svg" alt="article pictograph">
                </div>
                <h2>Article</h2>
            </div>
    
            <div class="cont-actions">
                <a href="add_article.html" class="action">
                    <div class="pic">
                        <img src="../assets/add_article.svg" alt="add article pictograph">
                    </div>
                    <p>Ajouter un article</p>
                </a>
            </div>
    
        </div>
    
        <div class="cat commands">
    
        <div class="big-pic">
            <div class="pic">
                <img src="../assets/command.svg" alt="command pictograph">
            </div>
            <h2>Commandes</h2>
        </div>
    
        <div class="cont-actions">
            <a href="add_command.php" class="action">
                <div class="pic">
                    <img src="../assets/add_command.svg" alt="add command pictograph">
                </div>
                <p>Passer une nouvelle commande</p>
            </a>
            <a href="search_commands.php" class="action">
                <div class="pic">
                    <img src="../assets/search.svg" alt="search pictograph">
                </div>
                <p>Rechercher une commande existante</p>
            </a>
        </div>
    
        </div>
    
    </main>

</div>
    

</body>
</html>