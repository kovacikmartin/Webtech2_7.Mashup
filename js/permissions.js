function setSession(value){

    let data = {};
    data["permission"] = value === "Yes" ? "true" : "false";

    $.ajax({
        method: "POST",
        url: "https://wt82.fei.stuba.sk/Prelude_to_Foundation/router/permission",
        data: data,
        dataType: "text",
        success: function(data){

            window.location.replace("https://wt82.fei.stuba.sk/Prelude_to_Foundation");

           
        },
        error: function(ex){

            console.log("ERROR setSession()" + ex);
        }
    });
}