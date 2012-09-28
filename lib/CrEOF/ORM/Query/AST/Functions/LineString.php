<?php

namespace CrEOF\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * LineString DQL function for querying using spatial objects as parameters
 */
class LineString extends FunctionNode
{
    /**
     * @var \Doctrine\ORM\Query\AST\Node
     */
    public $firstPointExpression;

    public $pointExpressions = array();

    /**
     * @inheritdoc
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $lexer = $parser->getLexer();

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->firstPointExpression = $parser->StringPrimary();

        while (count($this->pointExpressions) < 1 || $lexer->lookahead['type'] != Lexer::T_CLOSE_PARENTHESIS) {
            $parser->match(Lexer::T_COMMA);

            $this->pointExpressions[] = $parser->StringPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @inheritdoc
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        $result = 'LineString(';

        $result .= $this->dispatchValue($sqlWalker, $this->firstPointExpression) . ', ';

        for ($i = 0, $size = count($this->pointExpressions); $i < $size; $i++) {
            if ($i > 0) {
                $result .= ', ';
            }

            $result .= $this->dispatchValue($sqlWalker, $this->pointExpressions[$i]);
        }

        $result .= ')';

        return $result;
    }

    public function dispatchValue(\Doctrine\ORM\Query\SqlWalker $sqlWalker, $value)
    {
        switch (get_class($value)) {
            case 'Doctrine\ORM\Query\AST\InputParameter':
                return 'GeomFromText(' . $value->dispatch($sqlWalker) . ')';
                break;
            default:
                return $value->dispatch($sqlWalker);
                break;
        }
    }
}
