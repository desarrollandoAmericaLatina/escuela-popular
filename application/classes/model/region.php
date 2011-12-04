<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Country region
 *
 * @author     Oleh Burkhay <atma@atmaworks.com>
 */
class Model_Region extends ORM {

    /**
     * Region has many cities (towns)
     *
     * @var array Relationhips
     */
    protected $_has_many = array(
        'cities' => array('model' => 'city'),
    );

    /**
     * Rules for the Region model.
     *
     * @return array Rules
     */
    public function rules() {
        return array(
            'name' => array(
                array('not_empty'),
            ),
            'full_name' => array(
                array('not_empty'),
            ),
        );
    }

}

// End Region Model