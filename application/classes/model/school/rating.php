<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * School ratings
 *
 * @author     Oleh Burkhay <atma@atmaworks.com>
 */
class Model_School_Rating extends ORM {

    protected $_table_name = 'school_ratings';
    /**
     * Rating belongs to some School
     *
     * @var array Relationhips
     */
 
    
    protected $_belongs_to = array(
        'school' => array('model' => 'school'),
        'user' => array(
            'model' => 'user'
        ),
    );



    protected $_created_column = array('column' => 'created', 'format' => 'Y-m-d H:i:s');

    protected $_updated_column = array('column' => 'modified', 'format' => 'Y-m-d H:i:s');
    
    /**
     * Rules for the rating fields.
     *
     * @return array Rules
     */
    public function rules() {
        return array(
            'espacio_fisico' => array(
                array('digit'),
            ),
            'seguridad' => array(
                array('digit'),
            ),
            'instalaciones_educativas' => array(
                array('digit'),
            ),
            'organizacion' => array(
                array('digit'),
            ),
            'proceso_educativo' => array(
                array('digit'),
            ),
            'valores' => array(
                array('digit'),
            ),
            'otros_recursos' => array(
                array('digit'),
            ),
            
        );
    }

}

// End School_Rating Model