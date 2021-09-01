<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather</title>

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

    <?php 
        if(isset($_SESSION["permission"])){

            if($_SESSION["permission"] === "true"){
                require_once("partials/header.php");
                echo "<div class='row' id='weatherCity'></div>
                      <div id='weatherForecast' class='row'></div>";

                echo "<script src='//openweathermap.org/themes/openweathermap/assets/vendor/owm/js/d3.min.js'></script>
                      <script src='js/weather.js'></script>";
            }
            else{

                echo "<p>Sorry, you can't access this site without giving us permissions.</p>
                        <p>Can we use your IP and GPS data now ?</p>";
                echo "<input type='button' class='btn btn-info' value='Yes' onclick='setSession(this.value)'>";
                echo "<script src='js/permissions.js'></script>";
            }
        } 
        else{
            echo "Can we use your IP and GPS data ? <br>
                  <input type='button' class='btn btn-info' value='Yes' onclick='setSession(this.value)'>
                  <input type='button' class='btn btn-info' value='No' onclick='setSession(this.value)'>";
            
            echo "<script src='js/permissions.js'></script>";
            
        }

        require_once("partials/footer.php");
    ?>
</body>
</html>