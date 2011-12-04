<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Loads a custom set of filters and extensions for 
 * Twig based views
 *
 */
class View_Extension_Custom extends Twig_Extension {

    /**
     * Returns the added token parsers
     *
     * @return array
     */
    public function getTokenParsers() {
        return array(
            new View_Extension_Thumbnail_TokenParser(),
        );
    }

    /**
     * Returns the added filters
     *
     * @return array
     */
    public function getFilters() {
        return array(

        );
    }

    /**
     * @return string
     */
    public function getName() {
        return 'view_extension_custom';
    }

}
