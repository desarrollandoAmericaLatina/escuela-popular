if (navigator.geolocationn) {
    navigator.geolocation.getCurrentPosition(function(position) {
        
        $.post('/ajax/location/set.json', {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        }, function(r){
            if (r.success == true) {
                $('#location').show();
                $("#country").html(r.data.country);
                $("#region").html(r.data.region);
                $("#city").html(r.data.city);
            }
            console.log(r);
            $("#location-preloader").hide();
        });
        
        document.getElementById('latitude').innerHTML = position.coords.latitude;
        document.getElementById('longitude').innerHTML = position.coords.longitude;
    }); 
}

app.citySelectRefresh = function(input){
    var sstring = $(input).val();
    if (sstring.length == 0)
        return false;

    $.post(
        '/ajax/cities/find.json',
        {
            s: sstring
        },
        function(r){
            if (r.success == false || r.total == 0)
                return false;
            var regs = {}, select = '<option value=""></option>';
            $.each(r.data, function(k,v){
                k = "_"+v.region_id;
                if (typeof regs[k] == 'undefined') {
                    regs[k] = {
                        'id': v.region_id,
                        'name': v.region_full_name,
                        'cities': []
                    };
                }
            });
            $.each(r.data, function(k,v) {
                k = "_"+v.region_id;
                regs[k].cities.push({
                    'id': v.id,
                    'name': v.name
                });
            });

            $.each(regs, function(k,v) {
                select += '<optgroup label="'+v.name+'" data-region-id="'+v.id+'">';
                $.each(v.cities, function(kk,vv){
                    select += '<option value="'+vv.id+'">'+vv.name+'</option>';

                });
                select += '</optgroup>';
            });
            $('#city_select').html(select);
            $("#city_select").trigger("liszt:updated");

        },
        'json'
    );
}

app.GMapsOptions = function(lat, lng) {
    var LatLng = new google.maps.LatLng(lat, lng);
    return {
        zoom: 15,
        center: LatLng,
        mapTypeId: google.maps.MapTypeId.SATELLITE
    }
};

$(function(){
    $('.dropdown').dropdown();
    $('[rel=twipsy]').twipsy();
    
    $("#city_select").chosen({no_results_text: "No se ha encontrado el lugar"});
    
    $('#city_select').on('change', function() {
        console.log('change');
    });
    $('#city_select_chzn input').on('keyup', function() {
        if (window.CSTimer)
            window.clearTimeout(CSTimer);
        window.CSTimer = window.setTimeout(app.citySelectRefresh, 750, this);

    });
});
