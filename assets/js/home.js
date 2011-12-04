$(function(){
    
    app.curLatLng = new google.maps.LatLng(app.curLoc[0],app.curLoc[1]);
    app.mapOptions =  {
        zoom: 13,
        center: app.curLatLng,
        mapTypeId: google.maps.MapTypeId.SATELLITE
    }

    app.map = new google.maps.Map(document.getElementById("map_canvas"), app.mapOptions);

    app.mapPoints = [];
    
    for (var i=0;i<app.cities.length;i++) {
        app.mapPoints.push({
            marker: new google.maps.Marker({
                position: new google.maps.LatLng(app.cities[i]['lat'],app.cities[i]['lng']),
                map: app.map,
                title: app.cities[i]['name']
            }),
            info: new google.maps.InfoWindow({
                content: app.cities[i]['name']
            })
        });
    }

});
