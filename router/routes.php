<?php
 
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    require_once("../controllers/SiteVisitsController.php");

    use Pecee\SimpleRouter\SimpleRouter as Router;

    // stats for country
    Router::get("Prelude_to_Foundation/router/stats/{country_code}", "SiteVisitsController@getCountryStats");

    // list of cities ip
    Router::get("Prelude_to_Foundation/router/list/cities", "SiteVisitsController@getCitiesIp");

    // insert info who visited our main page
    Router::post("Prelude_to_Foundation/router/visit", function(){
        
        $siteVisitsController = new SiteVisitsController();

        $input = input()->all();
       
        $ip = $input["ip"];
        $city = $input["city"];
        $country = $input["country"];
        $country_code = $input["country_code"];

        $timezone = new DateTimeZone($input["timezone"]);
        $now = new DateTime("now", $timezone);
        
        return $siteVisitsController->insertVisit($ip, $city, $country, $country_code, $now);
    });

    // log visits of all sites 
    Router::post("Prelude_to_Foundation/router/log", function(){
        
        $siteVisitsController = new SiteVisitsController();

        $input = input()->all();
       
        $ip = $input["ip"];
        $site = $input["site"];

        $timezone = new DateTimeZone($input["timezone"]);
        $now = new DateTime("now", $timezone);
        
        return $siteVisitsController->insertSiteLog($ip, $site, $now);
    });

    // set session based on ip and gps permission
    Router::post("Prelude_to_Foundation/router/permission", function(){
        
        $input = input()->all();
        
        $permission = $input["permission"];

        $_SESSION["permission"] = $permission;
    });
?>