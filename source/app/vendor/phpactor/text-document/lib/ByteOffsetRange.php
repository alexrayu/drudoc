<?php

namespace Phpactor\TextDocument;

class ByteOffsetRange
{
    /**
     * @var ByteOffset
     */
    private $start;
    /**
     * @var ByteOffset
     */
    private $end;

    public function __construct(ByteOffset $start, ByteOffset $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public static function fromInts(int $start, int $end): self
    {
        return new self(
            ByteOffset::fromInt($start),
            ByteOffset::fromInt($end)
        );
    }

    public static function fromByteOffsets(ByteOffset $start, ByteOffset $end): self
    {
        return new self($start, $end);
    }

    public function start(): ByteOffset
    {
        return $this->start;
    }

    public function end(): ByteOffset
    {
        return $this->end;
    }
}
