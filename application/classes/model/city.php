<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * City
 *
 * @author     Oleh Burkhay <atma@atmaworks.com>
 */
class Model_City extends ORM {

    /**
     * City belong to some federal subject (region)
     *
     * @var array Relationhips
     */
    protected $_belongs_to = array(
        'region' => array('model' => 'region'),
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
            // for internal use
            'code' => array(
                array('not_empty'),
            ),
        );
    }

}

// End City Model