<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * School
 *
 * @author     Oleh Burkhay <atma@atmaworks.com>
 */
class Model_School extends ORM {

    /**
     * School belong to some region and commune (city)
     *
     * @var array Relationhips
     */
    protected $_belongs_to = array(
        'region' => array('model' => 'region'),
        'commune' => array(
            'model'       => 'city',
            'foreign_key' => 'commune_id',
        ),
    );
    
    protected $_has_many = array(
        'images' => array(
            'model'       => 'school_image',
            'foreign_key' => 'school_id',
        ),
        'ratings' => array(
            'model'       => 'school_rating',
            'foreign_key' => 'school_id',
        ),
    );
    

    /**
     * Rules for the City model.
     *
     * @return array Rules
     */
    public function rules() {
        return array(
            'name' => array(
                array('not_empty'),
            ),
            // rgistro de base de datos de ministerio de educacion de Chile
            'rbd' => array(
                array('digit'),
            ),
            'email' => array(
                array('email'),
            ),
        );
    }

}

// End School Model