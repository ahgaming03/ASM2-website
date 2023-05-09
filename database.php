<?php

    $db_server = "localhost";
    $db_user = "useradmin";
    $db_pass =  "FiSEIwp]uhxJZ4ol";
    $db_name = "phone_case_business";
    $conn ="";

    try{
        $conn = mysqli_connect($db_server, 
                           $db_user,
                           $db_pass, 
                           $db_name);
    }
    catch(mysqli_sql_exception)
    {
        echo"<script>console.error('Could not connect to database!')</script>";
    }
  
    // echo"Connected";
?>