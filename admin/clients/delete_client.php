<?php

   if(isset($_GET["id"])){
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "wbsif_db";

    // Create a connection to the database
    $connection = new mysqli($servername, $username, $password, $database);


    $sql = "DELETE FROM client_list WHERE id = $id";
    $connection->query($sql);


   } 

   header("location: /wbsif/admin/clients/list_clients.php");
        exit;


?>