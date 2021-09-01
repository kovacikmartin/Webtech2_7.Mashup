function getIp(){

    return $.get("https://ipapi.co/ip/", function(data){});
}

function getIpGeoData(ip){

    return $.get("https://ipapi.co/" + ip + "/json/", function(data){});
}

$.when(getIp()).done(function(ip){
    $.when(getIpGeoData(ip)).done(function(geoData){
        
        let dataVisit = {};

        dataVisit["ip"] = ip;
        dataVisit["city"] = geoData["city"];
        dataVisit["country"] = geoData["country_name"];
        dataVisit["country_code"] = geoData["country_code"];
        dataVisit["timezone"] = geoData["timezone"];
        
        $.ajax({
            method: "POST",
            url: "https://wt82.fei.stuba.sk/Prelude_to_Foundation/router/visit",
            data: dataVisit,
            dataType: "text",
            success: function(data){
    
            }
        });

        let dataLog = {};

        dataLog["ip"] = ip;
        dataLog["site"] = "weather";
        dataLog["timezone"] = geoData["timezone"];

        $.ajax({
            method: "POST",
            url: "https://wt82.fei.stuba.sk/Prelude_to_Foundation/router/log",
            data: dataLog,
            dataType: "text",
            success: function(data){
    
            }
        });

        let lat = geoData["latitude"];
        let lon = geoData["longitude"];
        let apiKey = "665b00c68414189cd3d27341c8e27367";
   
        $.get("https://api.openweathermap.org/data/2.5/onecall?lat="+lat+"&lon="+lon+"&exclude=hourly,minutely,alerts&units=metric&appid="+apiKey, function(data){

            let date;
            let temp;
            let tempFeel;
            let wind;
            let status;
            let weatherForecast = document.getElementById("weatherForecast");
            
            document.getElementById("weatherCity").innerHTML = "<h3 class='mx-auto'>Weather in " + geoData["city"] + "</h3>";
            weatherForecast.innerHTML = "";

            for(i=0; i < 6; i++){
                
                date = new Date(data["daily"][i].dt*1000);
                date = date.getFullYear() +"/"+(date.getMonth()+1)+"/"+date.getDate();
                
                temp = data["daily"][i]["temp"].day;
                tempFeel = data["daily"][i]["feels_like"].day;
    
                status = data["daily"][i]["weather"][0].main;
                wind = data["daily"][i].wind_speed;
                weatherForecast.innerHTML += `<div class='col'><b>` + date + `</b><br>
                                              Day temp: ` + temp + ` °C<br>
                                              Feels like: ` + tempFeel + ` °C<br>
                                              Status: ` + status + `<br>
                                              Wind: ` + wind + `m/s<br>`;
            }
        });
    });
})

