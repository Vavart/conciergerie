<?php 

    include "sql_connection.php";

    // Gathering the fields values
    $product_name = $_POST['product_name'];
    $unit_price = doubleval($_POST['price']);
    $nb_dispo = intval($_POST['nb_dispo']);
    $status = $_POST['status'];

    // echo '<pre>',var_dump($_POST),'</pre>';
    // die();

    
    // Insert in the database
    $query = "INSERT INTO produit (`id_produit`, `product_name`, `unit_price`, `status`, `nb_dispo`) VALUES (0,'$product_name','$unit_price','$status','$nb_dispo')";

    $result = $connect->query($query);

    header('Location: ../pages/index.html');
    exit()
?>