<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_Location extends Controller_Ajax {
    
	public function action_set()
	{
        $coords = Arr::extract($_POST, array('lat', 'lng'));
        if (!$coords['lat'] OR !$coords['long'])
        {
            $this->template['success'] = false;
            $this->template['message'] = 'Omitted required params';
            return;
        }
        
        $response = Request::factory('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$coords['lat'].','.$coords['long'].'&sensor=false&language=ru')->execute();
        if ($response->status() != 200)
            return false;
        $data = json_decode($response->body(), true);
        //var_dump($data);
        if ($data['status'] != 'OK' OR !isset($data['results']))
        {
            $this->template['success'] = false;
            $this->template['message'] = 'Can not decode coordinates';
            return;
        }
            
        $data = $data['results'];
        $location = array();
        foreach ($data as $r)
        {
            if (isset($r['address_components']))
            {
                foreach ($r['address_components'] as $c) {
                    if (count($r['types']) === 2 AND in_array('political', $c['types']))
                    {
                        if (in_array('country', $c['types']))
                        {
                            $location['country'] = $c['long_name'];
                            $location['country_code'] = $c['short_name'];
                        }
                        elseif (in_array('administrative_area_level_1', $c['types']))
                        {
                            $location['region'] = $c['long_name'];
                        }
                        elseif (in_array('locality', $c['types']))
                        {
                            // russian city names fix
                            if (mb_strpos($c['long_name'], 'город') === 0)
                                $location['city'] = mb_substr ($c['long_name'], 6);
                            else
                                $location['city'] = $c['long_name'];
                        }
                    }
                    if (count($location) === 4)
                    break;
                }
            }
            
        }
        $current_city = ORM::factory('city')
            ->where('name', '=', $location['city'])
            ->find();
        if ($current_city->loaded())
        {
            $this->template['success'] = true;
            $this->template['total'] = 1;
            $this->template['data'] = $location;
            $location['ip'] = Request::$client_ip;
            Cookie::set('ipinfo', serialize($location), 7200);
            Cookie::set('cc', $current_city->id, 3600*24*7);
        }
        else
        {
            $this->template['success'] = false;
            $this->template['message'] = 'Can not set site location to outside the Russia';
            return;
        }
        
        
        
	}
}