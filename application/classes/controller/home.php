<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Home extends Controller_Frontend {
    
	public function action_index()
	{
		$this->template->set_filename('pages/home.twig');
        $this->template->scripts[] = 'assets/js/home.js';
        
        if (isset($this->current_location))
        {
            $response = Request::factory('http://maps.googleapis.com/maps/api/geocode/json?address='.  urlencode($this->current_city['name']).','.urlencode($this->current_city['region_name']).',Chile&sensor=false&language=es')->execute()->body();
            $response = json_decode($response, true);

            if (isset($response['results'][0]))
            {
                $this->current_location = $current_location = array(
                    'lng' => $response['results'][0]['geometry']['location']['lng'],
                    'lat' => $response['results'][0]['geometry']['location']['lat'],
                );
                Cookie::set('curloc', serialize($current_location), 3600*24*7);
            }
        }
            
        $closest_schools = DB::query(Database::SELECT, '
        SELECT *,
        ((2 * 3960 *
        ASIN(
            SQRT(
                POWER(SIN((RADIANS('.$this->current_location['lat'].' - latitude))/2), 2) +
                COS(RADIANS(latitude)) *
                COS(RADIANS('.$this->current_location['lat'].')) *
                POWER(SIN((RADIANS('.$this->current_location['lng'].' - longitude))/2), 2)
            )
        )
        )) AS distance
        FROM `schools` ORDER BY distance ASC LIMIT 10;
        ')
        ->execute()->as_array();
        $this->template->closets_schools = $closest_schools;
        $this->template->inline_script .= '
            app.cities = [';
        foreach ($closest_schools as $sch)
        {
            $rs[] ='{name: "'.$sch['name'].'", lat: "'.$sch['latitude'].'", lng: "'.$sch['longitude'].'"}';
        }
        $this->template->inline_script .= implode(', ', $rs);
        $this->template->inline_script .= '];
            ';
	}

} // End Welcome
