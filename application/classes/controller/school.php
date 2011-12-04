<?php defined('SYSPATH') or die('No direct script access.');

class Controller_School extends Controller_Frontend {
    
	public function action_index()
	{
        
		$this->template->set_filename = 'welcome';
        
	}
    
    public function action_details()
	{
        $id = (int) $this->request->param('id');
        if (!$id)
        {
            throw new HTTP_Exception_404('Unable to find the school with ID: :id', array(
				':id' => $id,
			));
        }
        $school = ORM::factory('school')->where('school.id', '=', $id)->with('region')->with('commune')->find();
        if (!$school->loaded())
        {
            throw new HTTP_Exception_404('Unable to find the school with ID: :id', array(
				':id' => $id,
			));
        }
        $this->template->school = $school;
        $this->template->school->education_level = implode(', ', explode('|', $school->education_level));
        $this->template->school_images = $school->images->find_all();
        $rating = Db::query(Database::SELECT, '
            SELECT school_id, AVG(espacio_fisico) as espacio_fisico,
            AVG(seguridad) as seguridad,
            AVG(instalaciones_educativas) as instalaciones_educativas,
            AVG(organizacion) as organizacion,
            AVG(proceso_educativo) as proceso_educativo,
            AVG(valores) as valores,
            AVG(otros_recursos) as otros_recursos 
            FROM school_ratings WHERE school_id = '.$id.' GROUP BY school_id')->execute();
        
        if ($rating->count())
            $this->template->school_rating = $rating->current();
        else
            $this->template->school_rating = false;
        
        if (Auth::instance()->logged_in())
        {
            $rating = ORM::factory('school_rating')->where('user', '=', Auth::instance()->get_user());
            if ($rating->loaded())
            {
                $this->template->is_rated = true;
                $this->template->user_rate = $rating;
            }
        }
        
        $this->template->school_ratings = DB::query(Database::SELECT, 'SELECT rating.*, user.username as username, user.email as email FROM `school_ratings` rating LEFT JOIN users as user on rating.user_id = user.id WHERE school_id = '.$school->id )->execute()->as_array();
         
        $this->template->inline_script .= '
            app.school_id = '.$school->id.'
';
        
        $this->template->scripts[] = 'assets/js/jquery/rating.pack.js';
        $this->template->scripts[] = 'assets/js/jquery/sparkline.min.js';
        $this->template->scripts[] = 'assets/js/school_details.js';

        $this->template->set_filename('pages/school/details');
        
	}

} // End Welcome
