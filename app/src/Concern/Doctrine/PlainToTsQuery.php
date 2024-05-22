<?php

namespace App\Concern\Doctrine;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class PlainToTsQuery extends FunctionNode
{
    private ?Node $config = null;

    private Node $expr1;

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->expr1 = $parser->StringExpression();

        if ($parser->getLexer()->isNextToken(TokenType::T_COMMA)) {
            $parser->match(TokenType::T_COMMA);
            $this->config = $this->expr1;
            $this->expr1 = $parser->StringExpression();
        }

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker): string
    {
        if (null === $this->config) {
            return sprintf(
                'plainto_tsquery(%s)',
                $this->expr1->dispatch($sqlWalker)
            );
        }

        return sprintf(
            'plainto_tsquery(%s, %s)',
            $this->config->dispatch($sqlWalker),
            $this->expr1->dispatch($sqlWalker)
        );
    }
}
