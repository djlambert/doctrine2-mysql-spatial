<?php

namespace CrEOF\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * GeometryType DQL function for querying using spatial objects as parameters
 */
class GeometryType extends FunctionNode
{
    /**
     * @var \Doctrine\ORM\Query\AST\Node
     */
    public $geomExpression;

    /**
     * @inheritdoc
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'GeometryType(' . $this->geomExpression->dispatch($sqlWalker) . ')';
    }

    /**
     * @inheritdoc
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->geomExpression = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
