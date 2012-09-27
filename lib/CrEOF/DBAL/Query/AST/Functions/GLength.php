<?php

namespace CrEOF\DBAL\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * Distance DQL function for calculating distances between two points
 *
 * Example: DISTANCE(foo.point, POINT_STR(:param))
 */
class GLength extends FunctionNode
{
    /**
     * @var \Doctrine\ORM\Query\AST\Node
     */
    public $firstPointExpression;

    /**
     * @var \Doctrine\ORM\Query\AST\Node
     */
    public $secondPointExpression;

    /**
     * @inheritdoc
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'GLength(LineString(' .
            $this->firstPointExpression->dispatch($sqlWalker) .
            ', ' .
            $this->secondPointExpression->dispatch($sqlWalker) .
            '))';
    }

    /**
     * @inheritdoc
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->firstPointExpression = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_COMMA);

        $this->secondPointExpression = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
