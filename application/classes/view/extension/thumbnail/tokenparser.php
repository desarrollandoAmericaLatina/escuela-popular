<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Parses a {% thumbnail %} tag
 */
class View_Extension_Thumbnail_TokenParser extends Twig_TokenParser {

    /**
     * @param Twig_Token $token 
     * @return object
     */
    public function parse(Twig_Token $token) {
        $lineno = $token->getLine();

        $this->parser->getStream()->expect(Twig_Token::PUNCTUATION_TYPE, '.');

        $method = $this->parser->getStream()->expect(Twig_Token::NAME_TYPE)->getValue();
        $params = $this->parser->getExpressionParser()->parseMultiTargetExpression();
        $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

        return new View_Extension_Thumbnail_Node(array('expression' => $params), array('method' => $method), $lineno, $this->getTag());
    }

    /**
     * @return string
     */
    public function getTag() {
        return 'thumbnail';
    }

}
