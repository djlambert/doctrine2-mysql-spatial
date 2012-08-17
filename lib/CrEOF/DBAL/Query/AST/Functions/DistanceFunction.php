<?php
namespace CrEOF\DBAL\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * DQL function for calculating distances between two points
 *
 * Example: DISTANCE(foo.point, POINT_STR(:param))
 */
class DistanceFunction extends FunctionNode
{
    public $firstArithmeticPrimary;
    public $secondArithmeticPrimary;

    /**
     * @inheritdoc
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        //Need to do this hacky linestring length thing because
        //despite what MySQL manual claims, DISTANCE isn't actually implemented...
        return 'GLength(LineString(' .
            $this->firstArithmeticPrimary->dispatch($sqlWalker) .
            ', ' .
            $this->secondArithmeticPrimary->dispatch($sqlWalker) .
            '))';
    }

    /**
     * @inheritdoc
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->firstArithmeticPrimary = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_COMMA);

        $this->secondArithmeticPrimary = $parser->ArithmeticPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
