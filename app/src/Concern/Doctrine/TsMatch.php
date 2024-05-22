<?php

namespace App\Concern\Doctrine;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class TsMatch extends FunctionNode
{
    private Node $tsvector;
    private Node $tsquery;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $this->tsvector = $parser->StringPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->tsquery = $parser->StringPrimary();

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        return sprintf(
            '%s @@ %s',
            $this->tsvector->dispatch($sqlWalker),
            $this->tsquery->dispatch($sqlWalker)
        );
    }
}
