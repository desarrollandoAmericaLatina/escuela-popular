<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Compiler for thumbnail
 *
 */
class View_Extension_Thumbnail_Node extends Twig_Node {


    /**
     * @param object $compiler 
     * @return void
     */
    public function compile(Twig_Compiler $compiler) {
        $params = $this->getNode('expression')->getIterator();

        // Output the route		
        $compiler->write('echo View_Extension_Thumbnail::' . $this->getAttribute('method') . '(');

        foreach ($params as $i => $row) {
            $compiler->subcompile($row);

            if (($params->count() - 1) !== $i) {
                $compiler->write(',');
            }
        }

        $compiler->write(')')->raw(';');
    }

}
