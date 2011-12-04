$(function(){
    
    // Set up google map with current school-location
    var lat,lng, schMarker;
    lat = $('#school-location-map').data('lat');
    lng = $('#school-location-map').data('lng');
    app.schoolLocMap = new google.maps.Map(
        $('#school-location-map').get(0),
        app.GMapsOptions(lat, lng)
    );
    schMarker = new google.maps.Marker({
        position: new google.maps.LatLng(lat,lng),
        title: $('.page-header > h1').data('school-name')
    });
    schMarker.setMap(app.schoolLocMap);
    
    
    $('.star').rating(); 
    
    $('#send-rating').on('click', function(){
        var rating_data = {},
            fields = ['espacio_fisico', 'seguridad', 'instalaciones_educativas', 'organizacion', 'proceso_educativo', 'valores', 'otros_recursos'],
            err, data = [], pros, cons;
        $.each(fields, function(k,v){
            var item = $('[name='+v+']:checked');
            console.log(item);
            if (!item.size())
                err = true;
            else
                data.push({v: item.val()})
        });
        if (!err) {
            pros = $('#pros').val();
            if (pros.length < 1)
                err = true;
            else data.push({"pros": pros});
            cons = $('#cons').val();
            if (cons.length < 1)
                err = true;
            else data.push({"cons": cons});
        }
        if (!err) {
            data.push({"school": app.school_id});
            $.post('/ajax/school/rate.json', data, function(r){
                if (r.success == true) {
                    window.location = r.data.rated_url;
                    window.location.reload();
                }
            });
        } else {
            
        }
    });
});


