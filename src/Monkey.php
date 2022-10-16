<?php

declare(strict_types=1);

namespace ReactInspector\Stream;

use PhpParser\Node;
use PhpParser\ParserFactory;
use Roave\BetterReflection\BetterReflection;

use function method_exists;
use function property_exists;

final class Monkey
{
    /** @return array<Node> */
    public static function patch(string $class): array
    {
        $reflector       = (new BetterReflection())->reflector();
        $classReflection = $reflector->reflectClass($class);

        /** @psalm-suppress InternalMethod */
        return [
            ...self::iterateAst(
                (new ParserFactory())->createForNewestSupportedVersion()->parse(
                    $classReflection->getLocatedSource()->getSource(),
                ) ?? [],
            ),
        ];
    }

    /**
     * @param Node[] $ast
     *
     * @return iterable<string, Node>
     *
     * @psalm-suppress MixedReturnTypeCoercion
     */
    private static function iterateAst(iterable $ast): iterable
    {
        foreach ($ast as $key => $stmt) {
            yield $key => self::checkStmt($stmt);
        }
    }

    private static function checkStmt(Node $stmt): Node
    {
        /** @psalm-suppress NoInterfaceProperties */
        if (property_exists($stmt, 'stmts') && $stmt->stmts !== null) {
            /**
             * @psalm-suppress NoInterfaceProperties
             * @psalm-suppress MixedArgument
             */
            $stmt->stmts = [...self::iterateAst($stmt->stmts)];
        }

        /** @psalm-suppress NoInterfaceProperties */
        if (property_exists($stmt, 'expr') && $stmt->expr instanceof Node) {
            /** @psalm-suppress NoInterfaceProperties */
            $stmt->expr = self::checkStmt($stmt->expr);
        }

        /** @psalm-suppress NoInterfaceProperties */
        if (property_exists($stmt, 'else') && $stmt->else instanceof Node) {
            /** @psalm-suppress NoInterfaceProperties */
            $stmt->else = self::checkStmt($stmt->else);
        }

        foreach (['fread', 'fwrite', 'stream_get_contents'] as $functionName) {
            if (! ($stmt instanceof Node\Expr\FuncCall) || ! method_exists($stmt->name, 'toString') || $stmt->name->toString() !== $functionName) {
                continue;
            }

            /** @psalm-suppress MixedOperand */
            $stmt->name = new Node\Name('\React\Stream\\' . $stmt->name->toString());
        }

        return $stmt;
    }
}
