<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_Cities extends Controller_Ajax {
    
	public function action_find()
	{
        $s = Arr::get($_POST, 's');
        if (!$s) {
            $this->template['success'] = false;
            $this->template['message'] = 'Omitted required parameter';
            return;
        }
        $s = trim($s);
        
        $cities = DB::select('cities.*', array('regions.id', 'region_id'), array('regions.name', 'region_name'), array('regions.full_name', 'region_full_name'))
            ->join('regions')->on('regions.id', '=', 'cities.region_id')
            ->from('cities')->order_by('name', 'ASC')
            ->where('cities.name', 'LIKE', '%'.$s.'%')->limit(50)->execute()->as_array();

        $this->template['success'] = true;
        $this->template['data'] = $cities;
	}
}