<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Basic frontend actions
 *
 * @author Oleh Burkhay <atma@atmaworks.com>
 */
class Controller_Frontend extends Controller_Core
{

	/**
	 * Controls access for the whole controller
	 *
	 * Can be set to a string or an array, for example array('login', 'admin') or 'login'
	 */
	public $auth_required = FALSE;

	/** Controls access for separate actions
	 *
	 * Examples:
	 * 'adminpanel' => 'admin' will only allow users with the role admin to access action_adminpanel
	 * 'moderatorpanel' => array('login', 'moderator') will only allow users with the roles login and moderator to access action_moderatorpanel
	 */
	public $secure_actions = array(
		
	);

    public function before() {
        parent::before();
        
        if ($visited_cities = Cookie::get('vc'))
        {
            $visited_cities = explode(',', $visited_cities);
        }
        $current_city = Cookie::get('cc');
        $ipinfo = Cookie::get('ipinfo');
        
        if ($ipinfo OR !$current_city)
        {
            // Check for the info stored by ajax
            if ($ipinfo)
            {
                $ipinfo_data = unserialize($ipinfo);
                if ($ipinfo_data['ip'] != Request::$client_ip)
                {
                    Cookie::delete('ipinfo');
                    unset($ipinfo_data);
                }
            }

            // Try to detect location
            // We are working only with Chilean schools for now 
            if (!isset($ipinfo_data) OR $ipinfo_data['country_code'] != 'CL')
            {
                $ipinfo = IpInfo::factory();
                // Loads default location
                if (!$ipinfo->loaded() OR $ipinfo->country_code() != 'CL')
                {
                    $ipinfo = IpInfo::factory(Kohana::$config->load('ipinfo')->default_ip);
                }
                $ipinfo_data = array(
                    'ip' => $ipinfo->ip(),
                    'country_code' => $ipinfo->country_code(),
                    'country' => $ipinfo->country_name(),
                    'region' => $ipinfo->region(),
                    'city' => $ipinfo->city(),
                    'latitude' => $ipinfo->latitude(),
                    'longitude' => $ipinfo->longitude(),
                );
                $current_location = array(
                    'lng' => $ipinfo_data['longitude'],
                    'lat' => $ipinfo_data['latitude'],
                );
                Cookie::set('curloc', serialize($current_location), 3600*24*7);
            }
            Cookie::delete('ipinfo');
            if (!$current_city OR ($visited_cities AND !in_array($current_city, $visited_cities)) OR !$visited_cities)
            {
                $current_city = ORM::factory('city')
                    ->where('name', '=', $ipinfo_data['city'])
                    ->find()->id;
            }
            $visited_cities = $visited_cities ? array_merge(array($current_city), $visited_cities) : array($current_city);
            $visited_cities = array_unique($visited_cities);
        }

        if ($current_city AND !in_array($current_city, $visited_cities) AND count($visited_cities) >= 20)
        {
            array_pop($visited_cities);
            array_unshift($visited_cities, $current_city);
        }
        if ($current_city)
            Cookie::set('cc', $current_city, 3600*24*7);
        Cookie::set('vc', implode(',', $visited_cities), 3600*24*7);
        
        $cities = DB::select('cities.*', array('regions.id', 'region_id'), array('regions.name', 'region_name'), array('regions.full_name', 'region_full_name'))
                ->from('cities')
                ->join('regions')->on('regions.id', '=', 'cities.region_id')
                ->where('cities.id', 'IN', $visited_cities)
                ->order_by('region_name', 'ASC')
                ->order_by('name', 'ASC')
                ->as_assoc()->execute();
        
        $regions = array();
        foreach ($cities as $city)
        {
            if (!isset($regions[$city['region_id']]))
                $regions[$city['region_id']] = array(
                    'id' => $city['region_id'],
                    'name' => $city['region_name'],
                    'full_name' => $city['region_full_name'],
                    'cities' => array()
                );
        }
        foreach ($cities as $city)
        {
            $regions[$city['region_id']]['cities'][] = array(
                'id' => $city['id'],
                'name' => $city['name']
            );
        }
        $this->template->visited_cities = $regions;
        
        $this->template->defaults = $this->defaults = Kohana::$config->load('defaults');
        
        $this->template->app_js_cfg = array(
            'base_url' => URL::base(),
        );
        
        // Common backend styles
        $this->template->styles['assets/css/bootstrap.min.css'] = 'all';
        $this->template->styles['assets/css/common.css'] = 'screen';
        
        // Common backend scripts
        $this->template->inline_script = '';
        if (!isset($current_location)) {
            if ($current_location = Cookie::get('curloc'))
            {
                // refresh cookie
                Cookie::set('curloc', $current_location, 3600*24*7);
                $current_location = unserialize($current_location);
            }
        }
        if ($current_location)
        {
            $this->template->current_location = $this->current_location = array('lat' => $current_location['lat'], 'lng' => $current_location['lng']);
            $this->template->inline_script .= '
            app.curLoc = ['.$current_location['lat'].', '.$current_location['lng'].'];
';
        }
        $curcity = DB::query(Database::SELECT, 'SELECT cities.*, regions.id as region_id, regions.name as region_name, regions.full_name as region_full_name FROM `cities`
LEFT JOIN regions on cities.region_id = regions.id
 WHERE cities.id = '.$current_city)->execute();
        
        $curcity = $curcity->current();
        $this->template->current_city = $this->current_city = $curcity;
        $this->template->inline_script .= '
            app.curCity = {id: '.$curcity['id'].', name: "'.$curcity['name'].'", region: "'.$curcity['region_name'].'"};
';
        
        $this->template->scripts[] = 'assets/js/bootstrap/bootstrap.min.js';
        $this->template->scripts[] = 'assets/js/jquery/chosen.jquery.js';
        $this->template->scripts[] = 'http://maps.googleapis.com/maps/api/js?key='.$this->defaults['google']['maps']['key'].'&sensor=false';
        
        $this->template->scripts[] = 'assets/js/common.js';
        
    }
    
    public function after()
    {
       return parent::after();
    }

}
