<?php 

    include "sql_connection.php";

    // Gathering the fields values
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $address = $_POST['address'];
    $mail = $_POST['mail'];
    $facebook = $_POST['facebook'];
    $instagram = $_POST['instagram'];
    $membership = $_POST['membership'];

    // Create a code for the client
    $code_year = date("y");
    $code_name = "SPR";

    $query_code = "SELECT COUNT(*) FROM client";
    $result_query_code = $connect->query($query_code);
    $rows = $result_query_code->fetch_all(MYSQLI_ASSOC);
    $code_value = $rows[0]['COUNT(*)'] + 1;    
    $code_value = str_pad($code_value, 4, '0', STR_PAD_LEFT);
    $code = $code_year."-".$code_name."-".$code_value;
    
    // Insert in the database
    $query = "INSERT INTO `client` (`id_client`, `name`, `surname`, `code`, `postal_address`, `facebook_username`, `instagram_username`, `email`, `membership`, `next_discount`) VALUES (0,'$prenom','$nom','$code','$address','$facebook','$instagram','$mail','$membership',0)";

    $result = $connect->query($query);


    // For the phone number

    // Select the id of the client lately inserted
    $query = "SELECT id_client FROM client WHERE code='$code'";
    $result = $connect->query($query);
    $id_client = $result->fetch_all(MYSQLI_ASSOC);
    $id_client = $id_client[0]['id_client'];

    

    $how_many_phone_numbers = $_POST['how_many_phone_numbers'];
    for ($i = 0; $i < $how_many_phone_numbers; $i++) {
        $key = 'phone_number_'.($i+1);
        $phone_number = $_POST[$key];

        $query = "INSERT INTO telephone (`id_telephone`, `id_client`, `numero`) VALUES (0,'$id_client','$phone_number')";
        $result = $connect->query($query);
    }

    header('Location: ../pages/client.php?id='.$code);
    exit()
?>