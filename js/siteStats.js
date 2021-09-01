$(document).ready(function(){

    $('#statsAllCountriesTable').DataTable({
    
        "searching": false,
        "paging": false,
        "info": false,
        "scrollY": "350px",
        "scrollCollapse": true

    }).columns.adjust();
});

function getIp(){

    return $.get("https://ipapi.co/ip/", function(data){});
}

function getIpGeoData(ip){

    return $.get("https://ipapi.co/" + ip + "/json/", function(data){});
}

$.when(getIp()).done(function(ip){
    $.when(getIpGeoData(ip)).done(function(geoData){
        
        let dataLog = {};

        dataLog["ip"] = ip;
        dataLog["site"] = "stats";
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

function showCountryDetailModal(country_code){

    $.get("https://wt82.fei.stuba.sk/Prelude_to_Foundation/router/stats/"+country_code, function(data){
        
        $('#countryStatsTable').DataTable({

            "order": [],
            "searching": false,
            "paging": false,
            "info": false,
            "destroy": true,
            "data": data,
            "columns": [
                { "data": "city", "title": "City"},
                { "data": "total_visits", "title": "Visits"}
            ]
        }).columns.adjust();

        $('#countryStats').modal('show');
    });
}

function getVisitorCitiesIp(){

    return $.get("https://wt82.fei.stuba.sk/Prelude_to_Foundation/router/list/cities", function(data){});
}

function getCityCoordDataWithIp(ip){

    //return $.get("https://api.openweathermap.org/geo/1.0/direct?q="+city+"&limit=1&appid=665b00c68414189cd3d27341c8e27367", function(data){});
    return $.get("https://ipapi.co/" + ip + "/json/", function(data){});
}

$.when(getVisitorCitiesIp()).done(function(ip){
    for(i in ip){
        $.when(getCityCoordDataWithIp(ip[i]["ip"])).done(function(data){

            L.marker([data["latitude"], data["longitude"]]).addTo(mymap);
        });
    }
})

var mymap = L.map('map', {minZoom: 1, maxZoom: 18}).fitWorld();

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoieGtvdmFjaWttNiIsImEiOiJja2d1dzdqY20wejF4MnNwOTFvMHZ6bjkyIn0.Srve4i5c9BhBV3nW9t8Ryg', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1
}).addTo(mymap);