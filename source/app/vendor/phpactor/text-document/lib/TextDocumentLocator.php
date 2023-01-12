<?php

namespace Phpactor\TextDocument;

use Phpactor\TextDocument\Exception\TextDocumentNotFound;

interface TextDocumentLocator
{
    /**
     * Retrieve text document by URI, returns NULL if not found
     *
     * @throws TextDocumentNotFound
     */
    public function get(TextDocumentUri $uri): TextDocument;
}
