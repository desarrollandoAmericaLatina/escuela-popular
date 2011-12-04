<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax_School extends Controller_Ajax {
    
	public function action_rate()
	{
        $data = Arr::extract($_POST, array(
            'espacio_fisico', 'seguridad', 'instalaciones_educativas', 'organizacion', 'proceso_educativo', 'valores', 'otros_recursos', 'pros', 'cons', 'school'
        ));
        $data['user_id'] = Auth::instance()->get_user()->id;
        $query = DB::query(Database::INSERT, 'INSERT INTO school_ratings (espacio_fisico, seguridad, instalaciones_educativas, organizacion, proceso_educativo, valores, otros_recursos, pros, cons, user_id, school_id) VALUES (:espacio_fisico, :seguridad, :instalaciones_educativas, :organizacion, :proceso_educativo, :valores, :otros_recursos, :pros, :cons, :user_id, :school_id)')
    ->bind(':espacio_fisico', $data['espacio_fisico'])
    ->bind(':seguridad', $data['seguridad'])
    ->bind(':instalaciones_educativas', $data['instalaciones_educativas'])
    ->bind(':organizacion', $data['organizacion'])
    ->bind(':proceso_educativo', $data['proceso_educativo'])
    ->bind(':valores', $data['valores'])
    ->bind(':otros_recursos', $data['otros_recursos'])
    ->bind(':pros', $data['pros'])
    ->bind(':cons', $data['cons'])
    ->bind(':user_id', $data['user_id'])
    ->bind(':school_id', $data['school']);
        try {
            
            list($id, $count) = $query->execute();
            
            $this->template['success'] = true;
            $this->template['data'] = array('rated_url' => URL::base().'school/'.$_POST['school'].'#'.$id);
            $this->template['total'] = 1;
        } catch (ORM_Validation_Exception $e) {
            $this->template['success'] = false;
            $this->template['message'] = $e->errors;
            return;
        }

	}
}