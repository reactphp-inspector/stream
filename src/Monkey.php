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
        $classReflection = (new BetterReflection())->classReflector()->reflect($class);
        foreach ($classReflection->getMethods() as $method) {
            self::reroute($method, 'fread');
            self::reroute($method, 'fwrite');
            self::reroute($method, 'stream_get_contents');
        }

        return $classReflection;
    }

    private static function reroute(ReflectionMethod $method, string $functionName): void
    {
        if (strpos($method->getBodyCode(), $functionName) === FALSE_) {
            return;
        }

        $method->setBodyFromAst(iteratorOrArrayToArray(self::iterateAst($method->getBodyAst(), $functionName)));
    }

    /**
     * @param Node[] $ast
     *
     * @return iterable<string, Node>
     */
    private static function iterateAst(iterable $ast, string $functionName): iterable
    {
        foreach ($ast as $key => $stmt) {
            yield $key => self::checkStmt($stmt, $functionName);
        }
    }

    private static function checkStmt(Node $stmt, string $functionName): Node
    {
        if (property_exists($stmt, 'stmts')) {
            /** @psalm-suppress NoInterfaceProperties */
            $stmt->stmts = iteratorOrArrayToArray(self::iterateAst($stmt->stmts, $functionName));
        }

        /** @psalm-suppress NoInterfaceProperties */
        if (property_exists($stmt, 'expr') && $stmt->expr instanceof Node) {
            /** @psalm-suppress NoInterfaceProperties */
            $stmt->expr = self::checkStmt($stmt->expr, $functionName);
        }

        /** @psalm-suppress NoInterfaceProperties */
        if (property_exists($stmt, 'else') && $stmt->else instanceof Node) {
            /** @psalm-suppress NoInterfaceProperties */
            $stmt->else = self::checkStmt($stmt->else, $functionName);
        }

        if ($stmt instanceof Node\Expr\FuncCall && method_exists($stmt->name, 'toString') && $stmt->name->toString() === $functionName) {
            $stmt->name = new Node\Name('\React\Stream\\' . $stmt->name->toString());
        }

        return $stmt;
    }
}
