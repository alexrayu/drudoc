<?php

namespace Phpactor\TextDocument;

class StandardTextDocument implements TextDocument
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var TextDocumentUri|null
     */
    private $uri;

    /**
     * @var TextDocumentLanguage
     */
    private $language;

    public function __construct(
        TextDocumentLanguage $language,
        string $text,
        ?TextDocumentUri $uri = null
    ) {
        $this->text = $text;
        $this->uri = $uri;
        $this->language = $language;
    }
    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return $this->text;
    }

    /**
     * {@inheritDoc}
     */
    public function uri(): ?TextDocumentUri
    {
        return $this->uri;
    }

    /**
     * {@inheritDoc}
     */
    public function language(): TextDocumentLanguage
    {
        return $this->language;
    }
}
