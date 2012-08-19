<?php
namespace CrEOF\DBAL\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * PointStr DQL function for querying using Point objects as parameters
 *
 * Usage: POINT_STR(:param) where param should be mapped to $point where $point is Point
 *        without any special typing provided (eg. so that it gets converted to string)
 */
class PointStr extends FunctionNode
{
    public $arithmeticPrimary;

    /**
     * @inheritdoc
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'GeomFromText(' . $this->arithmeticPrimary->dispatch($sqlWalker) . ')';
    }

    /**
     * @inheritdoc
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->arithmeticPrimary = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

}
