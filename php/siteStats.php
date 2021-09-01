<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once("../controllers/SiteVisitsController.php");

$siteVisitsController = new SiteVisitsController();

$countriesStats = $siteVisitsController->getAllCountriesStats();
$visitHours = $siteVisitsController->getVisitHours();
$mostVisitedSite = $siteVisitsController->getMostVisitedSite();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site stats</title>

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="../css/siteStats.css">
</head>
<body>
    <?php 
        if(isset($_SESSION["permission"])){
            if($_SESSION["permission"] === "true"){
                require_once("../partials/header.php");
    ?>
                <div id="statsAllCountries">
                    <h3 class="text-center">Visits and location</h3>
                    <table id="statsAllCountriesTable">
                        <thead>
                        <tr>
                            <?php
                                echo "<th>Flag</th>";
                                echo "<th>Country</th>";
                                echo "<th>Total visits</th>";
                            ?>
                        </tr>
                        </thead>

                        <tbody>
                        <?php

                            foreach($countriesStats as $key => $country){

                                echo "<tr>";

                                echo "<td><img src='https://flagcdn.com/w80/".strtolower($country['country_code']).".png' width='80' alt='". $country['country'] ." flag'</td>";
                                echo "<td onclick=showCountryDetailModal('".$country['country_code']."');>" . $country['country'] . "</td>";
                                echo "<td>" . $country['total_visits'] . "</td>";

                                echo "</tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                </div>

                <div class='modal' id='countryStats' tabindex='-1' role='dialog'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title'>Visits by cities</h5>
                            </div>
                            <div class='modal-body'>
                                <table id='countryStatsTable'>
                                </table>
                                
                            </div>
                            <div class='modal-footer'>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mapContainer">
                    <h3 class="text-center">Visitors' location</h3>
                    <div id="map" style="height: 300px;"></div>
                </div>
                
                <div class="visitHoursContainer">
                    <h3 class="text-center">Visits by hours</h3>
                    <div id="visitHours" class="row">
                        <div class="col text-center">
                            <?php
            
                                foreach($visitHours as $hour){

                                    echo "<b>".$hour["time"].": </b>".$hour["visits"]."<br>";
                                }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="mostVisitedSiteContainer">
                    <h3 class="text-center">Most visited site</h3>
                    <div class="row">
                        <div class="col text-center">
                            <?php
                                $site;

                                if($mostVisitedSite[0]["site"] === "weather"){
                                    $site = "Weather";
                                }
                                elseif($mostVisitedSite[0]["site"] === "loc_info"){
                                    $site = "Location info";
                                }
                                else{
                                    $site = "Site stats";
                                }

                                echo $site . " (" . $mostVisitedSite[0]["visits"] .")";
                            ?>
                        </div>
                    </div>
                </div>
    <?php
                echo "<script src='../js/siteStats.js'></script>";
            }
            else{

                echo "<p>Sorry, you can't access this site without giving us permissions.</p>
                        <p>Can we use your IP and GPS data now ?</p>";
                echo "<input type='button' class='btn btn-info' value='Yes' onclick='setSession(this.value)'>";
                echo "<script src='../js/permissions.js'></script>";
            }
        }
        else{
            echo "Can we use your IP and GPS data ? <br>
                <input type='button' class='btn btn-info' value='Yes' onclick='setSession(this.value)'>
                <input type='button' class='btn btn-info' value='No' onclick='setSession(this.value)'>";
            
            echo "<script src='../js/permissions.js'></script>";
            
        }

        require_once("../partials/footer.php");
    ?>
</body>
</html>