<?php declare(strict_types=1);

namespace ReactInspector\Stream;

use PhpParser\Node;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflection\ReflectionMethod;
use function method_exists;
use function property_exists;
use function strpos;
use function WyriHaximus\iteratorOrArrayToArray;
use const WyriHaximus\Constants\Boolean\FALSE_;

final class Monkey
{
    public static function patch(string $class): ReflectionClass
    {
        $reflector = (new BetterReflection())->reflector();
        $classReflection = $reflector->reflectClass($class);

        return ReflectionClass::createFromNode(
            $reflector,
            self::checkStmt($classReflection->getAst()),
            $classReflection->getLocatedSource(),
            $classReflection->getDeclaringNamespaceAst(),
        );
    }

    /**
     * @param Node[] $ast
     *
     * @return iterable<string, Node>
     */
    private static function iterateAst(iterable $ast): iterable
    {
        foreach ($ast as $key => $stmt) {
            yield $key => self::checkStmt($stmt);
        }
    }

    private static function checkStmt(Node $stmt): Node
    {
        if (property_exists($stmt, 'stmts')) {
            /** @psalm-suppress NoInterfaceProperties */
            $stmt->stmts = iteratorOrArrayToArray(self::iterateAst($stmt->stmts));
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
            if ($stmt instanceof Node\Expr\FuncCall && method_exists($stmt->name, 'toString') && $stmt->name->toString() === $functionName) {
                $stmt->name = new Node\Name('\React\Stream\\' . $stmt->name->toString());
            }
        }

        return $stmt;
    }
}
