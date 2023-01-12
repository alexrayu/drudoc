<?php

namespace Phpactor\TextDocument;

final class LineColRange
{
    /**
     * @var LineCol
     */
    private $start;
    /**
     * @var LineCol
     */
    private $end;

    public function __construct(LineCol $start, LineCol $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function start(): LineCol
    {
        return $this->start;
    }

    public function end(): LineCol
    {
        return $this->end;
    }
}
