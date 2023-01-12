<?php

namespace Phpactor\WorseReflection\Core\Type;

use Phpactor\WorseReflection\Core\Trinary;
use Phpactor\WorseReflection\Core\Type;

final class ObjectType implements Type
{
    public function __toString(): string
    {
        return 'object';
    }

    public function toPhpString(): string
    {
        return $this->__toString();
    }

    public function accepts(Type $type): Trinary
    {
        if ($type instanceof ClassType) {
            return Trinary::true();
        }
        if ($type instanceof ObjectType) {
            return Trinary::true();
        }
        if ($type instanceof MixedType) {
            return Trinary::maybe();
        }
        if ($type instanceof MissingType) {
            return Trinary::maybe();
        }

        return Trinary::false();
    }
}