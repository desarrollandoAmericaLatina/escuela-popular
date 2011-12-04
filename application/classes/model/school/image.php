<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * School images
 *
 * @author     Oleh Burkhay <atma@atmaworks.com>
 */
class Model_School_Image extends ORM {

    /**
     * Image belongs to some School
     *
     * @var array Relationhips
     */
    protected $_belongs_to = array(
        'school' => array('model' => 'school'),
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
        );
    }

}

// End School_Image Model