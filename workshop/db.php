<?php
    
    $server = "localhost";
    $database = "school_db";
    $username = "root";
    $password ="";

    try{
    $pdo = new PDO("mysql:host=$server;dbname=$database;",$username, $password);
    
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        die("Unable to connect to the database".$e->getMessage());
    }
?>