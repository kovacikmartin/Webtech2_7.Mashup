<?php

require_once("Database.php");

class SiteVisitsController{

    private PDO $conn;

    public function __construct(){
        $this->conn = (new Database())->getConnection();
    }

    private function sameDayVisit($ip, $timestamp){

        $timestamp = $timestamp->format('Y-m-d');

        try{

            $sql = "SELECT timestamp FROM t_site_visit WHERE ip = ? ORDER BY timestamp DESC LIMIT 1";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmnt = $this->conn->prepare($sql);
            $stmnt->execute([$ip]);

            $result = $stmnt->fetch(PDO::FETCH_COLUMN);

            $date = DateTime::createFromFormat('Y-m-d H:i:s', $result);

            // date is not false -> createFromFormat had valid input
            if($date){
                $date = $date->format('Y-m-d');

            
                if($timestamp > $date){
                    return false;
                }
                else if($timestamp == $date){
                    return true;
                }    
            }
        }
        catch(PDOException $e){

            echo "<div class='alert alert-danger' role='alert'>
                    Sorry, there was an error. " . $e->getMessage()."
                </div>";
        }
    }

    private function sameDayLog($ip, $site, $timestamp){

        $timestamp = $timestamp->format('Y-m-d');

        try{

            $sql = "SELECT timestamp FROM t_site_log WHERE ip = ? AND site = ? ORDER BY timestamp DESC LIMIT 1";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmnt = $this->conn->prepare($sql);
            $stmnt->execute([$ip, $site]);

            $result = $stmnt->fetch(PDO::FETCH_COLUMN);

            $date = DateTime::createFromFormat('Y-m-d H:i:s', $result);

            // date is not false -> createFromFormat had valid input
            if($date){
                $date = $date->format('Y-m-d');

            
                if($timestamp > $date){
                    return false;
                }
                else if($timestamp == $date){
                    return true;
                }    
            }
        }
        catch(PDOException $e){

            echo "<div class='alert alert-danger' role='alert'>
                    Sorry, there was an error. " . $e->getMessage()."
                </div>";
        }
    }

    public function insertSiteLog($ip, $site, $timestamp){

        $sameDayLog = $this->sameDayLog($ip, $site, $timestamp);
        
        if(!$sameDayLog){

            try{

                $timestamp = $timestamp->format('Y-m-d H:i:s');

                $sql = "INSERT INTO t_site_log(ip, site, timestamp)
                                    VALUES(?, ?, ?)";

                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmnt = $this->conn->prepare($sql);
                $stmnt->execute([$ip, $site, $timestamp]);

            }
            catch(PDOException $e){

                echo "<div class='alert alert-danger' role='alert'>
                        Sorry, there was an error. " . $e->getMessage()."
                    </div>";
            }
        }
    }

    public function insertVisit($ip, $city, $country, $country_code, $timestamp){

        $sameDayVisit = $this->sameDayVisit($ip, $timestamp);
        
        if(!$sameDayVisit){
            try{

                $timestamp = $timestamp->format('Y-m-d H:i:s');

                $sql = "INSERT INTO t_site_visit(ip, city, country, country_code, timestamp)
                                    VALUES(?, ?, ?, ?, ?)";
    
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmnt = $this->conn->prepare($sql);
                $stmnt->execute([$ip, $city, $country, $country_code, $timestamp]);
    
            }
            catch(PDOException $e){
    
                echo "<div class='alert alert-danger' role='alert'>
                        Sorry, there was an error. " . $e->getMessage()."
                    </div>";
            }
        }
    }

    public function getAllCountriesStats(){


        try{

            $sql = "SELECT country, country_code, COUNT(ip) AS 'total_visits' FROM t_site_visit GROUP BY country, country_code";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmnt = $this->conn->query($sql);

            return $stmnt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            echo "<div class='alert alert-danger' role='alert'>
                        Sorry, there was an error. " . $e->getMessage()."
                    </div>";
        }
    }

    public function getCountryStats($country_code){

        try{

            $sql = "SELECT city, COUNT(ip) AS 'total_visits' FROM t_site_visit WHERE country_code = ? GROUP BY city, country_code";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmnt = $this->conn->prepare($sql);
            $stmnt->execute([$country_code]);

            return json_encode($stmnt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        catch(PDOException $e){
            echo "<div class='alert alert-danger' role='alert'>
                        Sorry, there was an error. " . $e->getMessage()."
                    </div>";
        }
    }

    public function getCitiesIp(){
        
        try{

            $sql = "SELECT DISTINCT(city), ip FROM t_site_visit";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmnt = $this->conn->query($sql);

            return json_encode($stmnt->fetchAll(PDO::FETCH_ASSOC), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        catch(PDOException $e){
            echo "<div class='alert alert-danger' role='alert'>
                        Sorry, there was an error. " . $e->getMessage()."
                    </div>";
        }

    }

    public function getVisitHours(){

        try{

            $sql = "SELECT 
                        CASE 
                            WHEN HOUR(timestamp) BETWEEN 6  AND 15 THEN '6:00-15:00'
                            WHEN HOUR(timestamp) BETWEEN 15 AND 21 THEN '15:00-21:00'
                            WHEN HOUR(timestamp) BETWEEN 21 AND 24 THEN '21:00-24:00'
                            ELSE '00:00-6:00'
                        END AS `time`,
                        COUNT(*) AS 'visits'
                    FROM t_site_visit
                    GROUP BY `time`
                    ORDER BY `time` ASC";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmnt = $this->conn->query($sql);

            return $stmnt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            echo "<div class='alert alert-danger' role='alert'>
                        Sorry, there was an error. " . $e->getMessage()."
                    </div>";
        }
    }

    public function getMostVisitedSite(){

        try{

            $sql = "SELECT site, COUNT(site) AS 'visits' FROM t_site_log GROUP BY site ORDER BY COUNT(site) DESC LIMIT 1";

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmnt = $this->conn->query($sql);

            return $stmnt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e){
            echo "<div class='alert alert-danger' role='alert'>
                        Sorry, there was an error. " . $e->getMessage()."
                    </div>";
        }

    }
}
?>