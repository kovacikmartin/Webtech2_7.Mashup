function getIp(){

    return $.get("https://ipapi.co/ip/", function(data){});
}

function getIpGeoData(ip){

    return $.get("https://ipapi.co/" + ip + "/json/", function(data){});
}

$.when(getIp()).done(function(ip){
    $.when(getIpGeoData(ip)).done(function(geoData){
        
        let userLocInfoIp = document.getElementById("userLocInfoIp");
        let userLocInfoCity = document.getElementById("userLocInfoCity");
        userLocInfoIp.innerHTML = "";

        userLocInfoIp.innerHTML += `Your IP: ` + ip + `<br>
                                    Latitude: ` + geoData["latitude"] + `<br>
                                    Longitude: ` + geoData["longitude"] + `<br>`;
        
    
        userLocInfoCity.innerHTML += `City: ` + geoData["city"] + `<br>
                                      Country: ` + geoData["country_name"] + `<br>
                                      Capital: ` + geoData["country_capital"] + `<br>`;


        let dataLog = {};

        dataLog["ip"] = ip;
        dataLog["site"] = "loc_info";
        dataLog["timezone"] = geoData["timezone"];

        $.ajax({
            method: "POST",
            url: "https://wt82.fei.stuba.sk/Prelude_to_Foundation/router/log",
            data: dataLog,
            dataType: "text",
            success: function(data){
    
            }
        });
    });
})